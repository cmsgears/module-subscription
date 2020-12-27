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

use cmsgears\subscription\common\models\resources\Subscription;

use cmsgears\subscription\common\services\interfaces\resources\ISubscriptionService;

use cmsgears\core\common\utilities\DateUtil;

/**
 * SubscriptionService provide service methods of Subscription.
 *
 * @since 1.0.0
 */
class SubscriptionService extends \cmsgears\core\common\services\base\ModelResourceService implements ISubscriptionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\subscription\common\models\resources\Subscription';

	public static $parentType = SubscriptionGlobal::TYPE_SUBSCRIPTION;

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

	// SubscriptionService -------------------

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
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name',
				],
				'type' => [
					'asc' => [ "$modelTable.type" => SORT_ASC ],
					'desc' => [ "$modelTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type',
				],
				'status' => [
					'asc' => [ "$modelTable.status" => SORT_ASC ],
					'desc' => [ "$modelTable.status" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
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
			'title' => "$modelTable.title",
			'content' => "$modelTable.content"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'title' => "$modelTable.title",
			'startDate' => "$modelTable.startDate",
			'endDate' => "$modelTable.endDate",
			'content' => "$modelTable.content"
		];

		// Result -----------

		return parent::findPage( $config );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'title', 'content',
			'initial', 'price', 'discount', 'total', 'currency', 'delivery',
			'trial', 'billing', 'advance', 'startDate', 'endDate'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [ 'status' ] );
		}

		$date = DateUtil::getDate();

		if( isset( $model->endDate ) && DateUtil::greaterThan( $model->endDate, $date ) ) {

			$model->status = Subscription::STATUS_EXPIRED;
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function updateStatus( $model, $status ) {

		$model->status = $status;

		$model->update();

		return $model;
	}

	public function activate( $model, $config = [] ) {

		if( !$model->isActive() ) {

			$this->updateStatus( $model, Subscription::STATUS_ACTIVE );
		}
	}

	public function suspend( $model, $config = [] ) {

		if( !$model->isActive() ) {

			$this->updateStatus( $model, Subscription::STATUS_SUSPENDED );
		}
	}

	public function cancel( $model, $config = [] ) {

		if( !$model->isActive() ) {

			$this->updateStatus( $model, Subscription::STATUS_CANCELLED );
		}
	}

	public function expire( $model, $config = [] ) {

		$date = DateUtil::getDate();

		if( !$model->status == Subscription::STATUS_EXPIRED &&
			( empty( $model->endDate ) || ( isset( $model->endDate ) && DateUtil::greaterThan( $model->endDate, $date ) ) ) ) {

			$this->updateStatus( $model, Subscription::STATUS_EXPIRED );
		}
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete Mappings
		PlanFeature::deleteByFeatureId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'activate': {

						$this->activate( $model );

						break;
					}
					case 'suspend': {

						$this->suspend( $model );

						break;
					}
					case 'cancel': {

						$this->cancel( $model );

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

	// SubscriptionService -------------------

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
