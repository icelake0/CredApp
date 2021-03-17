<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Responser;
use App\Services\ExchangeService;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    /**
     * @var ExchangeService
     * 
     * Injected instance of ExchangeService
     */
    protected $exchange_service;

    /**
     * Class constructor
     * 
     * @param ExchangeService $exchange_service
     */
    public function __construct(ExchangeService $exchange_service)
    {
        $this->exchange_service = $exchange_service;
    }

    /**
     * Set Auth User base currency
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setBaseCurrency(Request $request)
    {
        //TODO add Request validation
        $message = $this->exchange_service->updateUserBaseCurrency(
            auth()->user(),
            $request->base_currency
        )->message;
        return Responser::send(200, [], $message);
    }

    public function listCurrenciesWithExchangeRate()
    {
        $service_response =  $this->exchange_service
            ->getCurrenciesWithExchangeRate(
                auth()->user(),
            );
        return $service_response->success
            ? Responser::send(200, $service_response->data, $service_response->message)
            : Responser::sendError(400, $service_response->message, $service_response->message);
    }
}
