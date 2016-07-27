<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;

$core	= Yii::$app->core;
$user	= Yii::$app->user->getIdentity();
?>

<?php if( $core->hasModule( 'subscription' ) && $user->isPermitted( 'user' ) ) { ?>
	<div id="sidebar-subscription" class="collapsible-tab has-children <?php if( strcmp( $parent, 'sidebar-subscription' ) == 0 ) echo 'active'; ?>">
		<div class="collapsible-tab-header clearfix">
			<div class="colf colf5 wrap-icon"><span class="cmti cmti-newspaper"></span></div>
			<div class="colf colf5x4">Subscriptions</div>
		</div>
		<div class="collapsible-tab-content clear <?php if( strcmp( $parent, 'sidebar-subscription' ) == 0 ) echo 'expanded visible'; ?>">
			<ul>
				<li class='plan <?php if( strcmp( $child, 'plan' ) == 0 ) echo 'active'; ?>'><?= Html::a( 'Plans', ['/subscription/plan/all'] ) ?></li>
				<li class='feature <?php if( strcmp( $child, 'feature' ) == 0 ) echo 'active' ;?>'><?= Html::a( 'Features', ['/subscription/feature/all'] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>