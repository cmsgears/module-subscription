<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\subscription\common\config\SubscriptionGlobal;

$core	= Yii::$app->core;
$user	= $core->getUser();
?>
<?php if( $core->hasModule( 'subscription' ) && $user->isPermitted( SubscriptionGlobal::PERM_SUBSCRIPTION_ADMIN ) ) { ?>
	<div id="sidebar-subscription" class="collapsible-tab has-children <?= $parent === 'sidebar-subscription' ? 'active' : null ?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="cmti cmti-groups"></span></div>
			<div class="tab-title">Members</div>
		</div>
		<div class="tab-content clear <?= $parent === 'sidebar-subscription' ? 'expanded visible' : null ?>">
			<ul>
				<li class="member <?= $child === 'member' ? 'active' : null ?>"><?= Html::a( 'Members', [ '/subscription/member/all' ] ) ?></li>
				<li class="invoice <?= $child === 'invoice' ? 'active' : null ?>"><?= Html::a( 'Invoices', [ '/subscription/invoice/all' ] ) ?></li>
				<li class="payment <?= $child === 'payment' ? 'active' : null ?>"><?= Html::a( 'Payments', [ '/subscription/payment/all' ] ) ?></li>
				<li class="plan <?= $child === 'plan' ? 'active' : null ?>"><?= Html::a( 'Membership Plans', [ '/subscription/plan/all' ] ) ?></li>
				<li class="feature <?= $child === 'feature' ? 'active' : null ?>"><?= Html::a( 'Membership Feature', [ '/subscription/feature/all' ] ) ?></li>
				<li class="plan-category <?= $child === 'plan-category' ? 'active' : null ?>"><?= Html::a( 'Plan Categories', [ '/subscription/plan/category/all' ] ) ?></li>
				<li class="plan-tag <?= $child === 'plan-tag' ? 'active' : null ?>"><?= Html::a( 'Plan Tags', [ '/subscription/plan/tag/all' ] ) ?></li>
				<li class="plan-template <?= $child === 'plan-template' ? 'active' : null ?>"><?= Html::a( 'Plan Templates', [ '/subscription/plan/template/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>
