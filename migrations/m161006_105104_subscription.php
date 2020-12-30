<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\models\base\Meta;

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
		$this->upSubscriptionPlanItem();
		$this->upSubscriptionPlanMeta();
		$this->upSubscriptionPlanFollower();

		// Subscription Features
		$this->upSubscriptionFeature();
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
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'avatarId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'texture' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'visibility' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'pinned' => $this->boolean()->notNull()->defaultValue( false ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'popular' => $this->boolean()->notNull()->defaultValue( false ),
			'reviews' => $this->boolean()->notNull()->defaultValue( false ),
			'initial' => $this->float()->defaultValue( 0 ), // Setup Fee
			'price' => $this->float()->defaultValue( 0 ),
			'discount' => $this->float()->defaultValue( 0 ),
			'total' => $this->float()->defaultValue( 0 ),
			'currency' => $this->mediumText(),
			'delivery' => $this->smallInteger( 6 )->defaultValue( 0 ), // Daily, Weekly, etc
			'billing' => $this->smallInteger( 6 )->defaultValue( 0 ), // Daily, Weekly, etc
			'trial' => $this->smallInteger( 6 )->defaultValue( 0 ), // Daily, Weekly, etc
			'advance' => $this->boolean()->notNull()->defaultValue( false ), // Advance Payments
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
		$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_site', $this->prefix . 'subscription_plan', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_avatar', $this->prefix . 'subscription_plan', 'avatarId' );
		$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_creator', $this->prefix . 'subscription_plan', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_modifier', $this->prefix . 'subscription_plan', 'modifiedBy' );
	}

	private function upSubscriptionPlanItem() {

        $this->createTable( $this->prefix . 'subscription_plan_item', [
			'id' => $this->bigPrimaryKey( 20 ),
			'planId' => $this->bigInteger( 20 )->notNull(),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'price' => $this->float()->defaultValue( 0 ),
			'discount' => $this->float()->defaultValue( 0 ),
			'total' => $this->float()->defaultValue( 0 ),
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
		$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_item_plan', $this->prefix . 'subscription_plan_item', 'planId' );
		$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_item_creator', $this->prefix . 'subscription_plan_item', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_item_modifier', $this->prefix . 'subscription_plan_item', 'modifiedBy' );
	}

	private function upSubscriptionPlanMeta() {

		$this->createTable( $this->prefix . 'subscription_plan_meta', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'label' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'valueType' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( Meta::VALUE_TYPE_TEXT ),
			'value' => $this->text(),
			'data' => $this->mediumText()
		], $this->options );

		// Index for columns site, parent, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_meta_parent', $this->prefix . 'subscription_plan_meta', 'modelId' );
	}

	private function upSubscriptionPlanFollower() {

        $this->createTable( $this->prefix . 'subscription_plan_follower', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true ),
			'pinned' => $this->boolean()->notNull()->defaultValue( false ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'popular' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'data' => $this->mediumText()
        ], $this->options );

        // Index for columns user and model
		$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_follower_user', $this->prefix . 'subscription_plan_follower', 'modelId' );
		$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_follower_parent', $this->prefix . 'subscription_plan_follower', 'parentId' );
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
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
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

	// Subscription
	private function upSubscription() {

        $this->createTable( $this->prefix . 'subscription', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'userId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
            'parentId' => $this->bigInteger( 20 )->notNull(), // Id set to plan id, if subscription is plan based
            'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(), // Type set to plan, if subscription is plan based
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'initial' => $this->float()->defaultValue( 0 ),
			'price' => $this->float()->defaultValue( 0 ),
			'discount' => $this->float()->defaultValue( 0 ),
			'total' => $this->float()->defaultValue( 0 ),
			'currency' => $this->mediumText()->defaultValue( null ),
			'delivery' => $this->smallInteger( 6 )->defaultValue( 0 ), // Daily, Weekly, etc
			'billing' => $this->smallInteger( 6 )->defaultValue( 0 ), // Daily, Weekly, etc
			'trial' => $this->smallInteger( 6 )->defaultValue( 0 ), // Daily, Weekly, etc
			'advance' => $this->boolean()->notNull()->defaultValue( false ), // Advance Payments
			'startDate' => $this->dateTime()->defaultValue( null ),
			'endDate' => $this->dateTime()->defaultValue( null ),
			'prevBillingDate' => $this->dateTime()->defaultValue( null ),
			'nextBillingDate' => $this->dateTime()->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
        ], $this->options );

        // Index for columns user, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'subscription_user', $this->prefix . 'subscription', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'subscription_creator', $this->prefix . 'subscription', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'subscription_modifier', $this->prefix . 'subscription', 'modifiedBy' );
	}

	// Subscription Line Items
	private function upSubscriptionItem() {

		$this->createTable( $this->prefix . 'subscription_item', [
            'id' => $this->bigPrimaryKey( 20 ),
            'subscriptionId' => $this->bigInteger( 20 )->notNull(),
            'userId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'price' => $this->float()->defaultValue( 0 ),
			'discount' => $this->float()->defaultValue( 0 ),
			'total' => $this->float()->defaultValue( 0 ),
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

		$this->createIndex( 'idx_' . $this->prefix . 'subsc_item_parent', $this->prefix . 'subscription_item', 'subscriptionId' );
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_item_user', $this->prefix . 'subscription_item', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_item_creator', $this->prefix . 'subscription_item', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_item_modifier', $this->prefix . 'subscription_item', 'modifiedBy' );
	}

	private function generateForeignKeys() {

		// Subscription Plan
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_plan_site', $this->prefix . 'subscription_plan', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_plan_avatar', $this->prefix . 'subscription_plan', 'avatarId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_plan_creator', $this->prefix . 'subscription_plan', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_plan_modifier', $this->prefix . 'subscription_plan', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Subscription Plan Item
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_plan_item_plan', $this->prefix . 'subscription_plan_item', 'createdBy', $this->prefix . 'subscription_plan', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_plan_item_creator', $this->prefix . 'subscription_plan_item', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_plan_item_modifier', $this->prefix . 'subscription_plan_item', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Subscription Plan meta
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_plan_meta_parent', $this->prefix . 'subscription_plan_meta', 'modelId', $this->prefix . 'cms_page', 'id', 'CASCADE' );

		// Subscription Plan Follower
        $this->addForeignKey( 'fk_' . $this->prefix . 'subs_plan_follower_user', $this->prefix . 'subscription_plan_follower', 'modelId', $this->prefix . 'core_user', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_plan_follower_parent', $this->prefix . 'subscription_plan_follower', 'parentId', $this->prefix . 'cms_page', 'id', 'CASCADE' );


		// Subscription Feature
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_feature_creator', $this->prefix . 'subscription_feature', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subs_feature_modifier', $this->prefix . 'subscription_feature', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

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
		$this->addForeignKey( 'fk_' . $this->prefix . 'subsc_item_creator', $this->prefix . 'subscription_item', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'subsc_item_modifier', $this->prefix . 'subscription_item', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );
	}

    public function down() {

		if( $this->fk ) {

			$this->dropForeignKeys();
		}

		// Subscription Plan
		$this->dropTable( $this->prefix . 'subscription_plan' );
		$this->dropTable( $this->prefix . 'subscription_plan_item' );
		$this->dropTable( $this->prefix . 'subscription_plan_meta' );
		$this->dropTable( $this->prefix . 'subscription_plan_follower' );

		// Subscription Feature
		$this->dropTable( $this->prefix . 'subscription_feature' );
		$this->dropTable( $this->prefix . 'subscription_matrix' );

		// Subscription
        $this->dropTable( $this->prefix . 'subscription' );
		$this->dropTable( $this->prefix . 'subscription_item' );
    }

	private function dropForeignKeys() {

		// Subscription Plan
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_plan_site', $this->prefix . 'subscription_plan' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_plan_avatar', $this->prefix . 'subscription_plan' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_plan_creator', $this->prefix . 'subscription_plan' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_plan_modifier', $this->prefix . 'subscription_plan' );

		// Subscription Plan Item
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_plan_item_plan', $this->prefix . 'subscription_plan_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_plan_item_creator', $this->prefix . 'subscription_plan_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_plan_item_modifier', $this->prefix . 'subscription_plan_item' );

		// Subscription Plan meta
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_plan_meta_parent', $this->prefix . 'subscription_plan_meta' );

		// Subscription Plan Follower
        $this->dropForeignKey( 'fk_' . $this->prefix . 'subs_plan_follower_user', $this->prefix . 'subscription_plan_follower' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_plan_follower_parent', $this->prefix . 'subscription_plan_follower' );

		// Subscription Feature
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_feature_creator', $this->prefix . 'subscription_feature' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subs_feature_modifier', $this->prefix . 'subscription_feature' );

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
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subsc_item_creator', $this->prefix . 'subscription_item' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'subsc_item_modifier', $this->prefix . 'subscription_item' );
	}

}
