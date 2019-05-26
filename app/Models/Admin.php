<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Self_;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|string|max:255',
            'account' => 'required|unique:admins|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function plus($data)
    {
        $this->validator($data)->validate();
        $createData = [
            'account' => $data['account'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'tel' => $data['tel'] ?? '',
            'email' => $data['email'] ?? '',
            'sex' => $data['sex'] ?? '',
        ];
        self::create($createData);
    }
}
