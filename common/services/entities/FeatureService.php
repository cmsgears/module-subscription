<?php
namespace cmsgears\subscription\common\services\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\core\common\models\entities\CoreTables;

class FeatureService extends \cmsgears\core\common\services\entities\ObjectDataService {

	//Read -------------------

	/**
	 * @param array $config
	 * @return array - an array having id as key and name as value.
	 */
	public static function findIdNameList( $conditions = [] ) {

        $conditions[ 'type' ] = SubscriptionGlobal::TYPE_FEATURE;

		return self::getIdNameList( [ 'conditions' => $conditions ] );
	}

    /**
     * @param array $config
     * @return array - an array having id as key and name as value.
     */
    public static function findActiveFeatures() {

        return self::findIdNameList( [ 'active' => CoreGlobal::STATUS_ACTIVE ] );
    }
}