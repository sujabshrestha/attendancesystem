<?php

namespace Employer\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Candidate\Http\Requests\RegisterRequest;
use Employer\Http\Requests\LoginRequest;
use Employer\Repositories\auth\AuthEmployerInterface;
use Illuminate\Http\Request;

class ApiEmployerAuthController extends Controller
{

    protected $authEmployer, $response;
    public function __construct(AuthEmployerInterface $authEmployer, ResponseService $response)
    {
        $this->authEmployer = $authEmployer;
        $this->response = $response;
    }


    //candidate verification with otp
    public function register(RegisterRequest $request)
    {
        try {
            $candidatesubmit = $this->authEmployer->register($request);
            if ($candidatesubmit) {
                return $this->response->responseSuccessMsg("Successfully Registered. Otp Sent Successfull");
            }
            return $this->response->responseError("Something went wrong", 400);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function verifyOtp(Request $request)
    {

        try {
            $userVerification = $this->authEmployer->verifyOtp($request);
            if ($userVerification) {
                return $this->response->responseSuccessMsg("Successfully Verified", 200);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function passwordSubmit(Request $request)
    {
        try {

            $passwordsubmit = $this->authEmployer->passwordSubmit($request);
            if ($passwordsubmit) {
                $user = $passwordsubmit['user'];
                $token = $passwordsubmit['token'];
                return response(['user' => $user, 'token' => $token]);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function login(Request $request)
    {
        try {
            $candidatelogin = $this->authEmployer->login($request);
            if ($candidatelogin) {
                $data = [
                    'user' => $candidatelogin['user'],
                    'token' => $candidatelogin['token']
                ];
                return $this->response->responseSuccess($data, "Success", 200);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }


        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($data)) {
            return response(['error_message' => 'Incorrect Details.
            Please try again']);
        }

        $token = auth()->user()->createToken('API Token')->accessToken;

        return response(['user' => auth()->user(), 'token' => $token]);
    }


    public function logout(){
        try{
            $user = auth()->user()->token();
            $user->revoke();
            return $this->response->responseSuccessMsg("Successfully logged out");
        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }

}
