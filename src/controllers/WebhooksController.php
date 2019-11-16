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
use craft\web\Controller;

use yii\base\InvalidConfigException;

class WebhooksController extends Controller
{
    // Public Properties
    // =========================================================================

    public $settings;

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        $this->settings = StripeCheckout::$plugin->getSettings();
        if (!$this->settings->validate()) {
            throw new InvalidConfigException('Stripe Checkout settings don’t validate.');
        }
    }

    public function actionIndex()
    {
        $webhookUrl = StripeCheckout::getInstance()->settingsService->getWebhookUrl();

        $variables = [
          'webhookUrl' => $webhookUrl,
        ];

        return $this->renderTemplate('stripe-checkout/_settings/webhooks/index', $variables);
    }
}
