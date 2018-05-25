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

use yii\web\NotFoundHttpException;

class ChargesController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionIndex()
    {
        return $this->renderTemplate('stripe-checkout/_charges/index');
    }

    public function actionView($id = null)
    {
        $charge = StripeCheckout::getInstance()->chargeService->getChargeById($id);

        if (!$charge) {
            throw new NotFoundHttpException('Charge not found (#'.$id.')');
        }

        $variables = [
            'title'  => $charge->formattedAmount,
            'charge' => $charge,
        ];

        return $this->renderTemplate('stripe-checkout/_charges/charge', $variables);
    }
}
