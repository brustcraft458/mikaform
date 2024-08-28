<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class ForgotPasswordController extends Controller
{
    public function showSendOtpForm()
    {
        return view('send-otp'); // Tampilkan form untuk mengirim OTP
    }

  

    public function sendOtp(Request $request)
    {
        // Validasi nomor telepon atau email
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            session()->put('action_message', 'forgot_password_fail');
            return redirect()->route('send-otp-form');
        }

        // Cari pengguna berdasarkan nomor telepon atau email
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            session()->put('action_message', 'forgot_password_fail_no_user');
            return redirect()->route('send-otp-form');
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Simpan OTP ke pengguna
        $user->update(['otp' => $otp]);

        // Kirim OTP melalui API bot WhatsApp dengan menyertakan token Bearer
        $waurl = env('WA_GATEWAY_URL') . '/api/messages';
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('WA_GATEWAY_KEY')
        ])->post($waurl, [
            'phone' => $user->phone,
            'text' => 'Kode OTP Anda adalah: ' . $otp
        ]);

        // Cek respons dari API bot WhatsApp
        if ($response->successful()) {
            session()->put('forgot_password_step', 'page_2_otp');
            session()->put('forgot_password_user_id', $user->id);
            return redirect()->route('reset-password-form');
        } else {
            session()->put('action_message', 'forgot_password_fail');
            return redirect()->route('send-otp-form');
        }
    }

    public function showResetPasswordForm()
    {
        // Tampilkan form untuk reset sandi setelah OTP diverifikasi
        return view('reset-password');
    }

    public function resetPassword(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('reset-password-form')
                ->withErrors($validator)
                ->withInput();
        }

        // Ambil pengguna dari sesi
        $user = User::find(session('forgot_password_user_id'));

        // Verifikasi OTP
        if ($user->otp !== $request->otp) {
            session()->put('action_message', 'forgot_password_fail_otp');
            return redirect()->route('reset-password-form');
        }

        // Update sandi pengguna
        $user->update([
            'password' => Hash::make($request->password),
            'otp' => null, // Hapus OTP setelah sukses
        ]);

        session()->forget(['forgot_password_step', 'forgot_password_user_id']); // Hapus sesi yang tidak lagi dibutuhkan
        session()->put('action_message', 'forgot_password_success');
        
        return redirect()->route('login');
    }
}
