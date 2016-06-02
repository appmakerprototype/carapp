<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Validator;

class Controller extends BaseController {

    use AuthorizesRequests,
        AuthorizesResources,
        DispatchesJobs,
        ValidatesRequests;

    /**
     * Echo JSON Response
     */
    private static function echoResponse() {
        $json["success"] = false;
        $json["message"] = "Required field(s) is missing or empty";
        header('Content-type: application/json');
        http_response_code(400);

        echo json_encode($json);
        die();
    }

    /**
     * Custom Validator
     * 
     * @param Request $request - user request
     * @param array $validate_fields - type of fields to be validate
     */
    public static function validator($request, Array $validate_fields) {
        $validator = Validator::make($request->all(), $validate_fields);

        if ($validator->fails()) {
            Controller::echoResponse();
        }
    }

}
