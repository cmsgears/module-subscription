<?php
namespace cmsgears\subscription\common\services\entities;

use \Yii;
use yii\db\Expression;

// CMG Imports
use cmsgears\subscription\common\models\entities\SubscriptionItem;

use cmsgears\subscription\common\services\interfaces\entities\ISubscriptionItemService;

class SubscriptionItemService extends \cmsgears\core\common\services\base\EntityService implements ISubscriptionItemService {

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

	// SubscriptionItemService ---------------

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

	// SubscriptionItemService ---------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createItem( $model, $subscription, $config = [] ) {

		$item					= new SubscriptionItem();
		$active					= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;
		$startDate				= isset( $config[ 'startDate' ] ) ? $config[ 'startDate' ] : Yii::$app->formatter->asDatetime( date( 'Y-m-d h:i:s' ) );

		$item->parentId			= $model->id;
		$item->parentType		= $config[ 'parentType' ];
		$item->subscriptionId	= $subscription->id;
		$item->userId			= $model->ownerId;
		$item->startDate		= $startDate;
		$item->endDate			= $config[ 'endDate' ];
		$item->active			= $active;
		$item->save();

		return $item;
	}

	// Update -------------

	public function updateEndDate( $model, $date ) {

		$model->endDate	= $date;
		$model->update();

		return $model;
	}

	// Delete -------------
}
