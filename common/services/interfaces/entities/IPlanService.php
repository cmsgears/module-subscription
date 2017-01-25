<?php
namespace cmsgears\subscription\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IPlanService extends \cmsgears\core\common\services\interfaces\entities\IObjectService {

	// Data Provider ------

	// Read ---------------

	public function getFeatures( $plan, $associative = false );

	public function getFeaturesForUpdate( $plan, $features );

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------

	// Update -------------

	public function updateFeatures( $plan, $features );

	// Delete -------------

}
