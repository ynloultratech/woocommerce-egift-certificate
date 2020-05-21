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

/**
 * Plugin Name: WooCommerce eGiftCertificate
 * Plugin URI: https://www.paynup.com
 * Description: Use eGiftCertificate as a form of exchange for goods
 * Version: 1.0
 * Author: YnloUltratech
 * Author URI: http://ynloultratech.com/
 **/

if (!defined('ABSPATH')) {
    exit;
}

// verify WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action(
        'admin_notices',
        function () {
            $notice = <<<HTML
    <div class="notice notice-error is-dismissible">
        <p>WooCommerce eGiftCertificate extension is <strong>Enabled</strong> but require 
        <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a> to works. Please install <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a> plugin to add products and accept payments.
    </div>
HTML;
            echo $notice;

        }
    );

    return;
}

add_action(
    'plugins_loaded',
    static function () {

        // verify WooCommerce version
        if (!version_compare(WooCommerce::instance()->version, '3.4', '>=')) {
            add_action(
                'admin_notices',
                static function () {
                    $version = WooCommerce::instance()->version;
                    $notice = <<<HTML
        <div class="notice notice-error is-dismissible">
            <p>WooCommerce eGiftCertificate require <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a> version 3.4 or greater.
            Your current version ($version) is not compatible.</p>
        </div>
HTML;
                    echo $notice;

                }
            );

            return;
        }

        if (file_exists(__DIR__.DIRECTORY_SEPARATOR.'config.php')) {
            require __DIR__.DIRECTORY_SEPARATOR.'config.php';
        }

        include __DIR__.DIRECTORY_SEPARATOR.'functions.php';
        include __DIR__.DIRECTORY_SEPARATOR.'jwt.php';
        include __DIR__.DIRECTORY_SEPARATOR.'parsedown.php';
        include __DIR__.DIRECTORY_SEPARATOR.'updater.php';
        include __DIR__.DIRECTORY_SEPARATOR.'wc-gateway-egift-certificate.php';

        $updater = new eGiftCertificate_Updater(__FILE__);
        add_filter('pre_set_site_transient_update_plugins', [$updater, 'setTransient']);
        add_filter('plugins_api', [$updater, 'setPluginInfo']);
        add_filter('upgrader_post_install', [$updater, 'postInstall']);

        // register gateway
        add_filter(
            'woocommerce_payment_gateways',
            static function ($gateways) {
                $gateways[] = 'WC_Gateway_EGift_Certificate';

                return $gateways;
            }
        );
    }
);


