<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2017 Luke Youell
 */

namespace lukeyouell\stripecheckout\services;

use lukeyouell\stripecheckout\StripeCheckout;

use Craft;
use craft\base\Component;
use lukeyouell\stripecheckout\services\ChargeService;
use lukeyouell\stripecheckout\events\saveEvent;

/**
 * @author    Luke Youell
 * @package   StripeCheckout
 * @since     1.0.0
 */
class ChargeService extends Component
{
    
    const EVENT_BEFORE_SAVE = 'beforeSave';
    const EVENT_AFTER_SAVE = 'afterSave';


    
    // Public Methods
    // =========================================================================

    public static function createCharge($request)
    {
        $event = new SaveEvent([
            'data' => $request
        ]);
        $self = new static;
        $self->trigger(self::EVENT_BEFORE_SAVE, $event);

        $settings = StripeCheckout::$plugin->getSettings();
        $secretKey = $settings->accountMode === 'live' ? $settings->liveSecretKey : $settings->testSecretKey;
        \Stripe\Stripe::setApiKey($secretKey);

        $response = [];

        try {

          $response['charge'] = \Stripe\Charge::create([
            'source' => $request['token'],
            'receipt_email' => $request['email'],
            'amount' => $request['options']['amount'],
            'currency' => isset($request['options']['currency']) ? $request['options']['currency'] : $settings->defaultCurrency,
            'description' => isset($request['options']['description']) ? $request['options']['description'] : null,
            'shipping' => $request['shipping'],
            'metadata' => $request['metadata']
          ]);

          $response['success'] = true;

        } catch (\Stripe\Error\Base $e) {
          Craft::$app->getErrorHandler()->logException($e);
          $response['success'] = false;
          $response['message'] = $e->getMessage();
        } catch (Exception $e) {
          Craft::$app->getErrorHandler()->logException($e);
          $response['success'] = false;
          $response['message'] = $e->getMessage();
        }

        $event = new SaveEvent([
            'data' => $request,
            'record' => $response
        ]);
        $self = new static;
        $self->trigger(self::EVENT_AFTER_SAVE, $event);
        return $response;
    }
}
