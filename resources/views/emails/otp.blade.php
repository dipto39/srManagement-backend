@extends('emails.layouts.master')

@section('content')
    <p>Your OTP code for verification is:</p>
    <div class="otp">{{ $otp }}</div>
    <p>This OTP is valid for the next {{ $expires ?? 2 }} minutes. Do not share it with anyone.</p>
    <p class="footer">If you did not request this, please ignore this email.</p>
@endsection
