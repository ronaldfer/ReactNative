<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\Role;
use App\Models\Company;

class ProjectManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'first_name'           => ['required', 'string', 'max:255'],
            'last_name'            => ['required', 'string', 'max:255'],
            'email'                => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'company'              => ['required', 'string', 'max:255'],
            'phone'                => ['required', /*'min:11', 'numeric'*/],
            'g-recaptcha-response' => 'required|captcha',
        ],
        [
            'first_name.required'           => 'First name is required.',
            'last_name.required'            => 'Last name is required.',
            'email.required'                => 'Email is required.',
            'email.email'                   => 'Please enter a valid email address.',
            'company.required'              => 'Company name is required.',
            'phone.required'                => 'Phone number is required.',
            //'phone.numeric'                 => 'Enter valid numric phone number.',
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.'
        ])->validate();

        /*create Company*/
        $company = Company::create([
            'company_name' => $request->input('company'),
        ]);
        $company->save();

        $user = User::create([
            'first_name'   =>  $request->input('first_name'),
            'last_name'    =>  $request->input('last_name'),
            'email'        =>  $request->input('email'),
            'company_id'   =>  $company->id,
            'contact'      =>  $request->input('phone'),
        ]);
        $user->save();
        $user->addRole(Role::ProjectManager);
        if($user){
            $token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($user);
            $createPassword = [
                '_token' => $token,
                'email'  => $request->input('email'),
                'name'   => $request->input('first_name').' '.$request->input('last_name'),
            ];

            \Mail::to($request->input('email'))->send(new \App\Mail\CreatePasswordEmail($createPassword));

            $request->session()->flash('success', 'Please check your email.');
        }
        // $user->id
        return redirect()->route('login');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function backupCodePMRegister(){
        var_dump(empty($request->input('company')));
        // dd($request->all());
        /*if($request->input('company') == 0 && $request->input('company') != null ){
        */
        if(empty($request->input('company')) && $request->input('company') != null ){
            dd("if");
            /*create company*/
            Validator::make($request->all(), [
                'first_name'           => ['required', 'string', 'max:255'],
                'last_name'            => ['required', 'string', 'max:255'],
                'email'                => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'company'              => ['required', 'string', 'max:255'],
                'phone'                => ['required', /*'min:11',*/ 'numeric'],
                // 'address'              => ['required'],
                'g-recaptcha-response' => 'required|captcha',
            ],
            [
                'first_name.required'           => 'First name is required.',
                'last_name.required'            => 'Last name is required.',
                'email.required'                => 'Email is required.',
                'email.email'                   => 'Please enter a valid email address.',
                'company.required'              => 'Company name is required.',
                'phone.required'                => 'Phone number is required.',
                //'phone.min'                     => 'Enter valid phone number.',
                'phone.numeric'                 => 'Enter valid numric phone number.',
                // 'phone.regex'                   => 'Enter country code.',
                'address.required'              => 'Address is required.',
                'g-recaptcha-response.required' => 'Please verify that you are not a robot.'
            ])->validate();
            $company = Company::create([
                'company_name' => $request->input('others_company'),
            ]);
            $company->save();
            // dd($company->id);
            $user = User::create([
                'first_name'   =>  $request->input('first_name'),
                'last_name'    =>  $request->input('last_name'),
                'email'        =>  $request->input('email'),
                'company_id'   =>  $company->id,
                'contact'      =>  $request->input('phone'),
            ]);
            $user->save();
            $user->addRole(Role::ProjectManager);
            if($user){
                $token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($user);
                $createPassword = [
                    '_token' => $token,
                    'email'  => $request->input('email'),
                    'name'   => $request->input('first_name').' '.$request->input('last_name'),
                ];

                \Mail::to($request->input('email'))->send(new \App\Mail\CreatePasswordEmail($createPassword));

                $request->session()->flash('success', 'Please check your email.');
            }
        }else{
            Validator::make($request->all(), [
                'first_name'           => ['required', 'string', 'max:255'],
                'last_name'            => ['required', 'string', 'max:255'],
                'email'                => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'company'              => ['required', 'string', 'max:255'],
                'phone'                => ['required', 'min:11', 'numeric'],
                'g-recaptcha-response' => 'required|captcha',
            ],
            [
                'first_name.required'           => 'First name is required.',
                'last_name.required'            => 'Last name is required.',
                'email.required'                => 'Email is required.',
                'email.email'                   => 'Please enter a valid email address.',
                'company.required'              => 'Company name is required.',
                'phone.required'                => 'Phone number is required.',
                //'phone.min'                     => 'Enter valid phone number.',
                'phone.numeric'                 => 'Enter valid numric phone number.',
                'g-recaptcha-response.required' => 'Please verify that you are not a robot.'
            ])->validate();
            $user = User::create([
                'first_name'   =>  $request->input('first_name'),
                'last_name'    =>  $request->input('last_name'),
                'email'        =>  $request->input('email'),
                'company_id'   =>  $request->input('company'),
                'contact'      =>  $request->input('phone'),
            ]);
            $user->save();

            $user->addRole(Role::ProjectManager);
            if($user){
                $token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($user);
                $createPassword = [
                    '_token' => $token,
                    'email'  => $request->input('email'),
                    'name'   => $request->input('first_name').' '.$request->input('last_name'),
                ];

                \Mail::to($request->input('email'))->send(new \App\Mail\CreatePasswordEmail($createPassword));
                $request->session()->flash('success', 'Please check your email.');
                // $request->session()->flash('success', 'Project Manager created successfully .');
            }
        };

        /*$user = User::create([
            'first_name'   =>  $request->input('first_name'),
            'last_name'    =>  $request->input('last_name'),
            'email'        =>  $request->input('email'),
            'company_name' =>  $request->input('company_name'),
            'contact'      =>  $request->input('phone'),/*
            'address'      =>  $request->input('address'),
        ]);
        $user->save();

        $user->addRole(Role::ProjectManager);
        $name = $request->input('first_name').' '.$request->input('last_name');

        $token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($user);
        $createPassword = [
            '_token' => $token,
            'email'  => $request->input('email'),
            'name'   => $name
        ];

        \Mail::to($request->input('email'))->send(new \App\Mail\CreatePasswordEmail($createPassword));*/



    }

}
