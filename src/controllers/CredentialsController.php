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

use yii\base\InvalidConfigException;

class CredentialsController extends Controller
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
        $accountModeOptions = StripeCheckout::getInstance()->settingsService->getAccountModeOptions();

        $variables = [
          'plugin'    => $plugin,
          'settings'  => $settings,
          'overrides' => $overrides,
          'accountModeOptions' => $accountModeOptions,
        ];

        return $this->renderTemplate('stripe-checkout/_settings/credentials/index', $variables);
    }
}
