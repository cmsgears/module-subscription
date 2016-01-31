<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Delete Feature | ' . $coreProperties->getSiteTitle();
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Delete Feature</div>
	</div>
	<div class="box-wrap-content">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-menu' ] );?>

		<div class="frm-split frm-split-40-60 clearfix">
	    	<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => 'true' ] ) ?>
	    	<?= $form->field( $model, 'description' )->textarea( [ 'readonly' => 'true' ] ) ?>
			<?= $form->field( $model, 'active' )->checkbox( [ 'readonly' => 'true' ] ) ?>
			<?= $form->field( $model, 'icon' ) ?>
		</div>  
		<div class="filler-height"></div>

		<div class="align align-middle">
			<?=Html::a( 'Cancel',  [ 'all' ], [ 'class' => 'btn btn-medium' ] );?>
			<input class="btn btn-medium" type="submit" value="Delete" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>