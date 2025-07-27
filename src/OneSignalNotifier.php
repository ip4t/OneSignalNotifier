<?php

namespace Nourallah\OneSignalNotifier;

use Illuminate\Support\Facades\Http;

class OneSignalNotifier
{
    protected string $appId;
    protected string $apiKey;
    protected string $endpointUrl;


    public function __construct(?string $appId = null, ?string $apiKey = null)
    {
        $this->appId = $appId ?? config('onesignal.app_id');
        $this->apiKey = $apiKey ?? config('onesignal.rest_api_key');
        $this->endpointUrl = config('onesignal.endpointUrl', 'https://onesignal.com/api/v1/notifications');
    }

    /**
     * Send notification to all users.
     * 
     * @param array|string $messages Messages by language, e.g. ['en' => 'Hello', 'ar' => 'مرحبا']
     *                                Or a simple string for default 'en'.
     * @param array $data Extra data to send.
     * @return array Response from OneSignal API
     */
    public function sendToAll(array|string $messages, array $data = []): array
    {
        $contents = $this->prepareContents($messages);

        return $this->send([
            'included_segments' => ['All'],
            'contents' => $contents,
            'data' => $data,
        ]);
    }

    /**
     * Send notification to a specific user by player ID.
     * 
     * @param string $playerId OneSignal Player ID
     * @param array|string $messages Messages by language or string
     * @param array $data Extra data
     * @return array Response from OneSignal API
     */
    public function sendToUser(string $playerId, array|string $messages, array $data = []): array
    {

        $contents = $this->prepareContents($messages);

        return $this->send([
            'include_player_ids' => [$playerId],
            'contents' => $contents,
            'data' => is_array($data) ? $data : [],
        ]);
    }

    public function sendToUsers(array $playerIds, array|string $messages, array $data = []): array
    {

        $contents = $this->prepareContents($messages);
        dd([
            'include_player_ids' => $playerIds,
            'contents' => $contents,
            'data' => is_array($data) ? $data : [],
        ]);
        return $this->send([
            'include_player_ids' => $playerIds,
            'contents' => $contents,
            'data' => is_array($data) ? $data : [],
        ]);
    }

    /**
     * Helper: Convert messages input to contents array
     * 
     * @param array|string $messages
     * @return array
     */
    protected function prepareContents(array|string $messages): array
    {
        if (is_string($messages)) {
            // If just a string, assume English
            return ['en' => $messages];
        }

        // Otherwise, return as-is (expecting ['en' => ..., 'ar' => ..., etc])
        return $messages;
    }

    /**
     * Send the notification via OneSignal API
     * 
     * @param array $payload
     * @return array
     */
    protected function send(array $payload): array
    {

        $payload['app_id'] = $this->appId;

        if (isset($payload['data']) && is_array($payload['data'])) {
            $payload['data'] = (object) $payload['data'];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->endpointUrl, $payload);

        return $response->successful()
            ? $response->json()
            : ['error' => $response->json()];
    }
}