<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/lukeyouell/craft-stripecheckout
 * @copyright Copyright (c) 2018 Luke Youell
 */

namespace lukeyouell\stripecheckout\elements;

use lukeyouell\stripecheckout\StripeCheckout;
use lukeyouell\stripecheckout\elements\db\ChargeQuery;

use Craft;
use craft\base\Element;
use craft\elements\actions\Delete;
use craft\elements\db\ElementQuery;
use craft\elements\db\ElementQueryInterface;
use craft\helpers\UrlHelper;

class Charge extends Element
{
    // Public Properties
    // =========================================================================

    public $stripeId;

    public $email;

    public $live;

    public $chargeStatus;

    public $paid;

    public $refunded;

    public $amount;

    public $amountRefunded;

    public $currency;

    public $description;

    public $source;

    public $refunds;

    public $shipping;

    public $metadata;

    public $outcome;

    public $failureCode;

    public $failureMessage;

    // Static Methods
    // =========================================================================

    public static function displayName(): string
    {
        return Craft::t('stripe-checkout', 'Charge');
    }

    public static function refHandle(): string
    {
        return 'charge';
    }

    public static function hasTitles(): bool
    {
        return false;
    }

    public static function find(): ElementQueryInterface
    {
        return new ChargeQuery(static::class);
    }

    protected static function defineSources(string $context = null): array
    {
        $sources = [
            '*' => [
                'key'         => '*',
                'label'       => 'All Charges',
                'criteria'    => [],
                'defaultSort' => ['dateCreated', 'desc'],
            ],
        ];

        $sources[] = ['heading' => 'Charge Status'];

        $sources[] = [
            'key'         => 'live',
            'status'      => 'green',
            'label'       => 'Live',
            'criteria'    => [
                'live' => true,
            ],
            'defaultSort' => ['dateCreated', 'desc'],
        ];

        $sources[] = [
            'key'         => 'test',
            'status'      => 'orange',
            'label'       => 'Test',
            'criteria'    => [
                'live' => false,
            ],
            'defaultSort' => ['dateCreated', 'desc'],
        ];

        return $sources;
    }

    protected static function defineSearchableAttributes(): array
    {
        return ['id', 'amount', 'stripeId', 'email', 'description'];
    }

    protected static function defineActions(string $source = null): array
    {
        $actions[] = Craft::$app->getElements()->createAction([
            'type'                => Delete::class,
            'confirmationMessage' => Craft::t('stripe-checkout', 'Are you sure you want to delete the selected charges?'),
            'successMessage'      => Craft::t('stripe-checkout', 'Charges deleted.'),
        ]);

        return $actions;
    }

    protected static function defineTableAttributes(): array
    {
        $attributes = [
            'id'          => ['label' => Craft::t('stripe-checkout', 'ID')],
            'amount'      => ['label' => Craft::t('stripe-checkout', 'Amount')],
            'email'       => ['label' => Craft::t('stripe-checkout', 'Email')],
            'dateCreated' => ['label' => Craft::t('stripe-checkout', 'Date Created')],
            'dateUpdated' => ['label' => Craft::t('stripe-checkout', 'Date Updated')],
            'paid'        => ['label' => Craft::t('stripe-checkout', 'Paid'), 'icon' => 'tag'],
            'refunded'    => ['label' => Craft::t('stripe-checkout', 'Refunded'), 'icon' => 'refresh'],
            'live'        => ['label' => Craft::t('stripe-checkout', 'Mode'), 'icon' => 'tool'],
        ];

        return $attributes;
    }

    protected static function defineDefaultTableAttributes(string $source): array
    {
        $attributes = ['id', 'amount', 'email', 'dateCreated', 'dateUpdated', 'paid', 'refunded', 'live'];

        return $attributes;
    }

    public function getTableAttributeHtml(string $attribute): string
    {
        switch ($attribute) {
            case 'amount':
              return $this->amount;

            case 'email':
              return $this->email;

            default:
                {
                    return parent::tableAttributeHtml($attribute);
                }
        }
    }

    // Public Methods
    // =========================================================================

    public function getIsEditable(): bool
    {
        return false;
    }

    public function getCpEditUrl()
    {
        return UrlHelper::cpUrl('stripe-checkout/charges/'.$this->id);
    }

    // Indexes, etc.
    // -------------------------------------------------------------------------

    protected static function defineSortOptions(): array
    {
        $sortOptions = [
            'stripecheckout_charges.dateCreated' => 'Date Created',
            'stripecheckout_charges.dateUpdated' => 'Date Updated',
        ];

        return $sortOptions;
    }

    // Events
    // -------------------------------------------------------------------------
    public function afterSave(bool $isNew)
    {
        if ($isNew) {
            Craft::$app->db->createCommand()
                ->insert('{{%stripecheckout_charges}}', [
                    'id'             => $this->id,
                    'stripeId'       => $this->stripeId,
                    'email'          => $this->email,
                    'live'           => $this->live,
                    'chargeStatus'   => $this->chargeStatus,
                    'paid'           => $this->paid,
                    'refunded'       => $this->refunded,
                    'amount'         => $this->amount,
                    'amountRefunded' => $this->amountRefunded,
                    'currency'       => $this->currency,
                    'description'    => $this->description,
                    'source'         => $this->source,
                    'refunds'        => $this->refunds,
                    'shipping'       => $this->shipping,
                    'metadata'       => $this->metadata,
                    'outcome'        => $this->outcome,
                    'failureCode'    => $this->failureCode,
                    'failureMessage' => $this->failureMessage,
                ])
                ->execute();
        } else {
            Craft::$app->db->createCommand()
                ->update('{{%stripecheckout_charges}}', [
                    'stripeId'       => $this->stripeId,
                    'email'          => $this->email,
                    'live'           => $this->live,
                    'chargeStatus'   => $this->chargeStatus,
                    'paid'           => $this->paid,
                    'refunded'       => $this->refunded,
                    'amount'         => $this->amount,
                    'amountRefunded' => $this->amountRefunded,
                    'currency'       => $this->currency,
                    'description'    => $this->description,
                    'source'         => $this->source,
                    'refunds'        => $this->refunds,
                    'shipping'       => $this->shipping,
                    'metadata'       => $this->metadata,
                    'outcome'        => $this->outcome,
                    'failureCode'    => $this->failureCode,
                    'failureMessage' => $this->failureMessage,
                ], ['id' => $this->id])
                ->execute();
        }
        parent::afterSave($isNew);
    }
}
