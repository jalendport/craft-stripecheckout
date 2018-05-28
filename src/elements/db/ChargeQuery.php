<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/lukeyouell/craft-stripecheckout
 * @copyright Copyright (c) 2018 Luke Youell
 */

namespace lukeyouell\stripecheckout\elements\db;

use craft\elements\db\ElementQuery;
use craft\helpers\Db;

class ChargeQuery extends ElementQuery
{
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

    public $failureCode;

    public $failureMessage;

    public $data;

    public function stripeId($value)
    {
        $this->stripeId = $value;

        return $this;
    }

    public function email($value)
    {
        $this->email = $value;

        return $this;
    }

    public function live($value)
    {
        $this->live = $value;

        return $this;
    }

    public function chargeStatus($value)
    {
        $this->chargeStatus = $value;

        return $this;
    }

    public function paid($value)
    {
        $this->paid = $value;

        return $this;
    }

    public function refunded($value)
    {
        $this->refunded = $value;

        return $this;
    }

    public function amount($value)
    {
        $this->amount = $value;

        return $this;
    }

    public function amountRefunded($value)
    {
        $this->amountRefunded = $value;

        return $this;
    }

    public function currency($value)
    {
        $this->currency = $value;

        return $this;
    }

    public function description($value)
    {
        $this->description = $value;

        return $this;
    }

    public function failureCode($value)
    {
        $this->failureCode = $value;

        return $this;
    }

    public function failureMessage($value)
    {
        $this->failureMessage = $value;

        return $this;
    }

    public function data($value)
    {
        $this->data = $value;

        return $this;
    }

    protected function beforePrepare(): bool
    {
        // join in the products table
        $this->joinElementTable('stripecheckout_charges');

        // select the columns
        $this->query->select([
            'stripecheckout_charges.stripeId',
            'stripecheckout_charges.email',
            'stripecheckout_charges.live',
            'stripecheckout_charges.chargeStatus',
            'stripecheckout_charges.paid',
            'stripecheckout_charges.refunded',
            'stripecheckout_charges.amount',
            'stripecheckout_charges.amountRefunded',
            'stripecheckout_charges.currency',
            'stripecheckout_charges.description',
            'stripecheckout_charges.failureMessage',
            'stripecheckout_charges.failureCode',
            'stripecheckout_charges.data',
        ]);

        if ($this->stripeId) {
            $this->subQuery->andWhere(Db::parseParam('stripecheckout_charges.stripeId', $this->stripeId));
        }

        if ($this->email) {
            $this->subQuery->andWhere(Db::parseParam('stripecheckout_charges.email', $this->email));
        }

        if ($this->live) {
            $this->subQuery->andWhere(Db::parseParam('stripecheckout_charges.live', $this->live));
        }

        if ($this->chargeStatus) {
            $this->subQuery->andWhere(Db::parseParam('stripecheckout_charges.chargeStatus', $this->chargeStatus));
        }

        if ($this->paid) {
            $this->subQuery->andWhere(Db::parseParam('stripecheckout_charges.paid', $this->paid));
        }

        if ($this->refunded) {
            $this->subQuery->andWhere(Db::parseParam('stripecheckout_charges.refunded', $this->refunded));
        }

        if ($this->amount) {
            $this->subQuery->andWhere(Db::parseParam('stripecheckout_charges.amount', $this->amount));
        }

        if ($this->amountRefunded) {
            $this->subQuery->andWhere(Db::parseParam('stripecheckout_charges.amountRefunded', $this->amountRefunded));
        }

        if ($this->currency) {
            $this->subQuery->andWhere(Db::parseParam('stripecheckout_charges.currency', $this->currency));
        }

        if ($this->description) {
            $this->subQuery->andWhere(Db::parseParam('stripecheckout_charges.description', $this->description));
        }

        if ($this->failureCode) {
            $this->subQuery->andWhere(Db::parseParam('stripecheckout_charges.failureCode', $this->failureCode));
        }

        return parent::beforePrepare();
    }
}
