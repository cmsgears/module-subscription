<?php
namespace cmsgears\subscription\frontend\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\WebGlobalCore;

class SubscriptionController extends \cmsgears\core\frontend\controllers\base\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config ); 
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'all'  => [ 'permission' => CoreGlobal::PERM_USER ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'all'  => [ 'get' ],
	                'plans'  => [ 'get' ]
                ]
            ]
        ];
    }

	// SubscriptionController ------------

	public function actionAll() {

	    return $this->render( 'all' );
	}

	public function actionPlans() {

		$this->layout	= WebGlobalCore::LAYOUT_PUBLIC;

	    return $this->render( 'index' );
	}
}

?>