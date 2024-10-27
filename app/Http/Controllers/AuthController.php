<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
     /**
     * @return View
     */
    public function showLogin()
    {
        return view('login.form');
    }

    /**
     * @param App\Http\Requests\LoginFormRequest $tequest
     */
    public function login(LoginFormRequest $request)
    {
        $credentials = $request->only('login_id', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            //sessionに「store_id」を保存する
            $request->session()->put('store_id', Auth::user()->store_id);

            return redirect()->route('home');
        }

        return back()->withErrors([
            'login_error' => 'ユーザIDかパスワードが間違っています。',
        ]);
    }

    /**
     * ユーザーをアプリケーションからログアウトさせる
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('showLogin')->with('logout','ログアウトしました。');
    }

}
