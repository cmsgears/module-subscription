<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$title			= $this->context->title;
$this->title	= "{$title}s | " . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;
$baseUrl		= $this->context->baseUrl;

// View Templates
$moduleTemplates	= '@apwen/module-subscription/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'baseUrl' => $baseUrl, 'add' => true, 'addUrl' => "create?pid=$plan->id", 'data' => [],
	'title' => $this->title, 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [
		'name' => 'Name', 'desc' => 'Description'
	],
	'sortColumns' => [
		'name' => 'Name', 'status' => 'Status',
		'price' => 'Price', 'discount' => 'Discount', 'total' => 'Total',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [
		'status' => [
			'new' => 'New', 'active' => 'Active',
			'expired' => 'Expired'
		],
		'model' => []
	],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'status' => [ 'title' => 'Status', 'type' => 'select', 'options' => $statusMap ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'model' => [
			'activate' => 'Activate', 'expire' => 'Expire',
			'delete' => 'Delete'
		]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x4', null, null, null, null, 'x2', 'x2', 'x2' ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'price' => 'Price',
		'discount' => 'Discount',
		'total' => 'Total',
		'status' => [ 'title' => 'Status', 'generate' => function( $model ) { return $model->getStatusStr(); } ],
		'startDate' => 'Start Date',
		'endDate' => 'End Date',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/item",
	//'cardView' => "$moduleTemplates/grid/cards/item",
	//'actionView' => "$moduleTemplates/grid/actions/item"
])?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => $title, 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
])?>

<?= Popup::widget([
	'title' => "Delete $title", 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => $title, 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
])?>
