<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\assitances\assitances;
use App\Models\Ficha\Ficha;
use App\Models\Profiles\Profiles;
use App\Models\UserstatusServices\user_statuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'document',
        'email',
        'password',
        'status_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function perfil()
    {
        return $this->hasOne(Profiles::class, 'usuario_id');
    }
    public function status()
    {
        return $this->belongsTo(user_statuses::class, 'status_id');
    }
    public function assistances(): HasMany
    {
        return $this->hasMany(assitances::class, 'user_id');
    }
    public function fichas()
    {
        return $this->belongsToMany(Ficha::class,'ficha_user','usuario_id','ficha_id');
    }
}
