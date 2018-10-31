<?php

namespace Srmklive\PayPal\Traits;

use Carbon\Carbon;

trait RecurringProfiles
{
    /**
     * Create recurring subscription on custom frequency basis.
     * The billing frequency is in months, i.e: if you want a monthly subscription, then your billing frequency would be 1.
     *
     * @param string $token
     * @param float  $amount
     * @param string $description
     * @param int    $billingFrequency
     * @param int    $trialDays
     *
     * @return array
     */
    public function createCustomSubscription($token, $amount, $description, $billingFrequency, $trialDays = null)
    {
        $data = [
            'PROFILESTARTDATE' => Carbon::now()->toAtomString(),
            'DESC'             => $description,
            'BILLINGPERIOD'    => 'Month',
            'BILLINGFREQUENCY' => $billingFrequency,
            'AMT'              => $amount,
            'CURRENCYCODE'     => $this->currency,
        ];

        if (!is_null($trialDays) && is_numeric($trialDays)) {
            $data['TRIALBILLINGPERIOD'] = 'Day';
            $data['TRIALTOTALBILLINGCYCLES'] = $trialDays;
            $data['TRIALBILLINGFREQUENCY'] = 1;
            $data['TRIALAMT'] = 0;
        }

        return $this->createRecurringPaymentsProfile($data, $token);
    }

    /**
     * Create recurring subscription on monthly basis.
     *
     * @param string $token
     * @param float  $amount
     * @param string $description
     *
     * @return array
     */
    public function createMonthlySubscription($token, $amount, $description)
    {
        $data = [
            'PROFILESTARTDATE' => Carbon::now()->toAtomString(),
            'DESC'             => $description,
            'BILLINGPERIOD'    => 'Month',
            'BILLINGFREQUENCY' => 1,
            'AMT'              => $amount,
            'CURRENCYCODE'     => $this->currency,
        ];

        return $this->createRecurringPaymentsProfile($data, $token);
    }

    /**
     * Create recurring subscription on yearly basis.
     *
     * @param string $token
     * @param float  $amount
     * @param string $description
     *
     * @return array
     */
    public function createYearlySubscription($token, $amount, $description)
    {
        $data = [
            'PROFILESTARTDATE' => Carbon::now()->toAtomString(),
            'DESC'             => $description,
            'BILLINGPERIOD'    => 'Year',
            'BILLINGFREQUENCY' => 1,
            'AMT'              => $amount,
            'CURRENCYCODE'     => $this->currency,
        ];

        return $this->createRecurringPaymentsProfile($data, $token);
    }
}
