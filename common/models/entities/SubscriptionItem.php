<?php
namespace cmsgears\subscription\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\core\common\models\entities\ObjectData;
use cmsgears\core\common\models\entities\User;

use cmsgears\subscription\common\models\base\SubscriptionTables;

/**
 * SubscriptionItem Entity
 *
 * @property integer $id
 * @property integer $parentId
 * @property string $parentType
 * @property integer $subscriptionId
 * @property integer $userId
 * @property integer $active
 * @property date $startDate
 * @property date $endDate
 */
class SubscriptionItem extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const STATUS_ACTIVE		= 1;
	const STATUS_EXPIRED	= 0;

	public static $statusMap = [
		self::STATUS_ACTIVE => 'Active',
		self::STATUS_EXPIRED => 'Expired'
	];


	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

    /**
     * @inheritdoc
     */

	// yii\base\Model ---------

    /**
     * @inheritdoc
     */
	public function rules() {

		// model rules
        $rules = [
            [ [ 'parentId', 'parentType', 'subscriptionId', 'userId' ], 'required' ],
            [ [ 'id', 'active', 'startDate', 'endDate' ], 'safe' ],
            [ [ 'startDate', 'endDate' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// SubscriptionItem --------------------------

	/**
	 * @return string representation of flag
	 */
	public function getStatusStr() {

		return self::$statusMap[ $this->active ];
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return SubscriptionTables::TABLE_SUBSCRIPTION_ITEM;
	}

	// CMG parent classes --------------------

	// SubscriptionItem --------------------------

	// Read - Query -----------

	// Read - Find ------------

	public static function getByParentId( $id ) {

		return self::find()->where( 'parentId=:id', [ ':id' => $id ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}
