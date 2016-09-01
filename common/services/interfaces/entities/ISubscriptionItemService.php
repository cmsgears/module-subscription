<?php
namespace cmsgears\subscription\common\services\interfaces\entities;

// Yii Imports
use \Yii;

interface ISubscriptionItemService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---
    public function getByParentId( $id );

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------
	public function createItem( $model, $subscription, $config = [] );

	// Update -------------
	public function updateEndDate( $model, $date );

	// Delete -------------

}
