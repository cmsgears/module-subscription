<?php
namespace cmsgears\subscription\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\subscription\common\config\SubscriptionGlobal;

class MessageSource extends \yii\base\Component {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	// Protected --------------

	protected $messageDb = [
		// Generic Fields
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

	public function getMessage( $messageKey, $params = [], $language = null ) {

		return $this->messageDb[ $messageKey ];
	}
}
