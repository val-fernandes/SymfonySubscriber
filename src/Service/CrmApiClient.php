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
        return $this->sendRequest('GET', '/');
    }

    /**
     * Get API User info.
     *
     */
    private function getAPIUser(): array
    {
        try {
            return $this->sendRequest('GET', '/api/me');
        }
        catch (Exception $exception) {
            return ["apiUser" => false];
        } 
    }

    /**
     * Check if the External API has properly setup by checking the user's Token
     * @return bool
     */
    public function IsAPISetup()
    {
        $response = $this->getAPIUser();
    // If the API is setup correctly, we get an array for the key "apiUser". Otherwise the
    // function getAPIUser() will return false if there was an error.
        if (is_array($response['apiUser'])) {
            return true;
        }
        else {
            return false;
        }
    }

    //////////////
    // Ideally we want to make sure the API is working before making the call to all of the below functions.
    //////////////

    /**
     * Get all the subscriber list.
     */
    public function getSubcriberList() {
        return $this->sendRequest('GET', '/api/lists');
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

        $finalOptions = array_merge_recursive($defaultOptions, $options);
        
        $response = $this->httpClient->request($method, $url, $finalOptions);
        
        $statusCode = $response->getStatusCode();

        if ($statusCode == Response::HTTP_BAD_REQUEST) {
            // Attempt to get more detailed error from API response body
            $errorContent = $response->toArray(false); // false = don't throw on error
            $errorMessage = $errorContent['message'] ?? 'An unknown API error occurred.';
                
            if (isset($errorContent["fields"]) && count($errorContent["fields"]) > 0) {
                foreach ($errorContent["fields"] as $errField) {
                    $errorMessage .= PHP_EOL . "" . $errField['message'];
                    continue;
                }
            }
            throw new Exception("API Error: {$errorMessage} (Status: {$statusCode})", $statusCode);
        }
        elseif ($statusCode > Response::HTTP_BAD_REQUEST) {
            $errorMessage = 'An unknown API error occurred.';
            throw new Exception("API Error: {$errorMessage} (Status: {$statusCode})", $statusCode);
        }

        // For 204 No Content responses, return an empty array.
        if ($statusCode === Response::HTTP_NO_CONTENT) {
            return [];
        }

        return $response->toArray();
    }
}