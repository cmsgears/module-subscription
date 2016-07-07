<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\Permission;

use cmsgears\core\common\utilities\DateUtil;

class m160623_105108_subscription_data extends \yii\db\Migration {

	public $prefix;

	private $site;

	private $master;

	public function init() {

		$this->prefix		= 'cmg_';

		$this->site		= Site::findBySlug( CoreGlobal::SITE_MAIN );
		$this->master	= User::findByUsername( 'demomaster' );

		Yii::$app->core->setSite( $this->site );
	}

    public function up() {

		// Create RBAC
		$this->insertRolePermission();
    }

	private function insertRolePermission() {

		// Roles

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'homeUrl', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->master->id, $this->master->id, 'Subscription Manager', 'subscription-manager', 'dashboard', CoreGlobal::TYPE_SYSTEM, null, 'The role Subscription Manager is limited to manage subscriptions from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole		= Role::findBySlugType( 'super-admin', CoreGlobal::TYPE_SYSTEM );
		$adminRole			= Role::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$subManagerRole		= Role::findBySlugType( 'subscription-manager', CoreGlobal::TYPE_SYSTEM );

		// Permissions

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			[ $this->master->id, $this->master->id, 'Subscription', 'subscription', CoreGlobal::TYPE_SYSTEM, null, 'The permission subscription is to manage subscriptions and subscribers from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		$adminPerm			= Permission::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userPerm			= Permission::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
		$subPerm			= Permission::findBySlugType( 'subscription', CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
			[ $superAdminRole->id, $subPerm->id ],
			[ $adminRole->id, $subPerm->id ],
			[ $subManagerRole->id, $adminPerm->id ], [ $subManagerRole->id, $userPerm->id ], [ $subManagerRole->id, $subPerm->id ]
		];

		$this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );
	}

    public function down() {

        echo "m160623_105108_subscription_data will be deleted with m160621_014408_core.\n";

        return true;
    }
}

?>