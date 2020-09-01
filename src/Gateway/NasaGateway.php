<?php

declare(strict_types=1);

namespace App\Gateway;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class NasaGateway
{
    /** @var HttpClientInterface */
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function isEarthInDanger(): bool
    {
        $nasaResponse = $this->httpClient->request('GET', 'https://api.nasa.gov/neo/rest/v1/feed/today?detailed=false&api_key=DEMO_KEY');
        $nearEarthObjects = json_decode($nasaResponse->getContent(), true);

        $danger = false;
        foreach(array_values($nearEarthObjects['near_earth_objects'])[0] as $neo) {
            if($neo['is_potentially_hazardous_asteroid']) {
                $danger = true;
                break;
            }
        }

        return $danger;
    }
}
