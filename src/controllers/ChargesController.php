<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/lukeyouell/craft-stripecheckout
 * @copyright Copyright (c) 2018 Luke Youell
 */

namespace lukeyouell\stripecheckout\controllers;

use lukeyouell\stripecheckout\StripeCheckout;

use Craft;
use craft\web\Controller;

class ChargesController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionIndex()
    {
        return $this->renderTemplate('stripe-checkout/_charges/index');
    }
}
