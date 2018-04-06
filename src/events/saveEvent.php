<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license MIT
 */

namespace lukeyouell\stripecheckout\events;

use craft\services\Elements;
use yii\base\Event;

class SaveEvent extends Event
{
    /**
     * @var Submission The user submission.
     */
    public $data;
    public $record;
}
