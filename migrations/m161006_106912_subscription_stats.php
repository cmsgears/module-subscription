<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\ModelStats;
use cmsgears\subscription\common\models\base\SubscriptionTables;

/**
 * The subscription stats migration insert the default row count for all the tables available in
 * subscription module. A scheduled console job can be executed to update these stats.
 *
 * @since 1.0.0
 */
class m161006_106912_subscription_stats extends \cmsgears\core\common\base\Migration {

	// Public Variables

	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->options = Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

	public function up() {

		// Table Stats
		$this->insertTables();
	}

	private function insertTables() {

		$columns = [ 'parentId', 'parentType', 'name', 'type', 'count' ];

		$tableData = [
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'subscription_plan', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'subscription_plan_item', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'subscription_plan_meta', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'subscription_plan_follower', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'subscription_feature', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'subscription_matrix', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'subscription', 'rows', 0 ],
			[ 1, CoreGlobal::TYPE_SITE, $this->prefix . 'subscription_item', 'rows', 0 ]
		];

		$this->batchInsert( $this->prefix . 'core_stats', $columns, $tableData );
	}

	public function down() {

		ModelStats::deleteByTableName( SubscriptionTables::getTableName( SubscriptionTables::TABLE_SUBSCRIPTION_PLAN ) );
		ModelStats::deleteByTableName( SubscriptionTables::getTableName( SubscriptionTables::TABLE_SUBSCRIPTION_PLAN_ITEM ) );
		ModelStats::deleteByTableName( SubscriptionTables::getTableName( SubscriptionTables::TABLE_SUBSCRIPTION_PLAN_META ) );
		ModelStats::deleteByTableName( SubscriptionTables::getTableName( SubscriptionTables::TABLE_SUBSCRIPTION_PLAN_FOLLOWER ) );

		ModelStats::deleteByTableName( SubscriptionTables::getTableName( SubscriptionTables::TABLE_SUBSCRIPTION_FEATURE ) );
		ModelStats::deleteByTableName( SubscriptionTables::getTableName( SubscriptionTables::TABLE_SUBSCRIPTION_MATRIX ) );

		ModelStats::deleteByTableName( SubscriptionTables::getTableName( SubscriptionTables::TABLE_SUBSCRIPTION ) );
		ModelStats::deleteByTableName( SubscriptionTables::getTableName( SubscriptionTables::TABLE_SUBSCRIPTION_ITEM ) );
	}

}
