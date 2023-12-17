<?php
 
namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Services\Auth\UserServices;
use Illuminate\Support\Facades\Auth;
use App\Requests\Users\LoginUserValidator;
use App\Requests\Users\CreateUserValidator;
use App\Http\Controllers\Api\Base\BaseController;

class RegisterApiController extends BaseController
{
    public $userService;

    public function __construct(UserServices $userService)
    {
        $this->userService = $userService;
    }
    public function register(CreateUserValidator $createUserValidator)
    {
        if(!empty($createUserValidator->getErrors())){
            return response()->json($createUserValidator->getErrors() , 406);
        }
        $user=$this->userService->createUser($createUserValidator->request()->all());
        $message['user'] = $user;
        $message['token'] = $user->createToken('invoices_token')->plainTextToken;

        return $this->sendResponse($message);

    }
    // public function login(LoginUserValidator $loginUserValidator){

    //     if(!empty($loginUserValidator->getErrors())){
    //         return response()->json($loginUserValidator->getErrors() , 406);
    //     }

    //     $request = $loginUserValidator->request();
    //     if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
    //         $user = $request->user();
    //         $success['token'] = $user->createToken($user->name)->plainTextToken;
    //         $success['name'] = $user->name;
    //         return $this->sendResponse($success);
    //     }else{
    //         return $this->sendResponse('Unauthorised' , 'fail' , 401);
    //     }
    // }


}