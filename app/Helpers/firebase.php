<?php

use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

if (!function_exists('send_firebase_notification')) {
    /**
     * Send Firebase push notification
     * 
     * @param string|array $deviceTokens Single token or array of tokens
     * @param string $title Notification title
     * @param string $body Notification body
     * @param array $data Additional data payload (optional)
     * @param string|null $image Notification image URL (optional)
     * @return array Response with success/failure status
     */
    function send_firebase_notification($deviceTokens, string $title, string $body, array $data = [], ?string $image = null): array
    {
        try {
            $messaging = app(Messaging::class);
            
            // Build notification
            $notification = Notification::create($title, $body);
            
            if ($image) {
                $notification = $notification->withImageUrl($image);
            }
            
            // Handle single token
            if (is_string($deviceTokens)) {
                $message = CloudMessage::withTarget('token', $deviceTokens)
                    ->withNotification($notification);
                
                if (!empty($data)) {
                    $message = $message->withData($data);
                }
                
                $result = $messaging->send($message);
                
                return [
                    'success' => true,
                    'message' => 'Notification sent successfully',
                    'result' => $result
                ];
            }
            
            // Handle multiple tokens
            if (is_array($deviceTokens)) {
                $message = CloudMessage::new()
                    ->withNotification($notification);
                
                if (!empty($data)) {
                    $message = $message->withData($data);
                }
                
                $result = $messaging->sendMulticast($message, $deviceTokens);
                
                return [
                    'success' => true,
                    'message' => 'Notifications sent',
                    'successful' => $result->successes()->count(),
                    'failed' => $result->failures()->count(),
                    'result' => $result
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Invalid device tokens format'
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to send notification',
                'error' => $e->getMessage()
            ];
        }
    }
}

if (!function_exists('send_firebase_notification_to_topic')) {
    /**
     * Send Firebase notification to a topic
     * 
     * @param string $topic Topic name
     * @param string $title Notification title
     * @param string $body Notification body
     * @param array $data Additional data payload (optional)
     * @param string|null $image Notification image URL (optional)
     * @return array Response with success/failure status
     */
    function send_firebase_notification_to_topic(string $topic, string $title, string $body, array $data = [], ?string $image = null): array
    {
        try {
            $messaging = app(Messaging::class);
            
            $notification = Notification::create($title, $body);
            
            if ($image) {
                $notification = $notification->withImageUrl($image);
            }
            
            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification($notification);
            
            if (!empty($data)) {
                $message = $message->withData($data);
            }
            
            $result = $messaging->send($message);
            
            return [
                'success' => true,
                'message' => 'Notification sent to topic successfully',
                'result' => $result
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to send notification to topic',
                'error' => $e->getMessage()
            ];
        }
    }
}