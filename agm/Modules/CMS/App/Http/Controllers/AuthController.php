<?php

namespace Modules\CMS\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\CMS\App\Helpers\SessionHelper;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('cms.index');
        }
        return view('cms::auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            // Lấy thông tin user hiện tại
            $user = Auth::guard('web')->user();

            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            // Lưu token và thông tin user vào session
            session([
                'api_token_admin' => $token,
                'user_data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);

            SessionHelper::pushToastNotification('Welcome to the CMS', 'success');
            return redirect()->intended(route('cms.index'));
        }
        return redirect()->back()->with('flash_message', 'The provided credentials do not match our records.')
                    ->with('flash_type', 'error')
                    ->withInput($request->except('password'));

    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('cms.login')->with('clear_token', true);
    }
}
