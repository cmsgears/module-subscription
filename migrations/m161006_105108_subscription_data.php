<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\Permission;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The subscription data migration inserts the base data required to run the application.
 *
 * @since 1.0.0
 */
class m161006_105108_subscription_data extends \cmsgears\core\common\base\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	private $site;

	private $master;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		$this->site		= Site::findBySlug( CoreGlobal::SITE_MAIN );
		$this->master	= User::findByUsername( Yii::$app->migration->getSiteMaster() );

		Yii::$app->core->setSite( $this->site );
	}

    public function up() {

		// Create RBAC
		$this->insertRolePermission();
    }

	private function insertRolePermission() {

		// Roles

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'adminUrl', 'homeUrl', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->master->id, $this->master->id, 'Subscription Admin', 'subscription-admin', 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role Subscription Admin is limited to manage subscriptions from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole	= Role::findBySlugType( 'super-admin', CoreGlobal::TYPE_SYSTEM );
		$adminRole		= Role::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$subAdminRole	= Role::findBySlugType( 'subscription-admin', CoreGlobal::TYPE_SYSTEM );

		// Permissions

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'group', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			[ $this->master->id, $this->master->id, 'Admin Subscriptions', 'admin-subscriptions', CoreGlobal::TYPE_SYSTEM, null, false, 'The permission admin subscriptions is to manage subscriptions and subscribers from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		$adminPerm	= Permission::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userPerm	= Permission::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
		$subPerm	= Permission::findBySlugType( 'admin-subscriptions', CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
			[ $superAdminRole->id, $subPerm->id ],
			[ $adminRole->id, $subPerm->id ],
			[ $subAdminRole->id, $adminPerm->id ], [ $subAdminRole->id, $userPerm->id ], [ $subAdminRole->id, $subPerm->id ]
		];

		$this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );
	}

    public function down() {

        echo "m161006_105108_subscription_data will be deleted with m160621_014408_core.\n";

        return true;
    }

}
