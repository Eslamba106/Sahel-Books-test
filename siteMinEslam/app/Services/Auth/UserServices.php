<?php 

namespace App\Services\Auth;

use App\Models\UsersModel;
use Illuminate\Support\Facades\Hash;

class UserServices 
{
    public function createUser($data):UsersModel
    {
        $data['password'] = Hash::make($data['password']);
        return UsersModel::create($data);
    }
}







