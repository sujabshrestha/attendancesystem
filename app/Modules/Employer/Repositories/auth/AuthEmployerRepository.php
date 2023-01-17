<?php

namespace Employer\Repositories\auth;

use App\Models\User;
use App\Models\UserOtp;
use Carbon\Carbon;
use Employer\Models\Employer;
use Employer\Repositories\auth\AuthEmployerInterface;
use Exception;
use Files\Repositories\FileInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class AuthEmployerRepository implements AuthEmployerInterface
{

    protected $file = null;
    public function __construct(FileInterface $file)
    {
        $this->file = $file;
    }

    public function register($request)
    {

        $user = User::create([
            'phone' => $request->phone,
            'password' => bcrypt($request->phone),
            'type' => 'employer',
        ]);


        if ($user) {
            $user->assignRole('employer');

            $user->otp()->create([
                'otp' => rand(0, 9999)
            ]);

            $employer = new Employer();
            $employer->code = 'E-' . Str::random(20);
            $employer->phone = $request->phone;
            $employer->user_id = $user->id;

            if ($employer->save()) {
                return true;
            }
            throw new Exception("Something went wrong");
        }

        throw new Exception("Something went wrong while creating candidate");
    }



    public function verifyOtp($request)
    {
        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            $useropt = UserOtp::where('user_id', $user->id)
                ->where('otp', $request->otp)->first();
            if ($useropt) {
                $dbtimestamp = strtotime($useropt->updated_at);

                if (time() - $dbtimestamp > 15 * 60) {
                    throw new \Exception('OTP Has Expired. Please Request New OTP and Verify.');
                }
                if ($request->otp == $useropt->otp) {
                    return true;
                }
            }
            throw new Exception("OTP not found. Please Request New OTP and Verify.");
        }
        throw new Exception("User not found");
    }


    public function passwordSubmit($request)
    {
        $user = User::where('phone', $request->phone)->with('otp')->first();
        if ($user) {
            $user->password = bcrypt($request->password);
            $user->update();

            $user->otp->delete();
            $token = $user->createToken('API Token')->accessToken;
            return [
                'user' => $user,
                'token' =>  $token
            ];
        }
        throw new Exception("user not found");
    }


    public function login($request)
    {
        $data = [
            'phone' => $request->phone,
            'password' => $request->password
        ];

        if (!auth()->attempt($data)) {

            throw new Exception('Incorrect Details.
            Please try again');
        }

        $token = auth()->user()->createToken('API Token')->accessToken;

        return [
            'user' => auth()->user(),
            'token' => $token
        ];
    }
}
