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
use cmsgears\cms\common\config\CmsGlobal;
use cmsgears\subscription\common\config\SubscriptionGlobal;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\Permission;
use cmsgears\core\common\models\resources\Form;
use cmsgears\core\common\models\resources\FormField;

use cmsgears\cms\common\models\entities\Page;

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

		// Create RBAC and Site Members
		$this->insertRolePermission();

		// Create Subscription Plan permission groups and CRUD permissions
		$this->insertSubscriptionPermissions();

		// Create various config
		$this->insertSubscriptionConfig();

		// Init default config
		$this->insertDefaultConfig();

		// Init system pages
		$this->insertSystemPages();

		// Notifications
		$this->insertNotificationTemplates();
	}

	private function insertRolePermission() {

		// Roles

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'adminUrl', 'homeUrl', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->master->id, $this->master->id, 'Subscriptions Admin', SubscriptionGlobal::ROLE_SUBSCRIPTIONS_ADMIN, 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role Subscription Admin is limited to manage subscriptions from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole	= Role::findBySlugType( CoreGlobal::ROLE_SUPER_ADMIN, CoreGlobal::TYPE_SYSTEM );
		$adminRole		= Role::findBySlugType( CoreGlobal::ROLE_ADMIN, CoreGlobal::TYPE_SYSTEM );
		$subsAdminRole	= Role::findBySlugType( SubscriptionGlobal::ROLE_SUBSCRIPTION_ADMIN, CoreGlobal::TYPE_SYSTEM );

		// Permissions

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			[ $this->master->id, $this->master->id, 'Admin Subscriptions', SubscriptionGlobal::PERM_SUBSCRIPTIONS_ADMIN, CoreGlobal::TYPE_SYSTEM, null, 'The permission admin subscriptions is to manage subscriptions from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		$adminPerm		= Permission::findBySlugType( CoreGlobal::PERM_ADMIN, CoreGlobal::TYPE_SYSTEM );
		$userPerm		= Permission::findBySlugType( CoreGlobal::PERM_USER, CoreGlobal::TYPE_SYSTEM );
		$subsAdminPerm	= Permission::findBySlugType( SubscriptionGlobal::PERM_SUBSCRIPTIONS_ADMIN, CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
			[ $superAdminRole->id, $subsAdminPerm->id ],
			[ $adminRole->id, $subsAdminPerm->id ],
			[ $subsAdminRole->id, $adminPerm->id ], [ $subsAdminRole->id, $userPerm->id ], [ $subsAdminRole->id, $subsAdminPerm->id ]
		];

		$this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );
	}

	private function insertSubscriptionPermissions() {

		// Permissions
		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'group', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			// Permission Groups - Default - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'Manage Subscription Plans', SubscriptionGlobal::PERM_PLAN_MANAGE, CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission manage subscription plans allows user to manage subscription plans from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Subscription Plan Author', SubscriptionGlobal::PERM_PLAN_AUTHOR, CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission subscription plan author allows user to perform crud operations of subscription plan belonging to respective author from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],

			// Subscription Plan Permissions - Hard Coded - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'View Subscription Plans', SubscriptionGlobal::PERM_PLAN_VIEW, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission view subscription plans allows users to view their subscription plans from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Add Subscription Plan', SubscriptionGlobal::PERM_PLAN_ADD, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission add subscription plan allows users to create subscription plan from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Update Subscription Plan', SubscriptionGlobal::PERM_PLAN_UPDATE, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission update subscription plan allows users to update subscription plan from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Delete Subscription Plan', SubscriptionGlobal::PERM_PLAN_DELETE, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission delete subscription plan allows users to delete subscription plan from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Approve Subscription Plan', SubscriptionGlobal::PERM_PLAN_APPROVE, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission approve subscription plan allows user to approve, freeze or block subscription plan from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Print Subscription Plan', SubscriptionGlobal::PERM_PLAN_PRINT, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission print subscription plan allows user to print subscription plan from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Import Subscription Plans', SubscriptionGlobal::PERM_PLAN_IMPORT, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission import subscription plans allows user to import subscription plans from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Export Subscription Plans', SubscriptionGlobal::PERM_PLAN_EXPORT, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission export subscription plans allows user to export subscription plans from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		// Permission Groups
		$subPlanManagerPerm	= Permission::findBySlugType( SubscriptionGlobal::PERM_PLAN_MANAGE, CoreGlobal::TYPE_SYSTEM );
		$subPlanAuthorPerm	= Permission::findBySlugType( SubscriptionGlobal::PERM_PLAN_AUTHOR, CoreGlobal::TYPE_SYSTEM );

		// Permissions
		$vSubPlansPerm	= Permission::findBySlugType( SubscriptionGlobal::PERM_PLAN_VIEW, CoreGlobal::TYPE_SYSTEM );
		$aSubPlanPerm	= Permission::findBySlugType( SubscriptionGlobal::PERM_PLAN_ADD, CoreGlobal::TYPE_SYSTEM );
		$uSubPlanPerm	= Permission::findBySlugType( SubscriptionGlobal::PERM_PLAN_UPDATE, CoreGlobal::TYPE_SYSTEM );
		$dSubPlanPerm	= Permission::findBySlugType( SubscriptionGlobal::PERM_PLAN_DELETE, CoreGlobal::TYPE_SYSTEM );
		$apSubPlanPerm	= Permission::findBySlugType( SubscriptionGlobal::PERM_PLAN_APPROVE, CoreGlobal::TYPE_SYSTEM );
		$pSubPlanPerm	= Permission::findBySlugType( SubscriptionGlobal::PERM_PLAN_PRINT, CoreGlobal::TYPE_SYSTEM );
		$iSubPlansPerm	= Permission::findBySlugType( SubscriptionGlobal::PERM_PLAN_IMPORT, CoreGlobal::TYPE_SYSTEM );
		$eSubPlansPerm	= Permission::findBySlugType( SubscriptionGlobal::PERM_PLAN_EXPORT, CoreGlobal::TYPE_SYSTEM );

		//Hierarchy

		$columns = [ 'parentId', 'childId', 'rootId', 'parentType', 'lValue', 'rValue' ];

		$hierarchy = [
			// SubPlan Manager - Organization, Approver
			[ null, null, $subPlanManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 1, 18 ],
			[ $subPlanManagerPerm->id, $vSubPlansPerm->id, $subPlanManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 2, 3 ],
			[ $subPlanManagerPerm->id, $aSubPlanPerm->id, $subPlanManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 4, 5 ],
			[ $subPlanManagerPerm->id, $uSubPlanPerm->id, $subPlanManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 6, 7 ],
			[ $subPlanManagerPerm->id, $dSubPlanPerm->id, $subPlanManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 8, 9 ],
			[ $subPlanManagerPerm->id, $apSubPlanPerm->id, $subPlanManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 10, 11 ],
			[ $subPlanManagerPerm->id, $pSubPlanPerm->id, $subPlanManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 12, 13 ],
			[ $subPlanManagerPerm->id, $iSubPlansPerm->id, $subPlanManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 14, 15 ],
			[ $subPlanManagerPerm->id, $eSubPlansPerm->id, $subPlanManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 16, 17 ],

			// SubPlan Author- Individual
			[ null, null, $subPlanAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 1, 16 ],
			[ $subPlanAuthorPerm->id, $vSubPlansPerm->id, $subPlanAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 2, 3 ],
			[ $subPlanAuthorPerm->id, $aSubPlanPerm->id, $subPlanAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 4, 5 ],
			[ $subPlanAuthorPerm->id, $uSubPlanPerm->id, $subPlanAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 6, 7 ],
			[ $subPlanAuthorPerm->id, $dSubPlanPerm->id, $subPlanAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 8, 9 ],
			[ $subPlanAuthorPerm->id, $pSubPlanPerm->id, $subPlanAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 10, 11 ],
			[ $subPlanAuthorPerm->id, $iSubPlansPerm->id, $subPlanAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 12, 13 ],
			[ $subPlanAuthorPerm->id, $eSubPlansPerm->id, $subPlanAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 14, 15 ]
		];

		$this->batchInsert( $this->prefix . 'core_model_hierarchy', $columns, $hierarchy );
	}

	private function insertSubscriptionConfig() {

		$this->insert( $this->prefix . 'core_form', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Config Subscription', 'slug' => 'config-' . SubscriptionGlobal::CONFIG_SUBSCRIPTION,
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Career configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlugType( 'config-' . SubscriptionGlobal::CONFIG_SUBSCRIPTION, CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'meta', 'active', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'subscription_email', 'Subscription Email', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Subscription Email to receive Subscriptions specific emails.","placeholder":"Subscription Email"}' ]
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertDefaultConfig() {

		$columns = [ 'modelId', 'name', 'label', 'type', 'active', 'valueType', 'value', 'data' ];

		$metas = [
			[ $this->site->id, 'subscription_email', 'Subscription Email', SubscriptionGlobal::CONFIG_SUBSCRIPTION, 1, 'text', NULL, NULL ]
		];

		$this->batchInsert( $this->prefix . 'core_site_meta', $columns, $metas );
	}

	private function insertSystemPages() {

		$columns = [ 'siteId', 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'title', 'status', 'visibility', 'order', 'featured', 'comments', 'createdAt', 'modifiedAt' ];

		$pages = [
			// Subscription Pages
			[ $this->site->id, $this->master->id, $this->master->id, 'Subscription Plans', SubscriptionGlobal::PAGE_PLANS, CmsGlobal::TYPE_PAGE, null, null, Page::STATUS_ACTIVE, Page::VISIBILITY_PUBLIC, 0, false, false, DateUtil::getDateTime(), DateUtil::getDateTime() ],
			// Search Pages
			[ $this->site->id, $this->master->id, $this->master->id, 'Search Subscription Plans', SubscriptionGlobal::PAGE_SEARCH_PLANS, CmsGlobal::TYPE_PAGE, null, null, Page::STATUS_ACTIVE, Page::VISIBILITY_PUBLIC, 0, false, false, DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'cms_page', $columns, $pages );

		$summary = "Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content. It's also called placeholder (or filler) text. It's a convenient tool for mock-ups. It helps to outline the visual elements of a document or presentation, eg typography, font, or layout. Lorem ipsum is mostly a part of a Latin text by the classical author and philosopher Cicero.";
		$content = "Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content. It's also called placeholder (or filler) text. It's a convenient tool for mock-ups. It helps to outline the visual elements of a document or presentation, eg typography, font, or layout. Lorem ipsum is mostly a part of a Latin text by the classical author and philosopher Cicero.";

		$columns = [ 'parentId', 'parentType', 'seoName', 'seoDescription', 'seoKeywords', 'seoRobot', 'summary', 'content', 'publishedAt' ];

		$pages = [
			// Subscription Pages
			[ Page::findBySlugType( SubscriptionGlobal::PAGE_PLANS, CmsGlobal::TYPE_PAGE )->id, CmsGlobal::TYPE_PAGE, null, null, null, null, $summary, $content, DateUtil::getDateTime() ],
			// Search Pages
			[ Page::findBySlugType( SubscriptionGlobal::PAGE_SEARCH_PLANS, CmsGlobal::TYPE_PAGE )->id, CmsGlobal::TYPE_PAGE, null, null, null, null, $summary, $content, DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'cms_model_content', $columns, $pages );
	}

	private function insertNotificationTemplates() {

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'icon', 'type', 'description', 'active', 'renderer', 'fileRender', 'layout', 'layoutGroup', 'viewPath', 'createdAt', 'modifiedAt', 'message', 'content', 'data' ];

		$templates = [
			// Subscription
			[ $this->master->id, $this->master->id, 'New Subscription Plan', SubscriptionGlobal::TPL_NOTIFY_PLAN_NEW, null, 'notification', 'Trigger Notification and Email to Admin, when new Subscription Plan is created by site users.', true, 'twig', 0, null, false, null, DateUtil::getDateTime(), DateUtil::getDateTime(), 'New Subscription Plan - <b>{{model.displayName}}</b>', 'New Job - <b>{{model.displayName}}</b> has been submitted for registration. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"0","adminEmail":"1","userEmail":"0","directEmail":"0"}}' ]
		];

		$this->batchInsert( $this->prefix . 'core_template', $columns, $templates );
	}

    public function down() {

        echo "m161006_105108_subscription_data will be deleted with m160621_014408_core.\n";

        return true;
    }

}
