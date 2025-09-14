<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'user_avatar', 'primary_role', 'bio', 'date_of_birth', 'sex', 'display_date_of_birth',
    ];

    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    public function threads() {
        return $this->hasMany(Thread::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'primary_role');
    }

    /**
     * Checks if User has access to $permissions.
     */
    public function hasAccess(array $permissions): bool
    {
        // check if the permission is available in any role
        foreach ($this->roles as $role) {
            if ($role->hasAccess($permissions)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if the user belongs to role.
     */
    public function inRole(string $roleSlug)
    {
        return $this->roles()->where('slug', $roleSlug)->count() == 1;
    }

    /**
    * A user can have many messages
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
