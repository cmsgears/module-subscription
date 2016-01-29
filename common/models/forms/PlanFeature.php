<?php
namespace cmsgears\subscription\common\models\forms;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\subscription\common\config\SubscriptionGlobal;
use cmsgears\core\common\config\CoreGlobal;

class PlanFeature extends \cmsgears\core\common\models\forms\JsonModel {

	// Variables ---------------------------------------------------

	// Public Variables --------------------
	
	public $feature;
	public $featureId;
	public $htmlOptions;
	public $icon;
	public $order;
	
	public $name; // used for update

	// Constructor -------------------------------------------------

	// Instance Methods --------------------------------------------

	// yii\base\Model

	public function rules() {

		$trim		= [];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'feature', 'featureId', 'htmlOptions', 'icon', 'order' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];
		}

        $rules = [
			[ [ 'feature', 'featureId', 'htmlOptions', 'icon', 'order' ], 'safe' ],
			[ 'order', 'number', 'integerOnly' => true ]
		];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	public function attributeLabels() {

		return [
			'featureId' => Yii::$app->cmgSubscriptionMessage->getMessage( SubscriptionGlobal::FIELD_FEATURE ),
			'htmlOptions' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
			'icon' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER )
		];
	}
}

?>