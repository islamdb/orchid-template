<?php

namespace App\Models;

use App\Orchid\Presenters\UserPresenter;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Orchid\Attachment\Attachable;
use Orchid\Platform\Models\User as Authenticatable;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Dashboard;

class User extends Authenticatable
{
    use TwoFactorAuthenticatable, Attachable, AsSource;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'permissions',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'username',
        'email',
        'permissions',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'username',
        'email',
        'updated_at',
        'created_at',
    ];

    /**
     * Get id avatar of profile
     *
     * @return null
     */
    public function getAvatarIdAttribute()
    {
        return $this->attachment()
                ->orderByDesc('created_at')
                ->first()
                ->id ?? null;
    }

    /**
     * Get url avatar of profile
     *
     * @return null
     */
    public function getAvatarUrlAttribute()
    {
        return $this->attachment()
                ->orderByDesc('created_at')
                ->first()
                ->url ?? null;
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     *
     * @throws \Throwable
     */
    public static function createAdmin(string $name, string $email, string $password)
    {
        throw_if(static::where('email', $email)->exists(), 'User exist');

        static::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'permissions' => Dashboard::getAllowAllPermission(),
        ]);
    }

    /**
     * @return UserPresenter
     */
    public function presenter()
    {
        return new UserPresenter($this);
    }
}
