<?php
namespace cmsgears\subscription\common\services\entities;

use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\core\common\models\entities\ObjectData;
use cmsgears\subscription\common\models\forms\PlanFeature;

use cmsgears\subscription\common\services\interfaces\entities\IPlanService;

use cmsgears\core\common\utilities\DataUtil;

class PlanService extends \cmsgears\core\common\services\entities\ObjectDataService implements IPlanService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $parentType	= SubscriptionGlobal::TYPE_PLAN;

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

	// PlanService ---------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelTable	= static::$modelTable;

		$config[ 'conditions' ][ "$modelTable.type" ] =  SubscriptionGlobal::TYPE_PLAN;

		return parent::getPage( $config );
	}

	// Read ---------------

	public function getFeatures( $plan, $associative = false ) {

		$objectData		= $plan->generateObjectFromJson();
		$features		= $objectData->features;
		$featureObjects	= [];
		$planFeatures	= [];

		foreach ( $features as $feature ) {

			$planFeature		= new PlanFeature( $feature );
			$featureObjects[]	= $planFeature;

			if( $associative ) {

				$planFeatures[ $planFeature->featureId ]	= $planFeature;
			}
		}

		if( $associative ) {

			return $planFeatures;
		}

		return $featureObjects;
	}

	public function getFeaturesForUpdate( $plan, $features ) {

		$planFeatures	= self::getFeatures( $plan, true );
		$keys			= array_keys( $planFeatures );
		$featureObjects	= [];

		foreach ( $features as $feature ) {

			if( in_array( $feature[ 'id' ], $keys ) ) {

				$planFeature			= $planFeatures[ $feature[ 'id' ] ];
				$planFeature->name		= $feature[ 'name' ];
				$featureObjects[]		= $planFeature;
			}
			else {

				$planFeature				= new PlanFeature();
				$planFeature->featureId		= $feature[ 'id' ];
				$planFeature->name			= $feature[ 'name' ];
				$featureObjects[]			= $planFeature;
			}
		}

		return $featureObjects;
	}

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateFeatures( $plan, $features ) {

		$plan		= self::findById( $plan->id );
		$objectData	= $plan->generateObjectFromJson();

		// Clear all existing mappings
		$objectData->features	= [];

		// Add Features
		if( isset( $features ) && count( $features ) > 0 ) {

			foreach ( $features as $feature ) {

				if( isset( $feature->feature ) && $feature->feature ) {

					if( !isset( $feature->order ) || strlen( $feature->order ) == 0 ) {

						$feature->order	= 0;
					}

					$objectData->features[] = $feature;
				}
			}
		}

		$objectData->features	= DataUtil::sortObjectArrayByNumber( $objectData->features, 'order', true );

		$plan->generateJsonFromObject( $objectData );

		$plan->update();

		return true;
	}

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// PlanService ---------------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------
}
