<?php
$this->breadcrumbs = array(
    'Change Password',
);
?>
<?php if(empty($model->change_password)){	
   echo "<span style='color:red;font-weight:bold'>For security reasons, you are required to change your password.</span>";
}else{
    echo "<h1>Change Password</h1>";
}
?>


<?php $this->renderPartial('_form', array('model' => $model)); ?>