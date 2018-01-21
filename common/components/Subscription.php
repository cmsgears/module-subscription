<?php
namespace cmsgears\subscription\common\components;

// Yii Imports
use \Yii;
use yii\di\Container;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class Subscription extends \yii\base\Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

    /**
     * Initialise the CMG Core Component.
     */
    public function init() {

        parent::init();

		// Register application components and objects i.e. CMG and Project
		$this->registerComponents();
    }

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Subscription --------------------------

	// Properties

	// Components and Objects

	public function registerComponents() {

		// Register services
		$this->registerEntityServices();

		// Init services
		$this->initEntityServices();
	}

	public function registerEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\subscription\common\services\interfaces\entities\IFeatureService', 'cmsgears\subscription\common\services\entities\FeatureService' );
		$factory->set( 'cmsgears\subscription\common\services\interfaces\entities\IPlanService', 'cmsgears\subscription\common\services\entities\PlanService' );
		$factory->set( 'cmsgears\subscription\common\services\interfaces\entities\ISubscriptionService', 'cmsgears\subscription\common\services\entities\SubscriptionService' );
		$factory->set( 'cmsgears\subscription\common\services\interfaces\entities\ISubscriptionItemService', 'cmsgears\subscription\common\services\entities\SubscriptionItemService' );
	}

	public function initEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'subFeatureService', 'cmsgears\subscription\common\services\entities\FeatureService' );
		$factory->set( 'subPlanService', 'cmsgears\subscription\common\services\entities\PlanService' );
		$factory->set( 'subscriptionService', 'cmsgears\subscription\common\services\entities\SubscriptionService' );
		$factory->set( 'subscriptionItemService', 'cmsgears\subscription\common\services\entities\SubscriptionItemService' );
	}
}
