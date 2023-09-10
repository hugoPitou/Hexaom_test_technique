<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCoordinate(string $address): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.mapbox.com/geocoding/v5/mapbox.places/'.$address.'.json?access_token=pk.eyJ1IjoiZ29nb3BpdCIsImEiOiJjbG1idG13ZHMwNXhmM3BsazY1NDhkY3g5In0.Ut2XnaZm9uVVu2A68itHUA'
        );

        return $response->toArray();
    }
}