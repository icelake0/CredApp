<?php

namespace App\Services;

use App\Helpers\FixerCurlClient;
use App\Models\User;

class ExchangeService
{
    /**
     * Update user base currency
     * 
     * @param User $user
     * @param syring $base_currency
     * @return ServiceResponse
     */
    public function updateUserBaseCurrency(User $user, string $base_currency): ServiceResponse
    {
        $user->update([
            'base_currency' => $base_currency
        ]);
        return ServiceResponse::make(true, 'Base currency updated successfully');
    }

    /**
     * List exchange rate for user base currency
     * 
     * @param User $user
     * @return ServiceResponse
     */
    public function getCurrenciesWithExchangeRate(User $user): ServiceResponse
    {
        if (is_null($user->base_currency))
            return ServiceResponse::make(false, "User base currency not set");
        $fixer = FixerCurlClient::make();
        if (!$fixer->getExchangeRates($user->base_currency))
            return ServiceResponse::make(false, $fixer->error);
        return ServiceResponse::make(
            true,
            'Listing exchange rates for base currency',
            $fixer->data
        );
    }
}
