<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property int $role_id
 * @property string $username
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ID_SUPERADMIN = 1;
    public const ROLE_ID_ADMIN = 2;
    public const ROLE_ID_USER = 3;
    public const ROLES = [
        self::ROLE_ID_SUPERADMIN => 'Superadmin',
        self::ROLE_ID_ADMIN => 'Admin',
        self::ROLE_ID_USER => 'User',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email_verified_at',
        'name',
        'email',
        'password',
        'role_id',
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

    public function getId(): int
    {
        return $this->id;
    }

    public function getRoleId(): int
    {
        return (int)$this->role_id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (User $user) {
            $user->email_verified_at = time();
            $user->password = Hash::make($user->password);
        });

        static::updating(function (User $user) 
        {
            if (null === $user->password) {
                $user->password = $user->original['password'];
            }
        });
    }
}
