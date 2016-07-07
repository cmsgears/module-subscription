<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class m160623_105104_subscription extends \yii\db\Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Fixed
		$this->prefix		= 'cmg_';

		// Get the values via config
		$this->fk			= Yii::$app->migration->isFk();
		$this->options		= Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

    public function up() {

		// Subscription
		$this->upSubscription();

		if( $this->fk ) {

			$this->generateForeignKeys();
		}
    }

	private function upSubscription() {

        $this->createTable( $this->prefix . 'subscription', [
			'id' => $this->bigPrimaryKey( 20 ),
			'planId' => $this->bigInteger( 20 )->notNull(),
			'subscriberId' => $this->bigInteger( 20 )->notNull(),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
        ], $this->options );

        // Index for columns plan and user
		$this->createIndex( 'idx_' . $this->prefix . 'subscription_plan', $this->prefix . 'subscription', 'planId' );
		$this->createIndex( 'idx_' . $this->prefix . 'subscription_user', $this->prefix . 'subscription', 'subscriberId' );
	}

	private function generateForeignKeys() {

		// Subscription
        $this->addForeignKey( 'fk_' . $this->prefix . 'subscription_plan', $this->prefix . 'subscription', 'planId', $this->prefix . 'core_object', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subscription_user', $this->prefix . 'subscription', 'subscriberId', $this->prefix . 'core_user', 'id', 'RESTRICT' );
	}

    public function down() {

		if( $this->fk ) {

			$this->dropForeignKeys();
		}

        $this->dropTable( $this->prefix . 'subscription' );
    }

	private function dropForeignKeys() {

		// Subscription
        $this->dropForeignKey( 'fk_' . $this->prefix . 'subscription_plan', $this->prefix . 'subscription' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subscription_user', $this->prefix . 'subscription' );
	}
}

?>