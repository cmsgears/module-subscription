<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\subscription\common\components;

// CMG Imports
use cmsgears\subscription\common\config\SubscriptionGlobal;

class MessageSource extends \cmsgears\core\common\base\MessageSource {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	protected $messageDb = [
		// Generic Fields
		SubscriptionGlobal::FIELD_SUBSCRIPTION => 'Subscription',
		SubscriptionGlobal::FIELD_FEATURE => 'Feature',
		SubscriptionGlobal::FIELD_PLAN => 'Plan',
		SubscriptionGlobal::FIELD_SUBSCRIBER => 'Subscriber'
	];

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// MessageSource -------------------------

}
