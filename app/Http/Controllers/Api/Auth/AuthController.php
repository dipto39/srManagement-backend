<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Mail\UserWelcomeMail;
use App\Services\Auth\AuthService;
use App\Traits\ApiResponse;
use App\Traits\FileUpload;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;
    use FileUpload;
    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $loginData = $this->authService->login($credentials);

        if (!$loginData) {
            return $this->error('Invalid email or password', 401);
        }

        return $this->success($loginData, 'Login successful');
    }

    public function register(RegistrationRequest $request)
    {
        $data = $request->validated();

        $user = $this->authService->register($data);

        return $this->success($user, 'Registration successful', 201);
    }

    public function profile(Request $request)
    {
        $user = $this->authService->profile();

        return $this->success($user, 'Profile retrieved successfully');
    }


    public function sendMail()
    {
        $user = (object)[
            'name' => 'John Doe',
            'email' => 'diptobiswas39@gmail.com'
        ];
        $filePath = storage_path('app/public/s3.png'); // your image path
        Mail::to($user->email)->send(new UserWelcomeMail($user, $filePath));

        return $this->success(null, 'Welcome email sent successfully');
    }

    public function fileUpload(Request $request)
    {
        
        $file = $request->file('file');
        if($request->hasFile('file') == false){
            return $this->error('No file uploaded', 400);
        }else{
           $path = $this->uploadFile($request->file('file'), 'image', 'Codecanyon/uploads',null,'s3');
            return $this->success($path, 'File uploaded successfully');
        }
        
        // Get file from request
        // $path = $this->fileUrl("uploads/S4t1ZEEFpONGY6khmsyy.jpg", 's3');
        // return $this->success($path, 'File URL generated successfully');

        // Delete file
        // $path = "uploads/BE6kvm7s0NzhBrwfWiM0.jpg";
        // $deleted = $this->deleteFile($path, 's3');
        // if (!$deleted) {
        //     return $this->error('File not found or could not be deleted', 404);
        // }
        // return $this->success(null, 'File deleted successfully');
    }

}
