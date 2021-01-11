<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\subscription\common\models\entities;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cart\common\config\CartGlobal;
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\core\common\models\interfaces\base\IApproval;
use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IFeatured;
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\base\INameType;
use cmsgears\core\common\models\interfaces\base\IOwner;
use cmsgears\core\common\models\interfaces\base\ISlugType;
use cmsgears\core\common\models\interfaces\base\ITab;
use cmsgears\core\common\models\interfaces\base\IVisibility;
use cmsgears\core\common\models\interfaces\resources\IComment;
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\resources\IMeta;
use cmsgears\core\common\models\interfaces\resources\IVisual;
use cmsgears\core\common\models\interfaces\mappers\ICategory;
use cmsgears\core\common\models\interfaces\mappers\IFile;
use cmsgears\core\common\models\interfaces\mappers\IFollower;
use cmsgears\core\common\models\interfaces\mappers\IOption;
use cmsgears\core\common\models\interfaces\mappers\ITag;

use cmsgears\cms\common\models\interfaces\resources\IPageContent;
use cmsgears\cms\common\models\interfaces\mappers\IBlock;
use cmsgears\cms\common\models\interfaces\mappers\IElement;
use cmsgears\cms\common\models\interfaces\mappers\IWidget;

use cmsgears\subscription\common\models\base\SubscriptionTables;
use cmsgears\subscription\common\models\resources\SubscriptionPlanItem;
use cmsgears\subscription\common\models\resources\SubscriptionPlanMeta;
use cmsgears\subscription\common\models\mappers\SubscriptionPlanFollower;

use cmsgears\core\common\models\traits\base\ApprovalTrait;
use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\FeaturedTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\NameTypeTrait;
use cmsgears\core\common\models\traits\base\OwnerTrait;
use cmsgears\core\common\models\traits\base\SlugTypeTrait;
use cmsgears\core\common\models\traits\base\TabTrait;
use cmsgears\core\common\models\traits\base\VisibilityTrait;
use cmsgears\core\common\models\traits\resources\CommentTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\MetaTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;
use cmsgears\core\common\models\traits\mappers\CategoryTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;
use cmsgears\core\common\models\traits\mappers\FollowerTrait;
use cmsgears\core\common\models\traits\mappers\OptionTrait;
use cmsgears\core\common\models\traits\mappers\TagTrait;

use cmsgears\cms\common\models\traits\resources\PageContentTrait;
use cmsgears\cms\common\models\traits\mappers\BlockTrait;
use cmsgears\cms\common\models\traits\mappers\ElementTrait;
use cmsgears\cms\common\models\traits\mappers\WidgetTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * SubscriptionPlan represents the subscription plan available for the subscriber.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $avatarId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $texture
 * @property string $title
 * @property string $description
 * @property integer $status
 * @property integer $visibility
 * @property integer $order
 * @property boolean $pinned
 * @property boolean $featured
 * @property boolean $popular
 * @property boolean $reviews
 * @property float $initial
 * @property float $price
 * @property float $discount
 * @property float $total
 * @property string $currency
 * @property integer $delivery
 * @property integer $trial
 * @property integer $billing
 * @property boolean $advance
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
class SubscriptionPlan extends \cmsgears\core\common\models\base\Entity implements IApproval, IAuthor,
	IBlock, ICategory, IComment, IContent, IData, IElement, IFeatured, IFile, IFollower, IGridCache,
	IMeta, IMultiSite, INameType, IOption, IOwner, IPageContent, ISlugType, ITab, ITag, IVisibility,
	IVisual, IWidget {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Pre-Defined Status
	const STATUS_BASIC		=  20;
	const STATUS_ITEMS		=  40;
	const STATUS_MEDIA		=  80;
	const STATUS_ATTRIBUTES	= 160;
	const STATUS_SETTINGS	= 480;
	const STATUS_REVIEW		= 499;

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = SubscriptionGlobal::TYPE_PLAN;

	protected $followerClass;

	protected $metaClass;

	// Private ----------------

	// Traits ------------------------------------------------------

	use ApprovalTrait;
    use AuthorTrait;
	use BlockTrait;
	use CategoryTrait;
	use CommentTrait;
    use ContentTrait;
	use DataTrait;
	use ElementTrait;
	use FeaturedTrait;
	use FileTrait;
	use FollowerTrait;
	use GridCacheTrait;
	use MetaTrait;
	use MultiSiteTrait;
	use NameTypeTrait;
	use OptionTrait;
	use OwnerTrait;
	use PageContentTrait;
	use SlugTypeTrait;
	use TabTrait;
	use TagTrait;
	use VisibilityTrait;
	use VisualTrait;
	use WidgetTrait;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->followerClass = SubscriptionPlanFollower::class;

		$this->metaClass = SubscriptionPlanMeta::class;

		$this->tabs = [
			self::STATUS_BASIC => 'basic', self::STATUS_ITEMS => 'items',
			self::STATUS_MEDIA => 'media', self::STATUS_ATTRIBUTES => 'attributes',
			self::STATUS_SETTINGS => 'settings', self::STATUS_REVIEW => 'review'
		];

		$this->tabStatus = [
			'basic' => self::STATUS_BASIC, 'items' => self::STATUS_ITEMS,
			'media' => self::STATUS_MEDIA, 'attributes' => self::STATUS_ATTRIBUTES,
			'settings' => self::STATUS_SETTINGS, 'review' => self::STATUS_REVIEW
		];

        if( $this->isNewRecord ) {

			$this->initial = 0;
            $this->price = 0;
			$this->discount = 0;
			$this->total = 0;
        }
	}

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
            ],
			'sluggableBehavior' => [
				'class' => SluggableBehavior::class,
				'attribute' => 'name',
				'slugAttribute' => 'slug', // Unique for Site Id
				'immutable' => true,
				'ensureUnique' => true,
				'uniqueValidator' => [ 'targetAttribute' => [ 'siteId', 'slug' ] ]
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
			[ [ 'siteId', 'name' ], 'required' ],
			[ [ 'id', 'content' ], 'safe' ],
			// Unique
			[ 'slug', 'unique', 'targetAttribute' => [ 'siteId', 'slug' ], 'message' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SLUG ) ],
			// Text Limit
			[ [ 'type', 'currency' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'icon', 'texture' ], 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'slug', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'pinned', 'featured', 'popular', 'reviews', 'advance', 'gridCacheValid' ], 'boolean' ],
			[ [ 'initial', 'price', 'discount', 'total' ], 'number', 'min' => 0 ],
			[ [ 'status', 'visibility', 'order', 'delivery', 'billing', 'trial' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'siteId', 'avatarId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ],
			[ [ 'startDate', 'endDate' ], 'date', 'format' => Yii::$app->formatter->dateFormat ],
			[ 'endDate', 'compareDate', 'compareAttribute' => 'startDate', 'operator' => '>=', 'type' => 'datetime', 'message' => 'End date must be greater than or equal to Start date.' ]
        ];

       // Trim Text
        if( Yii::$app->core->trimFieldValue ) {

            $trim[] = [ [ 'name', 'title', 'description' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'avatarId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'slug' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'texture' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TEXTURE ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'visibility' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VISIBILITY ),
			'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'pinned' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PINNED ),
			'featured' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FEATURED ),
			'popular' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_POPULAR ),
			'reviews' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_REVIEWS ),
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

			// Default Status - New
			if( empty( $this->status ) || $this->status <= 0 ) {

				$this->status = self::STATUS_NEW;
			}

			// Default Order - zero
			if( empty( $this->order ) || $this->order <= 0 ) {

				$this->order = 0;
			}

			// Default Type - Default
			$this->type = $this->type ?? CoreGlobal::TYPE_DEFAULT;

			// Default Visibility - Private
			$this->visibility = $this->visibility ?? self::VISIBILITY_PRIVATE;

	        return true;
	    }

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// SubscriptionPlan ----------------------

	public function getItems() {

		return $this->hasMany( SubscriptionPlanItem::class, [ 'planId' => 'id' ] );
	}

	public function getActiveItems() {

		$planItemTable = SubscriptionPlanItem::tableName();

		return $this->hasMany( SubscriptionPlanItem::class, [ 'planId' => 'id' ] )
			->where( "$planItemTable.status=" . SubscriptionPlanItem::STATUS_ACTIVE );
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

	public function refreshTotal() {

		$items = $this->items;

		if( count( $items ) > 0 ) {

			$price		= 0;
			$discount	= 0;
			$total		= 0;

			foreach( $items as $item ) {

				if( $item->isActive() ) {

					$price += $item->price;

					$discount += $item->discount;

					$total += $item->total;
				}
			}

			$this->price	= $price;
			$this->discount	= $discount;
			$this->total	= $this->initial + $total;
		}
		else {

			$this->total = $this->initial + $this->price - $this->discount;
		}
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return SubscriptionTables::getTableName( SubscriptionTables::TABLE_SUBSCRIPTION_PLAN );
	}

	// CMG parent classes --------------------

	// Experience ----------------------------

	// Read - Query -----------

    /**
     * @inheritdoc
     */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'avatar', 'modelContent', 'modelContent.template', 'creator', 'modifier' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the plan with model content.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with model content.
	 */
	public static function queryWithContent( $config = [] ) {

		$config[ 'relations' ] = [ 'avatar', 'modelContent' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the plan with model content, banner and gallery.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with model content, banner and gallery.
	 */
	public static function queryWithFullContent( $config = [] ) {

		$config[ 'relations' ] = [ 'avatar', 'modelContent', 'modelContent.template', 'modelContent.banner', 'modelContent.gallery' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}

SubscriptionPlan::$statusMap[ SubscriptionPlan::STATUS_BASIC ]		= 'Basic';
SubscriptionPlan::$statusMap[ SubscriptionPlan::STATUS_ITEMS ]		= 'Items';
SubscriptionPlan::$statusMap[ SubscriptionPlan::STATUS_MEDIA ]		= 'Media';
SubscriptionPlan::$statusMap[ SubscriptionPlan::STATUS_ATTRIBUTES ]	= 'Attributes';
SubscriptionPlan::$statusMap[ SubscriptionPlan::STATUS_SETTINGS ]	= 'Settings';
SubscriptionPlan::$statusMap[ SubscriptionPlan::STATUS_REVIEW ]		= 'Review';
