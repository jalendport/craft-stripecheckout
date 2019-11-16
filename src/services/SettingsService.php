<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/jalendport/craft-stripecheckout
 * @copyright Copyright (c) 2018 Jalen Davenport
 */

namespace jalendport\stripecheckout\services;

use jalendport\stripecheckout\StripeCheckout;

use Craft;
use craft\base\Component;
use craft\helpers\UrlHelper;

class SettingsService extends Component
{
    // Public Properties
    // =========================================================================

    public $settings;

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        $this->settings = StripeCheckout::$plugin->getSettings();
        if (!$this->settings->validate()) {
            throw new InvalidConfigException('Stripe Checkout settings don’t validate.');
        }
    }

    public function getName()
    {
        $settings = $this->settings;

        $name = $settings->pluginNameOverride ?: StripeCheckout::$plugin->name;

        return $name;
    }

    public function getPublishableKey()
    {
        $settings = $this->settings;

        $key = $settings->accountMode == 'live' ? $settings->livePublishableKey : $settings->testPublishableKey;

        return $key;
    }

    public function getSecretKey()
    {
        $settings = $this->settings;

        $key = $settings->accountMode == 'live' ? $settings->liveSecretKey : $settings->testSecretKey;

        return $key;
    }

    public function getDefaultCurrency()
    {
        $currency = $this->settings->defaultCurrency;

        return $currency;
    }

    public function getAccountModeOptions()
    {
        $options = [
            'test' => 'Test',
            'live' => 'Live',
        ];

        return $options;
    }

    public function getCurrencyOptions()
    {
        $options = [
            'AUD' => 'AUD - Australian Dollars',
            'CAD' => 'CAD - Canadian Dollars',
            'CHF' => 'CHF - Swiss Franc',
            'DKK' => 'DAK - Danish Krone',
            'EUR' => 'EUR - Euro',
            'GBP' => 'GBP - British Pound Sterling',
            'HKD' => 'HKD - Hong Kong Dollar',
            'JPY' => 'JPY - Japanese Yen',
            'NOK' => 'NOK - Norwegian Krone',
            'NZD' => 'NZD - New Zealand Dollar',
            'SEK' => 'SEK - Swedish Krona',
            'USD' => 'USD - American Dollar',
        ];

        return $options;
    }

    public function getWebhookUrl()
    {
        $url = UrlHelper::siteUrl('actions/stripe-checkout/webhook');

        return $url;
    }
}
