<?php
namespace Module\Company\Controllers\Logic;




use Illuminate\Contracts\Support\Arrayable;
use Module\Application\Constants\UserStatus;
use Module\Application\Controllers\Logic\LogicController;
use Module\Application\Exceptions\NotChangedException;
use Module\Application\ModelChangeCollection;
use Module\Application\User;
use Module\Company\Company;
use Module\Company\CompanyUser;
use Module\Company\Constants\CompanyUserStatus;
use Module\Company\Events\CompanyCreated;
use Module\Company\Events\CompanyDeleted;
use Module\Company\Events\CompanyUpdated;
use Module\Company\Events\CompanyUserCreated;
use Module\Company\Events\CompanyUserDeleted;
use Module\Company\Events\CompanyUserUpdated;
use Module\Company\Events\MarginRateUpdated;

class CompanyController extends LogicController
{


    /**
     * @param $params [status_code, *name, legal_name, desc, phone, fax, email, website, currency_code, *locale_id, *timezone, note, physical_address_attention, physical_address_line1, physical_address_line2, physical_address_line3, physical_address_line4, physical_address_city, physical_address_state, physical_address_zip, physical_address_country, shipping_address_attention, shipping_address_line1, shipping_address_line2, shipping_address_line3, shipping_address_line4, shipping_address_city, shipping_address_state, shipping_address_zip, shipping_address_country, billing_address_attention, billing_address_line1, billing_address_line2, billing_address_line3, billing_address_line4, billing_address_city, billing_address_state, billing_address_zip, billing_address_country ]
     * @return object
     */
    public function create($params)
    {
        // create company
        $company = Company::create($params);
        $curLocale = app()->getLocale();
        app()->setLocale($company->locale->code);

        // create addresses
        $addressParams = $company->parseAddressParams($params);
        $company->addresses()->create($addressParams->physical);
        $company->addresses()->create($addressParams->shipping);
        $company->addresses()->create($addressParams->billing);

        // create offdays
        app(OffdayController::class)->generateOffdays(3, $company);

        // currencies
        $currencyController = app(CurrencyController::class);
        foreach($currencyController->getDefaultList() as $currencyData){
            $currencyController->create($company, $currencyData);
        }

        $currency = $company->currencies()->where('code', $params['currency_code'])->first();
        $company->fill([
            'currency_id' => $currency->id
        ])->save();

        // payterms
        $paytermController = app(PaytermController::class);
        foreach($paytermController->getDefaultList() as $data){
            $paytermController->create($company, $data);
        }

        // units
        $unitController = app(UnitController::class);
        foreach($unitController->getDefaultList() as $data){
            $unitController->create($company, $data);
        }

        // margin_rates
        $company->marginRate()->create([
            'rates' => [(object)['rate' => 0]],
        ]);

        // event
        event(new CompanyCreated($company));

        app()->setLocale($curLocale);

        return $company;
    }


    /**
     * @param Company $company
     * @param $params [status_code, *name, legal_name, desc, phone, fax, email, website, currency_code, *locale_id, *timezone, note, physical_address_attention, physical_address_line1, physical_address_line2, physical_address_line3, physical_address_line4, physical_address_city, physical_address_state, physical_address_zip, physical_address_country, shipping_address_attention, shipping_address_line1, shipping_address_line2, shipping_address_line3, shipping_address_line4, shipping_address_city, shipping_address_state, shipping_address_zip, shipping_address_country, billing_address_attention, billing_address_line1, billing_address_line2, billing_address_line3, billing_address_line4, billing_address_city, billing_address_state, billing_address_zip, billing_address_country ]
     * @return object
     * @throws NotChangedException
     */
    public function update(Company $company, $params)
    {
        // changes
        $changes = app(ModelChangeCollection::class);

        $changes['company'] = $company->getModelChanges($params);


        // addresses

        $addressParams = $company->parseAddressParams($params);
        $changes['physical'] = $company->address->getModelChanges($addressParams->physical);
        $changes['shipping'] = $company->shipping_address->getModelChanges($addressParams->shipping);
        $changes['billing'] = $company->billing_address->getModelChanges($addressParams->billing);

        $changes->checkChanges();


        // update
        $changes->save();


        // event
        event(new CompanyUpdated($company, $changes));


        return $changes['company']->model;
    }


    /**
     * @param Company $company
     * @return object
     */
    public function delete(Company $company)
    {
        // delete
        $company->delete();

        // event
        event(new CompanyDeleted($company));

        return $company;
    }


    /***
     * @param Company $company
     * @param $params [*status_code, *code, *name, *email, *password, roles_show[*id], permissions_show[*id]]
     * @return object
     */
    public function createUser(Company $company, $params)
    {
        // adjust params
        $params['password'] = bcrypt($params['password']);
        $params['locale_id'] = $company->locale->id ?? null;
        $params['timezone'] = $company->timezone;

        // create auth user
        $authUser = User::create($params);

        // create company user
        $params['user_id'] = $authUser->id;
        $user = $company->users()->create($params);

        \Cache::forget('spatie.permission.cache');
        $user->roles()->sync($params['roles_show']??[]);
        $user->permissions()->sync($params['permissions_show']??[]);


        // event
        event(new CompanyUserCreated($user));

        return $user;
    }


    /**
     * @param CompanyUser $user
     * @param $params [*status_code, *code, *name, *email, password, roles_show[*id], permissions_show[*id]]
     * @return object
     * @throws NotChangedException
     */
    public function updateUser(CompanyUser $user, $params)
    {
        $changes = app(ModelChangeCollection::class);

        // user
        $password = null;
        if($params['password']) {
            $password = bcrypt($params['password']);
        }
        else {
            unset($params['password']);
        }

        $changes['user'] = $user->getModelChanges($params);
        $changes['auth'] = $user->user->getModelChanges($params);
        $changes['roles'] = $user->getPivotChanges('roles', $params['roles_show']??[]);
        $changes['permissions'] = $user->getPivotChanges('permissions', $params['permissions_show']??[]);
        $changes->checkChanges();


        // update
        \Cache::forget('spatie.permission.cache');

        if($password){
            $changes['auth']->changes['password']->from = '******';
            $changes['auth']->changes['password']->to = '******';
        }

        $authStatusCode = null;
        switch($changes['user']->model->status_code){
            case CompanyUserStatus::ACTIVE:
                $authStatusCode = UserStatus::ACTIVE;
                break;
            case CompanyUserStatus::INACTIVE:
                $authStatusCode = UserStatus::INACTIVE;
                break;
        }

        $changes['auth']->model->fill([
            'status_code' => $authStatusCode
        ]);

        $changes->save();


        // event
        event(new CompanyUserUpdated($user, $changes));


        return $changes['user']->model;
    }


    /**
     * @param CompanyUser $user
     * @return object
     */
    public function deleteUser(CompanyUser $user)
    {
        \Cache::forget('spatie.permission.cache');

        // delete
        $user->user->delete();
        $user->delete();


        // event
        event(new CompanyUserDeleted($user));

        return $user;
    }


    /**
     * @param CompanyUser $user
     * @return \Illuminate\Support\Collection
     */
    public function getUserPermissions(CompanyUser $user)
    {
        $permissions = collect();
        $counts = collect();

        foreach($user->getAllPermissions() as $permission){
            $permission = $permission->name;
            $perm = explode('.', $permission);
            $cat = ucwords(str_replace('_', ' ', $perm[0]));
            $name = ucwords(str_replace('_', ' ', $perm[1]));

            if(!isset($counts[$cat])){
                $counts[$cat] = 0;
                $permissions[$cat] = collect();
                $permissions[$cat]->count = 0;
            }

            $counts[$cat] = $counts[$cat]+1;
            $permissions[$cat][] = collect(['name' => $name, 'permission' => $permission]);
            $permissions[$cat]->count = $permissions[$cat]->count+1;
        }

        $permissions->maxCount = $counts->max();

        return $permissions;
    }


    /**
     * @param Company $company
     * @param Arrayable $rates
     * @return mixed
     */
    public function updateMarginRates(Company $company, Arrayable $rates)
    {
        // changes
        $changes = app(ModelChangeCollection::class);
        $changes['rates'] = $company->marginRate->getModelChanges(['rates' => $rates]);
        $changes->checkChanges();

        // update
        $changes->save();

        // event
        event(new MarginRateUpdated($company->marginRate, $changes));

        return $changes['rates']->model;
    }


}
