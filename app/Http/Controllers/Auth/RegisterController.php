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
        return view('register');
    }

    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:tbl_users,phone|numeric',
            'username' => 'required',
            'password' => 'required|min:8',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Generate OTP
        $otp = rand(100000, 999999);
    
        // Buat pengguna baru
        $user = User::create([
            'phone' => $request->phone,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'otp' => $otp,
        ]);
    
        // Kirim OTP melalui API bot WhatsApp dengan menyertakan token Bearer
        $response = Http::withHeaders([
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI5Y2Q0NDE1Mi1iYTNmLTQ3YTEtYjU4Ni0zZjAwMmM0ZjEzMTMiLCJuYW1lIjoiYmlzd2EiLCJpYXQiOjE3MjQzNTQ0MjksImV4cCI6MTc1NTg5MDQyOX0.AhJftxPmNg3E7GY55QxWjwZelJG0IML_SscmQdGqEQc'
        ])->post('https://wa-server.muhsalfazi.my.id/api/messages', [
            'phone' => $user->phone,
            'text' => 'Your OTP code is: ' . $otp,
            'customer' => [
                'id' => $user->id
            ]
        ]);
    
        // Cek respons dari API bot WhatsApp
        if ($response->successful()) {
            $responseData = $response->json();
    
            // Pastikan kunci 'data' dan 'status' ada di dalam respons
            if (isset($responseData['data']['status']) && !in_array($responseData['data']['status'], ['pending', 'sending', 'success', 'failed'])) {
                return redirect()->back()->with('alert', 'Send Kode otp: ' . $responseData['data']['status']);
            }
    
            // Redirect ke halaman OTP jika berhasil
            return redirect()->route('otp.verify')->with('alert', 'OTP has been sent to your phone.');
        } else {
            // Tampilkan alert jika gagal mengirim OTP via WhatsApp
            return redirect()->back()->with('alert', 'Failed to send OTP via WhatsApp.');
        }
    }
    
}
