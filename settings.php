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
    'title_v2' => [
        'title' => __('Title', 'woocommerce'),
        'type' => 'text',
        'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
        'desc_tip' => true,
    ],
    'description_v2' => [
        'title' => __('Purchase Description', 'woocommerce'),
        'type' => 'text',
        'desc_tip' => true,
        'description' => __('This controls the description which the user sees during checkout.', 'woocommerce'),
        'default' => '',
    ],
    'description_redeem_v2' => [
        'title' => __('Redemption Description', 'woocommerce'),
        'type' => 'text',
        'desc_tip' => true,
        'description' => __('This controls the description which the user sees during checkout.', 'woocommerce'),
        'default' => '',
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
    'iframe' => [
        'title' => __('Pay in iFrame', 'woocommerce'),
        'type' => 'checkbox',
        'description' => __('Use a iframe to load eGiftCertificate payment page in the checkout page without redirections', 'woocommerce'),
        'default' => 'yes',
        'desc_tip' => true,
    ],
    'card_swiper' => [
        'title' => __('Allow Card Readers', 'woocommerce'),
        'type' => 'checkbox',
        'description' => __('Show/Hide a button to allow customers to swipe the credit card using a magnetic card reader', 'woocommerce'),
        'default' => '',
        'desc_tip' => true,
    ],
    'auto_redirect' => [
        'title' => __('Auto Redirect', 'woocommerce'),
        'type' => 'checkbox',
        'description' => __('Auto-redirect to order page after success payment', 'woocommerce'),
        'default' => 'yes',
        'desc_tip' => true,
    ],
    'auto_redeem' => [
        'title' => __('Auto Redeem', 'woocommerce'),
        'type' => 'checkbox',
        'description' => __('Redeem purchased eGiftCertificate automatically', 'woocommerce'),
        'default' => 'yes',
        'desc_tip' => true,
    ],
    'redeem_in_store' => [
        'title' => __('Redeem In Store', 'woocommerce'),
        'type' => 'checkbox',
        'description' => __('Redeem purchased eGiftCertificate in the store instead of payment form.', 'woocommerce'),
        'default' => '',
        'desc_tip' => true,
    ],
    'allow_share' => [
        'title' => __('Allow Share', 'woocommerce'),
        'type' => 'checkbox',
        'description' => __('Allow customers to share payments with others', 'woocommerce'),
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
