<?php

namespace App\Services\Auth;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\ApiException;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class AuthService
{
    use HasApiTokens, Notifiable; // add HasApiTokens here
    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return false; // invalid credentials
        }

        // create API token (Sanctum)
        $token = $user->createToken('API Token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }

    public function profile()
    {
        return Auth::user();
    }

    // Send OTP
    public function sendOtp(string $identifier, string $type, string $action): array
    {
        // Check otp is exist or not
        if ($action == 'reset') {
            $user = User::where('email', $identifier)->first();
            if (!$user) {
                throw new ApiException('User not found', 400);
            }
        }
        $otp = Otp::where('identifier', $identifier)->where('action', $action)->first();
        if ($otp) {
            if ($otp->isValid()) {
                throw new ApiException('OTP already sent, please wait for 2 minutes.', 400);
            } else {
                $otp->delete();
            }
        }


        // Generate OTP
        $otpCode = generateOTP(6);

        // Save hashed OTP
        $otp = Otp::create([
            'identifier' => $identifier,
            'otp'        => $otpCode,
            'type'       => $type,
            'action'     => $action,
            'expires_at' => now()->addMinutes(2),
        ]);

        // Send OTP via Email
        // if ($type === 'email') {
        //      Mail::to($identifier)->send(new OtpMail($otpCode));
        // }

        return [
            'status' => true,
            'message' => 'OTP sent successfully' . " " . $otpCode
        ];
    }


    //  Verify OTP
    public function verifyOtp(string $identifier, string $otp, string $action): array
    {
        $otpRecord = Otp::where('identifier', $identifier)
            ->where('action', $action)
            ->where('expires_at', '>', now())
            ->where('otp', $otp)
            ->first();

        if (!$otpRecord) {
            throw new ApiException('Invalid OTP', 400);
        }
        // Delete OTP
        $otpRecord->delete();
        return [
            'status' => true,
            'message' => 'OTP verified successfully'
        ];
    }

    // Reset Password

    public function forgotPassword(array $data)
    {
        $user = User::where('email', $data['user']->email)->first();
        $user->password = Hash::make($data['password']);
        $user->save();

     // 🔥 Revoke all tokens after reset
        $user->tokens()->delete();

        return $user;
    }
    public function resetPassword(string $newPassword , $oldPassword, $user)
    {
        // Check old password
        if (!Hash::check($oldPassword, $user->password)) {
            throw new ApiException('Invalid old password', 400);
        }
        // Update password
        $user->password = Hash::make($newPassword);
        $user->save();
        return $user;
    }
}
