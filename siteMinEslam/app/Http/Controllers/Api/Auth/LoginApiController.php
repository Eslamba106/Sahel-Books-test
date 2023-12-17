<?php
 
namespace App\Http\Controllers\Api\Auth;

use App\Models\UsersModel;
use Illuminate\Http\Request;
use App\Services\Auth\UserServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Requests\Users\LoginUserValidator;
use App\Requests\Users\CreateUserValidator;
use App\Http\Controllers\Api\Base\BaseController;

class LoginApiController extends BaseController
{
    public $userService;

    public function __construct(UserServices $userService)
    {
        $this->userService = $userService;
    }
// login function
    public function login(LoginUserValidator $loginUserValidator){

        if(!empty($loginUserValidator->getErrors()))
        {
            return response()->json($loginUserValidator->getErrors() , 406);
        }
        $request = $loginUserValidator->request();
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $user = $request->user();
            $token = $user->createToken($user->name);
            return response()->apiSuccess(['token' => $token->plainTextToken ,'user'=> $user]);
        }
        else
        {
            return $this->sendResponse('Unauthorised' , 'fail' , 401);
        }
    }
// logout function
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'massege' => 'User Successfully logout',
        ] , 200);
    }

    
}