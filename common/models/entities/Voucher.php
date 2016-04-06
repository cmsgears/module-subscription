<?php
namespace cmsgears\subscription\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\subscription\common\models\base\SubscriptionTables;

class Voucher extends \cmsgears\cart\common\models\entities\Voucher {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	const TYPE_SUBSCRIPTION_SETUP			=  200;
	const TYPE_SUBSCRIPTION_SETUP_PERCENT	=  210;
	const TYPE_SUBSCRIPTION_RECURRING		=  220;

	// Public -------------

	// Private/Protected --

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

	// <Yii Interfaces > -----------------

	// yii\base\Component ----------------

	// yii\base\Model --------------------

	// < parent class > ------------------

	// <CMG Interfaces > -----------------

	// <Model> ---------------------------

	// Static Methods ----------------------------------------------

	// <Yii Interfaces > -----------------

	// yii\db\ActiveRecord ---------------

	// < parent class > ------------------

	// <CMG Interfaces > -----------------

	// <Model> ---------------------------

	// Create -------------

	// Read ---------------

	// Update -------------

	// Delete -------------
}

Voucher::$typesMap[ Voucher::TYPE_SUBSCRIPTION_SETUP ] 			= 'Setup $';
Voucher::$typesMap[ Voucher::TYPE_SUBSCRIPTION_SETUP_PERCENT ] 	= 'Setup %';
Voucher::$typesMap[ Voucher::TYPE_SUBSCRIPTION_RECURRING ] 		= 'Recurring';

?>