<?php
namespace cmsgears\subscription\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\core\common\models\entities\ObjectData;
use cmsgears\subscription\common\models\forms\PlanFeature;

use cmsgears\subscription\admin\services\PlanService;
use cmsgears\subscription\admin\services\FeatureService;

class PlanController extends \cmsgears\core\admin\controllers\base\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 	= [ 'parent' => 'sidebar-subscription', 'child' => 'plans' ];
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'all' => [ 'permission' => SubscriptionGlobal::PERM_SUBSCRIPTION ],
	                'create' => [ 'permission' => SubscriptionGlobal::PERM_SUBSCRIPTION ],
	                'update' => [ 'permission' => SubscriptionGlobal::PERM_SUBSCRIPTION ],
	                'delete' => [ 'permission' => SubscriptionGlobal::PERM_SUBSCRIPTION ] 
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'all' => [ 'get' ],
	                'create' => [ 'get', 'post' ],
	                'update' => [ 'get', 'post' ],
	                'delete' => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// PlanController -----------------

	public function actionAll() {

		$dataProvider = PlanService::getPagination();

	    return $this->render('all', [
	         'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate() {

		$model			= new ObjectData();
		$model->siteId	= Yii::$app->cmgCore->siteId;
		$model->type	= SubscriptionGlobal::TYPE_PLAN; 
		$model->data	= "{ \"features\": {} }";
		$featuresList	= FeatureService::findIdNameList();

		$model->setScenario( 'create' );

		// Plan Features
		$planFeatures	= [];

		for ( $i = 0, $j = count( $featuresList ); $i < $j; $i++ ) {

			$planFeatures[] = new PlanFeature();
		}

		if( $model->load( Yii::$app->request->post(), 'ObjectData' ) && PlanFeature::loadMultiple( $planFeatures, Yii::$app->request->post(), 'PlanFeature' ) && 
			$model->validate() && PlanFeature::validateMultiple( $planFeatures ) ) {

			$plan	= PlanService::create( $model );

			PlanService::updateFeatures( $plan, $planFeatures );

			return $this->redirect( [ 'all' ] );
		}

    	return $this->render( 'create', [
    		'model' => $model,
    		'featuresList' => $featuresList,
    		'planFeatures' => $planFeatures
    	]);
	}

	public function actionUpdate( $slug ) {

		// Find Model
		$model	= PlanService::findBySlug( $slug );

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( 'update' );

			$featuresList	= FeatureService::findIdNameList();
			$planFeatures	= PlanService::getFeaturesForUpdate( $model, $featuresList );

			if( $model->load( Yii::$app->request->post(), 'ObjectData' ) && PlanFeature::loadMultiple( $planFeatures, Yii::$app->request->post(), 'PlanFeature' ) && 
				$model->validate() && PlanFeature::validateMultiple( $planFeatures ) ) {

				PlanService::update( $model );

				PlanService::updateFeatures( $model, $planFeatures );

				return $this->redirect( [ 'all' ] );
			}

	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'planFeatures' => $planFeatures,
	    		'featuresList' => $featuresList
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $slug ) {

		// Find Model
		$model	= PlanService::findBySlug( $slug );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$featuresList	= FeatureService::findIdNameList();
			$planFeatures	= PlanService::getFeaturesForUpdate( $model, $featuresList );

			if( $model->load( Yii::$app->request->post(), 'ObjectData' ) ) {

				PlanService::delete( $model );

				return $this->redirect( [ 'all' ] );
			}

	    	return $this->render( 'delete', [
	    		'model' => $model,
	    		'planFeatures' => $planFeatures,
	    		'featuresList' => $featuresList
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );	
	}
}

?>