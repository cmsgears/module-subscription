<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\subscription\common\models\resources;

// CMG Imports
use cmsgears\subscription\common\config\SubscriptionGlobal;

class SubscriptionVoucher extends \cmsgears\cart\common\models\resources\Voucher {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const SCHEME_SETUP			= 1000;
	const SCHEME_SETUP_PERCENT	= 1010;

	const SCHEME_RECURRING			= 1020;
	const SCHEME_RECURRING_PERCENT	= 1030;

	// Public -----------------

	public static $schemeMap = [
		self::SCHEME_SETUP => 'Setup $',
		self::SCHEME_SETUP_PERCENT => 'Setup %',
		self::SCHEME_RECURRING => 'Recurring $',
		self::SCHEME_RECURRING_PERCENT => 'Recurring %'
	];

	public static $revSchemeMap = [
		'Setup $' => self::SCHEME_SETUP,
		'Setup %' => self::SCHEME_SETUP_PERCENT,
		'Recurring $' => self::SCHEME_RECURRING,
		'Recurring %' => self::SCHEME_RECURRING_PERCENT
	];

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

	// yii\db\BaseActiveRecord

    /**
     * @inheritdoc
     */
	public function beforeSave( $insert ) {

	    if( parent::beforeSave( $insert ) ) {

			// Default Type
			$this->type = $this->type ?? SubscriptionGlobal::TYPE_SUBSCRIPTION;

	        return true;
	    }

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Voucher -------------------------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	// CMG parent classes --------------------

	// Voucher -------------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
