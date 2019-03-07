<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

/**
 * The subscription migration inserts the database tables of subscription module. It also insert
 * the foreign keys if FK flag of migration component is true.
 *
 * @since 1.0.0
 */
class m161006_105104_subscription extends \cmsgears\core\common\base\Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->fk		= Yii::$app->migration->isFk();
		$this->options	= Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

    public function up() {

		// Subscription Plan
		$this->upSubscriptionPlan();
		$this->upSubscriptionFeature();
		$this->upSubscriptionUnit();
		$this->upSubscriptionMatrix();

		// Subscription
		$this->upSubscription();
		$this->upSubscriptionItem();

		if( $this->fk ) {

			$this->generateForeignKeys();
		}
    }

	private function upSubscriptionPlan() {

        $this->createTable( $this->prefix . 'subscription_plan', [
			'id' => $this->bigPrimaryKey( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'startDate' => $this->dateTime()->defaultValue( null ),
			'endDate' => $this->dateTime()->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
        ], $this->options );

        // Index for columns user, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_creator', $this->prefix . 'subscription_plan', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_modifier', $this->prefix . 'subscription_plan', 'modifiedBy' );
	}

	private function upSubscriptionFeature() {

        $this->createTable( $this->prefix . 'subscription_feature', [
			'id' => $this->bigPrimaryKey( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'startDate' => $this->dateTime()->defaultValue( null ),
			'endDate' => $this->dateTime()->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
        ], $this->options );

        // Index for columns user, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'subs_feature_creator', $this->prefix . 'subscription_feature', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'subs_feature_modifier', $this->prefix . 'subscription_feature', 'modifiedBy' );
	}

	private function upSubscriptionUnit() {

        $this->createTable( $this->prefix . 'subscription_unit', [
			'id' => $this->bigPrimaryKey( 20 ),
			'planId' => $this->bigInteger( 20 )->notNull(),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
            'parentId' => $this->bigInteger( 20 )->notNull(),
            'parentType' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'startDate' => $this->dateTime()->defaultValue( null ),
			'endDate' => $this->dateTime()->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
        ], $this->options );

        // Index for columns user, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'subs_unit_plan', $this->prefix . 'subscription_unit', 'planId' );
		$this->createIndex( 'idx_' . $this->prefix . 'subs_unit_creator', $this->prefix . 'subscription_unit', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'subs_unit_modifier', $this->prefix . 'subscription_unit', 'modifiedBy' );
	}

	private function upSubscriptionMatrix() {

		$this->createTable( $this->prefix . 'subscription_matrix', [
			'planId' => $this->bigInteger( 20 )->notNull(),
			'featureId' => $this->bigInteger( 20 )->notNull(),
			'PRIMARY KEY( planId, featureId )'
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'subs_matrix_plan', $this->prefix . 'subscription_matrix', 'planId' );
		$this->createIndex( 'idx_' . $this->prefix . 'subs_matrix_feature', $this->prefix . 'subscription_matrix', 'featureId' );
	}

	// Primary subscription
	private function upSubscription() {

        $this->createTable( $this->prefix . 'subscription', [
			'id' => $this->bigPrimaryKey( 20 ),
			'userId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
            'parentId' => $this->bigInteger( 20 )->notNull(), // Id set to plan id, if subscription is plan based
            'parentType' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ), // Type set to plan, if subscription is plan based
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'price' => $this->float(),
			'discount' => $this->float(),
			'total' => $this->float(),
			'duration' => $this->smallInteger( 6 )->defaultValue( 0 ), // Total duration
			'cycle' => $this->smallInteger( 6 )->defaultValue( 0 ), // Billing duration
			'unit' => $this->smallInteger( 6 )->defaultValue( 0 ), // Duration unit
			'startDate' => $this->dateTime()->defaultValue( null ),
			'endDate' => $this->dateTime()->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
        ], $this->options );

        // Index for columns user, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'subscription_user', $this->prefix . 'subscription', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'subscription_creator', $this->prefix . 'subscription', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'subscription_modifier', $this->prefix . 'subscription', 'modifiedBy' );
	}

	// Subscription add-ons
	private function upSubscriptionItem() {

		$this->createTable( $this->prefix . 'subscription_item', [
            'id' => $this->bigPrimaryKey( 20 ),
            'subscriptionId' => $this->bigInteger( 20 )->notNull(),
            'userId' => $this->bigInteger( 20 )->notNull(),
            'parentId' => $this->bigInteger( 20 )->notNull(),
            'parentType' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'price' => $this->float(),
			'discount' => $this->float(),
			'total' => $this->float(),
			'startDate' => $this->dateTime()->defaultValue( null ),
			'endDate' => $this->dateTime()->defaultValue( null )
        ], $this->options );

		$this->createIndex( 'idx_' . $this->prefix . 'subsc_item_parent', $this->prefix . 'subscription_item', 'subscriptionId' );
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_item_user', $this->prefix . 'subscription_item', 'userId' );
	}

	private function generateForeignKeys() {

		// Subscription Plan
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_plan_creator', $this->prefix . 'subscription_plan', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_plan_modifier', $this->prefix . 'subscription_plan', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Subscription Feature
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_feature_creator', $this->prefix . 'subscription_feature', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_feature_modifier', $this->prefix . 'subscription_feature', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Subscription Unit
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_unit_plan', $this->prefix . 'subscription_unit', 'createdBy', $this->prefix . 'subscription_plan', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_unit_creator', $this->prefix . 'subscription_unit', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_unit_modifier', $this->prefix . 'subscription_unit', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Subscription Matrix
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_matrix_plan', $this->prefix . 'subscription_matrix', 'planId', $this->prefix . 'subscription_plan', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_matrix_feature', $this->prefix . 'subscription_matrix', 'featureId', $this->prefix . 'subscription_plan', 'id', 'CASCADE' );

		// Subscription
		$this->addForeignKey( 'fk_' . $this->prefix . 'subscription_user', $this->prefix . 'subscription', 'userId', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subscription_creator', $this->prefix . 'subscription', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subscription_modifier', $this->prefix . 'subscription', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Subscription Item
		$this->addForeignKey( 'fk_' . $this->prefix . 'subsc_item_parent', $this->prefix . 'subscription_item', 'subscriptionId', $this->prefix . 'subscription', 'id', 'NO ACTION' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subsc_item_user', $this->prefix . 'subscription_item', 'userId', $this->prefix . 'core_user', 'id', 'NO ACTION' );
	}

    public function down() {

		if( $this->fk ) {

			$this->dropForeignKeys();
		}

		// Subscription Plan
		$this->dropTable( $this->prefix . 'subscription_plan' );
		$this->dropTable( $this->prefix . 'subscription_feature' );
		$this->dropTable( $this->prefix . 'subscription_unit' );
		$this->dropTable( $this->prefix . 'subscription_matrix' );

		// Subscription
        $this->dropTable( $this->prefix . 'subscription' );
		$this->dropTable( $this->prefix . 'subscription_item' );
    }

	private function dropForeignKeys() {

		// Subscription Plan
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_plan_creator', $this->prefix . 'subscription_plan' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_plan_modifier', $this->prefix . 'subscription_plan' );

		// Subscription Feature
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_feature_creator', $this->prefix . 'subscription_feature' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_feature_modifier', $this->prefix . 'subscription_feature' );

		// Subscription Unit
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_unit_plan', $this->prefix . 'subscription_unit' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_unit_creator', $this->prefix . 'subscription_unit' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_unit_modifier', $this->prefix . 'subscription_unit' );

		// Subscription Matrix
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_matrix_plan', $this->prefix . 'subscription_matrix' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_matrix_feature', $this->prefix . 'subscription_matrix' );

		// Subscription
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subscription_user', $this->prefix . 'subscription' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subscription_creator', $this->prefix . 'subscription' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subscription_modifier', $this->prefix . 'subscription' );

		// Subscription Item
        $this->dropForeignKey( 'fk_' . $this->prefix . 'subsc_item_parent', $this->prefix . 'subscription_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subsc_item_user', $this->prefix . 'subscription_item' );
	}

}
