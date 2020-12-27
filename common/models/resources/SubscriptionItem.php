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
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;

use cmsgears\core\common\models\entities\User;
use cmsgears\subscription\common\models\base\SubscriptionTables;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

use cmsgears\core\common\utilities\DateUtil;

/**
 * SubscriptionItem
 *
 * @property integer $id
 * @property integer $subscriptionId
 * @property integer $userId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property string $parentType
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property integer $order
 * @property float $price
 * @property float $discount
 * @property float $total
 * @property string $currency
 * @property date $startDate
 * @property date $endDate
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
class SubscriptionItem extends \cmsgears\core\common\models\base\ModelResource implements IAuthor,
	IContent, IData, IGridCache {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const STATUS_NEW		=   0;
	const STATUS_ACTIVE		= 100;
	const STATUS_EXPIRED	= 200;

	public static $statusMap = [
		self::STATUS_NEW => 'New',
		self::STATUS_ACTIVE => 'Active',
		self::STATUS_EXPIRED => 'Expired'
	];

	public static $urlRevStatusMap = [
		'new' => self::STATUS_NEW,
		'active' => self::STATUS_ACTIVE,
		'expired' => self::STATUS_EXPIRED
	];

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = SubscriptionGlobal::TYPE_SUBSCRIPTION_ITEM;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use ContentTrait;
	use DataTrait;
	use GridCacheTrait;

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
			[ [ 'subscriptionId', 'name' ], 'required' ],
			[ [ 'id', 'content' ], 'safe' ],
			// Text Limit
			[ [ 'parentType', 'currency' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'gridCacheValid' ], 'boolean' ],
			[ [ 'price', 'discount', 'total' ], 'number', 'min' => 0 ],
			[ [ 'status', 'order' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'subscriptionId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ],
			[ [ 'startDate', 'endDate' ], 'date', 'format' => Yii::$app->formatter->dateFormat ],
			[ 'endDate', 'compareDate', 'compareAttribute' => 'startDate', 'operator' => '>=', 'type' => 'datetime', 'message' => 'End date must be greater than or equal to Start date.' ]
        ];

       // Trim Text
        if( Yii::$app->core->trimFieldValue ) {

            $trim[] = [ [ 'name', 'description' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'subscriptionId' => Yii::$app->subscriptionMessage->getMessage( SubscriptionGlobal::FIELD_SUBSCRIPTION ),
			'userId' => Yii::$app->subscriptionMessage->getMessage( SubscriptionGlobal::FIELD_SUBSCRIBER ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'price' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_PRICE ),
			'discount' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_DISCOUNT ),
			'total' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_TOTAL ),
			'currency' => Yii::$app->cartMessage->getMessage( CartGlobal::FIELD_CURRENCY ),
			'startDate' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATE_START ),
			'endDate' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATE_END ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// SubscriptionItem ----------------------

	public function getSubscription() {

		return $this->hasOne( Subscription::class, [ 'id' => 'subscriptionId' ] );
	}

	public function getUser() {

		return $this->hasOne( User::class, [ 'id' => 'userId' ] );
	}

	public function isNew() {

		return $this->status == self::STATUS_NEW;
	}

	public function isActive() {

		return $this->status == self::STATUS_ACTIVE;
	}

	public function isExpired() {

		$date = DateUtil::getDate();

		return $this->status == self::STATUS_EXPIRED || ( isset( $this->endDate ) && DateUtil::greaterThan( $this->endDate, $date ) );
	}

	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return SubscriptionTables::getTableName( SubscriptionTables::TABLE_SUBSCRIPTION_ITEM );
	}

	// CMG parent classes --------------------

	// SubscriptionItem ----------------------

	// Read - Query -----------

    /**
     * @inheritdoc
     */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'subscription' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	public static function findBySubscriptionId( $subscriptionId ) {

		return self::find()->where( 'subscriptionId=:id', [ ':id' => $subscriptionId ] )->all();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
