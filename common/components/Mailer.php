<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\subscription\common\components;

/**
 * Mailer triggers the mails provided by Subscription Module.
 *
 * @since 1.0.0
 */
class Mailer extends \cmsgears\core\common\base\Mailer {

	// Global -----------------

	const MAIL_PLAN_CREATE		= 'plan/create';
	const MAIL_PLAN_REGISTER	= 'plan/register';

	// Public -----------------

	public $htmlLayout	= '@cmsgears/module-core/common/mails/layouts/html';
	public $textLayout	= '@cmsgears/module-core/common/mails/layouts/text';
	public $viewPath	= '@cmsgears/module-subscription/common/mails/views';

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Mailer --------------------------------

	public function sendCreatePlanMail( $plan ) {

		$fromEmail	= $this->mailProperties->getSenderEmail();
		$fromName	= $this->mailProperties->getSenderName();

		$author = $plan->creator;

		$name	= $author->getName();
		$email	= $author->email;

		// Send Mail
		$this->getMailer()->compose( self::MAIL_POST_CREATE, [ 'coreProperties' => $this->coreProperties, 'post' => $plan, 'name' => $name, 'email' => $email ] )
			->setTo( $email )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Subscription Plan Registration | " . $this->coreProperties->getSiteName() )
			//->setTextBody( "text" )
			->send();
	}

	public function sendRegisterPlanMail( $plan ) {

		$fromEmail	= $this->mailProperties->getSenderEmail();
		$fromName	= $this->mailProperties->getSenderName();

		$author = $plan->creator;

		$name	= $author->getName();
		$email	= $author->email;

		// Send Mail
		$this->getMailer()->compose( self::MAIL_POST_REGISTER, [ 'coreProperties' => $this->coreProperties, 'post' => $plan, 'name' => $name, 'email' => $email ] )
			->setTo( $email )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Subscription Plan Registration | " . $this->coreProperties->getSiteName() )
			//->setTextBody( "text" )
			->send();
	}

}
