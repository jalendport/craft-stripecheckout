<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/lukeyouell/craft-stripecheckout
 * @copyright Copyright (c) 2018 Luke Youell
 */

namespace lukeyouell\stripecheckout;

use lukeyouell\stripecheckout\elements\Charge as ChargeElement;
use lukeyouell\stripecheckout\models\Settings;
use lukeyouell\stripecheckout\twigextensions\StripeCheckoutTwigExtension;
use lukeyouell\stripecheckout\variables\StripeCheckoutVariable;

use Craft;
use craft\base\Plugin;
use craft\events\PluginEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\helpers\UrlHelper;
use craft\services\Elements;
use craft\services\Plugins;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

class StripeCheckout extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var StripeCheckout
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '2.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Register twig extension
        Craft::$app->view->registerTwigExtension(new StripeCheckoutTwigExtension());

        // Register CP routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['stripe-checkout/charges'] = 'stripe-checkout/charges/index';
                $event->rules['stripe-checkout/charges/<id:\d+>'] = 'stripe-checkout/charges/view';

                $event->rules['stripe-checkout/settings/general'] = 'stripe-checkout/settings/index';
                $event->rules['stripe-checkout/settings/credentials'] = 'stripe-checkout/credentials/index';
                $event->rules['stripe-checkout/settings/webhooks'] = 'stripe-checkout/webhooks/index';
            }
        );

        // Register elements
        Event::on(
            Elements::class,
            Elements::EVENT_REGISTER_ELEMENT_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = ChargeElement::class;
            }
        );

        // Register variables
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                $variable = $event->sender;
                $variable->set($this->handle, StripeCheckoutVariable::class);
            }
        );

        // Redirect to settings after installation
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('settings/plugins/stripe-checkout'))->send();
                }
            }
        );

        // Register components
        $this->setComponents([
            'chargeService'   => \lukeyouell\stripecheckout\services\ChargeService::class,
            'checkoutService' => \lukeyouell\stripecheckout\services\CheckoutService::class,
            'settingsService' => \lukeyouell\stripecheckout\services\SettingsService::class,
            'webhookService'  => \lukeyouell\stripecheckout\services\WebhookService::class,
        ]);

        Craft::info(
            Craft::t(
                'stripe-checkout',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    public function getCpNavItem()
    {
        $ret = parent::getCpNavItem();

        $ret['label'] = $this->getSettings()->pluginNameOverride ?: $this->name;

        $ret['subnav']['tickets'] = [
            'label' => 'Charges',
            'url'   => 'stripe-checkout/charges',
        ];

        if (Craft::$app->getUser()->getIsAdmin()) {
            $ret['subnav']['settings'] = [
                'label' => 'Settings',
                'url'   => 'stripe-checkout/settings/general',
            ];
        }

        return $ret;
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
         // Get and pre-validate the settings
         $settings = $this->getSettings();
         $settings->validate();

         // Get the settings that are being defined by the config file
         $overrides = Craft::$app->getConfig()->getConfigFromFile(strtolower($this->handle));

         return Craft::$app->view->renderTemplate(
             'stripe-checkout/settings',
             [
                 'settings' => $settings,
                 'overrides' => array_keys($overrides)
             ]
         );
     }
}
