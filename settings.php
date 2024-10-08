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

defined('ABSPATH') || exit;

return [
    'enabled' => [
        'title' => __('Enable/Disable', 'woocommerce'),
        'type' => 'checkbox',
        'label' => __('Enable eGiftCertificate', 'woocommerce'),
        'default' => 'yes',
    ],
    'api_id' => [
        'title' => __('API ID', 'woocommerce'),
        'type' => 'text',
        'description' => __('Get your API credentials from Pay\'nUp.', 'woocommerce'),
        'default' => '',
        'required'=> true,
        'desc_tip' => true,
    ],
    'api_key' => [
        'title' => __('API Key', 'woocommerce'),
        'type' => 'password',
        'description' => __('Get your API credentials from Pay\'nUp.', 'woocommerce'),
        'default' => '',
        'desc_tip' => true,
    ],
    'qr_code' => [
        'title' => __('Private Checkout (QR Code)', 'woocommerce'),
        'type' => 'checkbox',
        'description' => __('Show QR code to continue payment in the customer phone', 'woocommerce'),
        'default' => '',
        'desc_tip' => true,
    ],
    'debug' => [
        'title' => __('Debug log', 'woocommerce'),
        'type' => 'checkbox',
        'label' => __('Enable logging', 'woocommerce'),
        'default' => 'no',
        'description' => sprintf(__('Log eGiftCertificate events, such as IPN requests, inside %s Note: this may log personal information. We recommend using this for debugging purposes only and deleting the logs when finished.', 'woocommerce'), '<code>'.WC_Log_Handler_File::get_log_file_path('egift-certificate').'</code>'),
    ],
];
