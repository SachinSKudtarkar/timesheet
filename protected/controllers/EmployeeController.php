<?php

class EmployeeController extends BaseController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column1', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';
    public $access_type = array('A', 'E');

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
                //'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        /**
         * Specifies the access control rules.
         * This method is used by the 'accessControl' filter.
         * @return array access control rules
         */
        return array(
            array('allow', // allow 'index' actions
                'actions' => array('index'),
                'users' => array('@'),
            ),
            array('allow', // allow 'index' actions
                'actions' => array('create', 'getroles','admin'),  
		'users' => array('@'),				
            ),
            array('allow', // allow 'index' actions
                'actions' => array('ImportFieldEngineers', 'Getfailedentriesoffieldengineers', 'ViewFieldEngineerList','GetResourceName'),
            ),
            array('allow', // allow 'index' actions
                'actions' => array('update', 'getroles'),
                'users' => array('@'),
            ),
            array('allow', // allow 'index' actions
                'actions' => array('delete'),
                'users' => array('@'),
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
        ini_set('display_errors', 1);
error_reporting(E_ALL);
        $model = new Employee('s');
        $model->access_type = 0;
        $model_employee_details = new EmployeeDetail('create');
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Employee']) && isset($_POST['EmployeeDetail'])) {
            
            $model->attributes = $_POST['Employee'];
            // set new attributes of user details
            $model_employee_details->attributes = $_POST['EmployeeDetail'];

            $model->setAttribute("is_active", 1); 
            // save in employee table & check

            $validate_emp = $model->validate();
            $validate_emp_details = $model_employee_details->validate();

            if ($validate_emp && $validate_emp_details) {
                $model->password = md5($_POST['Employee']['password']);
                $model->change_password = 0;
                $model->save(FALSE);

                $model_employee_details->emp_id = $model->emp_id;
                // Save user details
                $model_employee_details->save();

//				
//				#--------------------------------------------
//				# Set FlasMessage And Redirect on Listing page
//				#--------------------------------------------
                CHelper::setFlashSuccess("Employee record created.");
                $this->redirect(array('admin'));
            }else{
                CHelper::setFlashSuccess("Employee record not created.");
            }
        }
        $this->render('create', array(
            'model' => $model,
            'model_employee_details' => $model_employee_details,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        //$model=$this->loadModel($id);
        $join_table = array('EmployeeDetail');
        // Load related model
        $model = $this->loadModelRelation($id, $join_table, 'update');

        if (!$model = $this->loadModelRelation($id, $join_table, 'update')) {

            CHelper::setFlashError("Record not found.");
            $this->redirect(array('index'));
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Employee'])) {
            $tobe_saved = array('email', 'first_name', 'middle_name', 'last_name', 'access_type');

            $model[0]->attributes = $_POST['Employee'];
            if (isset($_POST['Employee']['password']) && !empty($_POST['Employee']['password'])) {
                array_push($tobe_saved, 'password');
            }

            //$model->is_deleted = ($is_access_right_set?0:1);
            if ($model[0]->validate()) {
                if (in_array('password', $tobe_saved)) {
                    $model[0]->password = md5($model[0]->password);
                    if (!empty(Yii::app()->session['login']['user_id']) && $model[0]->emp_id != Yii::app()->session['login']['user_id']) {
                        $model[0]->change_password = 0;
                    }
                    array_push($tobe_saved, 'change_password');
                }
                $model[0]->save(false, $tobe_saved);
                if (isset($_POST['EmployeeDetail'])) {
                    $employee_detail = new EmployeeDetail();
                    $employee_detail = $model[0]->EmployeeDetail[0];
                    $employee_detail->attributes = $_POST['EmployeeDetail'];
                    $employee_detail->save();
                }
                #--------------------------------------------
                # Set FlasMessage And Redirect on Listing page
                #--------------------------------------------
                CHelper::setFlashSuccess("Employee record updated.");
                $this->redirect(array('index', 'id' => $model[0]->emp_id));
            } // End if Save
            else {
                
            }
        }// End User POST

        $this->render('update', array(
            'model' => $model[0],
            'model_employee_details' => $model[0]->EmployeeDetail[0],
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        //$this->loadModel($id)->delete();
        Employee::model()->updateByPk($id, array('is_deleted' => 1)); 
//		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        // checking request type                
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'delete') {
            // If ajax request then just return message                    
            echo CHelper::setFlashSuccess("Employee record deleted.");
            Yii::app()->end();
        } else {
            // For normal request page will redirect and flash message will set                    
            CHelper::setFlashSuccess("Employee record deleted.");
            $this->redirect(array('/employee/index/'));
        }
    }

    public function actionGetroles() {
        $id = $_POST['id'];
        $model = Roles::model()->findByPK($id);
        //$this->randerPartial('//roles/update','')
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $model = new Employee('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Employee']))
            $model->attributes = $_GET['Employee'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Employee('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Employee']))
            $model->attributes = $_GET['Employee'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Employee the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Employee::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Employee $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'employee-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Returns the related data model based on the user_id given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModelRelation($id, $join_table = array(), $rule = '') {
        $criteria = new CDbCriteria;
        if (is_array($join_table) && count($join_table) > 0) {
            $criteria->with = $join_table;
        }
        $criteria->group = 't.emp_id';
        $criteria->distinct = true;
        $criteria->condition = "t.emp_id='" . $id . "' ";
        $model = Employee::model()->findAll($criteria);

        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionImportFieldEngineers() {
        $model = new Employee;
        if (isset($_POST['Employee'])) {
            $model->attributes = $_POST['Employee'];
            $model->file = CUploadedFile::getInstance($model, 'file');
            if (isset($model->file)) {
                if ($model->file->type == 'application/download' || $model->file->type == 'application/vnd.ms-excel' || $model->file->type == 'application/octet-stream' || $model->file->type == 'text/csv' || $model->file->type == 'application/csv') {
                    $file = fopen($model->file->tempName, "r");
                    $headerFlag = true;
                    $rowCount = 0;
                    $rowsNotUploaded[] = array('First Name', 'Last Name', 'Contact Number');
                    $rowsUploaded = array();
                    while (($dataArray = fgetcsv($file, 1000, ",")) !== FALSE) {
                        if ($headerFlag) {//@code to skip header line of csv
                            $headerFlag = false;
                            continue;
                        }
                        if (!empty($dataArray)) {
                            $emptyFlag = true;
                            $i = 0;
                            foreach ($dataArray as $val) {
                                $dataArray[$i] = trim(preg_replace('/[^A-Za-z0-9\-]/', '', $dataArray[$i]));
                                if (empty($dataArray[$i]) || !isset($dataArray[$i]) || $dataArray[$i] == NULL)
                                    $emptyFlag = false;
                                $i++;
                            }
                            if ($emptyFlag) {
                                $criteria = new CDbCriteria;
                                $criteria->join = 'INNER JOIN tbl_employee_detail ON (tbl_employee_detail.emp_id = t.emp_id)';
                                $criteria->condition = '(tbl_employee_detail.mobile=:contact_number OR tbl_employee_detail.phone=:contact_number) AND (t.access_type = 200)';
                                $criteria->params = array(':contact_number' => trim($dataArray[2]));
                                $modelEmployee = Employee::model()->find($criteria);

                                if (!isset($modelEmployee)) {
                                    $modelEmployee = new Employee;
                                    $modelEmployee->access_type = 200;
                                    $modelEmployee->created_date = new CDbExpression('now()');
                                }
                                $modelEmployee->first_name = trim($dataArray[0]);
                                $modelEmployee->last_name = trim($dataArray[1]);
                                $modelEmployee->email = trim($dataArray[0]) . "_" . trim($dataArray[1]) . "_" . time() . rand(10000, 99999);
                                $modelEmployee->modified_date = new CDbExpression('now()');
                                $modelEmployee->field_engg_added_by = Yii::app()->session['login']['user_id'];

                                if ($modelEmployee->save(false)) {
                                    $modelEmpDetails = EmployeeDetail::model()->findByAttributes(array('emp_id' => $modelEmployee->emp_id));

                                    if (!isset($modelEmpDetails)) {
                                        $modelEmpDetails = new EmployeeDetail;
                                        $modelEmpDetails->emp_id = $modelEmployee->emp_id;
                                    }
                                    $modelEmpDetails->mobile = trim($dataArray[2]);
                                    $modelEmpDetails->modified_date = new CDbExpression('now()');
                                    if ($modelEmpDetails->save(false)) {
                                        $rowsUploaded[] = $modelEmployee->emp_id;
                                    }
                                }
                            } else {
                                $rowsNotUploaded[] = $dataArray;
                            }
                        }
                    }
                    fclose($file);
                    if (!empty($rowsUploaded))
                        Yii::app()->user->setFlash('success', 'Field Engineer(s) Data Uploaded Successfully.');
                    $this->render('//employee/view_ploaded_field_engineers', array(
                        'model' => new Employee,
                        'rowsUploaded' => $rowsUploaded,
                        'rowsNotUploaded' => $rowsNotUploaded,
                    ));
                    yii::app()->end();

//                    Yii::app()->user->setFlash('success', 'Field Engineer(s) Data Uploaded Successfully.');
//                    $this->actionPost("ViewFieldEngineerList", array(
//                        Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken,
//                        'rowsUploaded' => $this->formatData($rowsUploaded, true),
//                        'rowsNotUploaded' => $this->formatData($rowsNotUploaded, true)
//                    ));
                }
            } else {
                Yii::app()->user->setFlash('error', 'File not found.');
            }
        }

        $this->render('//employee/upload_field_engineers', array(
            'model' => $model,
        ));
    }

    public function actionGetFailedEntriesOfFieldEngineers() {
        $data = unserialize($_REQUEST['data']);
        $output = "";
        foreach ($data as $row) {
            foreach ($row as $val) {
                $output .='"' . $val . '",';
            }
            $output .="\n";
        }
        // Download the file
        $filename = "Field_engineers_data.csv";
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);

        echo $output;
        exit;
    }

    public function actionViewFieldEngineerList() {
        //CHelper::debug($_REQUEST);
        $this->render('//employee/view_ploaded_field_engineers', array(
            'model' => new Employee,
            'rowsUploaded' => $this->formatData($_REQUEST['rowsUploaded']),
            'rowsNotUploaded' => $this->formatData($_REQUEST['rowsNotUploaded']),
        ));
    }

    public function actionPost($action, $data = array()) {
        ?><html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <script type="text/javascript">
                    function closethisasap() {
                        document.forms["redirectpost"].submit();
                    }
                </script>
                <body onload="closethisasap();">
                    <form name="redirectpost" method="post" action="<?php echo $action; ?>" >
                        <?php
                        if (!is_null($data)) {
                            foreach ($data as $k => $v) {
                                ?><input type="hidden" name="<?php echo $k; ?>" value="<?php echo $v; ?>" /><?php
                            }
                        }
                        ?>
                    </form>
                </body>
            </head>
        </html>
        <?php
        exit();
    }

    public function formatData($data, $encode = false) {
        if ($encode) {
            return base64_encode(serialize($data));
        }
        return unserialize(base64_decode($data));
    }
	
	public function actionGetResourceName($id){
	
	$model = Employee::model()->findByPk($id);
	echo $model->first_name." ".$model->last_name;
	exit;
	return $model->first_name." ".$model->last_name;
	}

}
