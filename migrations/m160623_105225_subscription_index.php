<?php

class m160623_105225_subscription_index extends \yii\db\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	public function init() {

		// Fixed
		$this->prefix	= 'cmg_';
	}

	public function up() {

		$this->upPrimary();
	}

	private function upPrimary() {

		// Sub Item
		$this->createIndex( 'idx_' . $this->prefix . 'subsc_item_type', $this->prefix . 'subscription_item', 'parentType' );
	}

	public function down() {

		$this->downPrimary();
	}

	private function downPrimary() {

		// Sub Item
		$this->dropIndex( 'idx_' . $this->prefix . 'subsc_item_type', $this->prefix . 'subscription_item' );
	}
}