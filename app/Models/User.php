<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Compatibility method for old project code.
     * يدعم الاستدعاءات القديمة داخل المشروع.
     */
    public function hasPermission($permission, $guardName = null): bool
    {
        try {
            return $this->hasPermissionTo($permission, $guardName ?? $this->getDefaultGuardName());
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Compatibility method for old project code.
     */
    public function hasAnyPermissionName(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Compatibility method for old project code.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Compatibility method for old project code.
     */
    public function isDoctor(): bool
    {
        return $this->hasRole('doctor');
    }

    /**
     * Compatibility method for old project code.
     */
    public function isReception(): bool
    {
        return $this->hasRole('reception');
    }

    /**
     * Compatibility method for old project code.
     */
    public function isAccountant(): bool
    {
        return $this->hasRole('accountant');
    }

    /**
     * Compatibility method for old project code.
     */
    public function isHr(): bool
    {
        return $this->hasRole('hr');
    }

    /**
     * Compatibility method for old project code.
     */
    public function isInventory(): bool
    {
        return $this->hasRole('inventory');
    }
}