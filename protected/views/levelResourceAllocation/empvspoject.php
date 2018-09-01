<<<<<<< HEAD
<?php
// Page: site summary page
// Developed on: 15/10/2014
// Developed by: Gorakh Wagh
// Purpose: Show graphical representation of sites competed data
?>
<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Resource Allocation Project Works',
);

$this->menu=array(
	array('label'=>'Create ResourceAllocationProjectWork', 'url'=>array('create')),
	array('label'=>'Manage ResourceAllocationProjectWork', 'url'=>array('admin')),
        array('label'=>'View Resource Statistics', 'url'=>array('resourcemanagement')),
        array('label'=>'Resource Arrangement', 'url'=>array('resourcearrangement')),
);

 
?>
 
        <div class="form-group clearfix test-panal" style="margin-top: 20px;">
             <h4>Resources Availability</h4>    
           
              <div class="hr-line"> <span class=""></span></div>
              
             <?php 
             
            $tblString = "<table width='100%' style='margin: 0 auto; text-align:center; border-collapse:collapse;'  >"
                    . "<thead  bgcolor='#CD853F' align='left' style='color:white; '>";
           // $tblString .= "<th bgcolor='#CD853F' align='center' style='color:white; ' width='10%'>Employee ID</th>"
            $tblString .= "<th bgcolor='#CD853F'   style='color:white; ' width='20%'>Employee Name</th>"
                    . "<th bgcolor='#CD853F' align='left' style='color:white; ' width='60%'>Projects</th>"
                    . "<th bgcolor='#CD853F' align='left' style='color:white; '>Estimated Dates</th>"
                    . "<th bgcolor='#CD853F' align='left' style='color:white; '>Days Remained</th>";
                     
            $tblString .= "</thead><tbody style='font-size:14px;'>";
             foreach($showdata as $indidata) {  
                 
                 $infi_id = $indidata['id'];
                 $tblString .= "<tr bgcolor='#FFDAB9' style='border-bottom: 1px solid #ccc;'>"
                         . "<td align='left'>" . $indidata['emp_name'] . "</td>"; 
                $tblString .= "<td align='left' style='font-size:12px;'>" . $indidata['projects'] . "</td>";  
                $tblString .= "<td align='left' style='font-size:12px;'><b>" .$indidata['estimated_date']. "</b></td>";  
                $tblString .= "<td align='left' style='font-size:12px; color:red'><b>" .$indidata['days_remained']. "</b></td>";  
                $tblString .= "</tr>"; 
                 
             } 
             
             echo $tblString;
             ?>
             
             
              </div>

   

 
        
    
=======
<?php
// Page: site summary page
// Developed on: 15/10/2014
// Developed by: Gorakh Wagh
// Purpose: Show graphical representation of sites competed data
?>
<?php
/* @var $this ResourceAllocationProjectWorkController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Resource Allocation Project Works',
);

$this->menu=array(
	array('label'=>'Create ResourceAllocationProjectWork', 'url'=>array('create')),
	array('label'=>'Manage ResourceAllocationProjectWork', 'url'=>array('admin')),
        array('label'=>'View Resource Statistics', 'url'=>array('resourcemanagement')),
        array('label'=>'Resource Arrangement', 'url'=>array('resourcearrangement')),
);

 
?>
 
        <div class="form-group clearfix test-panal" style="margin-top: 20px;">
             <h4>Resources Availability</h4>    
           
              <div class="hr-line"> <span class=""></span></div>
              
             <?php 
             
            $tblString = "<table width='100%' style='margin: 0 auto; text-align:center; border-collapse:collapse;'  >"
                    . "<thead  bgcolor='#CD853F' align='left' style='color:white; '>";
           // $tblString .= "<th bgcolor='#CD853F' align='center' style='color:white; ' width='10%'>Employee ID</th>"
            $tblString .= "<th bgcolor='#CD853F'   style='color:white; ' width='20%'>Employee Name</th>"
                    . "<th bgcolor='#CD853F' align='left' style='color:white; ' width='60%'>Projects</th>"
                    . "<th bgcolor='#CD853F' align='left' style='color:white; '>Estimated Dates</th>"
                    . "<th bgcolor='#CD853F' align='left' style='color:white; '>Days Remained</th>";
                     
            $tblString .= "</thead><tbody style='font-size:14px;'>";
             foreach($showdata as $indidata) {  
                 
                 $infi_id = $indidata['id'];
                 $tblString .= "<tr bgcolor='#FFDAB9' style='border-bottom: 1px solid #ccc;'>"
                         . "<td align='left'>" . $indidata['emp_name'] . "</td>"; 
                $tblString .= "<td align='left' style='font-size:12px;'>" . $indidata['projects'] . "</td>";  
                $tblString .= "<td align='left' style='font-size:12px;'><b>" .$indidata['estimated_date']. "</b></td>";  
                $tblString .= "<td align='left' style='font-size:12px; color:red'><b>" .$indidata['days_remained']. "</b></td>";  
                $tblString .= "</tr>"; 
                 
             } 
             
             echo $tblString;
             ?>
             
             
              </div>

   

 
        
    
>>>>>>> ca220980247a9c3a8f585c829fff41deab1c5ac4
