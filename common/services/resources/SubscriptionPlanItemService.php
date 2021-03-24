<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\subscription\common\services\resources;

// Yii Imports
use Yii;
use yii\data\Sort;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\subscription\common\models\resources\SubscriptionPlanItem;

use cmsgears\subscription\common\services\interfaces\resources\ISubscriptionPlanItemService;

use cmsgears\core\common\utilities\DateUtil;

/**
 * SubscriptionPlanItemService provide service methods of Subscription Feature.
 *
 * @since 1.0.0
 */
class SubscriptionPlanItemService extends \cmsgears\core\common\services\base\ResourceService implements ISubscriptionPlanItemService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\subscription\common\models\resources\SubscriptionPlanItem';

	public static $parentType = SubscriptionGlobal::TYPE_PLAN_ITEM;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SubscriptionPlanItemService -----------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name',
				],
				'status' => [
					'asc' => [ "$modelTable.status" => SORT_ASC ],
					'desc' => [ "$modelTable.status" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
				],
				'order' => [
					'asc' => [ "$modelTable.order" => SORT_ASC ],
					'desc' => [ "$modelTable.order" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Order'
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
				'startDate' => [
					'asc' => [ "$modelTable.startDate" => SORT_ASC ],
					'desc' => [ "$modelTable.startDate" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Start Date'
				],
				'endDate' => [
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
				]
			],
			'defaultOrder' => $defaultSort
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'hasOne' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Params
		$status	= Yii::$app->request->getQueryParam( 'status' );

		// Filter - Status
		if( isset( $status ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$modelTable.name",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'name' => "$modelTable.name",
			'desc' => "$modelTable.description",
			'status' => "$modelTable.status",
			'price' => "$modelTable.price",
			'discount' => "$modelTable.discount",
			'total' => "$modelTable.total",
			'startDate' => "$modelTable.startDate",
			'endDate' => "$modelTable.endDate",
			'content' => "$modelTable.content"
		];

		// Result -----------

		return parent::findPage( $config );
	}

	public function getPageByPlanId( $planId, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.planId" ] = $planId;

		return $this->getPage( $config );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		// Update Item Total
		$model->refreshTotal();

		$model = parent::create( $model, $config );

		Yii::$app->factory->get( 'subscriptionPlanService' )->refreshTotal( $model->plan );

		return $model;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'name', 'description', 'startDate', 'endDate', 'content',
			'price', 'discount', 'total'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [ 'status', 'order' ] );
		}

		$date = DateUtil::getDate();

		if( !empty( $model->endDate ) && DateUtil::greaterThan( $model->endDate, $date ) ) {

			$model->status = SubscriptionPlanItem::STATUS_EXPIRED;

			$attributes	= ArrayHelper::merge( $attributes, [ 'status' ] );
		}

		// Update Item Total
		$model->refreshTotal();

		$model = parent::update( $model, [
			'attributes' => $attributes
		]);

		// Update Plan Total
		Yii::$app->factory->get( 'subscriptionPlanService' )->refreshTotal( $model->plan );

		return $model;
	}

	public function updateStatus( $model, $status ) {

		$model->status = $status;

		$model->update();

		Yii::$app->factory->get( 'subscriptionPlanService' )->refreshTotal( $model->plan );

		return $model;
	}

	public function activate( $model, $config = [] ) {

		if( !$model->isActive() ) {

			$this->updateStatus( $model, SubscriptionPlanItem::STATUS_ACTIVE );
		}
	}

	public function expire( $model, $config = [] ) {

		$date = DateUtil::getDate();

		if( $model->status != SubscriptionPlanItem::STATUS_EXPIRED &&
			( empty( $model->endDate ) || DateUtil::greaterThan( $model->endDate, $date ) ) ) {

			$this->updateStatus( $model, SubscriptionPlanItem::STATUS_EXPIRED );
		}
	}

	// Delete -------------

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'activate': {

						$this->activate( $model );

						break;
					}
					case 'expire': {

						$this->expire( $model );

						break;
					}
				}

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// SubscriptionPlanItemService -----------

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
