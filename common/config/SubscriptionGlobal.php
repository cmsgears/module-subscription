<?php
namespace cmsgears\subscription\common\config;

class SubscriptionGlobal {

	// Traits - Metas, Tags, Attachments, Addresses --------------------

	const TYPE_PLAN				= 'sub-plan';
	const TYPE_FEATURE			= 'sub-feature';

	// Permissions -----------------------------------------------------

	const PERM_SUBSCRIPTION		= 'subscription';

	// Model Fields ----------------------------------------------------

	// Generic Fields
	const FIELD_FEATURE			= 'featureField';
	const FIELD_PLAN			= 'planField';
	const FIELD_SUBSCRIBER		= 'subscriberField';
}
