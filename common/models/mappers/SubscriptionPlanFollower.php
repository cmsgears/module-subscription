<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\subscription\common\models\mappers;

// CMG Imports
use cmsgears\subscription\common\models\base\SubscriptionTables;
use cmsgears\subscription\common\models\entities\SubscriptionPlan;

/**
 * SubscriptionPlanFollower represents interest of user in Job.
 *
 * @inheritdoc
 *
 * @since 1.0.0
 */
class SubscriptionPlanFollower extends \cmsgears\core\common\models\base\Follower {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// SubscriptionPlanFollower --------------

	/**
	 * Return corresponding Subscription Plan.
	 *
	 * @return \cmsgears\subscription\common\models\entities\SubscriptionPlan
	 */
	public function getParent() {

		return $this->hasOne( SubscriptionPlan::class, [ 'id' => 'parentId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return SubscriptionTables::getTableName( SubscriptionTables::TABLE_SUBSCRIPTION_PLAN_FOLLOWER );
	}

	// CMG parent classes --------------------

	// SubscriptionPlanFollower --------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
