<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'link-engg-feedback-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped bordered',
    'columns' => array(
        'id',
        'link_no',
        'approval_1_cat_1_feedback',
        'approval_2_cat_1_feedback',
        'approval_3_cat_1_feedback',
        'approval_1_cat_2_feedback',
        'approval_2_cat_2_feedback',
        'approval_3_cat_2_feedback',
        'approval_1_cat_3_feedback',
        'approval_2_cat_3_feedback',
        'approval_3_cat_3_feedback',
    ),
));
?>