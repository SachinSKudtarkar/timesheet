<?php
/* @var $this LinkEnggCrMasterController */
/* @var $model LinkEnggCrMaster */

?>

<h1>Approver's Feedback - Request#: <?=$model->id;?></h1>

<?php $this->renderPartial('_approval_feedback_form', array('model' => $model,'approval_type'=>$approval_type,'approval_status'=>$approval_status)); ?>