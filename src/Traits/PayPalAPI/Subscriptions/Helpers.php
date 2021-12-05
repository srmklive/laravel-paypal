<?php

namespace Srmklive\PayPal\Traits\PayPalAPI\Subscriptions;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Throwable;

trait Helpers
{
    /**
     * @var array
     */
    protected $trial_pricing = [];

    /**
     * @var int
     */
    protected $payment_failure_threshold = 3;

    /**
     * @var array
     */
    protected $product;

    /**
     * @var array
     */
    protected $billing_plan;

    /**
     * Setup a subscription.
     *
     * @param string $customer_name
     * @param string $customer_email
     * @param string $start_date
     *
     * @throws Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     */
    public function setupSubscription(string $customer_name, string $customer_email, string $start_date = '')
    {
        $start_date = isset($start_date) ? Carbon::parse($start_date)->toIso8601String() : Carbon::now()->toIso8601String();

        $subscription = $this->createSubscription([
            'plan_id'    => $this->billing_plan['id'],
            'start_time' => $start_date,
            'quantity'   => 1,
            'subscriber' => [
                'name'          => [
                    'given_name' => $customer_name,
                ],
                'email_address' => $customer_email,
            ],
        ]);

        unset($this->product);
        unset($this->billing_plan);
        unset($this->trial_pricing);

        return $subscription;
    }

    /**
     * Add a subscription trial pricing tier.
     *
     * @param string    $interval_type
     * @param string    $interval_count
     * @param float|int $price
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function addPlanTrialPricing(string $interval_type, string $interval_count, float $price = 0): \Srmklive\PayPal\Services\PayPal
    {
        $this->trial_pricing = $this->addPlanBillingCycle($interval_type, $interval_count, $price, true);

        return $this;
    }

    /**
     * Create a recurring daily billing plan.
     *
     * @param string    $name
     * @param string    $description
     * @param float|int $price
     *
     * @throws Throwable
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function addDailyPlan(string $name, string $description, float $price): \Srmklive\PayPal\Services\PayPal
    {
        if (isset($this->billing_plan)) {
            return $this;
        }

        $plan_pricing = $this->addPlanBillingCycle('DAY', 1, $price);
        $billing_cycles = collect([$this->trial_pricing, $plan_pricing])->filter()->toArray();

        $this->addBillingPlan($name, $description, $billing_cycles);

        return $this;
    }

    /**
     * Create a recurring weekly billing plan.
     *
     * @param string    $name
     * @param string    $description
     * @param float|int $price
     *
     * @throws Throwable
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function addWeeklyPlan(string $name, string $description, float $price): \Srmklive\PayPal\Services\PayPal
    {
        if (isset($this->billing_plan)) {
            return $this;
        }

        $plan_pricing = $this->addPlanBillingCycle('WEEK', 1, $price);
        $billing_cycles = collect([$this->trial_pricing, $plan_pricing])->filter()->toArray();

        $this->addBillingPlan($name, $description, $billing_cycles);

        return $this;
    }

    /**
     * Create a recurring monthly billing plan.
     *
     * @param string    $name
     * @param string    $description
     * @param float|int $price
     *
     * @throws Throwable
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function addMonthlyPlan(string $name, string $description, float $price): \Srmklive\PayPal\Services\PayPal
    {
        if (isset($this->billing_plan)) {
            return $this;
        }

        $plan_pricing = $this->addPlanBillingCycle('MONTH', 1, $price);
        $billing_cycles = collect([$this->trial_pricing, $plan_pricing])->filter()->toArray();

        $this->addBillingPlan($name, $description, $billing_cycles);

        return $this;
    }

    /**
     * Create a recurring annual billing plan.
     *
     * @param string    $name
     * @param string    $description
     * @param float|int $price
     *
     * @throws Throwable
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function addAnnualPlan(string $name, string $description, float $price): \Srmklive\PayPal\Services\PayPal
    {
        if (isset($this->billing_plan)) {
            return $this;
        }

        $plan_pricing = $this->addPlanBillingCycle('YEAR', 1, $price);
        $billing_cycles = collect([$this->trial_pricing, $plan_pricing])->filter()->toArray();

        $this->addBillingPlan($name, $description, $billing_cycles);

        return $this;
    }

    /**
     * Add Plan's Billing cycle.
     *
     * @param string $interval_unit
     * @param int    $interval_count
     * @param float  $price
     * @param bool   $trial
     *
     * @return array
     */
    protected function addPlanBillingCycle(string $interval_unit, int $interval_count, float $price, bool $trial = false): array
    {
        $pricing_scheme = [
            'fixed_price' => [
                'value'         => $price,
                'currency_code' => $this->getCurrency(),
            ],
        ];

        return [
            'frequency' => [
                'interval_unit'  => $interval_unit,
                'interval_count' => $interval_count,
            ],
            'tenure_type'    => ($trial === true) ? 'TRIAL' : 'REGULAR',
            'sequence'       => ($trial === true) ? 1 : 2,
            'total_cycles'   => ($trial === true) ? 1 : 0,
            'pricing_scheme' => $pricing_scheme,
        ];
    }

    /**
     * Create a product for a subscription's billing plan.
     *
     * @param string $name
     * @param string $description
     * @param string $type
     * @param string $category
     *
     * @throws Throwable
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function addProduct(string $name, string $description, string $type, string $category): \Srmklive\PayPal\Services\PayPal
    {
        if (isset($this->product)) {
            return $this;
        }

        $request_id = Str::random();

        $this->product = $this->createProduct([
            'name'          => $name,
            'description'   => $description,
            'type'          => $type,
            'category'      => $category,
        ], $request_id);

        return $this;
    }

    /**
     * Add subscription's billing plan's product by ID.
     *
     * @param string $product_id
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function addProductById(string $product_id): \Srmklive\PayPal\Services\PayPal
    {
        $this->product = [
            'id' => $product_id,
        ];

        return $this;
    }

    /**
     * Add subscription's billing plan by ID.
     *
     * @param string $plan_id
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function addBillingPlanById(string $plan_id): \Srmklive\PayPal\Services\PayPal
    {
        $this->billing_plan = [
            'id' => $plan_id,
        ];

        return $this;
    }

    /**
     * Create a product for a subscription's billing plan.
     *
     * @param string $name
     * @param string $description
     * @param array  $billing_cycles
     *
     * @throws Throwable
     *
     * @return void
     */
    protected function addBillingPlan(string $name, string $description, array $billing_cycles): void
    {
        $request_id = Str::random();

        $plan_params = [
            'product_id'          => $this->product['id'],
            'name'                => $name,
            'description'         => $description,
            'status'              => 'ACTIVE',
            'billing_cycles'      => $billing_cycles,
            'payment_preferences' => [
                'auto_bill_outstanding'     => true,
                'setup_fee_failure_action'  => 'CONTINUE',
                'payment_failure_threshold' => $this->payment_failure_threshold,
            ],
        ];

        $this->billing_plan = $this->createPlan($plan_params, $request_id);
    }
}
