<?php
namespace cmsgears\subscription\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\subscription\common\config\SubscriptionGlobal;

class MessageSource extends \yii\base\Component {

	// Variables ---------------------------------------------------

	private $messageDb = [
		// Generic Fields 
		SubscriptionGlobal::FIELD_FEATURE => 'Feature',
		SubscriptionGlobal::FIELD_PLAN => 'Plan',
		SubscriptionGlobal::FIELD_SUBSCRIBER => 'Subscriber'
	];

	/**
	 * Initialise the Cms Message DB Component.
	 */
    public function init() {

        parent::init();
    }

	public function getMessage( $messageKey, $params = [], $language = null ) {

		return $this->messageDb[ $messageKey ];
	}
}

?>