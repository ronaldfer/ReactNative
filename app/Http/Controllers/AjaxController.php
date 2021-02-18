<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Company;

class AjaxController extends Controller
{
    public function get_company($id){
    	$data = User::where('company_id',$id)->get(['id','first_name','last_name']);
        
    	if(!$data->isEmpty()){
            return response()->json($data);
        }else{
            return response()->json($data);
        }
    }

    public function get_email(Request $request){
        $email = $request->email;
    	$data = User::where('email',$email)->exists();
        
    	if($data){
            return 'false';
        }else{
            return 'true';
        }
    }

    public function check_company_name($id){
    	$data = Company::where('company_name',$id)->get(['company_name']);
    	if($data){
            return "false";
        }else{
            return "true";
        }
    }
}
