<?php
/* @var $this NddCoreWanController */
/* @var $model NddCoreWan */

/*$this->breadcrumbs = array(
    'Lic Install Tool' => array('create'),
    'Manage',
);*/

/*$this->menu = array(
    array('label' => 'Upload Input File', 'url' => array('create')),
    array('label' => 'Manage Lic Install Tool', 'url' => array('admin')),
);*/

Yii::app()->clientScript->registerScript("search", "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#lic-install-tool-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});

$('#updateprocess').click(function()
{ 
   $('table.items tr').each(function()
   {
        var obj = $(this).find('td:nth-child(5)');
        if(obj.text()==='unprocess')
           {
                obj.html('<img src=\"../images/loading.gif\">'); 
                $.ajax({
                url: '" . CHelper::createUrl("LicInstallTool/updateprocess") . "',
                type: 'POST',
                async:false,
                data:{'parseid':$(this).find('td:nth-child(1)').text(),'loopbacko':$(this).find('td:nth-child(2)').text(),'hostname':$(this).find('td:nth-child(3)').text(), 'ajax':1},
                success: function(data)
                { 
                    if(data)
                    {
                        obj.text(data);
                    }
                },
                error: function(XMLHttpRequest, data, errorThrown)
                {
                    if(XMLHttpRequest.responseText!=null)
                    {
                        alert(XMLHttpRequest.responseText);
                    }    
                    $('div.custom-loader').hide();
                    return false;
                },
             });
           }
       });
    });
");
?>

<h1> Lic install Tool List</h1>
<?php echo CHtml::link('Download',array('LicInstallTool/downloadData'), array('style'=>'text-decoration: underline;')); ?>
<div class="search-form" style="display:none">
<?php
$this->renderPartial('_search', array(
    'model' => $model,
));
?>
</div><!-- search-form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'lic-install-tool-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'enablePagination' =>false,
    'columns' => array(
        array(
        'id' => 'id',
        'type'=> 'raw',
        'value'=> '$data->id',
        'headerHtmlOptions'=>array('style'=>'width:0%; display:none'),
        'filterHtmlOptions'=>array('style'=>'width:0%; display:none'),
        'htmlOptions'=>array('style'=>'width:0%; display:none'),
        ),
        'loopbacko',
        'hostname',
        'file_name',
        'status',
        'date_time',
    /* array(
      'class' => 'CButtonColumn',
      ), */
    ),
));
?>
<table>
    <tr>
        <td>
            <?php echo CHtml::button('Run Utility', array('name'=>"updateprocess", 'id'=>"updateprocess", 'class'=>'btn-primary', 'style'=>'margin:35px 0px 0px 593px;')); 
             echo CHtml::hiddenField("ajax", '1'); ?>
        </td>
    </tr>
</table>