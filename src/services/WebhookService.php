<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/jalendport/craft-stripecheckout
 * @copyright Copyright (c) 2018 Jalen Davenport
 */

namespace jalendport\stripecheckout\services;

use jalendport\stripecheckout\StripeCheckout;

use Craft;
use craft\base\Component;

use yii\base\Exception;

class WebhookService extends Component
{
    // Public Methods
    // =========================================================================

    public function verifyEvent($event)
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

    public function handleEvent($event = null)
    {
        if ($event) {
            $charge = $event->data->object;

            switch ($event->type) {
                case 'charge.updated':
                case 'charge.captured':
                case 'charge.refunded':
                case 'charge.succeeded':
                    return StripeCheckout::getInstance()->chargeService->updateCharge($charge);
                    break;

                case 'charge.failed':
                    return StripeCheckout::getInstance()->chargeService->insertCharge($charge);
                    break;
            }
        }

        return null;
    }
}
