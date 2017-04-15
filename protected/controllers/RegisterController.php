<?php

class RegisterController extends BaseLoginController {

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
        
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        
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

    public function actions() {

        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xEEF5F7,
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Employee('create');
        $model_employee_details = new EmployeeDetail('create');
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        //$model->scenario = 'captchaRequired';
        if (isset($_POST['Employee']) && isset($_POST['EmployeeDetail'])) {

            $model->attributes = $_POST['Employee'];
            // set new attributes of user details
            $model_employee_details->attributes = $_POST['EmployeeDetail'];

            $model->setAttribute("is_active", 1);
            if (isset($_POST['access_rights'])) {
                $access_rights = isset($_POST['access_rights']) && count($_POST['access_rights']) ? $_POST['access_rights'] : array();
                $model->access_rights = serialize($access_rights);
            }
           
            //$model->change_password = 1;
            if ($model->validate()) {
                
                // save in employee table & check
                $model->password = md5($_POST['Employee']['password']);
                $model->save(false);
                
                if (isset($_POST['EmployeeDetail'])) {
                    // Set user id
                    $model_employee_details->emp_id = $model->emp_id;
                    // Save user details
                    $model_employee_details->save();
                }
//				
//				#--------------------------------------------
//				# Set FlasMessage And Redirect on Listing page
//				#--------------------------------------------
                CHelper::setFlashSuccess("Employee record created.");
                $this->redirect(array('index'));
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
        $model = $this->loadModel($id);
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
            $tobe_saved = array('first_name', 'middle_name', 'last_name', 'access_rights', 'access_type');

            $model[0]->attributes = $_POST['Employee'];
            if (isset($model[0]->password) && !empty($model[0]->password)) {

                array_push($tobe_saved, 'password');
            }

            if (isset($_POST['access_rights'])) {
                $access_rights = isset($_POST['access_rights']) && count($_POST['access_rights']) ? $_POST['access_rights'] : array();
                $model[0]->access_rights = serialize($access_rights);
            }
            //$model->is_deleted = ($is_access_right_set?0:1);
            if ($model[0]->save(true, $tobe_saved)) {
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
        $this->loadModel($id)->delete();
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

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $model = new Employee('register');
        $model_employee_details = new EmployeeDetail('register');
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);
        if (isset($_POST['Employee']) && isset($_POST['EmployeeDetail'])) {

            $model->attributes = $_POST['Employee'];
            // set new attributes of user details
            $model_employee_details->attributes = $_POST['EmployeeDetail'];

            //echo '<pre>';print_r($model);exit;
            $model->setAttribute("is_active", 1);
            $validate_emp = $model->validate();
            //CHelper::debug($model->getErrors());
            $validate_emp_details = $model_employee_details->validate();

            if ($validate_emp && $validate_emp_details) {
                // save in employee table & check
                $model->password = md5($_POST['Employee']['password']);
                $model->change_password = 1;
                if ($model->save(false)) {
                    if (isset($_POST['EmployeeDetail'])) {
                        // Set user id
                        $model_employee_details->emp_id = $model->emp_id;
                        // Save user details
                        $model_employee_details->save();
                    }
 
                    CHelper::setFlashSuccess("Registration successfull.");
                    $this->redirect(array('//login'));
                }
            }
        }
        $this->render('create', array(
            'model' => $model,  
            'model_employee_details' => $model_employee_details,
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
        if (isset($_POST['ajax']) && in_array($_POST['ajax'], array('employee-form', 'registration-form'))) {
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

}
