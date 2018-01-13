<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2017 Luke Youell
 */

namespace lukeyouell\stripecheckout\models;

use lukeyouell\stripecheckout\StripeCheckout;

use Craft;
use craft\base\Model;

/**
 * @author    Luke Youell
 * @package   StripeCheckout
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $accountMode = 'test';

    /**
     * @var string
     */
    public $defaultCurrency = 'GBP';

    /**
     * @var string
     */
    public $testPublishableKey = '';

    /**
     * @var string
     */
    public $testSecretKey = '';

    /**
     * @var string
     */
    public $livePublishableKey = '';

    /**
     * @var string
     */
    public $liveSecretKey = '';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accountMode', 'defaultCurrency', 'testPublishableKey', 'testSecretKey', 'livePublishableKey', 'liveSecretKey'], 'string']
        ];
    }
}
