<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $model ResourceAllocationProjectWork */

$this->breadcrumbs = array(
    'Resource Allocation Program Works' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'Set Manager', 'url' => array('SetManager')),
    array('label' => 'Assign Resource Under Manager', 'url' => array('SetRoles')),
    array('label' => 'View Resource Allocation List', 'url' => array('Admin')),
   
);
?>
<html>
<head><h4>Employee management</h4> </head>

<style>
#mgr{
    width: 320px;

    margin: 0 auto;
    margin-bottom: 10px;
}
.manager{
    width: 320px;
    margin-top:10px;
    margin-bottom: 10px;
}



</style>

<body>
    <form class="form no-mr clearfix" action="<?php echo Yii::app()->baseUrl; ?>/accessrolemaster/SetManager" method="post" > 
        <input type="hidden" name ="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken; ?>"  />

        <div class="mgr">
            <h1>Select Manager</h1>
            <?php  $employeeData = Employee::model()->findAll(array('select' => "emp_id,first_name,last_name,email", 'order' => 'first_name', 'condition' => 'is_active=1 and is_password_changed="yes"'));
            $emp_list = array();
            foreach ($employeeData as $key => $value) {
                $emp_list[$value['emp_id']] = $value['first_name'] . " " . $value['last_name']." (".$value['email'].")";
            } 
            echo CHtml::dropDownList("manager", 'id', $emp_list, array('multiple' => 'multiple', 'style' => 'height:200px;margin-top:20px;width:300px;'));
            ?>       
        </div>
        <div class="submitButton">
        <?php echo CHtml::submitButton('Done', array('id' => 'allocate', 'style' => 'margin-top:20px;')); ?>
        </div>
</form>
</body>

</html>




