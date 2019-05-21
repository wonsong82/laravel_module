<?php
namespace Module\Application\Controllers\Logic;


use Module\Application\Events\PermissionCreated;
use Module\Application\Events\PermissionDeleted;
use Module\Application\Events\PermissionUpdated;
use Module\Application\ModelChangeCollection;
use Module\Application\Permission;


class PermissionController extends LogicController
{
    public $permissions = [];


    /**
     * @param $params [*name]
     * @return Permission
     */
    public function create($params)
    {
        $permission = Permission::create($params);

        event(new PermissionCreated($permission));


        return $permission;
    }


    /**
     * @param Permission $permission
     * @param $params [*name]
     * @return mixed
     */
    public function update(Permission $permission, $params)
    {
        $changes = app(ModelChangeCollection::class);
        $changes['permission'] = $permission->getModelChanges($params);
        $changes->checkChanges();
        $changes->save();

        event(new PermissionUpdated($permission, $changes));

        return $changes['permission']->model;
    }


    /**
     * @param Permission $permission
     * @return Permission
     */
    public function delete(Permission $permission)
    {
        $permission->delete();


        event(new PermissionDeleted($permission));


        return $permission;
    }

}
