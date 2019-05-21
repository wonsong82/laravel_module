<?php
namespace Module\Company\Controllers\Logic;


use Module\Application\Controllers\Logic\LogicController;
use Module\Application\ModelChangeCollection;
use Module\Company\Company;
use Module\Company\Events\RoleCreated;
use Module\Company\Events\RoleDeleted;
use Module\Company\Events\RoleUpdated;
use Module\Company\Role;


class RoleController extends LogicController
{
    /**
     * @param Company $company
     * @param $params [*name]
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(Company $company, $params)
    {
        \Cache::forget('spatie.permission.cache');


        // create
        $role = $company->roles()->create($params);

        if($permissions = $params['permissions'] ?? null){
            $role->permissions()->sync($permissions);
        }


        // events
        event(new RoleCreated($role));


        return $role;
    }


    /**
     * @param Role $role
     * @param $params [*name]
     * @return mixed
     */
    public function update(Role $role, $params)
    {
        // changes
        $changes = app(ModelChangeCollection::class);
        $changes['role'] = $role->getModelChanges($params);
        $changes['permissions'] = $role->getPivotChanges('permissions', $params['permissions']??[]);
        $changes->checkChanges();


        // update
        \Cache::forget('spatie.permission.cache');

        $changes->save();


        // events
        event(new RoleUpdated($role, $changes));


        return $changes['role']->model;
    }


    /**
     * @param Role $role
     * @return Role
     */
    public function delete(Role $role)
    {
        // delete
        \Cache::forget('spatie.permission.cache');
        $role->delete();


        // events
        event(new RoleDeleted($role));


        return $role;
    }


    /**
     * @param Role $role
     * @return \Illuminate\Support\Collection
     */
    public function getPermissions(Role $role)
    {
        $permissions = collect();
        $counts = collect();

        foreach($role->permissions as $permission){
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

    
}
