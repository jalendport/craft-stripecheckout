<?php
/**
 * Stripe Checkout plugin for Craft CMS 3.x
 *
 * Bringing the power of Stripe Checkout to your Craft templates.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2017 Luke Youell
 */

namespace lukeyouell\stripecheckout\migrations;

use lukeyouell\stripecheckout\StripeCheckout;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * @author    Luke Youell
 * @package   StripeCheckout
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
        }

        return true;
    }

   /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = true;

        if (!$this->db->tableExists('{{%stripecheckout_charges}}')) {

          $this->createTable(
              '{{%stripecheckout_charges}}',
              [
                  'id' => $this->primaryKey(),
                  'stripeId' => $this->string(),
                  'email' => $this->string(),
                  'live' => $this->boolean(),
                  'status' => $this->string(),
                  'paid' => $this->boolean(),
                  'refunded' => $this->boolean(),
                  'amount' => $this->integer(),
                  'amountRefunded' => $this->integer(),
                  'currency' => $this->string(),
                  'description' => $this->string(),
                  'source' => $this->text(),
                  'refunds' => $this->text(),
                  'shipping' => $this->text(),
                  'metadata' => $this->text(),
                  'outcome' => $this->text(),
                  'failureCode' => $this->string(),
                  'failureMessage' => $this->string(),
                  'dateCreated' => $this->dateTime()->notNull(),
                  'dateUpdated' => $this->dateTime()->notNull(),
                  'uid' => $this->uid()
              ]
          );

        }

        return $tablesCreated;
    }

    /**
     * @return void
     */
    protected function removeTables()
    {
        $this->dropTableIfExists('{{%stripecheckout_charges}}');
    }
}
