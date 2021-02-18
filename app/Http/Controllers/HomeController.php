<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Models\RoleUser;
use App\Models\Project;
use App\Models\Company;
use Auth;
use Session;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // dd(Auth::user());
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        if(Auth::user()->hasRole('Admin')){
            $total_pm       = RoleUser::where('role_id',3)->get()->count();
            $total_staff    = RoleUser::where('role_id',1)->get()->count();
            $total_company  = Company::all()->count();
            return view('home',compact(['total_pm','total_staff','total_company']));
        }elseif(Auth::user()->hasRole('ProjectManager')){

            $user_id = Auth::user()->id;

            $company_list =  Company::all();
            /*$projects_list = Project::whereIn('pm_id',$user_id)->get();*/
            $projects_list = Project::where('pm_id',$user_id)->orWhere('pm_id', 'like', '%"'.$user_id.'"%')->get();

            return  view('home',compact(['projects_list', 'company_list']));
        }

    }

    public function changeMyProfile(){
        $data = User::findOrfail(Auth::user()->id);
        return view('common-pages.change-my-profile',compact('data'));
    }

    public function updateMyProfile(Request $request){

        if(Auth::user()->hasRole('Admin')){
            Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name'  => ['required', 'string', 'max:255'],
                'contact'    => ['required', 'min:11', 'numeric'],
                'address'    => ['required'],
            ])->validate();
        }else{
            Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name'  => ['required', 'string', 'max:255'],
                'contact'    => ['required', 'min:11', 'numeric'],
                /*'address'    => ['required'],*/
            ])->validate();
        }

        /*if image is exists*/
        if($request->hasFile('image')){
            if ($request->file('image')->isValid()) {
                $extension = $request->image->extension();
                $imageName = time().'.'.$extension;
                $supported_image = array('jpg','jpeg','png');
                if(in_array($extension, $supported_image)){
                    $data = User::find(Auth::user()->id)->update([
                        'first_name'  =>  $request->input('first_name'),
                        'last_name'   =>  $request->input('last_name'),
                        'contact'     =>  $request->input('contact'),
                        'address'     =>  $request->input('address'),
                        'image'       =>  $imageName,
                    ]);
                    if($data){
                        $request->image->move(public_path('assets/admin_logo'), $imageName);

                        $request->session()->flash('status', 'Profile successfully updated.');
                    }
                }else{
                    $request->session()->flash('error', 'Please check your image type.');
                }
            }
        }else{
            $data = User::find(Auth::user()->id)->update([
                'first_name'   =>  $request->input('first_name'),
                'last_name'    =>  $request->input('last_name'),
                'contact'      =>  $request->input('contact'),
                'address'      =>  $request->input('address'),
            ]);

            $request->session()->flash('status', 'Profile successfully updated.');
        }
        return redirect()->back();
    }

    public function changePassword(){
        return view('common-pages.changePassword');
    }

    public function updatePassword(Request $request){
        $user = Auth::user();
        $request->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($user)
                {
                    if (!Hash::check($value, $user->password)) {
                        return $fail(__('The current password is incorrect.'));
                    }
                }
            ],
            'new_password' => 'required|min:8',
            'new_confirm_password' => 'required|same:new_password'
        ], [
            'current_password.required' => 'Please enter your current password',
            'new_password.required' => 'Please enter a new password',
            'new_confirm_password.same' => 'The Confirm Password do not match'
        ]);
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        $request->session()->flash('success', 'Your password has been changed');
        if(Auth::user()->hasRole('Admin')){
            auth()->logout();
            return redirect('/admin');
        }else{
             auth()->logout();
            return redirect('/pm-login');
        }

    }

    public function forceDonwload(){
       $filename = "dummy.pdf";
       $path = public_path('/assets/pdf-file/'.$filename);

        $headers = ['Content-Type' => 'application/pdf',];
        return response()->download($path, $filename, $headers);
        // return response()->download($pathToFpathile, $name, $headers);
    }
}
