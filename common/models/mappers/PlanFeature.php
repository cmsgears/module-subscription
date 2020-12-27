<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\subscription\common\models\mappers;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\subscription\common\models\base\SubscriptionTables;
use cmsgears\subscription\common\models\entities\SubscriptionPlan;
use cmsgears\subscription\common\models\resources\SubscriptionFeature;

/**
 * Mapper to map subscription plans and features.
 *
 * @property integer $planId
 * @property integer $featureId
 *
 * @since 1.0.0
 */
class PlanFeature extends \cmsgears\core\common\models\base\Mapper {

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

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'planId', 'featureId' ], 'required' ],
			// Unique
			[ [ 'planId', 'featureId' ], 'unique', 'targetAttribute' => [ 'planId', 'featureId' ] ],
			// Other
			[ [ 'planId', 'featureId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'planId' => Yii::$app->subscriptionMessage->getMessage( SubscriptionGlobal::FIELD_PLAN ),
			'featureId' => Yii::$app->subscriptionMessage->getMessage( SubscriptionGlobal::FIELD_FEATURE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// PlanFeature ---------------------------

	/**
	 * Return the plan associated with the mapping.
	 *
	 * @return \cmsgears\subscription\common\models\entities\SubscriptionPlan
	 */
	public function getPlan() {

		return $this->hasOne( SubscriptionPlan::class, [ 'id' => 'planId' ] );
	}

	/**
	 * Return the feature associated with the mapping.
	 *
	 * @return \cmsgears\subscription\common\models\resources\SubscriptionFeature
	 */
	public function getFeature() {

		return $this->hasOne( SubscriptionFeature::class, [ 'id' => 'featureId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return SubscriptionTables::getTableName( SubscriptionTables::TABLE_SUBSCRIPTION_MATRIX );
	}

	// CMG parent classes --------------------

	// PlanFeature ------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'plan', 'feature' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the mapping with plan.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with plan.
	 */
	public static function queryWithPlan( $config = [] ) {

		$config[ 'relations' ] = [ 'plan' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the mapping with feature.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with feature.
	 */
	public static function queryWithFeature( $config = [] ) {

		$config[ 'relations' ] = [ 'feature' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Find and return the mapping associated with given plan id and feature id.
	 *
	 * @param integer $planId
	 * @param integer $featureId
	 * @return PlanFeature
	 */
	public static function findByPlanIdFeatureId( $planId, $featureId ) {

		return self::find()->where( 'planId=:rid AND $featureId=:pid', [ ':rid' => $planId, ':pid' => $featureId ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete all the mappings associated with given plan id.
	 *
	 * @param type $planId
	 * @return int the number of rows deleted.
	 */
	public static function deleteByPlanId( $planId ) {

		return self::deleteAll( 'planId=:id', [ ':id' => $planId ] );
	}

	/**
	 * Delete all the mappings associated with given feature id.
	 *
	 * @param type $featureId
	 * @return int the number of rows deleted.
	 */
	public static function deleteByFeatureId( $featureId ) {

		return self::deleteAll( 'featureId=:id', [ ':id' => $featureId ] );
	}

}
