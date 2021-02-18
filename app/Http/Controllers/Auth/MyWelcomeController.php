<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response
use Spatie\WelcomeNotification\WelcomeController as BaseWelcomeController;
class MyWelcomeController extends BaseWelcomeController
{
    public function sendPasswordSavedResponse(): Response{
        return redirect()->route('home');
    }
}
