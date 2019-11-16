<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/jalendport/craft-stripecheckout
 * @copyright Copyright (c) 2018 Jalen Davenport
 */

namespace jalendport\stripecheckout\controllers;

use jalendport\stripecheckout\StripeCheckout;

use Craft;
use craft\helpers\Json;
use craft\web\Controller;

use Yii;
use yii\base\InvalidConfigException;

class WebhookController extends Controller
{
    // Protected Properties
    // =========================================================================

    protected $allowAnonymous = ['index'];

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        $this->enableCsrfValidation = false;

        $secretKey = StripeCheckout::getInstance()->settingsService->getSecretKey();
        \Stripe\Stripe::setApiKey($secretKey);
    }

    public function actionIndex()
    {
        $this->requirePostRequest();

        $request = @file_get_contents('php://input');
        $webhook = Json::decode($request, false);

        $event = StripeCheckout::getInstance()->webhookService->verifyEvent($webhook->id);

        if (!$event) {
            Craft::$app->response->setStatusCode(400);
            return $this->asJson(['success' => false, 'reason' => 'Event couldn’t be verified.']);
        }

        $handled = StripeCheckout::getInstance()->webhookService->handleEvent($webhook);

        if (!$handled) {
            Craft::$app->response->setStatusCode(400);
            return $this->asJson(['success' => false, 'reason' => 'Event couldn’t be handled.']);
        }

        Craft::$app->response->setStatusCode(200);
        return $this->asJson(['success' => true]);
    }
}
