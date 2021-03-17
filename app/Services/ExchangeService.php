<?php

namespace App\Services;

use App\Helpers\FixerCurlClient;
use App\Models\AlertThreshold;
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

    /**
     * Set AlertThreshold for auth user
     * 
     * @param User $user
     * @param string $currency
     * @param float $threshold
     * @return ServiceResponse
     */
    public function setAlertThreshold(User $user, string $currency, float $threshold)
    {
        AlertThreshold::updateOrCreate([
            'user_id' => $user->id,
            'currency' => $currency
        ], ['threshold' => $threshold]);
        return ServiceResponse::make(true, 'Alert threshold set successfully');
    }

    /**
     * Set AlertThreshold for auth user
     * 
     * @param  float $amount
     * @param  int $tenure
     * @param int $repayment_day
     * @param float $interest
     * @return ServiceResponse
     */
    public function calculateRepayment(
        float $amount,
        int $tenure,
        int $repayment_day,
        float $interest
    ) {
        $month_repayment = [];
        $total_repayment = $amount + $amount * $interest / 100;
        $porated_repayment = $total_repayment / $tenure;
        for ($i = 1; $i <= $tenure; $i++)
            $month_repayment[] = [
                'amount' => $porated_repayment,
                'date' => "{$repayment_day}/{$i}/2021"
            ];
        return ServiceResponse::make(true, 'Repayment calculated successfully', [
            'total_repayment' => $total_repayment,
            'montly_repayment' => $month_repayment
        ]);
    }
}
