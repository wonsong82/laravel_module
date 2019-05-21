<?php
namespace Module\Application\Controllers\Logic;


use Module\Application\Controllers\Logic\LogicController;
use Module\Application\ModelChangeCollection;
use Module\Application\Permission;
use Module\Application\Role;
use Module\Application\Events\RoleCreated;
use Module\Application\Events\RoleDeleted;
use Module\Application\Events\RoleUpdated;


class RoleController extends LogicController
{   
    /**
     * @param $params [*name]
     * @return Role
     */
    public function create($params)
    {
        \Cache::forget('spatie.permission.cache');

        // create
        $role = Role::create($params);

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
        \Cache::forget('spatie.permission.cache');

        // delete
        $role->delete();


        // events
        event(new RoleDeleted($role));


        return $role;
    }




    
}
