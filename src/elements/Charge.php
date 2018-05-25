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

    public static function hasStatuses(): bool
    {
        return true;
    }

    public static function statuses(): array
    {
        return [
            'green'  => 'Succeeded',
            'orange' => 'Pending',
            'red'    => 'Failed',
        ];
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

        $sources[] = ['heading' => 'Account Mode'];

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
                'live' => 'not 1',
            ],
            'defaultSort' => ['dateCreated', 'desc'],
        ];

        $sources[] = ['heading' => 'Payment'];

        $sources[] = [
            'key'         => 'paid',
            'status'      => 'green',
            'label'       => 'Paid',
            'criteria'    => [
                'paid' => true,
            ],
            'defaultSort' => ['dateCreated', 'desc'],
        ];

        $sources[] = [
            'key'         => 'paymentPending',
            'status'      => 'orange',
            'label'       => 'Pending',
            'criteria'    => [
                'paid' => 'not 1',
            ],
            'defaultSort' => ['dateCreated', 'desc'],
        ];

        $sources[] = ['heading' => 'Refund'];

        $sources[] = [
            'key'         => 'fullRefund',
            'status'      => 'green',
            'label'       => 'Full',
            'criteria'    => [
                'refunded' => true,
            ],
            'defaultSort' => ['dateCreated', 'desc'],
        ];

        $sources[] = [
            'key'         => 'partialRefund',
            'status'      => 'orange',
            'label'       => 'Partial',
            'criteria'    => [
                'refunded' => 'not 1',
                'amountRefunded' => '> 0',
            ],
            'defaultSort' => ['dateCreated', 'desc'],
        ];

        $sources[] = [
            'key'         => 'noRefund',
            'status'      => 'light',
            'label'       => 'None',
            'criteria'    => [
                'refunded' => 'not 1',
                'amountRefunded' => '< 1',
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
            'amount'      => ['label' => Craft::t('stripe-checkout', 'Amount')],
            'email'       => ['label' => Craft::t('stripe-checkout', 'Email')],
            'dateCreated' => ['label' => Craft::t('stripe-checkout', 'Date Created')],
            'dateUpdated' => ['label' => Craft::t('stripe-checkout', 'Date Updated')],
            'paid'        => ['label' => Craft::t('stripe-checkout', 'Paid'), 'icon' => 'tag'],
            'refunded'    => ['label' => Craft::t('stripe-checkout', 'Refunded'), 'icon' => 'refresh'],
            'live'        => ['label' => Craft::t('stripe-checkout', 'Mode'), 'icon' => 'tool'],
            'stripe'      => ['label' => Craft::t('stripe-checkout', 'Stripe'), 'icon' => 'share'],
        ];

        return $attributes;
    }

    protected static function defineDefaultTableAttributes(string $source): array
    {
        $attributes = ['amount', 'email', 'dateCreated', 'dateUpdated', 'paid', 'refunded', 'live', 'stripe'];

        return $attributes;
    }

    // Public Methods
    // =========================================================================

    public function __toString()
    {
        return $this->getFormattedAmount();
    }

    public function getTableAttributeHtml(string $attribute): string
    {
        switch ($attribute) {
            case 'amount':
                return $this->getFormattedAmount();

            case 'email':
                return $this->email;

            case 'paid':
                return $this->getPaidLabelHtml();

            case 'refunded':
                return $this->getRefundedLabelHtml();

            case 'live':
                return $this->getLiveLabelHtml();

            case 'stripe':
                return $this->getStripeLabelHtml();

            default:
                {
                    return parent::tableAttributeHtml($attribute);
                }
        }
    }

    public function getCpEditUrl()
    {
        return UrlHelper::cpUrl('stripe-checkout/charges/'.$this->id);
    }

    public function getStripeUrl()
    {
        return 'https://dashboard.stripe.com/payments/'.$this->stripeId;
    }

    public function getStatus()
    {
        switch ($this->chargeStatus) {
            case 'succeeded':
                return 'green';

            case 'pending':
                return 'orange';

            case 'failed':
                return 'red';

            default:
                return 'orange';
        }
    }

    public function getFormattedAmount()
    {
        $amount = ($this->amount / 100);
        $amount = number_format($amount, 2);
        $currency = strtoupper($this->currency);

        return $amount . ' ' . $currency;
    }

    public function getPaidLabelHtml()
    {
      if ($this->paid) {
          $colour = 'green';
          $title  = 'Paid';
      } else {
          $colour = 'orange';
          $title  = 'Pending payment';
      }

      $html = '<span class="status '.$colour.'" title="'.$title.'"></span>';

      return $html;
    }

    public function getRefundedLabelHtml()
    {
      if ($this->refunded) {
          $colour = 'green';
          $title  = 'Refunded';
      } else if ($this->amountRefunded > 0) {
          $colour = 'orange';
          $title  = 'Partially refunded';
      } else {
          $colour = 'light';
          $title  = 'Not refunded';
      }

      $html = '<span class="status '.$colour.'" title="'.$title.'"></span>';

      return $html;
    }

    public function getLiveLabelHtml()
    {
      if ($this->live) {
          $colour = 'green';
          $title  = 'Live';
      } else {
          $colour = 'orange';
          $title  = 'Test';
      }

      $html = '<span class="status '.$colour.'" title="'.$title.'"></span>';

      return $html;
    }

    public function getStripeLabelHtml()
    {
        return '<a href="'.$this->getStripeUrl().'" target="_blank" data-icon="share" title="View on Stripe"></a>';
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
