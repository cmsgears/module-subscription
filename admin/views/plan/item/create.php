<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$title			= $this->context->title;
$this->title 	= "Add {$title} | " . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
$apixBase		= $this->context->apixBase;
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-plan', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud layer layer-5">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'name' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea() ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'status' )->dropDownList( $statusMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'order' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconInput( $form, $model, 'startDate', [ 'right' => true, 'icon' => 'cmti cmti-calendar', 'options' => [ 'class' => 'datepicker', 'autocomplete' => 'off' ] ] ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconInput( $form, $model, 'endDate', [ 'right' => true, 'icon' => 'cmti cmti-calendar', 'options' => [ 'class' => 'datepicker', 'autocomplete' => 'off' ] ] ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium layer layer-4"></div>
		<div class="box box-crud layer layer-4">
			<div class="box-header">
				<div class="box-header-title">Price Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<p class="info">The plans having at least one item will derive their price from the active items.</p>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'price' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'discount' ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Create" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
