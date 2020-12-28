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
		$this->upDependent();
	}

	private function upPrimary() {

		// Sub Plan
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_plan_name', $this->prefix . 'subscription_plan', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_plan_type', $this->prefix . 'subscription_plan', 'type' );

		// Sub Feature
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_feature_name', $this->prefix . 'subscription_feature', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_feature_type', $this->prefix . 'subscription_feature', 'type' );

		// Sub Plan Item
		$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_item_type_p', $this->prefix . 'subscription_plan_item', 'parentType' );
		//$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_item_type_pipt', $this->prefix . 'subscription_plan_item', [ 'parentId', 'parentType' ] );

		// Sub Item
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_item_type_p', $this->prefix . 'subscription_item', 'parentType' );
		//$this->createIndex( 'idx_' . $this->prefix . 'subsc_item_type_pipt', $this->prefix . 'subscription_item', [ 'parentId', 'parentType' ] );
	}

	private function upDependent() {

		// Sub Plan Meta
		$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_meta_name', $this->prefix . 'subscription_plan_meta', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_meta_type', $this->prefix . 'subscription_plan_meta', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_meta_type_v', $this->prefix . 'subscription_plan_meta', 'valueType' );
		//$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_meta_mit', $this->prefix . 'subscription_plan_meta', [ 'modelId', 'type' ] );
		//$this->createIndex( 'idx_' . $this->prefix . 'subs_plan_meta_mitn', $this->prefix . 'subscription_plan_meta', [ 'modelId', 'type', 'name' ] );
	}

	public function down() {

		$this->downPrimary();
		$this->downDependent();
	}

	private function downPrimary() {

		// Sub Plan
		$this->dropIndex( 'idx_' . $this->prefix . 'subsc_plan_name', $this->prefix . 'subscription_plan' );
		$this->dropIndex( 'idx_' . $this->prefix . 'subsc_plan_type', $this->prefix . 'subscription_plan' );

		// Sub Feature
		$this->dropIndex( 'idx_' . $this->prefix . 'subsc_feature_name', $this->prefix . 'subscription_feature' );
		$this->dropIndex( 'idx_' . $this->prefix . 'subsc_feature_type', $this->prefix . 'subscription_feature' );

		// Sub Unit
		$this->dropIndex( 'idx_' . $this->prefix . 'subs_plan_item_type_p', $this->prefix . 'subscription_plan_item' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'subs_plan_item_type_pipt', $this->prefix . 'subscription_plan_item' );

		// Sub Item
		$this->dropIndex( 'idx_' . $this->prefix . 'subsc_item_type_p', $this->prefix . 'subscription_item' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'subsc_item_type_pipt', $this->prefix . 'subscription_item' );
	}

	private function downDependent() {

		// Page Meta
		$this->dropIndex( 'idx_' . $this->prefix . 'subs_plan_meta_name', $this->prefix . 'subscription_plan_meta' );
		$this->dropIndex( 'idx_' . $this->prefix . 'subs_plan_meta_type', $this->prefix . 'subscription_plan_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'subs_plan_meta_type_v', $this->prefix . 'subscription_plan_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'subs_plan_meta_mit', $this->prefix . 'subscription_plan_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'subs_plan_meta_mitn', $this->prefix . 'subscription_plan_meta' );
	}

}
