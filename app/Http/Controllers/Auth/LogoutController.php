<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        Session()->flush();

        return response()->json([
            'code'  => 2000,
            'data'  => ''
        ]);
    }
}
