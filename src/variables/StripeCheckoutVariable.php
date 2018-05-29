<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/lukeyouell/craft-stripecheckout
 * @copyright Copyright (c) 2018 Luke Youell
 */

namespace lukeyouell\stripecheckout\variables;

use lukeyouell\stripecheckout\StripeCheckout;
use lukeyouell\stripecheckout\elements\Charge;
use lukeyouell\stripecheckout\elements\db\ChargeQuery;

use Craft;

class StripeCheckoutVariable
{
    // Public Methods
    // =========================================================================

    public function checkoutOptions($options = [])
    {
        return StripeCheckout::getInstance()->checkoutService->getCheckoutHtml($options);
    }

    public function charges(array $criteria = []): ChargeQuery
    {
        $query = Charge::find();
        Craft::configure($query, $criteria);

        return $query;
    }
}
