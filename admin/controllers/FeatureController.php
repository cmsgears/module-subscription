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

use cmsgears\subscription\admin\services\FeatureService; 

class FeatureController extends \cmsgears\core\admin\controllers\base\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 	= [ 'parent' => 'sidebar-subscription', 'child' => 'features' ];
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
	
	// FeatureController -----------------

	public function actionAll() {

		$dataProvider = FeatureService::getPagination();

	    return $this->render('all', [
	         'dataProvider' => $dataProvider
	    ]);
	}
	
	public function actionCreate() {

		$model			= new ObjectData();
		$model->siteId	= Yii::$app->cmgCore->siteId;
		$model->type	= SubscriptionGlobal::TYPE_FEATURE; 

		$model->setScenario( 'create' );
		
		if( $model->load( Yii::$app->request->post(), 'ObjectData' ) && $model->validate() ) {

			if( FeatureService::create( $model ) ) {
				
				$this->redirect( [ 'all' ] );
			}
		}

    	return $this->render( 'create', [
    		'model' => $model
    	]);
	}
	
	public function actionUpdate( $slug ) {

		$model			= FeatureService::findBySlug( $slug );
		
		if( isset( $model ) ) {
				
			$model->setScenario( 'update' );
			
			if( $model->load( Yii::$app->request->post(), 'ObjectData' ) && $model->validate() ) {
	
				FeatureService::update( $model );
			}
	
	    	return $this->render( 'update', [
	    		'model' => $model
	    	]);
		}	
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
	
	public function actionDelete( $slug ) {

		// Find Model
		$model	= FeatureService::findBySlug( $slug );

		// Delete/Render if exist
		if( isset( $model ) ) { 

			if( $model->load( Yii::$app->request->post(), 'ObjectData' ) ) {

				if( FeatureService::delete( $model ) ) {

					$this->redirect( [ 'all' ] );
				}
			} 

	    	return $this->render( 'delete', [
	    		'model' => $model
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );	
	}
	 
}

?>