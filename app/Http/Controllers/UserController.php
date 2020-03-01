<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Parser;
use Symfony\Component\HttpFoundation\Response as ResponseHTTP;
use Validator;

/**
  @OA\Info(
      description="",
      version="1.0.0",
      title="YDS - Managed Service Provider",
 )
 **/

/**
  @OA\SecurityScheme(
      securityScheme="bearerAuth",
          type="http",
          scheme="bearer",
          bearerFormat="JWT"
      ),
 **/
class AccountController extends Controller {

    /**
          @OA\Post(
              path="/v1/login",
              tags={"Login"},
              summary="Login",
              operationId="login",

              @OA\Parameter(
                  name="email",
                  in="query",
                  required=true,
                  @OA\Schema(
                      type="string"
                  )
              ),
              @OA\Parameter(
                  name="password",
                  in="query",
                  required=true,
                  @OA\Schema(
                      type="string"
                  )
              ),
              @OA\Response(
                  response=200,
                  description="Success",
                  @OA\MediaType(
                      mediaType="application/json",
                  )
              ),
              @OA\Response(
                  response=401,
                  description="Unauthorized"
              ),
              @OA\Response(
                  response=400,
                  description="Invalid request"
              ),
              @OA\Response(
                  response=404,
                  description="not found"
              ),
          )
         **/

    /**
      login API

      @return \Illuminate\Http\Response
     **/
    public function login(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);

            $data = [];

            if ($validator->fails()) {
                $errors = $validator->errors();
                foreach ($errors->all() as $field => $validationMessage) {
                    $data['error'][] = $validationMessage;
                }
                $success = [
                    'status' => ResponseHTTP::HTTP_BAD_REQUEST,
                    'data' => $data
                ];
                $message = 'Validation failed!.';
            } else {
                if (Auth::guard()->attempt(['email' => request('email'), 'password' => request('password')])) {
                    $user = Auth::user()->select('id', 'first_name', 'last_name', 'email', 'avatar', 'referral_code')->where('id', Auth::id())->get()->first();

                    $data['token'] = $user->createToken('MyApp')->accessToken;
                    $data['user'] = $user;

                    $success = [
                        'status' => ResponseHTTP::HTTP_OK ,
                        'data' => $data,
                    ];

                    $message = 'Login successfull!.';

                    //store device information
                    UserDevice::addUserDevices($request, $user, config('constants.status.active'));
                } else {
                    $success = [
                        'status' => ResponseHTTP::HTTP_BAD_REQUEST ,
                    ];
                    $message = 'Invalid Email or Password!.';

                }
            }

            return $this->APIResponse->respondWithMessageAndPayload($success ,$message);
        } catch (\Exception $e) {
            return $this->APIResponse->handleAndResponseException($e);
        }
    }
}
?>
