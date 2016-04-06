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

/**
 * Subscription Entity
 *
 * @property integer $id
 * @property integer $planId
 * @property integer $subscriberId
 * @property integer $status
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class Subscription extends \cmsgears\core\common\models\entities\CmgEntity {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	const STATUS_NEW		=  0;
	const STATUS_ACTIVE		= 50;

	public static $statusMap = [
		self::STATUS_NEW => 'New',
		self::STATUS_ACTIVE => 'Active'
	];

	// Public -------------

	// Private/Protected --

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [

            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'createdAt',
 				'updatedAtAttribute' => 'modifiedAt',
 				'value' => new Expression('NOW()')
            ]
        ];
    }

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

		// model rules
        $rules = [
            [ [ 'planId', 'subscriberId' ], 'required' ],
            [ [ 'id', 'status' ], 'safe' ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'planId' => Yii::$app->cmgSubscriptionMessage->getMessage( SubscriptionGlobal::FIELD_PLAN ),
			'subscriberId' => Yii::$app->cmgSubscriptionMessage->getMessage( SubscriptionGlobal::FIELD_SUBSCRIBER ),
			'status' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_STATUS )
		];
	}

	// Subscription ----------------------

	/**
	 * @return ObjectData array
	 */
	public function getPlan() {

    	return $this->hasOne( ObjectData::className(), [ 'id' => 'planId' ] );
	}

	/**
	 * @return ObjectData array
	 */
	public function getUser() {

    	return $this->hasOne( User::className(), [ 'id' => 'subscriberId' ] );
	}

	/**
	 * @return string representation of flag
	 */
	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return SubscriptionTables::TABLE_SUBSCRIPTION;
	}

	// Subscription ----------------------

	// Create -------------

	// Read ---------------

	// Update -------------

	// Delete -------------
}

?>