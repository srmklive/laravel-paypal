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
     * @var array
     */
    protected $shipping_address;

    /**
     * @var array
     */
    protected $payment_preferences;

    /**
     * @var bool
     */
    protected $has_setup_fee = false;

    /**
     * @var string
     */
    protected $return_url;

    /**
     * @var string
     */
    protected $cancel_url;

    /**
     * @var array
     */
    protected $application_context;

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
        $start_date = !empty($start_date) ? Carbon::parse($start_date)->toIso8601String() : Carbon::now()->toIso8601String();

        $body = [
            'plan_id'    => $this->billing_plan['id'],
            'start_time' => $start_date,
            'quantity'   => 1,
            'subscriber' => [
                'name'          => [
                    'given_name' => $customer_name,
                ],
                'email_address' => $customer_email,
            ],
        ];

        if ($this->has_setup_fee) {
            $body['plan'] = [
                'payment_preferences' => $this->payment_preferences,
            ];
        }

        if (isset($this->shipping_address)) {
            $body['subscriber']['shipping_address'] = $this->shipping_address;
        }

        if (!empty($this->application_context)) {
            $body['application_context'] = $this->application_context;
        }

        if ($this->return_url && $this->cancel_url) {
            $body['application_context']['return_url'] = $this->return_url;
            $body['application_context']['cancel_url'] = $this->cancel_url;
        }

        $subscription = $this->createSubscription($body);

        unset($this->product);
        unset($this->billing_plan);
        unset($this->trial_pricing);
        unset($this->return_url);
        unset($this->cancel_url);
        unset($this->application_context);

        return $subscription;
    }

    /**
     * Add a subscription trial pricing tier.
     *
     * @param string    $interval_type
     * @param int       $interval_count
     * @param float|int $price
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function addPlanTrialPricing(string $interval_type, int $interval_count, float $price = 0): \Srmklive\PayPal\Services\PayPal
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
        $billing_cycles = empty($this->trial_pricing) ? [$plan_pricing] : collect([$this->trial_pricing, $plan_pricing])->filter()->toArray();

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
        $billing_cycles = empty($this->trial_pricing) ? [$plan_pricing] : collect([$this->trial_pricing, $plan_pricing])->filter()->toArray();

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
        $billing_cycles = empty($this->trial_pricing) ? [$plan_pricing] : collect([$this->trial_pricing, $plan_pricing])->filter()->toArray();

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
        $billing_cycles = empty($this->trial_pricing) ? [$plan_pricing] : collect([$this->trial_pricing, $plan_pricing])->filter()->toArray();

        $this->addBillingPlan($name, $description, $billing_cycles);

        return $this;
    }

    /**
     * Create a recurring billing plan with custom intervals.
     *
     * @param string    $name
     * @param string    $description
     * @param float|int $price
     * @param string    $interval_unit
     * @param int       $interval_count
     *
     * @throws Throwable
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function addCustomPlan(string $name, string $description, float $price, string $interval_unit, int $interval_count): \Srmklive\PayPal\Services\PayPal
    {
        $billing_intervals = ['DAY', 'WEEK', 'MONTH', 'YEAR'];

        if (isset($this->billing_plan)) {
            return $this;
        }

        if (!in_array($interval_unit, $billing_intervals)) {
            throw new \RuntimeException('Billing intervals should either be '.implode(', ', $billing_intervals));
        }

        $plan_pricing = $this->addPlanBillingCycle($interval_unit, $interval_count, $price);
        $billing_cycles = empty($this->trial_pricing) ? [$plan_pricing] : collect([$this->trial_pricing, $plan_pricing])->filter()->toArray();

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

        if (empty($this->trial_pricing)) {
            $plan_sequence = 1;
        } else {
            $plan_sequence = 2;
        }

        return [
            'frequency' => [
                'interval_unit'  => $interval_unit,
                'interval_count' => $interval_count,
            ],
            'tenure_type'    => ($trial === true) ? 'TRIAL' : 'REGULAR',
            'sequence'       => ($trial === true) ? 1 : $plan_sequence,
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

    /**
     * Set application context.
     *
     * @param array $data
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function setApplicationContext(array $data): \Srmklive\PayPal\Services\PayPal
    {
        $this->application_context = $data;

        return $this;
    }

    /**
     * Set return & cancel urls.
     *
     * @param string $return_url
     * @param string $cancel_url
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function setReturnAndCancelUrl(string $return_url, string $cancel_url): \Srmklive\PayPal\Services\PayPal
    {
        $this->return_url = $return_url;
        $this->cancel_url = $cancel_url;

        return $this;
    }

    /**
     * Set custom failure threshold when adding a subscription.
     *
     * @param int $threshold
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function addPaymentFailureThreshold(int $threshold): \Srmklive\PayPal\Services\PayPal
    {
        $this->payment_failure_threshold = $threshold;

        return $this;
    }

    /**
     * Add setup fee when adding a subscription.
     *
     * @param float $price
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function addSetupFee(float $price): \Srmklive\PayPal\Services\PayPal
    {
        $this->has_setup_fee = true;
        $this->payment_preferences = [
            'auto_bill_outstanding'     => true,
            'setup_fee'                 => [
                'value'         => $price,
                'currency_code' => $this->getCurrency(),
            ],
            'setup_fee_failure_action'  => 'CONTINUE',
            'payment_failure_threshold' => $this->payment_failure_threshold,
        ];

        return $this;
    }

    /**
     * Add shipping address.
     *
     * @param string $full_name
     * @param string $address_line_1
     * @param string $address_line_2
     * @param string $admin_area_2
     * @param string $admin_area_1
     * @param string $postal_code
     * @param string $country_code
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function addShippingAddress(string $full_name, string $address_line_1, string $address_line_2, string $admin_area_2, string $admin_area_1, string $postal_code, string $country_code): \Srmklive\PayPal\Services\PayPal
    {
        $this->shipping_address = [
            'name' => [
                'full_name' => $full_name,
            ],
            'address' => [
                'address_line_1'  => $address_line_1,
                'address_line_2'  => $address_line_2,
                'admin_area_2'    => $admin_area_2,
                'admin_area_1'    => $admin_area_1,
                'postal_code'     => $postal_code,
                'country_code'    => $country_code,
            ],
        ];

        return $this;
    }
}
