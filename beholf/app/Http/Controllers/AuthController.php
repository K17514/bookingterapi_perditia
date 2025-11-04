<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',      // at least one uppercase letter
                'regex:/[0-9]/',      // at least one digit
                'regex:/[\W]/',       // at least one special character (non-word character)
            ],
            // 'g-recaptcha-response' => 'required|captcha',
            'foto' => 'nullable|image|max:2048',

        ]);


        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 4,  // Automatically assign level = 4
        ];

        // Handle foto upload if exists
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/fotos'), $filename);
            $data['foto'] = 'uploads/fotos/' . $filename; // <- FIXED
        }


        User::create($data);

        return redirect()->route('login')->with('success', 'Register successful. Please login.');
    }


    public function showLoginForm()
    {
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        session(['captcha_sum' => $num1 + $num2]);
        return view('auth.login', compact('num1', 'num2'));
    }
    //    public function login(Request $request): RedirectResponse
// {
//     // Check if Google reCAPTCHA is available
//     if (empty($request->input('g-recaptcha-response'))) {
//         // If Google reCAPTCHA is not available, validate offline captcha
//         $request->validate([
//             'email' => 'required|email',
//             'password' => 'required',
//             'captcha' => 'required|integer',  // Offline captcha
//         ]);

    //         // Validate offline captcha manually
//         if ($request->captcha != session('captcha_sum')) {
//             return back()->withErrors(['captcha' => 'Incorrect captcha answer'])->withInput();
//         }
//     } else {
//         // If Google reCAPTCHA is available, validate the reCAPTCHA response
//         $request->validate([
//             'email' => 'required|email',
//             'password' => 'required',
//             'g-recaptcha-response' => 'required|captcha', // reCAPTCHA validation
//         ]);
//     }

    //     // Attempt login with email + password
//     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
//         $request->session()->regenerate();
//         return redirect()->route('home');
//     }

    //     // If login fails
//     return back()
//         ->withErrors(['email' => 'Invalid credentials.'])
//         ->onlyInput('email');
// }

    public function login(Request $request): RedirectResponse
    {
        // Check if Google reCAPTCHA is available
        if (empty($request->input('g-recaptcha-response'))) {
            // If Google reCAPTCHA is not available, validate offline captcha
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'captcha' => 'required|integer',  // Offline captcha
            ]);

            // Validate offline captcha manually
            if ($request->captcha != session('captcha_sum')) {
                return back()->withErrors(['captcha' => 'Incorrect captcha answer'])->withInput();
            }
        } else {
            // If Google reCAPTCHA is available, validate the reCAPTCHA response
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'g-recaptcha-response' => 'required|captcha', // reCAPTCHA validation
            ]);
        }

        // Attempt login with email + password
        $credentials = $request->only('email', 'password');

        // Check if the "Remember Me" checkbox is checked
        $remember = $request->has('remember'); // This will be true if "Remember Me" is checked

        // Authenticate and remember user if "Remember Me" is checked
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user(); // get logged in user

            switch ($user->level) {
                case '1':
                    return redirect()->route('home');
                case '2':
                    return redirect()->route('home');
                case '3':
                    return redirect()->route('home');
                default:
                    return redirect()->route('loadscreen'); // fallback
            }
        }


        // If login fails
        return back()
            ->withErrors(['email' => 'Invalid credentials.'])
            ->onlyInput('email');
    }

    //     public function logout(Request $request): RedirectResponse
//     {
//         Auth::logout();
//         $request->session()->invalidate();
//         $request->session()->regenerateToken();

    //         return redirect()->route('login');
//     }
// }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
