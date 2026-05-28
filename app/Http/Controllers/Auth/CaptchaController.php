<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    public function generate()
    {
        return captcha_img();
    }

    public function validate(Request $request)
    {
        $request->validate([
            'captcha' => 'required|captcha',
        ]);

        return response()->json(['success' => true]);
    }
}
