<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Exception;

/**
 * This class[service] is responsible for making requests to the
 * external API endpoints for subscribers and enquiries.
 */
class CrmApiClient
{
    private HttpClientInterface $httpClient;
    private string $apiBaseUrl;
    private string $apiToken;

    /**
     * Default constructor.
     *
     */
    public function __construct(HttpClientInterface $httpClient, string $apiBaseUrl, string $apiToken)
    {
        $this->httpClient = $httpClient;
        $this->apiBaseUrl = rtrim($apiBaseUrl, '/');
        $this->apiToken = $apiToken;

        if (empty($this->apiBaseUrl) || empty($this->apiToken)) {
            throw new \InvalidArgumentException('API Base URL and Token must be configured.');
        }
    }

    /**
     * Check the status of the API.
     *
     */
    public function checkStatus(): array
    {
        return []; // TODO
    }

    /**
     * Get API User info.
     *
     */
    private function getAPIUser(): array
    {
        return []; // TODO
    }

    /**
     * A helper method to send requests to the API.
     *
     * @param string $method The HTTP method (GET, POST, PUT, etc.).
     * @param string $endpoint The API endpoint (e.g., '/api/subscribers').
     * @param array $options Additional options for the request (e.g., 'json' payload).
     * @return array The decoded JSON response.
     * @throws Exception On non-successful HTTP status codes or other request failures.
     */
    private function sendRequest(string $method, string $endpoint, array $options = []): array
    {
        $url = $this->apiBaseUrl . $endpoint;

        $defaultOptions = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        $finalOptions = array_merge($defaultOptions, $options);
        
        $response = $this->httpClient->request($method, $url, $finalOptions);
        
        $statusCode = $response->getStatusCode();

        return $response->toArray();
    }
}