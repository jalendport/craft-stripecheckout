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
use craft\helpers\UrlHelper;
use craft\web\Controller;

use yii\web\NotFoundHttpException;

class ChargesController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionIndex()
    {
        $variables = [
            'title' => 'Charges',
        ];

        return $this->renderTemplate('stripe-checkout/_charges/index', $variables);
    }

    public function actionView($id = null)
    {
        $charge = StripeCheckout::getInstance()->chargeService->getChargeById($id);

        if (!$charge) {
            throw new NotFoundHttpException('Charge not found (#'.$id.')');
        }

        $crumbs = [
            [
                'label' => StripeCheckout::getInstance()->settingsService->getName(),
                'url'   => UrlHelper::cpUrl('stripe-checkout'),
            ],
        ];

        $variables = [
            'title'  => $charge->formattedAmount,
            'crumbs' => $crumbs,
            'charge' => $charge,
        ];

        return $this->renderTemplate('stripe-checkout/_charges/charge', $variables);
    }
}
