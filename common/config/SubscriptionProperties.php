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
 * SubscriptionProperties provide methods to access the properties specific to Subscription.
 *
 * @since 1.0.0
 */
class SubscriptionProperties extends \cmsgears\core\common\config\Properties {

	// Variables ---------------------------------------------------

	// Global -----------------

	/**
	 * The property to receive organization specific emails.
	 */
	const PROP_SUBSCRIPTION_EMAIL = 'subscription_email';

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private static $instance;

	// Constructor and Initialisation ------------------------------

	/**
	 * Return Singleton instance.
	 */
	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new SubscriptionProperties();

			self::$instance->init( SubscriptionGlobal::CONFIG_SUBSCRIPTION );
		}

		return self::$instance;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SubscriptionProperties ----------------

	/**
	 * Returns subscription email required to send subscription specific emails.
	 */
	public function getSubscriptionEmail() {

		return $this->properties[ self::PROP_SUBSCRIPTION_EMAIL ];
	}

}
