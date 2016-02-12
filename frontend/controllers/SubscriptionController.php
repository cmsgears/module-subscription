<?php
namespace cmsgears\subscription\frontend\controllers;

// Yii Imports
use \Yii; 

// CMG Imports
use cmsgears\core\frontend\config\WebGlobalCore;

class SubscriptionController extends \cmsgears\core\frontend\controllers\base\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config ); 
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------
 
	
	// FeatureController -----------------

	public function actionIndex() {
		 
		$this->layout	= WebGlobalCore::LAYOUT_PUBLIC; 
		 
	    return $this->render( 'index' );
	}
}

?>