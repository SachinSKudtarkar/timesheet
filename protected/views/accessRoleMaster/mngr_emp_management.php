<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $model ResourceAllocationProjectWork */

$this->breadcrumbs = array(
    'Resource Allocation Program Works' => array('SetManager'),
    'SetManager',
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
#txtarea1{
    width: 320px;

    margin: 0 auto;
    margin-bottom: 10px;
}
.txtarea{
    width: 320px;
    margin-top:10px;
    margin-bottom: 10px;
}


.txtarea2 {
  width: 320px;
    margin-top:10px;
    margin-bottom: 10px;
}

#txtarea3{
    width: 320px;
}

</style>

<body>
    <form class="form no-mr clearfix" action="<?php echo Yii::app()->baseUrl; ?>/accessrolemaster/setroles" method="post" id="allocateEmployee"> 
        <input type="hidden" name ="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken; ?>"  />

        <div class="txtarea">
            <h1>Select Manager</h1>
            <?php 
            

            $employeeData = AccessRoleMaster::model()->get_manager_list();

            $emp_list = array();
            foreach ($employeeData as $key => $value) {
                $emp_list[$value['emp_id']] = $value['first_name'] . " " . $value['last_name'];
            } 
            echo CHtml::dropDownList("txtarea1", 'id', $emp_list);
            ?>       
        </div>

        <div class="txtarea2">
            <h1>Available Resource</h1>
            <?php  $employeeData = Employee::model()->findAll(array('select' => "emp_id,first_name,last_name", 'order' => 'first_name', 'condition' => 'is_active=1 and is_password_changed="yes"'));
            $emp_list = array();
            foreach ($employeeData as $key => $value) {
                $emp_list[$value['emp_id']] = $value['first_name'] . " " . $value['last_name'];
            } 
            echo CHtml::dropDownList("txtarea3", 'id', $emp_list, array('multiple' => 'multiple', 'style' => 'height:200px;margin-top:20px;'));
            ?>       
        </div>
        <div class="submitButton">
        <?php echo CHtml::submitButton('Done', array('id' => 'allocate', 'style' => 'margin-top:20px;')); ?>
        </div>
</form>
</body>

</html>




