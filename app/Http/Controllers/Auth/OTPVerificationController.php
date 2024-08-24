<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class OTPVerificationController extends Controller
{
    public function showVerificationForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:tbl_users,id',
            'otp' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Get user by ID
        $user = User::find($request->user_id);

        // Check if OTP matches
        if ($user->otp === $request->otp) {
            // Update user's verified_at and clear OTP
            $user->update([
                'verified_at' => Carbon::now(),
                'otp' => null,
            ]);

            return response()->json(['success' => 'OTP verified successfully. Your account is now verified.'], 200);
        } else {
            return response()->json(['error' => 'Invalid OTP code. Please try again.'], 422);
        }
    }
}
