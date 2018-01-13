<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2017 Luke Youell
 */

namespace lukeyouell\stripecheckout\variables;

use lukeyouell\stripecheckout\StripeCheckout;

use Craft;
use craft\helpers\Template;
use craft\helpers\DateTimeHelper;
use lukeyouell\stripecheckout\services\SecurityService;
use lukeyouell\stripecheckout\services\RecordService;

/**
 * @author    Luke Youell
 * @package   StripeCheckout
 * @since     1.0.0
 */
class StripeCheckoutVariable
{
    // Public Methods
    // =========================================================================

    public function publishableKey()
    {
        $settings = StripeCheckout::$plugin->getSettings();

        $key = $settings->accountMode === 'live' ? $settings->livePublishableKey : $settings->testPublishableKey;

        return $key;
    }

    public function getCharges()
    {
      return RecordService::getCharges();
    }

    public function getCharge($id)
    {
      return RecordService::getChargeById($id);
    }

    public function checkoutOptions($options = [])
    {
        $settings = $settings = StripeCheckout::$plugin->getSettings();

        $checkout = [];

        $checkout['key'] = $this->publishableKey();
        $checkout['amount'] = isset($options['amount']) ? floor($options['amount']) : null;
        $checkout['locale'] = isset($options['locale']) ? $options['locale'] : null;
        $checkout['name'] = isset($options['name']) ? $options['name'] : null;
        $checkout['description'] = isset($options['description']) ? $options['description'] : null;
        $checkout['image'] = isset($options['image']) ? $options['image'] : null;
        $checkout['currency'] = isset($options['currency']) ? $options['currency'] : $settings->defaultCurrency;
        $checkout['email'] = isset($options['email']) ? $options['email'] : null;
        $checkout['label'] = isset($options['label']) ? $options['label'] : null;
        $checkout['panel-label'] = isset($options['panelLabel']) ? $options['panelLabel'] : null;
        $checkout['zip-code'] = (isset($options['zipCode']) and ($options['zipCode'])) ? 'true' : 'false';
        $checkout['billing-address'] = (isset($options['billingAddress']) and ($options['billingAddress'])) ? 'true' : 'false';
        $checkout['shipping-address'] = (isset($options['shippingAddress']) and ($options['shippingAddress'])) ? 'true' : 'false';
        $checkout['allow-remember-me'] = (isset($options['allowRememberMe']) and ($options['allowRememberMe'])) ? 'true' : 'false';
        $checkout['metadata'] = isset($options['metadata']) ? $options['metadata'] : null;

        $encryptedOptions = SecurityService::encrypt($checkout);

        $return = '<div class="stripe-container">';
        $return .= '<input type="hidden" name="stripeOptions" value="'.$encryptedOptions.'">';

        $dataString = [];
        unset($checkout['metadata']);

        foreach ($checkout as $key => $val) {
          $dataString[] = 'data-'.$key.'="'.$val.'"';
        }

        $dataString = implode(' ', $dataString);

        $return .= '<script src="https://checkout.stripe.com/checkout.js" class="stripe-button" '.$dataString.'></script>';
        $return .= '</div>';

        return Template::raw($return);
    }

    public function decode($data)
    {
      return json_decode($data, true);
    }
}
