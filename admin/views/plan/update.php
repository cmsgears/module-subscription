<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Add Plan | ' . $coreProperties->getSiteTitle();
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Update Plan</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-sidebar' ] );?>

    	<?= $form->field( $model, 'name' ) ?>
    	<?= $form->field( $model, 'description' )->textarea() ?>
		<?= $form->field( $model, 'active' )->checkbox() ?>

		<div class="box-content clearfix">
			<div class="header">Update Features</div>
			<?php foreach ( $featuresList as $key => $feature ) { ?>
				<span class="box-half">
					<?= $form->field( $planFeatures[ $key ], "[$key]feature" )->checkbox( [ 'label' => $feature[ 'name' ] ] ) ?>
					<?= $form->field( $planFeatures[ $key ], "[$key]featureId" )->hiddenInput( [ 'value' => $feature['id'] ] )->label( false ) ?>
					<div class="frm-split-40-60 clearfix">
						<?= $form->field( $planFeatures[ $key ], "[$key]htmlOptions" )->textInput( [ "placeholder" => "html options" ] ) ?>
						<?= $form->field( $planFeatures[ $key ], "[$key]order" )->textInput( [ "placeholder" => "order" ] ) ?>
					</div>
				</span>
			<?php } ?>
		</div>

		<div class="clear filler-height"></div>

		<div class="align align-center">
			<?=Html::a( 'Cancel', [ 'all' ], [ 'class' => 'btn btn-medium' ] );?>
			<input class="element-medium" type="submit" value="Update" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>