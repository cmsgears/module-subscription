<?php
// Yii Imports
use \Yii;
use yii\helpers\Html;
use yii\helpers\Url;

$core	= Yii::$app->cmgCore;
$user	= Yii::$app->user->getIdentity();
?>

<?php if( $core->hasModule( 'cmgsubscription' ) && $user->isPermitted( 'user' ) ) { ?>
	<div id="sidebar-listing" class="collapsible-tab has-children <?php if( strcmp( $parent, 'sidebar-subscription' ) == 0 ) echo 'active';?>">
		<div class="collapsible-tab-header clearfix">
			<div class="colf colf5 wrap-icon"><span class="cmti cmti-newspaper"></span></div>
			<div class="colf colf5x4">Subscriptions</div>
		</div>
		<div class="collapsible-tab-content clear <?php if( strcmp( $parent, 'sidebar-subscription' ) == 0 ) echo 'expanded visible';?>">
			<ul>
				<li class='plans <?php if( strcmp( $child, 'plans' ) == 0 ) echo 'active';?>'><?= Html::a( "Plans", ['/cmgsubscription/plan/all'] ) ?></li>
				<li class='features <?php if( strcmp( $child, 'features' ) == 0 ) echo 'active';?>'><?= Html::a( "Features", ['/cmgsubscription/feature/all'] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>