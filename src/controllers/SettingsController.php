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

class SettingsController extends Controller
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
        $plugin = StripeCheckout::$plugin;
        $settings = $this->settings;
        $overrides = Craft::$app->getConfig()->getConfigFromFile(strtolower($plugin->handle));
        $currencyOptions = StripeCheckout::getInstance()->settingsService->getCurrencyOptions();

        $variables = [
          'plugin'    => $plugin,
          'settings'  => $settings,
          'overrides' => $overrides,
          'currencyOptions' => $currencyOptions,
        ];

        return $this->renderTemplate('stripe-checkout/_settings/general/index', $variables);
    }
}
