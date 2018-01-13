<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2017 Luke Youell
 */

namespace lukeyouell\stripecheckout\services;

use lukeyouell\stripecheckout\StripeCheckout;

use Craft;
use craft\base\Component;

/**
 * @author    Luke Youell
 * @package   StripeCheckout
 * @since     1.0.0
 */
class SecurityService extends Component
{
    public static function encrypt($data)
    {
        return base64_encode(Craft::$app->getSecurity()->encryptByPassword(serialize($data), null));
    }

    public static function decrypt($data)
    {
        return unserialize(Craft::$app->getSecurity()->decryptByPassword(base64_decode($data), null));
    }

    public static function cleanPost($data)
    {
        $removeKeys = ['CRAFT_CSRF_TOKEN', 'action', 'redirect', 'stripeOptions', 'stripeToken', 'stripeTokenType', 'stripeEmail', 'stripeBillingName', 'stripeBillingAddressLine1', 'stripeBillingAddressCity', 'stripeBillingAddressState', 'stripeBillingAddressCountry', 'stripeBillingAddressZip', 'stripeShippingName', 'stripeShippingAddressLine1', 'stripeShippingAddressCity', 'stripeShippingAddressState', 'stripeShippingAddressCountry', 'stripeShippingAddressZip'];

        foreach ($removeKeys as $key) {
          unset($data[$key]);
        }

        return $data;
    }
}
