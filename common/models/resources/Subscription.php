<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\subscription\common\models\resources;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;

use cmsgears\core\common\models\entities\User;
use cmsgears\subscription\common\models\base\SubscriptionTables;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

use cmsgears\core\common\utilities\DateUtil;

/**
 * Subscription
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $userId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property string $parentType
 * @property string $title
 * @property string $type
 * @property integer $status
 * @property float $initial
 * @property float $price
 * @property float $discount
 * @property float $total
 * @property string $currency
 * @property integer $delivery
 * @property integer $billing
 * @property boolean $advance
 * @property datetime $startDate
 * @property datetime $endDate
 * @property datetime $prevBillingDate
 * @property datetime $nextBillingDate
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class Subscription extends \cmsgears\core\common\models\base\ModelResource implements IAuthor,
	IContent, IData, IGridCache, IMultiSite {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const STATUS_NEW		=   0;
	const STATUS_TRIAL		= 100;
	const STATUS_ACTIVE		= 200;
	const STATUS_SUSPENDED	= 300;
	const STATUS_CANCELLED	= 400;
	const STATUS_EXPIRED	= 500;

	public static $statusMap = [
		self::STATUS_NEW => 'New',
		self::STATUS_TRIAL => 'Trial',
		self::STATUS_ACTIVE => 'Active',
		self::STATUS_SUSPENDED => 'Suspended',
		self::STATUS_CANCELLED => 'Cancelled',
		self::STATUS_EXPIRED => 'Expired'
	];

	public static $urlRevStatusMap = [
		'new' => self::STATUS_NEW,
		'trial' => self::STATUS_TRIAL,
		'active' => self::STATUS_ACTIVE,
		'suspended' => self::STATUS_SUSPENDED,
		'cancelled' => self::STATUS_CANCELLED,
		'expired' => self::STATUS_EXPIRED
	];

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = SubscriptionGlobal::TYPE_SUBSCRIPTION;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use ContentTrait;
	use DataTrait;
	use GridCacheTrait;
	use MultiSiteTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [
            'authorBehavior' => [
                'class' => AuthorBehavior::class
            ],
            'timestampBehavior' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'modifiedAt',
                'value' => new Expression('NOW()')
            ]
        ];
    }

	// yii\base\Model ---------

    /**
     * @inheritdoc
     */
    public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'id', 'content' ], 'safe' ],
			// Text Limit
			[ [ 'parentType', 'type', 'currency' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			// Other
			[ [ 'advance', 'gridCacheValid' ], 'boolean' ],
			[ [ 'initial', 'price', 'discount', 'total' ], 'number', 'min' => 0 ],
			[ [ 'status', 'delivery', 'billing', 'trial' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'siteId', 'userId', 'parentId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ],
			[ [ 'startDate', 'endDate', 'prevBillingDate', 'nextBillingDate' ], 'date', 'format' => Yii::$app->formatter->dateFormat ],
			[ 'endDate', 'compareDate', 'compareAttribute' => 'startDate', 'operator' => '>=', 'type' => 'datetime', 'message' => 'End date must be greater than or equal to Start date.' ],
			[ 'nextBillingDate', 'compareDate', 'compareAttribute' => 'prevBillingDate', 'operator' => '>=', 'type' => 'datetime', 'message' => 'Next billing date must be greater than or equal to previous billing date.' ]
        ];

       // Trim Text
        if( Yii::$app->core->trimFieldValue ) {

            $trim[] = [ [ 'title' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'initial' => 'Initial Fee',
			'price' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_PRICE ),
			'discount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DISCOUNT ),
			'total' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL ),
			'currency' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_CURRENCY ),
			'delivery' => 'Delivery Cycle',
			'billing' => 'Billing Cycle',
			'trial' => 'Trial Period',
			'advance' => 'Advance Payment',
			'startDate' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATE_START ),
			'endDate' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATE_END ),
			'prevBillingDate' => 'Previous Billing Date',
			'nextBillingDate' => 'Next Billing Date',
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// yii\db\BaseActiveRecord

    /**
     * @inheritdoc
     */
	public function beforeSave( $insert ) {

	    if( parent::beforeSave( $insert ) ) {

			// Default Site
			if( empty( $this->siteId ) || $this->siteId <= 0 ) {

				$this->siteId = Yii::$app->core->siteId;
			}

			// Default Type
			$this->type = $this->type ?? CoreGlobal::TYPE_DEFAULT;

	        return true;
	    }

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Subscription --------------------------

	public function getUser() {

		return $this->hasOne( User::class, [ 'id' => 'userId' ] );
	}

	public function isNew() {

		return $this->status == self::STATUS_NEW;
	}

	public function isTrial() {

		return $this->status == self::STATUS_TRIAL;
	}

	public function isActive() {

		return $this->status == self::STATUS_ACTIVE;
	}

	public function isSuspended() {

		return $this->status == self::STATUS_SUSPENDED;
	}

	public function isCancelled() {

		return $this->status == self::STATUS_CANCELLED;
	}

	public function isExpired() {

		$date = DateUtil::getDate();

		return $this->status == self::STATUS_EXPIRED || ( isset( $this->endDate ) && DateUtil::greaterThan( $this->endDate, $date ) );
	}

	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
	}

	public function getDeliveryStr() {

		return SubscriptionGlobal::$durationMap[ $this->delivery ];
	}

	public function getBillingStr() {

		return SubscriptionGlobal::$durationMap[ $this->billing ];
	}

	public function getAdvanceStr() {

		return Yii::$app->formatter->asBoolean( $this->advance );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return SubscriptionTables::getTableName( SubscriptionTables::TABLE_SUBSCRIPTION );
	}

	// CMG parent classes --------------------

	// Subscription --------------------------

	// Read - Query -----------

    /**
     * @inheritdoc
     */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'user' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
