<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\RoleUser;
use App\Models\Role;
use App\Models\Company;

class CompanyController extends Controller
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
    public function save(Request $request)
    {
        dd('ocean');
        /*Validator::make($request->all(), [
            'getCompanyname'  => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'getOwnerName'    => ['required', 'string', 'max:255'],
            'getPhone'        => ['required', 'min:11', 'numeric'],
            'getAddress'      => ['required'],
        ])->validate();
        $user = User::create([
                    'first_name'   =>  $request->input('getOwnerName'),
                    'email'        =>  $request->input('email'),
                    'company_name' =>  $request->input('getCompanyname'),
                    'contact'      =>  $request->input('getPhone'),
                    'address'      =>  $request->input('getAddress'),
                    'status'       =>  0,/*
                    'image'        =>  $imageName,
                ]);
        $user->save();

        $user->addRole(Role::Company);
        $token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($user);
        $createPassword = [
            '_token' => $token,
            'email'  => $request->input('email'),
            'name'   => $request->input('name')
        ];
        if($user){
            $lastData = User::latest('id','first_name')->get()->first();
            \Mail::to($request->input('email'))->send(new \App\Mail\CreatePasswordEmail($createPassword));
            return response()->json(['message'=>'suceess','companyData'=>$lastData],200);
        }*/
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

    function checkCompanyName(Request $request){
        $company_name = $request->company_name;
        $data = Company::where('company_name',$company_name)->exists();

        if($data){
            return 'false';
        }else{
            return 'true';
        }
    }
}
