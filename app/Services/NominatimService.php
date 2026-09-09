<?php

namespace App\Services;
use GuzzleHttp\Client;

class NominatimService
{
    private $nominatimUrl = 'https://nominatim.openstreetmap.org/search.php';
    private $userAgent = 'Mozzila/1.0 (bimbiru2010@gmail.com)';

    public function getCoordinates(string $address) {
        $client = new Client();
        $response = $client->get($this->nominatimUrl, [
            'query' => [
                'q' => $address,
                'format' => 'json',
                'limit' => 1
            ],

            'headers' => [
                'User-Agent' => $this->userAgent
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        if (!isset($data[0])) {
            return null;
        }

        return [
            'latitude' => $data[0]['lat'],
            'longitude' => $data[0]['lon']
        ];
    }
}
