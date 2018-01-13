<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2017 Luke Youell
 */

namespace lukeyouell\stripecheckout\controllers;

use lukeyouell\stripecheckout\StripeCheckout;

use Craft;
use craft\web\Controller;
use lukeyouell\stripecheckout\services\RecordService;
use lukeyouell\stripecheckout\services\ChargeService;
use lukeyouell\stripecheckout\services\SecurityService;

/**
 * @author    Luke Youell
 * @package   StripeCheckout
 * @since     1.0.0
 */
class ChargeController extends Controller
{

    // Protected Properties
    // =========================================================================

    protected $allowAnonymous = true;

    // Public Methods
    // =========================================================================

    public function actionIndex()
    {
        $this->requirePostRequest();

        $settings = StripeCheckout::$plugin->getSettings();
        $request = Craft::$app->getRequest();

        $stripeRequest = [];
        $stripeRequest['token'] = $request->getRequiredBodyParam('stripeToken');
        $stripeRequest['email'] = $request->getRequiredBodyParam('stripeEmail');
        $stripeRequest['options'] = SecurityService::decrypt($request->getRequiredBodyParam('stripeOptions'));
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

        $metadataKeys = isset($stripeRequest['options']['metadata']) ? $stripeRequest['options']['metadata'] : [];
        $stripeRequest['metadata'] = [];

        foreach ($metadataKeys as $key)
        {
          $stripeRequest['metadata'][$key] = is_array($request->post($key)) ? implode(', ', $request->post($key)) : $request->post($key);
        }

        $response = ChargeService::createCharge($stripeRequest);

        if ($response['success'])
        {
          $record = RecordService::insertCharge($response['charge']);
          Craft::$app->session->setFlash('charge', $response['charge']);
          return $request->post('redirect') ? $this->redirectToPostedUrl($response['charge']) : $this->asJson($response['charge']);
        } else {
          Craft::$app->getSession()->setError($response['message']);
          return null;
        }
    }
}
