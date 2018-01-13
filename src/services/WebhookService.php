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
use lukeyouell\stripecheckout\services\RecordService;

/**
 * @author    Luke Youell
 * @package   StripeCheckout
 * @since     1.0.0
 */
class WebhookService extends Component
{
    // Public Methods
    // =========================================================================

    public function init () {
      $settings = StripeCheckout::$plugin->getSettings();
      $secretKey = $settings->accountMode === 'live' ? $settings->liveSecretKey : $settings->testSecretKey;

      \Stripe\Stripe::setApiKey($secretKey);
    }

    public static function verifyEvent($event)
    {
        try {

          return \Stripe\Event::retrieve($event);

        } catch(\Stripe\Error\Card $e) {
          Craft::$app->getErrorHandler()->logException($e);
        } catch (\Stripe\Error\RateLimit $e) {
          Craft::$app->getErrorHandler()->logException($e);
        } catch (\Stripe\Error\InvalidRequest $e) {
          Craft::$app->getErrorHandler()->logException($e);
        } catch (\Stripe\Error\Authentication $e) {
          Craft::$app->getErrorHandler()->logException($e);
        } catch (\Stripe\Error\ApiConnection $e) {
          Craft::$app->getErrorHandler()->logException($e);
        } catch (\Stripe\Error\Base $e) {
          Craft::$app->getErrorHandler()->logException($e);
        } catch (Exception $e) {
          Craft::$app->getErrorHandler()->logException($e);
        }

        return false;
    }

    public static function handleEvent($event)
    {
      $data = $event->data->object;

      switch ($event->type) {

        case 'charge.updated':
        case 'charge.captured':
        case 'charge.refunded':

          return RecordService::updateCharge($data);
          break;

        case 'charge.failed':

          return RecordService::insertCharge($data);
          break;

        default:

          return false;

      }
    }
}
