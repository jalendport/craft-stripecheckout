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
use craft\helpers\StringHelper;
use craft\web\Controller;

class ChargeController extends Controller
{
    // Protected Properties
    // =========================================================================

    protected $allowAnonymous = ['index'];

    // Public Methods
    // =========================================================================

    public function actionIndex()
    {
        $this->requirePostRequest();

        $request = Craft::$app->getRequest();
        $stripeOptions = unserialize(StringHelper::decdec($request->getRequiredBodyParam('stripeOptions')));

        $stripeRequest = [];
        $stripeRequest['token'] = $request->getRequiredBodyParam('stripeToken');
        $stripeRequest['email'] = $request->getRequiredBodyParam('stripeEmail');
        $stripeRequest['options'] = $stripeOptions;
        $stripeRequest['shipping'] = [
            'name' => $request->post('stripeShippingName'),
            'address' => [
                'line1' => $request->post('stripeShippingAddressLine1'),
                'city' => $request->post('stripeShippingAddressCity'),
                'state' => $request->post('stripeShippingAddressState'),
                'country' => $request->post('stripeShippingAddressCountry'),
                'postal_code' => $request->post('stripeShippingAddressZip')
            ]
        ];

        $stripeRequest['metadata'] = [];
        $metadata = isset($stripeRequest['options']['metadata']) ? $stripeRequest['options']['metadata'] : [];

        foreach ($metadata as $key) {
            $stripeRequest['metadata'][$key] = is_array($request->post($key)) ? StringHelper::toString($request->post($key)) : $request->post($key);
        }

        $response = StripeCheckout::getInstance()->chargeService->createCharge($stripeRequest);

        if (!isset($response->id)) {
            if ($request->getAcceptsJson()) {
                return $this->asJson($response);
            }

            Craft::$app->session->setFlash('errors', $response);
            Craft::$app->getSession()->setError('Couldn’t create the charge.');
        } else {
            if ($request->getAcceptsJson()) {
                return $this->asJson($response);
            }

            Craft::$app->session->setFlash('charge', $response);
            Craft::$app->getSession()->setNotice('Charge created.');
        }

        return $this->redirectToPostedUrl();
    }
}
