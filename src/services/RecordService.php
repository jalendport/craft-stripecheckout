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

/**
 * @author    Luke Youell
 * @package   StripeCheckout
 * @since     1.0.0
 */
class RecordService extends Component
{
    public static function getCharges()
    {
        return \Craft::$app->db->createCommand('SELECT * FROM {{%stripecheckout_charges}} ORDER BY dateCreated DESC')->queryAll();
    }

    public static function getChargeById($id)
    {
        return \Craft::$app->db->createCommand('SELECT * FROM {{%stripecheckout_charges}} WHERE id = '.$id)->queryOne();
    }

    public static function insertCharge($data)
    {
        return \Craft::$app->db->createCommand()
        ->insert(
          '{{%stripecheckout_charges}}',
          [
            'stripeId' => isset($data->id) ? $data->id : null,
            'email' => isset($data->receipt_email) ? $data->receipt_email : null,
            'live' => isset($data->livemode) ? $data->livemode : null,
            'status' => isset($data->status) ? $data->status : null,
            'paid' => isset($data->paid) ? $data->paid : null,
            'refunded' => isset($data->refunded) ? $data->refunded : null,
            'amount' => isset($data->amount) ? $data->amount : null,
            'amountRefunded' => isset($data->amount_refunded) ? $data->amount_refunded : null,
            'currency' => isset($data->currency) ? $data->currency : null,
            'description' => isset($data->description) ? $data->description : null,
            'source' => isset($data->source) ? json_encode($data->source) : null,
            'refunds' => isset($data->refunds) ? json_encode($data->refunds->data) : null,
            'shipping' => isset($data->shipping) ? json_encode($data->shipping) : null,
            'metadata' => isset($data->metadata) ? json_encode($data->metadata) : null,
            'outcome' => isset($data->outcome) ? json_encode($data->outcome) : null,
            'failureCode' => isset($data->failure_code) ? $data->failure_code : null,
            'failureMessage' => isset($data->failure_message) ? $data->failure_message : null
          ]
        )->execute();
    }

    public static function updateCharge($data)
    {
        return \Craft::$app->db->createCommand()
        ->update(
          '{{%stripecheckout_charges}}',
          [
            'email' => isset($data->receipt_email) ? $data->receipt_email : null,
            'live' => $data->livemode,
            'status' => isset($data->status) ? $data->status : null,
            'paid' => $data->paid,
            'refunded' => $data->refunded,
            'amount' => isset($data->amount) ? $data->amount : null,
            'amountRefunded' => isset($data->amount_refunded) ? $data->amount_refunded : null,
            'currency' => isset($data->currency) ? $data->currency : null,
            'description' => isset($data->description) ? $data->description : null,
            'source' => isset($data->source) ? json_encode($data->source) : null,
            'refunds' => isset($data->refunds) ? json_encode($data->refunds->data) : null,
            'shipping' => isset($data->shipping) ? json_encode($data->shipping) : null,
            'metadata' => isset($data->metadata) ? json_encode($data->metadata) : null,
            'outcome' => isset($data->outcome) ? json_encode($data->outcome) : null,
            'failureCode' => isset($data->failure_code) ? $data->failure_code : null,
            'failureMessage' => isset($data->failure_message) ? $data->failure_message : null
          ], ['stripeId' => $data->id]
        )->execute();
    }
}
