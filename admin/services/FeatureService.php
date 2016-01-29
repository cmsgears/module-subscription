<?php
namespace cmsgears\subscription\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\core\common\models\entities\ObjectData;

class FeatureService extends \cmsgears\subscription\common\services\FeatureService {
	
	//Read -------------------
	
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

		$config[ 'conditions' ][ 'type' ] =  SubscriptionGlobal::TYPE_FEATURE;

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