<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Exchange\SetAlertThresholdRequest;
use App\Http\Requests\Api\V1\Exchange\SetBaseCurrencyRequest;
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
    public function setBaseCurrency(SetBaseCurrencyRequest $request)
    {
        $message = $this->exchange_service->updateUserBaseCurrency(
            auth()->user(),
            $request->base_currency
        )->message;
        return Responser::send(200, [], $message);
    }

    /**
     * List Exchange Rates for Auth User
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Set an AlertThreshold for Auth user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setAlertThreshold(SetAlertThresholdRequest $request)
    {
        $message = $this->exchange_service->setAlertThreshold(
            auth()->user(),
            $request->currency,
            $request->threshold
        )->message;
        return Responser::send(200, [], $message);
    }
}
