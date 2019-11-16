<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/jalendport/craft-stripecheckout
 * @copyright Copyright (c) 2018 Jalen Davenport
 */

namespace jalendport\stripecheckout\assetbundles;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class ChargeBundle extends AssetBundle
{
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = '@jalendport/stripecheckout/assetbundles';

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        $this->css = [
            'css/charge.css',
        ];

        parent::init();
    }
}
