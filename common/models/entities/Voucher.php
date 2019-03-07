<?php
namespace cmsgears\subscription\common\models\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

class Voucher extends \cmsgears\cart\common\models\entities\Voucher {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const TYPE_SUBSCRIPTION_SETUP			=  'sub-setup$';
	const TYPE_SUBSCRIPTION_SETUP_PERCENT	=  'sub-setup%';
	const TYPE_SUBSCRIPTION_RECURRING		=  'recurring';

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

Voucher::$typesMap[ Voucher::TYPE_SUBSCRIPTION_SETUP ] 			= 'Setup Fee $';
Voucher::$typesMap[ Voucher::TYPE_SUBSCRIPTION_SETUP_PERCENT ] 	= 'Setup Fee %';
Voucher::$typesMap[ Voucher::TYPE_SUBSCRIPTION_RECURRING ] 		= 'Recurring';
