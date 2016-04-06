<?php
namespace cmsgears\subscription\common\models\forms;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\subscription\common\config\SubscriptionGlobal;

class PlanFeature extends \cmsgears\core\common\models\forms\JsonModel {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $feature;
	public $featureId;
	public $htmlOptions;
	public $order;

	public $name; // used for update

	// Constructor -------------------------------------------------

	// Instance Methods --------------------------------------------

	// yii\base\Model

	public function rules() {

        $rules = [
			[ [ 'featureId', 'htmlOptions', 'order' ], 'safe' ],
			[ 'feature', 'boolean' ],
			[ 'order', 'number', 'integerOnly' => true ]
		];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'htmlOptions' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	public function attributeLabels() {

		return [
			'featureId' => Yii::$app->cmgSubscriptionMessage->getMessage( SubscriptionGlobal::FIELD_FEATURE ),
			'htmlOptions' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
			'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER )
		];
	}
}

?>