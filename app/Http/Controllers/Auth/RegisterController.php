<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    public function webRegister()
    {
        if (!session()->has('register_step') || session('register_step') == '') {
            session()->put('register_step', 'page_1');
        }
        return view('register');
    }

    public function handleRegister(Request $request) {
        // Proccess
        if ($request->has('register_1')) {
            return $this->registerUser($request);
        } elseif ($request->has('register_2_otp')) {
            return $this->verifyOTP($request);
        }

        return response()->json($request->all());
    }

    public function registerUser(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
            'username' => 'required',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            session()->flash('action_message', 'register_fail');
            return redirect()->route('register');
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Cari
        $foundByUsername = User::where('username', $request->username)->first();
        $foundByPhone = User::where('phone', $request->phone)->first();
            
        if ($foundByUsername || $foundByPhone) {
            $foundUser = $foundByUsername ?: $foundByPhone;
        
            if (is_null($foundUser->verified_at)) {
                // Temp User
                $foundUser->delete();
            } else {
                // Clear
                session()->flush();
                session()->regenerate();
            
                // Determine action message
                $actionMessage = $foundByUsername ? 'register_fail_user_exists' : 'register_fail_phone_exists';
                session()->flash('action_message', $actionMessage);
            
                return redirect()->route('register');
            }
        }


        // Buat pengguna baru
        $user = User::create([
            'phone' => $request->phone,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'otp' => $otp,
        ]);

        // Kirim OTP melalui API bot WhatsApp dengan menyertakan token Bearer
        $waurl = env('WA_GATEWAY_URL') . '/api' . '/messages';
        $response = Http::withHeaders([
            'Authorization' => env('WA_GATEWAY_KEY')
        ])->post($waurl, [
            'phone' => $user->phone,
            'text' => 'Your OTP code is: ' . $otp
        ]);

        // Cek respons dari API bot WhatsApp
        if ($response->successful()) {
            session()->put('register_cache_user_id', $user->id);
            session()->put('register_step', 'page_2_otp');
            
            return redirect()->route('register');
        } else {
            return response()->json(['info' => 'wa gateaway error']);
        }
    }

    public function verifyOTP(Request $request) {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Get user by ID
        $user_id = session('register_cache_user_id');
        $user = User::find($user_id);

        // Clear
        session()->flush();
        session()->regenerate();

        // Check if OTP matches
        if ($user->otp === $request->otp) {
            // Update user's verified_at and clear OTP
            $user->update([
                'verified_at' => now(),
                'otp' => null,
            ]);

            
            session()->flash('action_message', 'register_success');
            return redirect()->route('form_template');
        } else {
            session()->flash('action_message', 'register_fail');
            return redirect()->route('register');
        }
    }

}
