<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Response;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{
    protected $client;

    public function __construct(string $baseUri)
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'timeout'  => 10.0,
            'verify'  => false,
        ]);
    }

    /**
     * Send a GET request.
     *
     * @param string $uri
     * @param array $queryParams
     * @param array $headers
     * @return array
     */
    public function get(string $uri, array $queryParams = [], array $headers = []): array
    {
        return $this->request('GET', $uri, ['query' => $queryParams, 'headers' => $headers]);
    }

    /**
     * Send a POST request.
     *
     * @param string $uri
     * @param array $formParams
     * @param array $headers
     * @return array
     */
    public function post(string $uri, array $formParams = [], array $headers = []): array
    {
        return $this->request('POST', $uri, ['form_params' => $formParams, 'headers' => $headers]);
    }

    /**
     * General request method.
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return array
     */
    protected function request(string $method, string $uri, array $options): array
    {
        try {
            $response = $this->client->request($method, 'https://'.config('services.assistpro.base_uri').$uri, $options);
            return $this->handleResponse($response);
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle the response.
     *
     * @param ResponseInterface $response
     * @return array
     */
    protected function handleResponse(ResponseInterface $response): array
    {
        return [
            'status' => $response->getStatusCode(),
            'body' => json_decode($response->getBody()->getContents(), true),
        ];
    }

    /**
     * Handle request exception.
     *
     * @param RequestException $e
     * @return array
     */
    protected function handleException(RequestException $e): array
    {
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            return [
                'status' => $response->getStatusCode(),
                'body' => json_decode($response->getBody()->getContents(), true),
            ];
        }

        return [
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'body' => ['error' => 'Server error'],
        ];
    }
}