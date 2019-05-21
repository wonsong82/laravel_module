<?php
namespace Module\Application\Controllers\Logic;




use Module\Application\Events\UserCreated;
use Module\Application\Events\UserDeleted;
use Module\Application\Events\UserUpdated;
use Module\Application\Exceptions\NotChangedException;
use Module\Application\ModelChangeCollection;
use Module\Application\User;

class UserController extends LogicController
{
    /**
     * @param $params [*status_code, *email, *name, *password, *locale_id, *timezone, roles_show[*id], permissions_show[*id]]
     * @return mixed
     */
    public function create($params)
    {
        $params['password'] = bcrypt($params['password']);

        $user = User::create($params);

        \Cache::forget('spatie.permission.cache');
        $user->roles()->sync($params['roles_show']??[]);
        $user->permissions()->sync($params['permissions_show']??[]);


        event(new UserCreated($user));


        return $user;
    }


    /**
     * @param User $user
     * @param $params [*status_code, *email, *name, password, *locale_id, *timezone, roles_show[*id], permissions_show[*id]]
     * @return mixed
     * @throws NotChangedException
     */
    public function update(User $user, $params)
    {
        $changes = app(ModelChangeCollection::class);

        $password = null;
        if($params['password']){
            $password = $params['password'] = bcrypt($params['password']);
        }
        else {
            unset($params['password']);
        }

        // check changes
        $changes['user'] = $user->getModelChanges($params);
        $changes['roles'] = $user->getPivotChanges('roles', $params['roles_show']??[]);
        $changes['permissions'] = $user->getPivotChanges('permissions', $params['permissions_show']??[]);

        $changes->checkChanges();

        // save
        \Cache::forget('spatie.permission.cache');

        if($password){
            $changes['user']->changes['password']->from = '******';
            $changes['user']->changes['password']->to = '******';
        }

        $changes->save();



        event(new UserUpdated($user, $changes));

        return $changes['user']->model;
    }



    /**
     * @param User $user
     * @return User
     */
    public function delete(User $user)
    {
        \Cache::forget('spatie.permission.cache');

        $user->delete();
        
        event(new UserDeleted($user));
        
        
        return $user;
    }



    
}
