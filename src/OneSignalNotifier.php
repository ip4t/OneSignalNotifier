<?php

namespace Nourallah\OneSignalNotifier;


use Illuminate\Support\Facades\Http;

class OneSignalNotifier
{


    protected string $appId;
    protected string $apiKey;

    public function __construct()
    {
        $this->appId = config('onesignal.app_id');
        $this->apiKey = config('onesignal.rest_api_key');
    }

    public function sendToAll(string $message, array $data = [])
    {
        return $this->send([
            'included_segments' => ['All'],
            'contents' => ['en' => $message],
            'data' => $data,
        ]);
    }

    public function sendToUser(string $playerId, string $message, array $data = [])
    {
        return $this->send([
            'include_player_ids' => [$playerId],
            'contents' => ['en' => $message],
            'data' => $data,
        ]);
    }

    protected function send(array $payload)
    {
        $payload['app_id'] = $this->appId;

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', $payload);

        return $response->successful()
            ? $response->json()
            : ['error' => $response->json()];
    }

}
