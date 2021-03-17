<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class FixerCurlClient
{
    /**
     * @var Client
     * 
     * GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     * 
     * fixer api key
     */
    protected $api_key;

    /**
     * @var string
     * 
     * fixer api host
     */
    protected $host;

    /**
     * @var string
     * 
     * request url
     */
    protected $url;

    /**
     * @var string
     * 
     * response date from fixer
     */
    public $data;

    /**
     * @var string
     * 
     * response error from fixer
     */
    public $error;

    /**
     * Class constructor 
     * 
     */
    protected function __construct()
    {
        $this->init();
    }

    /**
     * Set up class for use 
     * 
     * @return void
     */
    protected function init()
    {
        $this->client = new Client;
        $this->host = config('app.fixer_host');
        $this->api_key = config('app.fixer_api_key');
        $this->error = 'We are currently unable to connect to the exchange rate server';
    }

    /**
     * Make an instance of FixerCurl
     * 
     * @return FixerCurl
     */
    public static function make()
    {
        return new static();
    }

    /**
     * Get exchange rates for base currency
     * 
     * @param string $base
     * @param array|null $currencies
     * @return bool
     */
    public function getExchangeRates(string $base, array $currencies = []): bool
    {
        $this->setUrl("/api/latest", [
            'base' => $base,
            'symbols' => implode(',', $currencies)
        ]);
        try {
            $response = $this->client->get($this->url);
            $response_content = json_decode($response->getBody()->getContents(), true);
            if ($response_content['success'] ?? false) {
                $this->data = [
                    'date' => $response_content['date'] ?? null,
                    'base' => $response_content['base'] ?? null,
                    'rates' => $response_content['rates'] ?? null,

                ];
                return true;
            }
            $this->error = $response_content['error']['info'] ?? $response_content['error']['type'] ?? null;
        } catch (\Exception $_) {}
        return false;
    }

    /**
     * Set up url for curl client
     * 
     * @param string $endpoint
     * @param array $params
     * @return void
     */
    protected function setUrl(string $endpoint, array $params = [])
    {
        $this->url = "{$this->host}{$endpoint}?access_key={$this->api_key}";
        foreach ($params as $key => $value)
            $this->url = "{$this->url}&{$key}={$value}";
    }
}
