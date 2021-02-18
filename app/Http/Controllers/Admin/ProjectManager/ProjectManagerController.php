<?php

namespace App\Http\Controllers\Admin\ProjectManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;

class ProjectManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd()
        $data =  User::whereHas('roles', function($role) {
            $role->where('name', '=','ProjectManager');
        })->paginate(10);

        $company_data =  Company::all();

        return view('admin.projectManager.view-project-manager',compact(['data','company_data']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data =  Company::all();
        return view('admin.projectManager.create-project-manager',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        Validator::make($request->all(), [
            'first_name'    => 'required |string | max:255',
            'last_name'     => 'required |string | max:255',
            'email'         => 'required |string | email | max:255 | unique:users',
            'company'       => 'required |string | max:255',
            // 'contact'       => 'required |min:11|integer',
            'contact'       => ['required', 'string','min:10',/*'regex:/\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
                    2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
                    4[987654310]|3[9643210]|3[70]|7|1)\d{1,14}$/'*/],
        ],
        [
            'first_name.required' => 'First name is required.',
            'last_name.required'  => 'Last name is required.',
            'email.required'      => 'Email is required.',
            'email.email'         => 'Please enter a valid email address.',
            'company.required'    => 'Company is required.',
            'contact.required'    => 'Contact number is required.',
            'contact.min'         => 'Contact number must have 11 digits.',
            /*'contact.regex'       => 'Enter contact number with country code.,'*/
        ])->validate();
        //dd($request->input('others_company'));
        if($request->input('company') == 0){
            /*create company*/
            $company = Company::create([
                'company_name' => $request->input('others_company'),
            ]);
            $company->save();
            $user = User::create([
                'first_name'    =>  $request->input('first_name'),
                'last_name'     =>  $request->input('last_name'),
                'email'         =>  $request->input('email'),
                'company_id'    =>  $company->id,
                'contact'       =>  $request->input('contact'),
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

                $request->session()->flash('success', 'Project Manager added successfully .');
            }
        }else{
            $user = User::create([
                'first_name'    =>  $request->input('first_name'),
                'last_name'     =>  $request->input('last_name'),
                'email'         =>  $request->input('email'),
                'company_id'    =>  $request->input('company'),
                'contact'       =>  $request->input('contact'),
                // 'address'       =>  $request->input('address'),
                // 'password' => Hash::make($data['password']),
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

                $request->session()->flash('success', 'Project Manager added successfully .');
            }
        };
        // if($request->input('company'))

        return redirect()->route('admin.all-project-manager');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pm_data = User::find($id);
        $company_data = Company::find($pm_data->company_id,['company_name']);
        if($company_data == null){
           $company_data = [];
        }
        // dd('else');

        // dd($company_data);
        if($pm_data){
            return response()->json(['pm_data'=>$pm_data,'company_data'=>$company_data],200);
        }else{
            return response()->json(['pm_data'=>'Project manager not found.'],403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pm_data = User::findOrFail($id);
        $data =  Company::all();
        return view('admin.projectManager.edit-project-manager',compact(['pm_data','data']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $pm_id = $request->input('pm_id');
        // dd($request->input('contact'));
        Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'company'    => 'required|string|max:255',
            // 'contact'    => 'required|digits:11|numeric',
            'contact'    => ['required', 'string','min:10',/*'regex:/\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
                    2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
                    4[987654310]|3[9643210]|3[70]|7|1)\d{1,14}$/'*/],
        ])->validate();

        $data = User::find($pm_id)->update([
            'first_name'   =>  $request->input('first_name'),
            'last_name'    =>  $request->input('last_name'),
            'company_id'   =>  $request->input('company'),
            'contact'      =>  $request->input('contact'),
            // 'address'      =>  $request->input('address'),
        ]);
        $request->session()->flash('success', 'Profile updated successfully.');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $pm_data = User::find($id)->delete($id);

        $RoleUser = RoleUser::where('user_id',$id)->delete($id);
        $request->session()->flash('status', 'Account deleted successfully.');
        return back();
    }


    public function changeStatus(Request $request){
        $pm_data = User::findOrfail($request->user_id);
        $pm_data->status = $request->status;
        $pm_data->save();
        $msg = $request->status == 1 ? 'Project Manager account activated successfully.' : 'Project Manager account deactivated successfully.';
        $data = $pm_data->save() ? $msg :'';
        return response()->json(['success'=>$data],200);
    }
}
