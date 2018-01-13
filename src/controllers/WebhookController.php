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
use lukeyouell\stripecheckout\services\WebhookService;

/**
 * @author    Luke Youell
 * @package   StripeCheckout
 * @since     1.0.0
 */
class WebhookController extends Controller
{

    // Protected Properties
    // =========================================================================

    protected $allowAnonymous = true;

    // Public Methods
    // =========================================================================

    public function init()
    {
      parent::init();
      $this->enableCsrfValidation = false;
      $settings = StripeCheckout::$plugin->getSettings();
      $secretKey = $settings->accountMode === 'live' ? $settings->liveSecretKey : $settings->testSecretKey;
      \Stripe\Stripe::setApiKey($secretKey);
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $this->requirePostRequest();

        http_response_code(200);

        $request = @file_get_contents("php://input");
        $webhook = json_decode($request);

        $event = WebhookService::verifyEvent($webhook->id);

        if ($event) {

          $handle = WebhookService::handleEvent($webhook);

          return $this->asJson(['status' => 'handled']);

        } else {

          return $this->asJson(['status' => 'failed']);

        }
    }
}
