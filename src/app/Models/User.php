<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\Role;
use Filament\Panel;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    // protected $fillable = [
    //     'name', 'email', 'password', 'role', 'opd_id'
    // ];

    // public function opd() { return $this->belongsTo(Opd::class); }

    // public function isSuperAdmin(): bool {
    //     return $this->role === 'super_admin';
    // }

    // public function isOpd(): bool {
    //     return $this->role === 'opd';


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
    protected $casts = [
        'role' => Role::class,
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin1' => in_array($this->role, [Role::ADMIN, Role::SUPER_ADMIN]),
            'opd'    => $this->role === Role::OPD,
            default  => false,
        };
    }
}