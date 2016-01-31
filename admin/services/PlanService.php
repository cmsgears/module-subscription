<?php
namespace cmsgears\subscription\admin\services;

// Yii Imports
use \Yii; 

// CMG Imports
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\core\common\utilities\SortUtil;

class PlanService extends  \cmsgears\subscription\common\services\PlanService {
	
	//Read ------------------- 
	
	//Create -----------------
	
	//Update -----------------
	
	/**
	 * @param array $features
	 * @return boolean
	 */
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

					$objectData->features[] 	= $feature;
				}
			}
		}

		$objectData->features	= SortUtil::sortObjectArrayByNumber( $objectData->features, 'order', true );

		$plan->generateJsonFromObject( $objectData );

		$plan->update();

		return true;
	}
}