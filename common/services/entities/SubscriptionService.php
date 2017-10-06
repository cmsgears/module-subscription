<?php
namespace cmsgears\subscription\common\services\entities;

use \Yii;

// CMG Imports
use cmsgears\subscription\common\models\entities\Subscription;

use cmsgears\subscription\common\services\interfaces\entities\ISubscriptionService;

class SubscriptionService extends \cmsgears\core\common\services\base\EntityService implements ISubscriptionService {

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

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FeatureService ------------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// SubscriptionService -------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    public function getBySubscriberId( $id, $first = false ) {

		return Subscription::findBySubscriberId( $id, $first );
    }

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createSubscription( $config = [] ) {


		$subscription	= new Subscription();
		$status			= isset( $config[ 'status' ] ) ? $config[ 'status' ] : Subscription::STATUS_NEW;

		$subscription->planId			= $config[ 'planId' ];
		$subscription->subscriberId		= $config[ 'subscriberId' ];
		$subscription->status			= $status;
		$subscription->expirationDate	= $config[ 'expirationDate' ];
		$subscription->save();

		return $subscription;
	}

	// Update -------------

	// Delete -------------
}
