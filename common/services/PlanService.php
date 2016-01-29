<?php
namespace cmsgears\subscription\common\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\core\common\models\entities\ObjectData;
use cmsgears\subscription\common\models\forms\PlanFeature;

class PlanService extends \cmsgears\core\common\services\ObjectDataService {
	
	//Read -------------------
	
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
	
	
	/**
	 * @param string $slug
	 * @return ObjectData
	 */
	public static function findBySlug( $slug ) {

		return self::findBySlugType( $slug, SubscriptionGlobal::TYPE_PLAN );
	}  
	
	public static function getPagination( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ]
	        ]
	    ]);

		if( !isset( $config[ 'conditions' ] ) ) {

			$config[ 'conditions' ]	= [];
		}

		$config[ 'conditions' ][ 'type' ] =  SubscriptionGlobal::TYPE_PLAN;

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = 'name';
		}

		return self::getDataProvider( new ObjectData(), $config );
	}
	
	//Create -----------------
	
	//Update -----------------
	
	//Delete -----------------
}