<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Delete Plan | ' . $coreProperties->getSiteTitle();
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Delete Plan</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-sidebar' ] );?>

    	<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] ) ?>
    	<?= $form->field( $model, 'description' )->textarea( [ 'readonly' => true ] ) ?>
		<?= $form->field( $model, 'active' )->checkbox( [ 'readonly' => true ] ) ?>

		<div class="box-content clearfix">
			<div class="header">Update Features</div>
			<?php foreach ( $features as $key => $feature ) { ?>
				<span class="box-half">
					<?= $form->field( $planFeatures[ $key ], "[$key]feature" )->checkbox( [ 'label' => $feature[ 'name' ], 'readonly' => true ] ) ?>
					<?= $form->field( $planFeatures[ $key ], "[$key]featureId" )->hiddenInput( [ 'value' => $feature['id'] ] )->label( false ) ?>
					<div class="frm-split-40-60 clearfix">
						<?= $form->field( $planFeatures[ $key ], "[$key]htmlOptions" )->textInput( [ "placeholder" => "html options" ] ) ?>
						<?= $form->field( $planFeatures[ $key ], "[$key]icon" )->textInput( [ "placeholder" => "label" ] ) ?>
						<?= $form->field( $planFeatures[ $key ], "[$key]order" )->textInput( [ "placeholder" => "order" ] ) ?>
					</div>
				</span>
			<?php } ?>
		</div>

		<div class="clear filler-height"></div>

		<div class="align align-middle">
			<?=Html::a( 'Cancel', [ 'all' ], [ 'class' => 'btn btn-medium' ] );?>
			<input class="btn btn-medium" type="submit" value="Delete" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>