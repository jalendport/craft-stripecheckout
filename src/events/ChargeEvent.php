<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/jalendport/craft-stripecheckout
 * @copyright Copyright (c) 2018 Jalen Davenport
 */

namespace jalendport\stripecheckout\events;

use yii\base\Event;

class ChargeEvent extends Event
{
    // Properties
    // =========================================================================

    public $request;

    public $charge;
}
