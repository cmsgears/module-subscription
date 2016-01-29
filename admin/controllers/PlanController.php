<?php
namespace cmsgears\subscription\admin\controllers;

// Yii Imports
use \Yii; 
use yii\filters\VerbFilter;  
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\subscription\common\config\SubscriptionGlobal;
use cmsgears\listing\common\config\ListingGlobal;

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
	                'all'  => [ 'permission' => SubscriptionGlobal::PERM_SUBSCRIPTION ], 
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'all'  => [ 'get' ],
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
		$features		= FeatureService::findIdNameList();

		$model->setScenario( 'create' ); 
		
		// Plan Features
		$planFeatures	= [];
		
		for ( $i = 0, $j = count( $features ); $i < $j; $i++ ) {

			$planFeatures[] = new PlanFeature();
		}
		
		if( $model->load( Yii::$app->request->post(), 'ObjectData' ) && PlanFeature::loadMultiple( $planFeatures, Yii::$app->request->post(), 'PlanFeature' ) && 
			$model->validate() && PlanFeature::validateMultiple( $planFeatures ) ) {
				
			$plan	= PlanService::create( $model );
		 
			if( $plan ) {

				PlanService::updateFeatures( $plan, $planFeatures );

				$this->redirect( [ 'all' ] );
			}
		} 

    	return $this->render( 'create', [
    		'model' => $model,
    		'features' => $features,
    		'planFeatures' => $planFeatures 
    	]);
	}
	
	public function actionUpdate( $slug ) {

		// Find Model
		$model	= PlanService::findBySlug( $slug );

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( 'update' );

			$features		= FeatureService::findIdNameList();
			$planFeatures	= PlanService::getFeaturesForUpdate( $model, $features );

			if( $model->load( Yii::$app->request->post(), 'ObjectData' ) && PlanFeature::loadMultiple( $planFeatures, Yii::$app->request->post(), 'PlanFeature' ) && 
			$model->validate() && PlanFeature::validateMultiple( $planFeatures ) ) {

				if( PlanService::update( $model ) ) {

					PlanService::updateFeatures( $model, $planFeatures );

					$this->redirect( [ 'all' ] );
				}
			}
	
	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'planFeatures' => $planFeatures,
	    		'features' => $features
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
			
			$features		= FeatureService::findIdNameList();
			$planFeatures	= PlanService::getFeaturesForUpdate( $model, $features );

			if( $model->load( Yii::$app->request->post(), 'ObjectData' ) ) {

				if( PlanService::delete( $model ) ) {

					$this->redirect( [ 'all' ] );
				}
			}

	    	return $this->render( 'delete', [
	    		'model' => $model,
	    		'planFeatures' => $planFeatures,
	    		'features' => $features
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );	
	}
}

?>