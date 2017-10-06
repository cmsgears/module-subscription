<?php
namespace cmsgears\subscription\common\services\interfaces\entities;

// Yii Imports
use \Yii;

interface ISubscriptionService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---
    public function getBySubscriberId( $id );

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------

	// Update -------------

	// Delete -------------

}
