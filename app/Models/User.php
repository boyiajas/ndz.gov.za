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
        'department_id',
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

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function departments(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_user')->withTimestamps();
    }

    /**
     * Retrieve all department IDs assigned to this user.
     *
     * @return array<int>
     */
    public function departmentIds(): array
    {
        $ids = $this->relationLoaded('departments')
            ? $this->departments->pluck('id')->all()
            : $this->departments()->pluck('departments.id')->all();

        if (empty($ids) && $this->department_id) {
            $ids = [(int) $this->department_id];
        }

        return array_values(array_unique(array_filter(array_map('intval', $ids))));
    }

    public function hasDepartment(int $departmentId): bool
    {
        return in_array($departmentId, $this->departmentIds(), true);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isGlobalAdmin(): bool
    {
        return $this->isAdmin() || ($this->role === self::ROLE_MANAGER && empty($this->departmentIds()));
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

    public function canManageVacancies(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_MANAGER,
            self::ROLE_EDITOR,
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

    public function canViewAuditLogs(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_MANAGER,
        ], true);
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
