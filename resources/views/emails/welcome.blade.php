@extends('emails.layouts.master')

@section('content')
<h2>Welcome to Car2Go Pro,{{ $user->name }}!</h2>
<p>Your account has been successfully created.</p>

<p>Thank you,<br> {{ config('app.name', 'Laravel') }}</p>
@endsection
