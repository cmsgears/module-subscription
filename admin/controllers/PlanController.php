<?php
/**
 * This file is part of project APWEN. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.apwen.org/
 * @copyright Copyright (c) 20220 APWEN
 */

namespace cmsgears\subscription\admin\controllers;

// Yii Imports
use Yii;
use yii\helpers\Url;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\payment\common\config\PaymentProperties;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\utilities\CodeGenUtil;

/**
 * PlanController provides actions specific to Subscription Plan.
 *
 * @since 1.0.0
 */
class PlanController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $title;
	public $parentType;

	public $tagWidgetSlug;

	public $metaService;

	// Protected --------------

	protected $type;
	protected $templateType;

	protected $prettyReview;

	protected $templateService;
	protected $modelContentService;

	protected $currencyMap;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-subscription/admin/views/plan' );

		// Permission
		$this->crudPermission = SubscriptionGlobal::PERM_SUBSCRIPTION_ADMIN;

		// Config
		$this->title		= 'Subscription Plan';
		$this->type			= CoreGlobal::TYPE_DEFAULT;
		$this->parentType	= SubscriptionGlobal::TYPE_PLAN;
		$this->templateType	= SubscriptionGlobal::TYPE_PLAN;
		$this->prettyReview	= false;
		$this->baseUrl		= 'plan';
		$this->apixBase		= 'subscription/plan';

		$this->tagWidgetSlug = 'tag-subs-plans';

		$currencies = PaymentProperties::getInstance()->getCurrencies();

		$this->currencyMap = CodeGenUtil::generateMapFromCsv( $currencies );

		// Services
		$this->modelService		= Yii::$app->factory->get( 'subscriptionPlanService' );
		$this->metaService		= Yii::$app->factory->get( 'subscriptionPlanMetaService' );
		$this->templateService	= Yii::$app->factory->get( 'templateService' );

		$this->modelContentService = Yii::$app->factory->get( 'modelContentService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-subscription', 'child' => 'plan' ];

		// Return Url
		$this->returnUrl = Url::previous( 'subscription-plans' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/subscription/plan/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
			],
			'all' => [ [ 'label' => 'Subscription Plans' ] ],
			'create' => [ [ 'label' => 'Subscription Plans', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Subscription Plans', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Subscription Plans', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ],
			'review' => [ [ 'label' => 'Subscription Plans', 'url' => $this->returnUrl ], [ 'label' => 'Review' ] ],
			'gallery' => [ [ 'label' => 'Subscription Plans', 'url' => $this->returnUrl ], [ 'label' => 'Gallery' ] ],
			'data' => [ [ 'label' => 'Subscription Plans', 'url' => $this->returnUrl ], [ 'label' => 'Data' ] ],
			'config' => [ [ 'label' => 'Subscription Plans', 'url' => $this->returnUrl ], [ 'label' => 'Config' ] ],
			'settings' => [ [ 'label' => 'Subscription Plans', 'url' => $this->returnUrl ], [ 'label' => 'Settings' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		$behaviors = parent::behaviors();

		$behaviors[ 'rbac' ][ 'actions' ][ 'review' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'gallery' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'data' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'attributes' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'config' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'settings' ] = [ 'permission' => $this->crudPermission ];

		$behaviors[ 'verbs' ][ 'actions' ][ 'review' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'gallery' ] = [ 'get' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'data' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'attributes' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'config' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'settings' ] = [ 'get', 'post' ];

		return $behaviors;
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'gallery' => [ 'class' => 'cmsgears\cms\common\actions\gallery\Manage' ],
			'data' => [ 'class' => 'cmsgears\cms\common\actions\data\data\Form' ],
			'attributes' => [ 'class' => 'cmsgears\cms\common\actions\data\attribute\Form' ],
			'config' => [ 'class' => 'cmsgears\cms\common\actions\data\config\Form' ],
			'settings' => [ 'class' => 'cmsgears\cms\common\actions\data\setting\Form' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// PlanController ------------------------

	public function actionAll( $config = [] ) {

		Url::remember( Yii::$app->request->getUrl(), 'subscription-plans' );

		$modelClass = $this->modelService->getModelClass();

		$dataProvider = $this->modelService->getPageByType( $this->type );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'visibilityMap' => $modelClass::$visibilityMap,
			'statusMap' => $modelClass::$subStatusMap,
			'filterStatusMap' => $modelClass::$filterSubStatusMap
		]);
	}

	public function actionCreate( $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		$model = new $modelClass;

		$model->siteId	= Yii::$app->core->siteId;
		$model->type	= $this->type;

		$content = $this->modelContentService->getModelObject();

		$avatar		= File::loadFile( null, 'Avatar' );
		$banner		= File::loadFile( null, 'Banner' );
		$mbanner	= File::loadFile( null, 'MobileBanner' );
		$video		= File::loadFile( null, 'Video' );
		$mvideo		= File::loadFile( null, 'MobileVideo' );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $content->load( Yii::$app->request->post(), $content->getClassName() ) &&
			$model->validate() && $content->validate() ) {

			$this->model = $this->modelService->add( $model, [
				'admin' => true, 'content' => $content,
				'publish' => $model->isActive(), 'avatar' => $avatar,
				'banner' => $banner, 'mbanner' => $mbanner,
				'video' => $video, 'mvideo' => $mvideo
			]);

			return $this->redirect( 'all' );
		}

		$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

		return $this->render( 'create', [
			'model' => $model,
			'content' => $content,
			'avatar' => $avatar,
			'banner' => $banner,
			'mbanner' => $mbanner,
			'video' => $video,
			'mvideo' => $mvideo,
			'visibilityMap' => $modelClass::$visibilityMap,
			'statusMap' => $modelClass::$subStatusMap,
			'templatesMap' => $templatesMap,
			'currencyMap' => $this->currencyMap
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$content	= $model->modelContent;
			$template	= $content->template;

			$avatar		= File::loadFile( $model->avatar, 'Avatar' );
			$banner		= File::loadFile( $content->banner, 'Banner' );
			$mbanner	= File::loadFile( $content->mobileBanner, 'MobileBanner' );
			$video		= File::loadFile( $content->video, 'Video' );
			$mvideo		= File::loadFile( $content->mobileVideo, 'MobileVideo' );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $content->load( Yii::$app->request->post(), $content->getClassName() ) &&
				$model->validate() && $content->validate() ) {

				$this->model = $this->modelService->update( $model, [
					'admin' => true, 'content' => $content,
					'publish' => $model->isActive(), 'oldTemplate' => $template,
					'avatar' => $avatar, 'banner' => $banner, 'mbanner' => $mbanner,
					'video' => $video, 'mvideo' => $mvideo
				]);

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			$tagTemplate	= Yii::$app->factory->get( 'templateService' )->getGlobalBySlugType( CoreGlobal::TEMPLATE_TAG, $this->templateType );
			$tagTemplateId	= isset( $tagTemplate ) ? $tagTemplate->id : null;

			return $this->render( 'update', [
				'model' => $model,
				'content' => $content,
				'avatar' => $avatar,
				'banner' => $banner,
				'mbanner' => $mbanner,
				'video' => $video,
				'mvideo' => $mvideo,
				'visibilityMap' => $modelClass::$visibilityMap,
				'statusMap' => $modelClass::$subStatusMap,
				'templatesMap' => $templatesMap,
				'tagTemplateId' => $tagTemplateId,
				'currencyMap' => $this->currencyMap
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

			$content = $model->modelContent;

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

			$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			return $this->render( 'delete', [
				'model' => $model,
				'content' => $content,
				'avatar' => $model->avatar,
				'banner' => $content->banner,
				'mbanner' => $content->mobileBanner,
				'video' => $content->video,
				'mvideo' => $content->mobileVideo,
				'visibilityMap' => $modelClass::$visibilityMap,
				'statusMap' => $modelClass::$subStatusMap,
				'templatesMap' => $templatesMap,
				'currencyMap' => $this->currencyMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
