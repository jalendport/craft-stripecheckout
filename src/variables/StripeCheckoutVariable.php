<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/jalendport/craft-stripecheckout
 * @copyright Copyright (c) 2018 Jalen Davenport
 */

namespace jalendport\stripecheckout\variables;

use jalendport\stripecheckout\StripeCheckout;
use jalendport\stripecheckout\elements\Charge;
use jalendport\stripecheckout\elements\db\ChargeQuery;

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
