<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\subscription\common\models\base;

/**
 * It provide table name constants of db tables available in Subscription Module.
 *
 * @since 1.0.0
 */
class SubscriptionTables extends \cmsgears\core\common\models\base\DbTables {

	// Entities -------------

	const TABLE_SUBSCRIPTION_PLAN = 'cmg_subscription_plan';

	// Resources ------------

	const TABLE_SUBSCRIPTION_PLAN_ITEM = 'cmg_subscription_plan_item';
	const TABLE_SUBSCRIPTION_PLAN_META = 'cmg_subscription_plan_meta';

	const TABLE_SUBSCRIPTION_FEATURE = 'cmg_subscription_feature';

	const TABLE_SUBSCRIPTION		= 'cmg_subscription';
	const TABLE_SUBSCRIPTION_ITEM	= 'cmg_subscription_item';

	// Mappers & Traits -----

	const TABLE_SUBSCRIPTION_MATRIX	= 'cmg_subscription_matrix';

	const TABLE_SUBSCRIPTION_PLAN_FOLLOWER = 'cmg_subscription_plan_follower';

}
