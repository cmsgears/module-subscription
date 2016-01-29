<?php
namespace cmsgears\subscription\common\services;

// Yii Imports
use \Yii; 

// CMG Imports
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\core\common\models\entities\CoreTables;

class FeatureService extends \cmsgears\core\common\services\ObjectDataService {
	
	//Read -------------------
	
	/**
	 * @param string $slug
	 * @return ObjectData
	 */
	public static function findBySlug( $slug ) {

		return self::findBySlugType( $slug, SubscriptionGlobal::TYPE_FEATURE );
	} 
	
	/**
	 * @param array $config
	 * @return array - an array having id as key and name as value.
	 */
	public static function findIdNameList() {

		return self::getIdNameList( [ 'conditions' => [ 'type' => SubscriptionGlobal::TYPE_FEATURE, 'active'  => 1 ] ] );
	}
	
	//Create -----------------
	
	//Update -----------------
	
	//Delete -----------------
}