
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'dashboard-grid',
	'dataProvider'=>$model->dashboard_listing(),
	
	'columns'=>array(
              array(
                'header' => '',
                'name' => 'grouping_column',
                'value'=>'$data->grouping_column'
               
            ),
            array(
                'header' => 'ENB status(Pending/Completed)',
                'name' => 'enb_status',
                'type' => 'raw',
                'value'=>'$data->enb_status ."/". $data->enb_status_pending'
               
            ),
            array(
                'header' => 'Utility Aaddress Status (Pending/Completed)',
                'name' => 'utility_addr_status',
                'value'=>'$data->utility_addr_status ."/". $data->utility_addr_status_pending'
            ),
            array( 
                'header' => 'Microwave Status (Pending/Completed)',
                'name' => 'mw_status',
                'value'=>'$data->mw_status ."/". $data->mw_status_pending'
              ),
            array(
                'header' => 'Other Status (Pending/Completed)',
                'name' => 'other_status',
                'value'=>'$data->other_status ."/". $data->other_status_pending'
            )
	),
)); ?>
