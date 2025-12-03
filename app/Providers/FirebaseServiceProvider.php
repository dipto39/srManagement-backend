<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
  public function register(): void
    {
        $this->app->singleton(Messaging::class, function ($app) {
            $credentialsPath = base_path(env('FIREBASE_CREDENTIALS'));
            
            if (!file_exists($credentialsPath)) {
                throw new \Exception("Firebase credentials file not found at: {$credentialsPath}");
            }
            
            return (new Factory)
                ->withServiceAccount($credentialsPath)
                ->createMessaging();
        });
    }

    public function boot(): void
    {
        //
    }
}
