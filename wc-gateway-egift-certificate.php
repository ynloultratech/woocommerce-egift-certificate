<?php
/**
 *  LICENSE: This file is subject to the terms and conditions defined in
 *  file 'LICENSE', which is part of this source code package.
 *
 * @copyright 2019 Copyright(c) - All rights reserved.
 * @author    YNLO-Ultratech Development Team <developer@ynloultratech.com>
 * @package   woocommerce-egift-WC_Gateway_EGift_Certificate
 * @version   1.0.x
 */

class WC_Gateway_EGift_Certificate extends WC_Payment_Gateway
{
    /**
     * Whether or not logging is enabled
     *
     * @var bool
     */
    public static $log_enabled = false;

    /**
     * Logger instance
     *
     * @var WC_Logger
     */
    public static $log = false;

    /**
     * @var string
     */
    protected $eGiftPaymentUrl = 'https://egiftcert.paynup.com';

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @var string
     */
    protected $apiID;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * Constructor for the gateway.
     */
    public function __construct()
    {
        $this->id = 'egift-certificate';
        $this->has_fields = false;
        $this->order_button_text = __('continue', 'woocommerce');
        $this->method_title = __('eGiftCertificate', 'woocommerce');
        $this->method_description = __('Use eGiftCertificate to interchange for goods', 'woocommerce');
        $this->supports = [
            'products',
        ];

        if (defined('EGIFT_PAYMENT_URL')) {
            $this->eGiftPaymentUrl = EGIFT_PAYMENT_URL;
        }

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables.
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->debug = 'yes' === $this->get_option('debug', 'no');
        $this->apiID = $this->get_option('api_id');
        $this->apiKey = $this->get_option('api_key');
        self::$log_enabled = $this->debug;

        add_action('woocommerce_update_options_payment_gateways_'.$this->id, [$this, 'process_admin_options']);
        add_action('woocommerce_api_egift-ipn', [$this, 'ipnHandler']);
    }

    /**
     * Processes and saves options.
     * If there is an error thrown, will continue to save and validate fields, but will leave the erroring field out.
     *
     * @return bool was anything saved?
     */
    public function process_admin_options()
    {
        $saved = parent::process_admin_options();

        if ('yes' !== $this->get_option('debug', 'no')) {
            if (empty(self::$log)) {
                self::$log = wc_get_logger();
            }
            self::$log->clear('egift-certificate');
        }

        return $saved;
    }

    /**
     * Process the payment and return the result.
     *
     * @param  int $order_id Order ID.
     *
     * @return array
     */
    public function process_payment($order_id)
    {
        /** @var WC_Order $order */
        $order = wc_get_order($order_id);

        include 'jwt.php';

        $token = JWT::encode(
            [
                'jti' => $order->get_id(),
                'iss' => $this->apiID,
                'iat' => (new DateTime())->getTimestamp(),
                'exp' => (new DateTime('+2hours'))->getTimestamp(),
                'ip' => get_the_user_ip(),
                'agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
                'origin' => isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null,
            ],
            $this->apiKey
        );

        $params = [
            'token' => $token,
            'orderNumber' => $order->get_id(),
            'amount' => $order->get_total(),
            'redirectUrl' => $this->get_return_url($order),
            'IPNHandlerUrl' => wc()->api_request_url('egift-ipn'),
        ];

        return [
            'result' => 'success',
            'redirect' => $this->eGiftPaymentUrl.'?'.http_build_query($params),
        ];
    }

    public function ipnHandler()
    {
        $token = @file_get_contents('php://input');

        include 'jwt.php';

        if (!$token) {
            self::log('IPN received without a token in the body', 'error');
            wp_die('IPN Request Failure', 'eGiftCertificate IPN', ['response' => 500]);
        }

        try {
            $payload = JWT::decode($token, $this->apiKey, ['HS256']);
        } catch (\Exception $exception) {
            self::log('IPN received with invalid token', 'error');
            wp_die($exception->getMessage(), 'eGiftCertificate IPN', ['response' => 500]);
            exit;
        }

        if (isset($payload->orderNumber)) {
            $order = wc_get_order($payload->orderNumber);

            if ($payload->iss !== $this->apiID
                || !$order
                || $payload->amount != $order->get_total()
            ) {
                self::log('IPN received with invalid token content, invalid order or amount', 'error');
                wp_die('IPN does not match with any existent order', 'eGiftCertificate IPN', ['response' => 500]);
                exit;
            }

            if ($payload->status === 'SOLD') {
                self::log('IPN received with SOLD status of eGiftCertificate');
                $order->add_order_note(sprintf('eGiftCertificate obtained: %s', $payload->pin));
            }

            if ($payload->status === 'USED') {
                self::log('IPN received with USED status of eGiftCertificate');
                $order->add_order_note('eGiftCertificate validated & redeemed successfully');
                $order->payment_complete($payload->pin);
                wc()->cart->empty_cart();
            }
        } else {
            self::log('IPN received with invalid payload');
            wp_die('Invalid IPN Payload', 'eGiftCertificate IPN', ['response' => 500]);
        }
    }

    /**
     * Initialise Gateway Settings Form Fields.
     */
    public function init_form_fields()
    {
        $this->form_fields = include 'settings.php';
    }

    /**
     * Logging method.
     *
     * @param string $message Log message.
     * @param string $level   Optional. Default 'info'. Possible values:
     *                        emergency|alert|critical|error|warning|notice|info|debug.
     */
    public static function log($message, $level = 'info')
    {
        if (self::$log_enabled) {
            if (empty(self::$log)) {
                self::$log = wc_get_logger();
            }
            self::$log->log($level, $message, ['source' => 'egift-certificate']);
        }
    }
}