<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2017 Luke Youell
 */

namespace lukeyouell\stripecheckout\records;

use lukeyouell\stripecheckout\StripeCheckout;

use Craft;
use craft\db\ActiveRecord;

/**
 * @author    Luke Youell
 * @package   StripeCheckout
 * @since     1.0.0
 */
class StripeCheckoutRecord extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stripecheckout_charges}}';
    }
}
