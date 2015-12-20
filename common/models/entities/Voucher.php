<?php
namespace cmsgears\cart\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;

use cmsgears\core\common\models\entities\NamedCmgEntity;

class Voucher extends cmsgears\cart\common\models\entities\Voucher {

	const TYPE_SUBSCRIPTION_SETUP			=  200;
	const TYPE_SUBSCRIPTION_SETUP_PERCENT	=  210;
	const TYPE_SUBSCRIPTION_RECURRING		=  220;

	public static $typesMap = [
	    self::TYPE_CART  => 'Cart $',
	    self::TYPE_CART_PERCENT => 'Cart %',
	    self::TYPE_PRODUCT => 'Product $',
	    self::TYPE_PRODUCT_PERCENT => 'Product %',
	    self::TYPE_SUBSCRIPTION_SETUP => 'Setup $',
	    self::TYPE_SUBSCRIPTION_SETUP_PERCENT => 'Setup %',
	    self::TYPE_SUBSCRIPTION_RECURRING => 'Recurring'
	];
}

?>