<?php
namespace cmsgears\subscription\frontend;

// Yii Imports
use \Yii;

// CMG Imports 

class Module extends \cmsgears\core\common\base\Module {

    public $controllerNamespace = 'cmsgears\subscription\frontend\controllers';
 

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/module-subscription/frontend/views' );
    } 
}

?>