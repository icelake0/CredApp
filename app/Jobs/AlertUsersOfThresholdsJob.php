<?php

namespace App\Jobs;

use App\Helpers\FixerCurlClient;
use App\Models\User;
use App\Notifications\NotifyUserOfAlertThresholds;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AlertUsersOfThresholdsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     * 
     * user to run job for
     */
    protected $user;

    /**
     * @var array
     * 
     * user alert thresholds
     */
    protected $alert_thresholds = [];

    /**
     * @var array
     * 
     * User exchange rates base on alert thresholds
     */
    protected $exchange_rates = [];

    /**
     * @var array
     * 
     * User exchange rates that requires alert base on alert thresholds
     */
    protected $exchange_rates_requiring_alert = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->setAlertThresholds();
        $fixer = FixerCurlClient::make();
        if ($fixer->getExchangeRates(
            $this->user->base_currency,
            array_keys($this->alert_thresholds)
        )) {
            $this->exchange_rates = $fixer->data['rates'] ?? [];
            $this->setExchangeRatesThatRequiresAlert();
            $this->notifyUser();
        };
    }

    /**
     * Set alert_thresholds class property
     * 
     * @return void
     */
    protected function setAlertThresholds()
    {
        $this->alert_thresholds = $this->user->alertThresholds->pluck('threshold', 'currency')->toArray();
    }

    /**
     * Set exchange_rates_requiring_alert class property
     * 
     * @return void
     */
    protected function setExchangeRatesThatRequiresAlert()
    {
        foreach ($this->exchange_rates as $currency => $exchange_rate)
            if (
                isset($this->alert_thresholds[$currency])
                && $exchange_rate <= $this->alert_thresholds[$currency] ///TODO revove =
            )
                $this->exchange_rates_requiring_alert[$currency] = $exchange_rate;
    }

    /**
     * Send notification to user if required
     * 
     * @return void
     */
    protected function notifyUser()
    {
        if (count($this->exchange_rates_requiring_alert)) {
            $this->user->notify(
                new NotifyUserOfAlertThresholds($this->exchange_rates_requiring_alert)
            );
        }
    }
}
