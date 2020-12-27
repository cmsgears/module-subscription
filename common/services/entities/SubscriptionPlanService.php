<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\subscription\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\subscription\common\models\mappers\PlanFeature;

use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\subscription\common\services\interfaces\entities\ISubscriptionPlanService;
use cmsgears\subscription\common\services\interfaces\resources\ISubscriptionPlanMetaService;

use cmsgears\core\common\services\traits\base\SimilarTrait;
use cmsgears\core\common\services\traits\resources\SocialLinkTrait;
use cmsgears\core\common\services\traits\mappers\CategoryTrait;

/**
 * SubscriptionPlanService provide service methods of Job model.
 *
 * @since 1.0.0
 */
class SubscriptionPlanService extends \cmsgears\cms\common\services\base\ContentService implements ISubscriptionPlanService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\subscription\common\models\entities\SubscriptionPlan';

	public static $parentType = SubscriptionGlobal::TYPE_PLAN;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $fileService;
	protected $metaService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use CategoryTrait;
	use SimilarTrait;
	use SocialLinkTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, ISubscriptionPlanMetaService $metaService, $config = [] ) {

		$this->fileService	= $fileService;
		$this->metaService 	= $metaService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SubscriptionPlanService ---------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$contentTable	= Yii::$app->factory->get( 'modelContentService' )->getModelTable();
		$templateTable	= Yii::$app->factory->get( 'templateService' )->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'template' => [
					'asc' => [ "$templateTable.name" => SORT_ASC ],
					'desc' => [ "$templateTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Template',
				],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'slug' => [
					'asc' => [ "$modelTable.slug" => SORT_ASC ],
					'desc' => [ "$modelTable.slug" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Slug'
				],
	            'type' => [
	                'asc' => [ "$modelTable.type" => SORT_ASC ],
	                'desc' => [ "$modelTable.type" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Type'
	            ],
	            'icon' => [
	                'asc' => [ "$modelTable.icon" => SORT_ASC ],
	                'desc' => [ "$modelTable.icon" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Icon'
	            ],
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'status' => [
					'asc' => [ "$modelTable.status" => SORT_ASC ],
					'desc' => [ "$modelTable.status" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
				],
				'visibility' => [
					'asc' => [ "$modelTable.visibility" => SORT_ASC ],
					'desc' => [ "$modelTable.visibility" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Visibility'
				],
				'order' => [
					'asc' => [ "$modelTable.order" => SORT_ASC ],
					'desc' => [ "$modelTable.order" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Order'
				],
				'pinned' => [
					'asc' => [ "$modelTable.pinned" => SORT_ASC ],
					'desc' => [ "$modelTable.pinned" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Pinned'
				],
				'featured' => [
					'asc' => [ "$modelTable.featured" => SORT_ASC ],
					'desc' => [ "$modelTable.featured" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Featured'
				],
				'popular' => [
					'asc' => [ "$modelTable.popular" => SORT_ASC ],
					'desc' => [ "$modelTable.popular" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Popular'
				],
				'initial' => [
					'asc' => [ "$modelTable.initial" => SORT_ASC ],
					'desc' => [ "$modelTable.initial" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Setup Fee'
				],
				'price' => [
					'asc' => [ "$modelTable.price" => SORT_ASC ],
					'desc' => [ "$modelTable.price" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Price'
				],
				'discount' => [
					'asc' => [ "$modelTable.discount" => SORT_ASC ],
					'desc' => [ "$modelTable.discount" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Discount'
				],
				'total' => [
					'asc' => [ "$modelTable.total" => SORT_ASC ],
					'desc' => [ "$modelTable.total" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Total'
				],
				'currency' => [
					'asc' => [ "$modelTable.currency" => SORT_ASC ],
					'desc' => [ "$modelTable.currency" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Currency'
				],
				'delivery' => [
					'asc' => [ "$modelTable.delivery" => SORT_ASC ],
					'desc' => [ "$modelTable.delivery" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Delivery Cycle'
				],
				'billing' => [
					'asc' => [ "$modelTable.billing" => SORT_ASC ],
					'desc' => [ "$modelTable.billing" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Billing Cycle'
				],
				'trial' => [
					'asc' => [ "$modelTable.trial" => SORT_ASC ],
					'desc' => [ "$modelTable.trial" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Trial Period'
				],
				'advance' => [
					'asc' => [ "$modelTable.advance" => SORT_ASC ],
					'desc' => [ "$modelTable.advance" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Advance Payment'
				],
				'sdate' => [
					'asc' => [ "$modelTable.startDate" => SORT_ASC ],
					'desc' => [ "$modelTable.startDate" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Start Date'
				],
				'edate' => [
					'asc' => [ "$modelTable.endDate" => SORT_ASC ],
					'desc' => [ "$modelTable.endDate" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'End Date'
				],
				'cdate' => [
					'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
					'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Created At'
				],
				'udate' => [
					'asc' => [ "$modelTable.modifiedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.modifiedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Updated At'
				],
				'pdate' => [
					'asc' => [ "$contentTable.publishedAt" => SORT_ASC ],
					'desc' => [ "$contentTable.publishedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Published At'
				]
			],
			'defaultOrder' => $defaultSort
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		// Filters ----------

		// Searching --------

		// Reporting --------

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPublicPage( $config = [] ) {

		$config[ 'route' ] = isset( $config[ 'route' ] ) ? $config[ 'route' ] : 'subscription/plan';

		return parent::getPublicPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getFeatures( $model ) {

		$featuresClass	= Yii::$app->factory->get( 'subscriptionFeaturesService' )->getModelClass();
		$featuresTable	= Yii::$app->factory->get( 'subscriptionFeaturesService' )->getModelTable();
		$matrixTable	= PlanFeature::tableName();

		return $featuresClass::find()->leftJoin( [ $matrixTable, "$matrixTable.featureId=$featuresTable.id" ] )
			->where( "$matrixTable.planId=:pid", [ ':pid' => $model->id ] )
			->all();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getEmail( $model ) {

		return $model->creator->email;
	}

	// Create -------------

	public function create( $model, $config = [] ) {

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;

		$modelClass = static::$modelClass;

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar ] );

		// Default Private
		$model->visibility = $model->visibility ?? $modelClass::VISIBILITY_PRIVATE;

		// Default New
		$model->status = $model->status ?? $modelClass::STATUS_NEW;

		// Create Model
		return parent::create( $model, $config );
	}

	public function add( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$modelClass = static::$modelClass;

		$content 	= isset( $config[ 'content' ] ) ? $config[ 'content' ] : new ModelContent();
		$publish	= isset( $config[ 'publish' ] ) ? $config[ 'publish' ] : false;
		$banner 	= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$mbanner 	= isset( $config[ 'mbanner' ] ) ? $config[ 'mbanner' ] : null;
		$video 		= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$mvideo 	= isset( $config[ 'mvideo' ] ) ? $config[ 'mvideo' ] : null;
		$gallery	= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;

		$galleryService			= Yii::$app->factory->get( 'galleryService' );
		$modelContentService	= Yii::$app->factory->get( 'modelContentService' );

		$galleryClass = $galleryService->getModelClass();

		$transaction = Yii::$app->db->beginTransaction();

		try {

			// Copy Template
			$config[ 'template' ] = $content->template;

			$this->copyTemplate( $model, $config );

			// Create Model
			$model = $this->create( $model, $config );

			// Create Gallery
			if( isset( $gallery ) ) {

				$gallery->siteId	= $model->siteId;
				$gallery->type		= static::$parentType;
				$gallery->status	= $galleryClass::STATUS_ACTIVE;
				$gallery->name		= empty( $gallery->name ) ? $model->name : $gallery->name;

				$gallery = $galleryService->create( $gallery );
			}
			else {

				$gallery = $galleryService->createByParams([
					'siteId' => $model->siteId,
					'type' => static::$parentType, 'status' => $galleryClass::STATUS_ACTIVE,
					'name' => $model->name, 'title' => $model->title
				]);
			}

			// Create and attach model content
			$modelContentService->create( $content, [
				'parent' => $model, 'parentType' => static::$parentType,
				'publish' => $publish,
				'banner' => $banner, 'mbanner' => $mbanner,
				'video' => $video, 'mvideo' => $mvideo,
				'gallery' => $gallery
			]);

			$transaction->commit();
		}
		catch( Exception $e ) {

			$transaction->rollBack();

			return false;
		}

		return $model;
	}

	public function register( $model, $config = [] ) {

		$notify	= isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;
		$mail	= isset( $config[ 'mail' ] ) ? $config[ 'mail' ] : true;
		$user	= isset( $config[ 'user' ] ) ? $config[ 'user' ] : Yii::$app->core->getUser();

		$modelClass = static::$modelClass;
		$parentType	= static::$parentType;

		$content 	= isset( $config[ 'content' ] ) ? $config[ 'content' ] : new ModelContent();
		$publish	= isset( $config[ 'publish' ] ) ? $config[ 'publish' ] : false;
		$banner 	= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$mbanner 	= isset( $config[ 'mbanner' ] ) ? $config[ 'mbanner' ] : null;
		$video 		= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$mvideo 	= isset( $config[ 'mvideo' ] ) ? $config[ 'mvideo' ] : null;
		$gallery	= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;
		$adminLink	= isset( $config[ 'adminLink' ] ) ? $config[ 'adminLink' ] : 'subscription/plan/review';

		$galleryService			= Yii::$app->factory->get( 'galleryService' );
		$modelContentService	= Yii::$app->factory->get( 'modelContentService' );

		$galleryClass = $galleryService->getModelClass();

		$registered	= false;

		$transaction = Yii::$app->db->beginTransaction();

		try {

			// Copy Template
			$config[ 'template' ] = $content->template;

			$this->copyTemplate( $model, $config );

			// Create Location
			if( isset( $location ) ) {

				$location = $locationService->create( $location );

				$model->locationId = $location->id;
			}

			// Create Model
			$model = $this->create( $model, $config );

			// Create Gallery
			if( isset( $gallery ) ) {

				$gallery->siteId	= $model->siteId;
				$gallery->type		= $parentType;
				$gallery->status	= $galleryClass::STATUS_ACTIVE;
				$gallery->name		= empty( $gallery->name ) ? $model->name : $gallery->name;

				$gallery = $galleryService->create( $gallery );
			}
			else {

				$gallery = $galleryService->createByParams([
					'siteId' => $model->siteId,
					'type' => $parentType, 'status' => $galleryClass::STATUS_ACTIVE,
					'name' => $model->name, 'title' => $model->title
				]);
			}

			// Create and attach model content
			$modelContentService->create( $content, [
				'parent' => $model, 'parentType' => $parentType,
				'publish' => $publish,
				'banner' => $banner, 'mbanner' => $mbanner,
				'video' => $video, 'mvideo' => $mvideo,
				'gallery' => $gallery
			]);

			$transaction->commit();

			$registered	= true;
		}
		catch( Exception $e ) {

			$transaction->rollBack();

			return false;
		}

		if( $registered ) {

			// Notify Site Admin
			if( $notify ) {

				$this->notifyAdmin( $model, [
					'template' => SubscriptionGlobal::TPL_NOTIFY_PLAN_NEW,
					'adminLink' => "{$adminLink}?id={$model->id}"
				]);
			}

			// Email Subscription Admin
			if( $mail ) {

				Yii::$app->subscriptionMailer->sendRegisterPlanMail( $model );
			}
		}

		return $model;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$content 	= isset( $config[ 'content' ] ) ? $config[ 'content' ] : null;
		$publish	= isset( $config[ 'publish' ] ) ? $config[ 'publish' ] : false;
		$avatar 	= isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner 	= isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$mbanner 	= isset( $config[ 'mbanner' ] ) ? $config[ 'mbanner' ] : null;
		$video 		= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;
		$mvideo 	= isset( $config[ 'mvideo' ] ) ? $config[ 'mvideo' ] : null;
		$gallery	= isset( $config[ 'gallery' ] ) ? $config[ 'gallery' ] : null;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'avatarId', 'name', 'slug', 'type', 'icon', 'texture',
			'title', 'description', 'visibility', 'content',
			'initial', 'price', 'discount', 'total', 'currency', 'delivery',
			'trial', 'billing', 'advance', 'startDate', 'endDate'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'status', 'order', 'pinned', 'featured', 'popular'
			]);
		}

		// Copy Template
		if( isset( $content ) ) {

			$config[ 'template' ] = $content->template;

			if( $this->copyTemplate( $model, $config ) ) {

				$attributes[] = 'data';
			}
		}

		// Services
		$galleryService			= Yii::$app->factory->get( 'galleryService' );
		$modelContentService	= Yii::$app->factory->get( 'modelContentService' );
		$modelCategoryService	= Yii::$app->factory->get( 'modelCategoryService' );
		$modelTagService		= Yii::$app->factory->get( 'modelTagService' );

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar ] );

		// Create/Update gallery
		if( isset( $gallery ) ) {

			$gallery = $galleryService->createOrUpdate( $gallery );
		}

		// Update model content
		if( isset( $content ) ) {

			$modelContentService->update( $content, [
				'publish' => $publish,
				'banner' => $banner, 'mbanner' => $mbanner,
				'video' => $video, 'mvideo' => $mvideo,
				'gallery' => $gallery
			]);
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function bindFeatures( $binder ) {

		$planId	= $binder->binderId;
		$binded	= $binder->binded;

		// Clear all existing mappings
		PlanFeature::deleteByPlanId( $planId );

		// Create updated mappings
		if( count( $binded ) > 0 ) {

			foreach( $binded as $id ) {

				$toSave	= new PlanFeature();

				$toSave->planId = $planId;

				$toSave->featureId = $id;

				$toSave->save();
			}
		}

		return true;
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		$config[ 'hard' ] = $config[ 'hard' ] ?? !Yii::$app->core->isSoftDelete();

		if( $config[ 'hard' ] ) {

			$transaction = Yii::$app->db->beginTransaction();

			try {

				// Delete metas
				$this->metaService->deleteByModelId( $model->id );

				// Delete files
				$this->fileService->deleteMultiple( ArrayHelper::merge( $model->files, [ $model->avatar ] ) );

				// Delete Model Content
				Yii::$app->factory->get( 'modelContentService' )->delete( $model->modelContent );

				// Delete Followers
				Yii::$app->factory->get( 'subscriptionPlanFollowerService' )->deleteByModelId( $model->id );

				$transaction->commit();

				// Delete model
				return parent::delete( $model, $config );
			}
			catch( Exception $e ) {

				$transaction->rollBack();

				throw new Exception( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
			}
		}

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// SubscriptionPlanService ---------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
