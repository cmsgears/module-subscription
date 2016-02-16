<?php
namespace cmsgears\subscription\common\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\core\common\models\entities\ObjectData;
use cmsgears\subscription\common\models\forms\PlanFeature;

use cmsgears\core\common\utilities\SortUtil;

class PlanService extends \cmsgears\core\common\services\ObjectDataService {

	// Read -------------------

	public static function findBySlug( $slug ) {

		return self::findBySlugType( $slug, SubscriptionGlobal::TYPE_PLAN );
	}

	public static function getFeatures( $plan, $associative = false ) {

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
	
	public static function getFeaturesForUpdate( $plan, $features ) {

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

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		if( !isset( $config[ 'conditions' ] ) ) {

			$config[ 'conditions' ]	= [];
		}

		$config[ 'conditions' ][ 'type' ] =  SubscriptionGlobal::TYPE_PLAN;

		return self::getDataProvider( new ObjectData(), $config );
	}

	// Update -----------------

	public static function updateFeatures( $plan, $features ) {

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

		$objectData->features	= SortUtil::sortObjectArrayByNumber( $objectData->features, 'order', true );

		$plan->generateJsonFromObject( $objectData );

		$plan->update();

		return true;
	}
}

?>