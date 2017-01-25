<?php
namespace cmsgears\subscription\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\core\common\models\entities\ObjectData;
use cmsgears\subscription\common\models\forms\PlanFeature;

class PlanController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $featureService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->crudPermission 	= SubscriptionGlobal::PERM_SUBSCRIPTION;

		$this->modelService		= Yii::$app->factory->get( 'subPlanService' );
		$this->featureService	= Yii::$app->factory->get( 'subFeatureService' );

		$this->sidebar 			= [ 'parent' => 'sidebar-subscription', 'child' => 'plan' ];

		$this->returnUrl		= Url::previous( 'plans' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/subscription/plan/all' ], true );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// PlanController ------------------------

	public function actionAll() {

		Url::remember( [ 'plan/all' ], 'plans' );

	    return parent::actionAll();
	}

	public function actionCreate() {

		$modelClass		= $this->modelService->getModelClass();
		$model			= new $modelClass;
		$model->siteId	= Yii::$app->core->siteId;
		$model->type	= SubscriptionGlobal::TYPE_PLAN;
		$model->data	= "{ \"features\": {} }";
		$features		= $this->featureService->getIdNameList();

		// Plan Features
		$planFeatures	= [];

		for ( $i = 0, $j = count( $features ); $i < $j; $i++ ) {

			$planFeatures[] = new PlanFeature();
		}

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && PlanFeature::loadMultiple( $planFeatures, Yii::$app->request->post(), 'PlanFeature' ) &&
			$model->validate() && PlanFeature::validateMultiple( $planFeatures ) ) {

			$plan = $this->modelService->create( $model );

			$this->modelService->updateFeatures( $plan, $planFeatures );

			return $this->redirect( $this->returnUrl );
		}

    	return $this->render( 'create', [
    		'model' => $model,
    		'featuresList' => $features,
    		'planFeatures' => $planFeatures
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model		= $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$features		= $this->featureService->getIdNameList();
			$planFeatures	= $this->modelService->getFeaturesForUpdate( $model, $features );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && PlanFeature::loadMultiple( $planFeatures, Yii::$app->request->post(), 'PlanFeature' ) &&
				$model->validate() && PlanFeature::validateMultiple( $planFeatures ) ) {

				$plan = $this->modelService->update( $model );

				$this->modelService->updateFeatures( $plan, $planFeatures );

				return $this->redirect( $this->returnUrl );
			}

	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'featuresList' => $features,
	    		'planFeatures' => $planFeatures
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model		= $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$features		= $this->featureService->getIdNameList();
			$planFeatures	= $this->modelService->getFeaturesForUpdate( $model, $features );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				$this->modelService->delete( $model );

				return $this->redirect( $this->returnUrl );
			}

	    	return $this->render( 'delete', [
	    		'model' => $model,
	    		'featuresList' => $features,
	    		'planFeatures' => $planFeatures
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
