<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Models\SendWelcomeNotification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        dd($data);
        return Validator::make($data, [
            'first_name'           => ['required'],
            'last_name'            => ['required'],
            'email'                => ['required', 'string', 'email', 'max:255', 'unique:users'],
            /*'password'           => ['required', 'string', 'min:8', 'confirmed'],*/
            'company_name'         => ['required', 'string', 'max:255'],
            'phone'                => ['required', 'min:11', 'numeric'],
            'address'              => ['required'],
            'g-recaptcha-response' => ['required','captcha'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'first_name'   => $data['first_name'],
            'last_name'    => $data['last_name'],
            'email'        => $data['email'],
            'company_name' => $data['company_name'],
            'contact'      => $data['phone'],
            'address'      => $data['address'],
        ]);

        $token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($user);
        $createPassword = [
            '_token' => $token,
            'email'  => $data['email'],
            'name'   => $data['first_name'].' '.$data['last_name']
        ];
        \Mail::to($data['email'])->send(new \App\Mail\CreatePasswordEmail($createPassword));
        return $user;
    }
}