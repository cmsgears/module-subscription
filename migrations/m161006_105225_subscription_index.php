<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

/**
 * The subscription index migration inserts the recommended indexes for better performance. It
 * also list down other possible index commented out. These indexes can be created using
 * project based migration script.
 *
 * @since 1.0.0
 */
class m161006_105225_subscription_index extends \cmsgears\core\common\base\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;
	}

	public function up() {

		$this->upPrimary();
	}

	private function upPrimary() {

		// Sub Plan
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_plan_name', $this->prefix . 'subscription_plan', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_plan_type', $this->prefix . 'subscription_plan', 'type' );

		// Sub Feature
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_feature_name', $this->prefix . 'subscription_feature', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_feature_type', $this->prefix . 'subscription_feature', 'type' );

		// Sub Unit
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_unit_type_p', $this->prefix . 'subscription_unit', 'parentType' );
		//$this->createIndex( 'idx_' . $this->prefix . 'subsc_unit_type_pipt', $this->prefix . 'subscription_unit', [ 'parentId', 'parentType' ] );

		// Sub Item
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_item_type', $this->prefix . 'subscription_item', 'parentType' );
		//$this->createIndex( 'idx_' . $this->prefix . 'subsc_item_type_pipt', $this->prefix . 'subscription_item', [ 'parentId', 'parentType' ] );
	}

	public function down() {

		$this->downPrimary();
	}

	private function downPrimary() {

		// Sub Plan
		$this->dropIndex( 'idx_' . $this->prefix . 'subsc_plan_name', $this->prefix . 'subscription_plan' );
		$this->dropIndex( 'idx_' . $this->prefix . 'subsc_plan_type', $this->prefix . 'subscription_plan' );

		// Sub Feature
		$this->dropIndex( 'idx_' . $this->prefix . 'subsc_feature_name', $this->prefix . 'subscription_feature' );
		$this->dropIndex( 'idx_' . $this->prefix . 'subsc_feature_type', $this->prefix . 'subscription_feature' );

		// Sub Unit
		$this->dropIndex( 'idx_' . $this->prefix . 'subsc_unit_type_p', $this->prefix . 'subscription_unit' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'subsc_unit_type_pipt', $this->prefix . 'subscription_unit' );

		// Sub Item
		$this->dropIndex( 'idx_' . $this->prefix . 'subsc_item_type', $this->prefix . 'subscription_item' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'subsc_item_type_pipt', $this->prefix . 'subscription_item' );
	}

}