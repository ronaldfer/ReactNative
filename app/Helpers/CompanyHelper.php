<?php
namespace App\Helpers;
use App\Models\Company;

class CompanyHelper
{
	public static function getCompany()
    {
    	$data =  Company::all();
        return json_encode($data);
    }
}