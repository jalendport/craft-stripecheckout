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
use craft\helpers\StringHelper;
use craft\helpers\Template;

class CheckoutService extends Component
{
    // Public Methods
    // =========================================================================

    public function getCheckoutHtml($options = [])
    {
        $checkoutOptions = $this->getCheckoutOptions($options);

        $html = $this->buildHtml($checkoutOptions);

        return $html;
    }

    public function getCheckoutOptions($options = [])
    {
        $checkout = [];
        $key = StripeCheckout::getInstance()->settingsService->getPublishableKey();
        $defaultCurrency = StripeCheckout::getInstance()->settingsService->getDefaultCurrency();

        $checkout['key'] = $key;
        $checkout['amount'] = isset($options['amount']) ? floor($options['amount']) : null;
        $checkout['locale'] = isset($options['locale']) ? $options['locale'] : null;
        $checkout['name'] = isset($options['name']) ? $options['name'] : null;
        $checkout['description'] = isset($options['description']) ? $options['description'] : null;
        $checkout['image'] = isset($options['image']) ? $options['image'] : null;
        $checkout['currency'] = isset($options['currency']) ? $options['currency'] : $defaultCurrency;
        $checkout['email'] = isset($options['email']) ? $options['email'] : null;
        $checkout['label'] = isset($options['label']) ? $options['label'] : null;
        $checkout['panel-label'] = isset($options['panelLabel']) ? $options['panelLabel'] : null;
        $checkout['zip-code'] = (isset($options['zipCode']) and $options['zipCode']) ? 'true' : 'false';
        $checkout['billing-address'] = (isset($options['billingAddress']) and $options['billingAddress']) ? 'true' : 'false';
        $checkout['shipping-address'] = (isset($options['shippingAddress']) and $options['shippingAddress']) ? 'true' : 'false';
        $checkout['allow-remember-me'] = (isset($options['allowRememberMe']) and $options['allowRememberMe']) ? 'true' : 'false';
        $checkout['metadata'] = isset($options['metadata']) ? $options['metadata'] : null;

        return $checkout;
    }

    public function buildHtml($checkoutOptions = [])
    {
        $encryptedOptions = StringHelper::encenc(serialize($checkoutOptions));
        unset($checkoutOptions['metadata']);

        // Build data string
        $dataString = [];

        foreach ($checkoutOptions as $key => $val) {
            if ($val) {
                $dataString[] = 'data-'.$key.'="'.$val.'"';
            }
        }

        $dataString = StringHelper::toString($dataString, ' ');

        // Put everything together
        $html  = '<div class="stripe-container">';
        $html .= '<input type="hidden" name="stripeOptions" value="'.$encryptedOptions.'">';
        $html .= '<script src="https://checkout.stripe.com/checkout.js" class="stripe-button" '.$dataString.'></script>';
        $html .= '</div>';

        return Template::raw($html);
    }
}
