<?php
/**
 * This file is part of project APWEN. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.apwen.org/
 * @copyright Copyright (c) 20220 APWEN
 */

namespace cmsgears\subscription\admin\controllers\plan;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\core\common\behaviors\ActivityBehavior;

/**
 * ItemController provides actions specific to Subscription Plan.
 *
 * @since 1.0.0
 */
class ItemController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $title;

	public $parentType;

	// Protected --------------

	protected $planService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-subscription/admin/views/plan/item' );

		// Permission
		$this->crudPermission = SubscriptionGlobal::PERM_SUBSCRIPTION_ADMIN;

		// Config
		$this->title		= 'Subscription Plan Item';
		$this->parentType	= SubscriptionGlobal::TYPE_PLAN;
		$this->baseUrl		= 'plan/item';
		$this->apixBase		= 'subscription/plan/item';

		// Services
		$this->modelService	= Yii::$app->factory->get( 'subscriptionPlanItemService' );
		$this->planService	= Yii::$app->factory->get( 'subscriptionPlanService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-subscription', 'child' => 'plan' ];

		// Return Url
		$this->returnUrl = Url::previous( 'subscription-plan-items' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/subscription/plan/item/all' ], true );

		// All Url
		$allUrl = Url::previous( 'subscription-plans' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/subscription/plan/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Subscription Plans', 'url' =>  $allUrl ]
			],
			'all' => [ [ 'label' => 'Subscription Plan Items' ] ],
			'create' => [ [ 'label' => 'Subscription Plan Items', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Subscription Plan Items', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Subscription Plan Items', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					'index' => [ 'permission' => $this->crudPermission ],
					'all' => [ 'permission' => $this->crudPermission ],
					'create' => [ 'permission' => $this->crudPermission ],
					'update' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get', 'post' ],
					'all' => [ 'get' ],
					'create' => [ 'get', 'post' ],
					'update' => [ 'get', 'post' ],
					'delete' => [ 'get', 'post' ]
				]
			],
			'activity' => [
				'class' => ActivityBehavior::class,
				'admin' => true,
				'create' => [ 'create' ],
				'update' => [ 'update' ],
				'delete' => [ 'delete' ]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ItemController ------------------------

	public function actionAll( $pid, $config = [] ) {

		Url::remember( Yii::$app->request->getUrl(), 'subscription-plan-items' );

		$plan = $this->planService->getById( $pid );

		if( isset( $plan ) ) {

			$modelClass = $this->modelService->getModelClass();

			$dataProvider = $this->modelService->getPageByPlanId( $pid );

			return $this->render( 'all', [
				'dataProvider' => $dataProvider,
				'plan' => $plan,
				'statusMap' => $modelClass::$urlRevStatusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionCreate( $pid, $config = [] ) {

		$plan = $this->planService->getById( $pid );

		if( isset( $plan ) ) {

			$modelClass = $this->modelService->getModelClass();

			$model = new $modelClass;

			$model->planId = $plan->id;

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->add( $model, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'create', [
				'model' => $model,
				'statusMap' => $modelClass::$statusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionUpdate( $id, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'update', [
				'model' => $model,
				'statusMap' => $modelClass::$statusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		// Find Model
		$model = $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				try {

					$this->model = $model;

					$this->modelService->delete( $model, [ 'admin' => true ] );

					return $this->redirect( $this->returnUrl );
				}
				catch( Exception $e ) {

					throw new HttpException( 409, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
				}
			}

			return $this->render( 'delete', [
				'model' => $model,
				'statusMap' => $modelClass::$statusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
