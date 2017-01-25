<?php
namespace cmsgears\subscription\admin;

// Yii Imports
use \Yii;

// CMG Imports 

class Module extends \cmsgears\core\common\base\Module {

    public $controllerNamespace = 'cmsgears\subscription\admin\controllers';
 

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/module-subscription/admin/views' );
    }

	public function getSidebarHtml() {

		$path	= Yii::getAlias( "@cmsgears" ) . "/module-subscription/admin/views/sidebar.php";

		return $path;
	}
}

?>