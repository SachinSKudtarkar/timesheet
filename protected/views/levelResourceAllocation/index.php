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

<h1>Resource Allocation Project Works</h1>

<!--<table cellspacing='1' cellpadding='0' border= 1px black cellpadding='0' cellspacing='0' width="90%">
    <thead>
    <th>Pid</th>
     <th>Project Name</th>
     <th>Monday<?php echo "<br>"; echo CHTML::radioButton('rd_day'); ?></th>
      <th>Tuesday<?php echo "<br>";  echo CHTML::radioButton('rd_day'); ?></th>
      <th>Wednesday<?php echo "<br>";   echo CHTML::radioButton('rd_day'); ?></th>
      <th>Thursday<?php echo "<br>";   echo CHTML::radioButton('rd_day'); ?></th>
      <th>Friday<?php echo "<br>";   echo CHTML::radioButton('rd_day'); ?></th>
      <th>Saturday<?php echo "<br>";   echo CHTML::radioButton('rd_day'); ?></th>
      <th>Sunday<?php echo "<br>";   echo CHTML::radioButton('rd_day'); ?></th>
</thead>
<tbody>
    
<?php 

        foreach($data as $indiData)
        {
 ?>

 <tr>
     <td style="margin:5px;"><?php //echo $indiData['pid']; ?></td>
      <td><?php //echo $indiData['project_name']; ?></td>
      <td></td>
      <td> </td>
      <td> </td>
      <td> </td>
      <td> </td>
      <td> </td>
      <td> </td>

 </tr>
<?php
        }
        ?>
    </tbody>
</table>-->


<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
