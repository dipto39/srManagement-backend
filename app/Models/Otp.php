<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'otp',
        'type',
        'action',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // Check if OTP is valid
    public function isValid(): bool
    {
        return $this->expires_at && $this->expires_at->isFuture();
    }

    // Generate OTP helper
    public static function generate(string $identifier, string $type = 'email', string $action = 'register', int $length = 6, int $minutes = 5)
    {
        $otp = rand(pow(10, $length - 1), pow(10, $length) - 1);

        return self::create([
            'identifier' => $identifier,
            'otp' => $otp,
            'type' => $type,
            'action' => $action,
            'expires_at' => Carbon::now()->addMinutes($minutes),
        ]);
    }
}
