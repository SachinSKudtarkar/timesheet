<?php
/* @var $this EmployeeController */
/* @var $model Employee */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'roles-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'clientOptions' => array('validateOnSubmit' => true, 'validateOnChange' => true,),
        //'focus'=>array($model,'first_name'),
        ));
?>
<div class="form">
    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 100)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>
    <div class="span3 offset5">
        <div class="form-group">

            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'label' => $model->isNewRecord ? 'Create' : 'Save',
                'htmlOptions' => array(
                    'class' => 'btn-primary',
                ),
            ));
            ?>             
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'link',
                'url' => array('/roles/index'),
                'label' => $model->isNewRecord ? 'Cancel' : 'Back',
                'htmlOptions' => array(
                    'class' => 'btn-primary',
                ),
            ));
            ?>
            <?php
            if ($model->id != '') {
                $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'button',
                    'label' => 'Duplicate',
                    'htmlOptions' => array(
                        'class' => 'btn-warning duplicate',
                        'id' => $model->id,
                    ),
                ));
            }
            ?>
        </div>
    </div>
    <?php
// check created by && Super admin can only access this portion
    /* if( CHelper::isAccess('SA', 'admin.SubAdmin.AccessRights') )
      { */
    ?>
    <table cellspacing="0" cellpadding="0" border="0" class="table table-bordered text-center access_rights">
        <tbody>
            <tr>
                <th class="tble-txt-hd" colspan="8">Access Rights </th>
            </tr>
            <?php
            // Criteria to get all access rights
            $criteria = new CDbCriteria;
            $criteria->select = array('id', 'type', 'heading');
            $criteria->addCondition('t.is_disabled = 0');
            $criteria->addCondition('accessRightDetails.is_disabled = 0');
            // Super admin access
            // $criteria->addCondition('t.type in("A","E")');
            $criteria->with = array('accessRightDetails');
            $criteria->order = 'heading_order, accessRightDetails.menu_order ASC';

            // Retrive all criteria
            $access_rights = AccessRightMaster::model()->findAll($criteria);
//            echo "<xmp>";
//            print_r($access_rights);
//            echo "</xmp>";
            // check is access on post or not
            $on_post = ( isset($_POST) && array_key_exists('access_rights', $_POST) ) ? $_POST['access_rights'] : array();

            // Get default selected access
            $selected_access = ( $model->access_rights ) ? unserialize($model->access_rights) : $on_post;
            /// $selected_access = array();
            // count first
            $max_row = count($access_rights[0]->accessRightDetails);
            // render all access
            foreach ($access_rights as $access_right) {
                $access = CHtml::listData($access_right->accessRightDetails, 'value', 'name');
                if ($model->isNewRecord) {
                    $selected = array_key_exists($access_right->type, $selected_access) ? $selected_access[$access_right->type] : ( $model->isNewRecord ? array() : array_keys($access));
                } else {
                    $selected = array_key_exists($access_right->type, $selected_access) ? $selected_access[$access_right->type] : ( $model->isNewRecord ? array_keys($access) : array());
                }

                echo '<tr><th width="18%">' . $access_right->heading . '</th>';
                // manage grid
                $count = ($max_row - count($access) );
                if (count($access) < $max_row) {
                    for ($i = 0; $i < $count; $i++) {
                        $access['noitem_' . $i] = '';
                    }
                }

                echo CHtml::checkBoxList('access_rights[' . $access_right->type . '][]', $selected, $access, array('template' => '<td width="11%"><div style="width:13px; margin:0 auto;">{input}</div><br>{label}</td>', 'separator' => '', 'container' => '')
                );
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
    <?php // }     ?>
    <div class="span3 offset5">
        <div class="form-group">

            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'label' => $model->isNewRecord ? 'Create' : 'Save',
                'htmlOptions' => array(
                    'class' => 'btn-primary',
                ),
            ));
            ?>             
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'link',
                'url' => array('/roles/index'),
                'label' => $model->isNewRecord ? 'Cancel' : 'Back',
                'htmlOptions' => array(
                    'class' => 'btn-primary',
                ),
            ));
            ?>
            <?php
            if ($model->id != '') {
                $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'button',
                    'label' => 'Duplicate',
                    'htmlOptions' => array(
                        'class' => 'btn-warning duplicate',
                        'id' => $model->id,
                    ),
                ));
            }
            ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->
<?php
CHelper::registerScript('generateAccess', "
/* Control Access Rights */
$('.access_rights td input').change(function(){
	var view = $(this).parents('tr').find('input[value$=index]');
    var checked_length = $(this).parents('tr').find('input:checked').length;
    var current_value = $(this).val().indexOf('index');
    
    if( ( !view.is(':checked') &&  current_value == -1 ) || checked_length >= 1 ) {
        view.prop('checked', true);
    }else if( checked_length <= 0 ){
        view.prop('checked', false);
    }

});
$('.access_rights input[value^=noitem_]').parents('td').html('<p align=\"center\">-</p>');
"
        , CClientScript::POS_READY);
?>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
    'id' => 'dialogForm',
    'options' => array(
        'title' => 'Create Duplicate Roles',
        'autoOpen' => false,
        'modal' => true,
        'width' => 500,
        'height' => 200,
    ),
));
?>
<div class="duplicateForm">

</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $(".duplicate").click(function () {
            var id = this.id;
            loadForm("<?php echo Yii::app()->createUrl('roles/duplicate') ?>" + "/" + id);
        });

        function loadForm(url) {
            $('#dialogForm').dialog('close');
            $('#dialogForm').dialog('open');
            jQuery.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                success: function (data)
                {
                    if (data.status == 'failure')
                    {
                        $('#dialogForm div.duplicateForm').html(data.html);
                    }

                }
            });
            return false;
        }
    });
</script>
