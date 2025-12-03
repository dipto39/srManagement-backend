<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    // Authentication Example
    public function verifyToken(Request $request, Auth $auth)
    {
        try {
            $idToken = $request->bearerToken();
            $verifiedIdToken = $auth->verifyIdToken($idToken);
            $uid = $verifiedIdToken->claims()->get('sub');
            
            return response()->json([
                'success' => true,
                'uid' => $uid
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 401);
        }
    }

    // Database Example
    public function getUsers(Database $database)
    {
        $reference = $database->getReference('users');
        $snapshot = $reference->getSnapshot();
        
        return response()->json($snapshot->getValue());
    }

    // Create User Example
    public function createUser(Request $request, Database $database)
    {
        $reference = $database->getReference('users');
        $newUser = $reference->push([
            'name' => $request->name,
            'email' => $request->email,
            'created_at' => now()->toIso8601String()
        ]);
        
        return response()->json([
            'success' => true,
            'id' => $newUser->getKey()
        ]);
    }

    // Storage Example
    public function uploadFile(Request $request, Storage $storage)
    {
        $file = $request->file('file');
        $bucket = $storage->getBucket();
        
        $object = $bucket->upload(
            file_get_contents($file->getRealPath()),
            [
                'name' => 'uploads/' . time() . '_' . $file->getClientOriginalName(),
                'metadata' => [
                    'contentType' => $file->getMimeType()
                ]
            ]
        );
        
        return response()->json([
            'success' => true,
            'url' => $object->signedUrl(new \DateTime('+1 hour'))
        ]);
    }
}