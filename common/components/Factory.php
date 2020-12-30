<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\subscription\common\components;

// Yii Imports
use Yii;

/**
 * The Subscription Factory component initialize the services available in Subscription Module.
 *
 * @since 1.0.0
 */
class Factory extends \cmsgears\core\common\base\Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Register services
		$this->registerServices();

		// Register service alias
		$this->registerServiceAlias();
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Factory -------------------------------

	public function registerServices() {

		$this->registerResourceServices();
		$this->registerMapperServices();
		$this->registerEntityServices();
	}

	public function registerServiceAlias() {

		$this->registerResourceAliases();
		$this->registerMapperAliases();
		$this->registerEntityAliases();
	}

	public function registerResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\subscription\common\services\interfaces\resources\ISubscriptionPlanItemService', 'cmsgears\subscription\common\services\resources\SubscriptionPlanItemService' );
		$factory->set( 'cmsgears\subscription\common\services\interfaces\resources\ISubscriptionPlanMetaService', 'cmsgears\subscription\common\services\resources\SubscriptionPlanMetaService' );

		$factory->set( 'cmsgears\subscription\common\services\interfaces\resources\ISubscriptionService', 'cmsgears\subscription\common\services\entities\SubscriptionService' );
		$factory->set( 'cmsgears\subscription\common\services\interfaces\resources\ISubscriptionItemService', 'cmsgears\subscription\common\services\entities\SubscriptionItemService' );

		$factory->set( 'cmsgears\subscription\common\services\interfaces\resources\IFeatureService', 'cmsgears\subscription\common\services\entities\FeatureService' );
	}

	public function registerMapperServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\subscription\common\services\interfaces\mappers\ISubscriptionPlanFollowerService', 'cmsgears\subscription\common\services\mappers\SubscriptionPlanFollowerService' );
	}

	public function registerEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\subscription\common\services\interfaces\entities\ISubscriptionPlanService', 'cmsgears\subscription\common\services\entities\SubscriptionPlanService' );
	}

	public function registerResourceAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'subscriptionPlanItemService', 'cmsgears\subscription\common\services\resources\SubscriptionPlanItemService' );
		$factory->set( 'subscriptionPlanMetaService', 'cmsgears\subscription\common\services\resources\SubscriptionPlanMetaService' );

		$factory->set( 'subscriptionService', 'cmsgears\subscription\common\services\entities\SubscriptionService' );
		$factory->set( 'subscriptionItemService', 'cmsgears\subscription\common\services\entities\SubscriptionItemService' );

		$factory->set( 'subscriptionFeatureService', 'cmsgears\subscription\common\services\entities\FeatureService' );
	}

	public function registerMapperAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'subscriptionPlanFollowerService', 'cmsgears\subscription\common\services\mappers\SubscriptionPlanFollowerService' );
	}

	public function registerEntityAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'subscriptionPlanService', 'cmsgears\subscription\common\services\entities\SubscriptionPlanService' );
	}

}
