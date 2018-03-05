<?php
/* @var $this DayCommentController */
/* @var $model DayComment */

$this->breadcrumbs = array(
    'Day Comments' => array('index'),
    'Manage',
);
$this->menu = array(
    array('label' => 'DayComment', 'url' => array('index')),
    array('label' => 'View My Status', 'url' => array('admin')),
);
?> 
<h1>Manage Day Comments</h1> 
<?php
$x = $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'day-comment-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'project_name',
        'sub_project_name',
        array(
            'name' => 'emp_id',
            'value' => array($model, 'getUserName'),
            'type' => 'raw',
        ),
        array(
            'name' => 'day',
            'value' => array($model, 'getFormatedDate'),
            'type' => 'raw',
        ),
        'comment',
        'hours', 
    ),
));


echo $this->renderExportGridButton($x, 'Export Grid Results', array('class' => 'btn btn-primary pull-left clearfix mr-tp-20'));
 

?>
