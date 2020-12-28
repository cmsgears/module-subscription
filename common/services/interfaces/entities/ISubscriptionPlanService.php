<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\subscription\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\ISimilar;
use cmsgears\core\common\services\interfaces\mappers\ICategory;
use cmsgears\cms\common\services\interfaces\base\IContentService;

/**
 * ISubscriptionPlanService declares methods specific to subscription plan.
 *
 * @since 1.0.0
 */
interface ISubscriptionPlanService extends IContentService, ICategory, ISimilar {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getFeatures( $model );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function bindFeatures( $binder );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
