<?php

namespace App\Http\Controllers\Admin\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\RoleUser;
use App\Models\Role;
use App\Models\Company;
use Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   //Auth::user()->hasRole('Admin'); 

        $data =  Company::paginate(10);
        return view('admin.company.view-company',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.company.create-company');
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
            'company_name' => ['required', 'string', 'max:255', 'unique:companies'],
            /*'email'        => ['required', 'string', 'email', 'max:255'],
            'company'      => ['required', 'string', 'max:255'],
            */// 'contact'      => ['required', 'min:11', 'numeric'],
            /*'contact'      => ['required', 'string','min:11','regex:/\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
                    2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
                    4[987654310]|3[9643210]|3[70]|7|1)\d{1,14}$/'],
            'address'      => ['required'],
            'company_logo' => ['required','mimes:jpeg,jpg,png,gif','max:10000']*/
        ],
        [
            'company_name.required' => 'Custmer name is required.',
            'company_name.unique'   => 'Customer name already exists.',
        ])->validate();
        if($request->hasFile('company_logo')){
            if ($request->file('company_logo')->isValid()) {
                
                /*image upload code */
                $extension = $request->company_logo->extension();
                $imageName = time().'.'.$extension;
                /*user create*/
                $company_data = Company::create([
                    'image'        =>  $imageName,
                    'owner_name'   =>  $request->input('name'),
                    'company_name' =>  $request->input('company_name'),
                    'email'        =>  $request->input('email'),
                    'contact'      =>  $request->input('contact'),
                    'address'      =>  $request->input('address'),
                ]);
                $company_data->save();

                if($company_data){
                    $request->company_logo->move(public_path('assets/company_logo'), $imageName);
                    $request->session()->flash('success', 'Customer added successfully.');
                }
                
            }
        }else{
            $company_data = Company::create([
                'company_name'  =>  $request->input('company_name'),
                'owner_name'    =>  $request->input('name'),
                'email'         =>  $request->input('email'),
                'contact'       =>  $request->input('contact'),
                'address'       =>  $request->input('address'),
            ]);
            $company_data->save();
            $request->session()->flash('success', 'Customer added successfully.');
        }
        return redirect()->route('admin.all-company');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company_data = Company::find($id);
        if($company_data){
            return response()->json(['company_data'=>$company_data],200);
        }else{
            return response()->json(['company_data'=>'Project manager not found.'],403);
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
        $company_data = Company::findOrfail($id);
        return view('admin.company.edit-company',compact('company_data'));
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
        $company_id = $request->input('company_id');
        // dd($request->input('contact'));
        /*Validator::make($request->all(), [
            'company_name'                  => ['required', 'string', 'max:255','unique:companies'],
            'company'               => ['required', 'string', 'max:255'],
            'contact'               => ['required', 'numeric','min:12','regex:/\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
                    2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
                    4[987654310]|3[9643210]|3[70]|7|1)\d{1,14}$/'],
            'address'               => ['required'],
        ],
        [
            'company_name.required' => 'Custmer name is required.',
            'company_name.unique'   => 'Customer name already exists.',
        ])->validate();*/
        /*if image is exists*/
        if($request->hasFile('company_logo')){
            if ($request->file('company_logo')->isValid()) {
                $extension = $request->company_logo->extension();
                $imageName = time().'.'.$extension;
                $supported_image = array('jpg','jpeg','png');
                if(in_array($extension, $supported_image)){
                    $data = Company::find($company_id)->update([
                        'owner_name'   =>  $request->input('name'),
                        'company_name' =>  $request->input('company_name'),
                        'email'        =>  $request->input('email'),
                        'contact'      =>  $request->input('contact'),
                        'address'      =>  $request->input('address'),
                        'image'        =>  $imageName,
                    ]);
                    if($data){
                        $request->company_logo->move(public_path('assets/company_logo'), $imageName);

                        $request->session()->flash('success', 'Customer Profile successfully updated.');
                    }
                }else{
                    $request->session()->flash('status', 'Please check your image type.');
                }
            }
        }else{
            $data = Company::find($company_id)->update([
                'company_name'  =>  $request->input('company_name'),
                'owner_name'    =>  $request->input('name'),
                'email'         =>  $request->input('email'),
                'contact'       =>  $request->input('contact'),
                'address'       =>  $request->input('address'),
            ]);
            $request->session()->flash('success', 'Customer Profile successfully updated.');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id){
        $pm_data = Company::find($id)->delete($id);
        $request->session()->flash('success', 'Account deleted successfully.');
        return back();
    }
}
