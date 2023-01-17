<?php

namespace Candidate\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Candidate\Http\Requests\RegisterRequest;
use Candidate\Repositories\auth\AuthCandidateInterface;

use Illuminate\Http\Request;

class ApiCandidateAuthController extends Controller
{

    protected $authCandidate, $response;
    public function __construct(AuthCandidateInterface $authCandidate, ResponseService $response)
    {
        $this->authCandidate = $authCandidate;
        $this->response = $response;
    }


    //candidate verification with otp
    public function register(RegisterRequest $request)
    {
        try {
            $candidatesubmit = $this->authCandidate->register($request);
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
            $userVerification = $this->authCandidate->verifyOtp($request);
            if ($userVerification) {
                return true;
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function passwordSubmit(Request $request)
    {
        try {

            $passwordsubmit = $this->authCandidate->passwordSubmit($request);
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
            $candidatelogin = $this->authCandidate->login($request);
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



    public function logout()
    {
        try {
            $user = auth()->user()->token();
            $user->revoke();
            return $this->response->responseSuccessMsg("Successfully logged out");
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }
}
