<?php

namespace lukeyouell\stripecheckout\migrations;

use lukeyouell\stripecheckout\StripeCheckout;
use lukeyouell\stripecheckout\elements\Charge;
use lukeyouell\stripecheckout\elements\db\ChargeQuery;

use Craft;
use craft\db\Migration;

/**
 * m180531_090631_v2_upgrade migration.
 */
class m180531_090631_v2_upgrade extends Migration
{
    // Public Properties
    // =========================================================================

    public $driver;

    // Public Methods
    // =========================================================================

    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
            $this->transferData();
        }

        return true;
    }

    public function safeDown()
    {
        echo "m180531_090631_v2_upgrade cannot be reverted\n";

        return false;
    }

    // Protected Methods
    // =========================================================================

    protected function createTables()
    {
        $tablesCreated = false;

        // support_tickets table
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%checkout_charges}}');
        if ($tableSchema === null) {
            $tablesCreated = true;

            $this->createTable(
                '{{%checkout_charges}}',
                [
                    'id'             => $this->primaryKey(),
                    'dateCreated'    => $this->dateTime()->notNull(),
                    'dateUpdated'    => $this->dateTime()->notNull(),
                    'uid'            => $this->uid(),
                    // Custom columns in the table
                    'stripeId'       => $this->string(),
                    'email'          => $this->string(),
                    'live'           => $this->boolean(),
                    'chargeStatus'   => $this->string(),
                    'paid'           => $this->boolean(),
                    'refunded'       => $this->boolean(),
                    'amount'         => $this->integer(),
                    'amountRefunded' => $this->integer(),
                    'currency'       => $this->string(),
                    'description'    => $this->string(),
                    'failureCode'    => $this->string(),
                    'failureMessage' => $this->string(),
                    'data'           => $this->text(),
                ]
            );
        }

        return $tablesCreated;
    }

    protected function addForeignKeys()
    {
        $this->addForeignKey(null, '{{%checkout_charges}}', ['id'], '{{%elements}}', ['id'], 'CASCADE');
    }

    protected function transferData()
    {
        // Fetch existing charges
        echo "    > Starting data transfer\n";
        echo "    > Fetching existing charge records\n";
        $records = $this->getCharges();

        if ($records) {
            // Insert found charges into new table
            echo "    > Starting transfer of charge records\n";
            $inserted = $this->insertCharges($records);

            if (!$inserted) {
              echo "    > Unable to transfer charge records\n";
            } else {
              echo "    > Charge records transferred\n";
            }
        }

        echo "    > Data transfer finished\n";
    }

    protected function getCharges()
    {
        return \Craft::$app->db->createCommand('SELECT * FROM {{%stripecheckout_charges}}')->queryAll();
    }

    protected function insertCharges($records = null)
    {
        if ($records) {
            foreach ($records as $record) {
                // Ignore if charge already exists for some reason
                $exists = StripeCheckout::getInstance()->chargeService->getChargeByStripeId($record['stripeId']);

                if (!$exists) {
                    $charge = new Charge();

                    $charge->stripeId    = $record['stripeId'];
                    $charge->dateCreated = $record['dateCreated'];

                    $res = Craft::$app->getElements()->saveElement($charge, true, false);

                    if (!$res) {
                        echo "    > {$charge->stripeId} not inserted\n";
                    } else {
                        echo "    > {$charge->stripeId} inserted\n";

                        // Reconcile charge
                        $reconciled = $this->reconcileCharge($charge->stripeId);

                        if (!$reconciled) {
                          echo "    > {$charge->stripeId} not reconciled\n";
                        } else {
                          echo "    > {$charge->stripeId} reconciled\n";
                        }
                    }
                }
            }

            return true;
        }

        return false;
    }

    protected function reconcileCharge($id = null)
    {
        $secretKey = StripeCheckout::getInstance()->settingsService->getSecretKey();

        \Stripe\Stripe::setApiKey($secretKey);

        $charge = \Stripe\Charge::retrieve($id);

        if ($charge) {
            $inserted = StripeCheckout::getInstance()->chargeService->insertCharge($charge);

            if ($inserted) {
                return true;
            }
        }

        return false;
    }

    protected function dropTables()
    {
        $this->dropTable('{{%stripecheckout_charges}}');
    }
}
