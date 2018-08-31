<?php
/*   error_reporting(E_ALL);
ini_set("display_startup_errors","1");
ini_set("display_errors","1");  */
class LevelResourceAllocationController extends Controller {


    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'create_task', 'update', 'allocate', 'getPname', 'fetchAllocatedResource',  'getPId', 'getallocatedResource',  'resourceAllocatedtask'),
//                'expression' => 'CHelper::isAccess("RESOURCEALLOCATION", "full_access")',
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
//                'expression' => 'CHelper::isAccess("RESOURCEALLOCATION", "full_access")',
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new LevelResourceAllocation;
        $dataProvider = new CActiveDataProvider('LevelResourceAllocation');
		
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);


        if (isset($_POST['LevelResourceAllocation'])) {
            $model->attributes = $_POST['LevelResourceAllocation'];
            $model->modified_by = Yii::app()->session['login']['user_id'];
            $model->modified_at = date('Y-m-d H:i:s');
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $getData = $model->projectStatus();  // Project Name and daily Status

        $this->render('create', array('model' => $model, 'data' => $getData, 'dataProvider' => $dataProvider));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ResourceAllocationProjectWork'])) {
            $model->attributes = $_POST['ResourceAllocationProjectWork'];
            $model->modified_by = Yii::app()->session['login']['user_id'];
            $model->modified_at = date('Y-m-d H:i:s');
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('ResourceAllocationProjectWork');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new LevelResourceAllocation('search');
		
        $model->unsetAttributes();  // clear any default values               
        if (isset($_GET['LevelResourceAllocation']))
            $model->attributes = $_GET['LevelResourceAllocation'];
	
        $this->render('admin', array(
            'model' => $model,
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ResourceAllocationProjectWork the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = ResourceAllocationProjectWork::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ResourceAllocationProjectWork $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'resource-allocation-project-work-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAllocate() {
	
        $level_id = isset($_POST['LevelName']) ? $_POST['LevelName'] : 0;

        $_POST['txtarea2'] = array_filter($_POST['txtarea2']);
//        $all_resources = isset($_POST['txtarea2']) ? implode(',', $_POST['txtarea2']) : '';
		$resourceIds = $_POST['txtarea2'];
		$modified_by = Yii::app()->session['login']['user_id'];
        $modified_at = date('Y-m-d H:i:s');
		if($resourceIds)
		{
			$sql = "DELETE FROM tbl_assign_resource_level WHERE level_id = :level_id";
			$parameters = array(":level_id" => $level_id);
			Yii::app()->db->createCommand($sql)->execute($parameters);
			foreach($resourceIds as $resource_id)
			{
				$query = "SELECT count(*) as count FROM tbl_assign_resource_level WHERE emp_id = '{$resource_id}'";
				$result = YII::app()->db->createCommand($query)->queryRow();
				
				
				if($result['count'] > 0)
				{
				
					$updateQuery = "UPDATE tbl_assign_resource_level SET level_id = '{$level_id}', modified_by ='{$modified_by}' , updated_at = '{$modified_at}'   WHERE emp_id = '{$resource_id}'  ";
					Yii::app()->db->createCommand($updateQuery)->execute();
					$done = 1;
				}else{
					
					$sql = "insert into tbl_assign_resource_level (emp_id,level_id,created_by,created_at) values (:emp_id,:level_id, :created_by, :date)";
					$parameters = array(":emp_id" => $resource_id, ":level_id" => $level_id, ":date" => date('Y-m-d H:i:s'), ":created_by" => Yii::app()->session['login']['user_id']);
					Yii::app()->db->createCommand($sql)->execute($parameters);
					$done = 1;
				}
			}
			
		}
		


        if ($done) {


            if (empty($resourceIds)) {
                Yii::app()->user->setFlash('success', "Resources are deallocated Successfully.");
            } else {
                Yii::app()->user->setFlash('success', "Resources allocated Successfully.");
            }

            $this->redirect(array('levelresourceallocation/admin'));
        } else {
            Yii::app()->user->setFlash('error', "Resources allocation failed. Please select level and allocate resource properly.");
            $this->redirect(array('levelresourceallocation/create'));
        }
    }

    public  function getLevel($model){
		$name = LevelMaster::model()->findByPk($model->level_id);
		
		return $name['level_name'];
	}
	
	public  function getEmployee($model){
		$name = Employee::model()->findByPk($model->emp_id);
		
		return ucwords($name['first_name'].' '.$name['last_name']);
	}

    public function sendEmail($message, $emails, $project_name) {
        $from = "support@infinitylabs.in";
        $from_name = "Infinity Team";

        $to = array();
        $cc = array();
        //$to[] = array("email" => "kpanse@cisco.com", "name" => "Krishnaji");
        $to[] = array("email" => "pm@infinitylabs.in", "name" => "PM");


        foreach ($emails as $indiemails) {
            $cc[] = array("email" => $indiemails['email'], "name" => "");
        }

        $subject = "Resource(s) Allocated For the Project " . $project_name;
        return CommonUtility::sendmail($to, null, $from, $from_name, $subject, $message, $cc, null);
    }

    public function getUserName($id) {
        $name = Yii::app()->db->createCommand()
                ->select('CONCAT(first_name," ",last_name) as full_name')
                ->from('tbl_employee')
                ->where('emp_id=:id', array(':id' => $id))
                ->queryRow();

        return $name['full_name'];
    }

    public function actionfetchAllocatedResource() {
        $level_id = isset($_REQUEST['level_id']) ? $_REQUEST['level_id'] : 0;
        echo $data = $this->getAllocatedResource($level_id);
    }


    public function getAllocatedResource($level_id) {
	
        $query = "SELECT emp_id FROM tbl_assign_resource_level WHERE level_id ={$level_id}";
        $resources = Yii::app()->db->createCommand($query)->queryAll();
		$emp_ids = implode(',', array_map(function ($entry) {
					return $entry['emp_id'];
					}, $resources));;
		
        if (!empty($resources)) {
            $query = 'SELECT emp_id,CONCAT(first_name," ",last_name) as full_name FROM tbl_employee WHERE emp_id IN(' . $emp_ids . ') and is_active = 1 and is_password_changed="yes"';
            $empDetails = Yii::app()->db->createCommand($query)->queryAll();
            $rtrnstringarray = array();
            foreach ($empDetails as $indidetails) {
                $rtrnstringarray[] = $indidetails['emp_id'] . "==" . ucwords($indidetails['full_name']);
            }
            return $rtrnstring = implode(',', $rtrnstringarray);
        } else
            return 0;
    }


    public function actionGetPId() {
        $query = "select spid,sub_project_name from tbl_sub_project where pid ={$_POST['pid']}";
        $data = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $name['spid']), CHtml::encode($name['sub_project_name']), true);
        }
    }

    public function actionGetallocatedResource() {
        $query = "select allocated_resource from tbl_resource_allocation_project_work  where pid ={$_REQUEST['pid']}";
        $allocated_resource = Yii::app()->db->createCommand($query)->queryRow();

        $query1 = "select emp_id,concat(first_name,' ',last_name) as name from tbl_employee  where emp_id in ({$allocated_resource['allocated_resource']}) order by first_name";
        $resource = Yii::app()->db->createCommand($query1)->queryAll();

        foreach ($resource as $value => $name) {
            echo CHtml::tag('option', array('value' => $name['emp_id']), CHtml::encode($name['name']), true);
        }
    }





    

}
