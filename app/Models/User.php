<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    public const ROLE_ADMIN = 'admin';

    public const ROLE_MANAGER = 'manager';

    public const ROLE_EDITOR = 'editor';

    public const ROLE_CITIZEN = 'citizen';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_MANAGER,
        self::ROLE_EDITOR,
        self::ROLE_CITIZEN,
    ];

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function canManageDocuments(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_MANAGER,
            self::ROLE_EDITOR,
        ], true);
    }

    public function canManageContent(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_MANAGER,
            self::ROLE_EDITOR,
        ], true);
    }

    public function canManageProcurement(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_MANAGER,
        ], true);
    }

    public function canManageUsers(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function canManageSettings(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
