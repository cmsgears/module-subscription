<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\subscription\common\config;

/**
 * SubscriptionGlobal defines the global constants and variables available for
 * Subscription and dependent modules.
 *
 * @since 1.0.0
 */
class SubscriptionGlobal {

	// System Sites ---------------------------------------------------

	const SITE_SUBSCRIPTION = 'subscription';

	// System Pages ---------------------------------------------------

	const PAGE_SEARCH_PLANS = 'search-subscriptions';

	const PAGE_PLANS = 'subscriptions';

	// Grouping by type ------------------------------------------------

	const TYPE_PLAN			= 'subscription-plan';
	const TYPE_PLAN_ITEM	= 'subscription-plan-item';

	const TYPE_FEATURE = 'subscription-feature';

	const TYPE_SUBSCRIPTION			= 'subscription';
	const TYPE_SUBSCRIPTION_ITEM	= 'subscription-item';

	// Templates -------------------------------------------------------

	// Page Templates
	const TEMPLATE_PLANS = 'subscriptions';

	const TPL_NOTIFY_PLAN_NEW = 'subscription-plan-new';

	// Config ----------------------------------------------------------

	const CONFIG_SUBSCRIPTION = 'subscription';

	// Categories Slug -------------------------------------------------

	// Roles -----------------------------------------------------------

	const ROLE_SUBSCRIPTIONS_ADMIN = 'subscriptions-admin';

	// Permissions -----------------------------------------------------

	const PERM_SUBSCRIPTIONS_ADMIN = 'admins-subscription';

	// Plans

	const PERM_PLAN_MANAGE	= 'manage-subscription-plans';
	const PERM_PLAN_AUTHOR	= 'subscription-plan-author';

	const PERM_PLAN_VIEW	= 'view-subscription-plans';
	const PERM_PLAN_ADD		= 'add-subscription-plan';
	const PERM_PLAN_UPDATE	= 'update-subscription-plan';
	const PERM_PLAN_DELETE	= 'delete-subscription-plan';
	const PERM_PLAN_APPROVE	= 'approve-subscription-plan';
	const PERM_PLAN_PRINT	= 'print-subscription-plan';
	const PERM_PLAN_IMPORT	= 'import-subscription-plans';
	const PERM_PLAN_EXPORT	= 'export-subscription-plans';

	// Model Attributes ------------------------------------------------

	// Default Maps ----------------------------------------------------

	const DURATION_DAILY		=   5;
	const DURATION_WEEKLY		=  10;
	const DURATION_BI_WEEKLY	= 100;
	const DURATION_MONTHLY		= 200;
	const DURATION_QUARTERLY	= 300;
	const DURATION_HALF_YEARLY	= 400;
	const DURATION_ANNUALLY		= 500;

	public static $durationMap = [
		self::DURATION_DAILY => "Daily",
		self::DURATION_WEEKLY => "Weekly",
		self::DURATION_BI_WEEKLY => "Bi-Weekly",
		self::DURATION_MONTHLY => "Monthly",
		self::DURATION_QUARTERLY => "Quarterly",
		self::DURATION_HALF_YEARLY => "Half-Yearly",
		self::DURATION_ANNUALLY => "Anually"
	];

	// Messages --------------------------------------------------------

	// Errors ----------------------------------------------------------

	// Model Fields ----------------------------------------------------

	// Generic Fields
	const FIELD_SUBSCRIPTION = 'subscriptionField';

	const FIELD_FEATURE		= 'subscriptionFeatureField';
	const FIELD_PLAN		= 'subscriptionPlanField';
	const FIELD_SUBSCRIBER	= 'subscriberField';

}
