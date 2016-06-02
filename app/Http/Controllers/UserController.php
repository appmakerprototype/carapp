<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class UserController extends Controller {

    protected $user;

    public function __construct(UserRepository $user) {
        $this->user = $user;
    }

    /**
     * Register the user
     * 
     * @param Request $request
     * @return type
     */
    public function register(Request $request) {
        Controller::validator($request, ['email' => 'required', 'password' => 'required', 'name' => 'required', 'type' => 'required']);

        DB::table('users')->insert([
            "email" => $request->email,
            "password" => Auth\PasswordController::Encrypt($request->email, $request->password),
            "name" => $request->name,
            "type" => $request->type,
            "api_key" => Auth\PasswordController::generateApiKey(),
            "key_code" => Auth\PasswordController::generateRandomString()
        ]);
        
        $json = [];
        $json["success"] = TRUE;
        $json["message"] = "User Successfully Registered!";
        
        return response()->json($json, 201)->header('Content-type', 'application/json');
    }

    /**
     * Login User
     * 
     * @param Request $request
     * @return type
     */
    public function login(Request $request) {
        Controller::validator($request, ['email' => 'required', 'password' => 'required']);

        $email = $request->email;
        $password = Auth\PasswordController::Encrypt($email, $request->password);
        $pass_enc = $user_email = "";
        $json = [];

        $users = $this->user->getUser($email);
        foreach ($users as $user) {
            $user_email = $user->email;
            $pass_enc = $user->password;
        }

        if (strcasecmp($email, $user_email) != 0) {
            $json["success"] = false;
            $json["message"] = "Invalid Email!";
        } else if (strcasecmp($password, $pass_enc) != 0) {
            $json["success"] = false;
            $json["message"] = "Invalid password!";
        } else {
            $json["success"] = true;
            $json["message"] = "Successfully logged in!";
        }

        return response()->json($json, 200)->header('Content-type', 'application/json');
    }

}
