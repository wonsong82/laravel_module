<?php

namespace Module\Application;

use Module\Account\Role;
use Module\Account\User;
use Module\Application\Traits\HasConstants;
use Backpack\CRUD\CrudTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use CrudTrait, HasConstants;


    protected $table = 'notifications';
    protected $fillable = [
        'type_code',
        //type,
        'notifyable_type',
        'notifyable_id',
        'title',
        'detail',
        //is_read,
    ];


    /***
     * Notification bound to individual users only
     */
    public function getIsNewAttribute()
    {
        if(auth()->guest()){
            throw new AuthenticationException();
        }

        $user = $this
            ->users()
            ->where('id', auth()->user()->id)
            ->first();

        return $user->pivot->is_read;
    }


    public function setUsers($users)
    {
        $this->users()->attach($users->pluck('id'));
    }


    public function setUsersHasPermissions($permissions)
    {
        $permissions = is_string($permissions)? [$permissions] : $permissions;

        $users = User::where(function($q) use ($permissions){
            $q->whereHas('roles', function($q) use ($permissions){
                $q->whereHas('permissions', function($q) use ($permissions){
                    $q->where(function($q) use($permissions){
                        foreach($permissions as $permission){
                            $q->orWhere('permissions.name', $permission);
                        }
                    });
                });
            });
        })->orWhere(function($q) use ($permissions){
            $q->whereHas('permissions', function($q) use($permissions){
                $q->where(function($q) use($permissions){
                    foreach($permissions as $permission){
                        $q->orWhere('permissions.name', $permission);
                    }
                });
            });
        })->pluck('id');

        $this->users()->attach($users);

        return $this;
    }


    public function setUsersHasRoles($roles)
    {
        $roles = is_string($roles) ? [$roles] : $roles;

        $users = User::wherehas('roles', function($q) use ($roles){
            $q->where(function($q) use ($roles){
                foreach($roles as $role){
                    $q->orWhere('roles.name', $role);
                }
            });
        })->pluck('id');

        $this->users()->attach($users);

        return $this;
    }





    // RELATIONS

    public function notifyable()
    {
        return $this->morphTo();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_read', 'read_at');
    }



}
