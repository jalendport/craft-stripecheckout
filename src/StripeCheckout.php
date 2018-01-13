<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2017 Luke Youell
 */

namespace lukeyouell\stripecheckout;

use lukeyouell\stripecheckout\variables\StripeCheckoutVariable;
use lukeyouell\stripecheckout\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

/**
 * Class StripeCheckout
 *
 * @author    Luke Youell
 * @package   StripeCheckout
 * @since     1.0.0
 *
 */
class StripeCheckout extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var StripeCheckout
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('stripeCheckout', StripeCheckoutVariable::class);
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Event::on(
          UrlManager::class,
          UrlManager::EVENT_REGISTER_CP_URL_RULES,
          function(RegisterUrlRulesEvent $event) {
            $event->rules['stripe-checkout/charge/<id:\d+>'] = ['template' => 'stripe-checkout/charge'];
        });

        Craft::info(
            Craft::t(
                'stripe-checkout',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'stripe-checkout/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
