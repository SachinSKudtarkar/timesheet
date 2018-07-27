<?php

class RolesController extends BaseController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column1', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
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
                'expression' => 'CHelper::isAccess("EMPLOYEE","index")',
                'users' => array('@'),
            ),
            array('allow', // allow 'index' actions
                'actions' => array('create', 'duplicate', 'duplicateSave'),
                'expression' => 'CHelper::isAccess("EMPLOYEE","create")',
                'users' => array('@'),
            ),
            array('allow', // allow 'index' actions
                'actions' => array('update'),
                'expression' => 'CHelper::isAccess("EMPLOYEE","update")',
                'users' => array('@'),
            ),
            array('allow', // allow 'index' actions
                'actions' => array('delete',),
                'expression' => 'CHelper::isAccess("EMPLOYEE","delete")',
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
        $model = new Roles('create');
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Roles'])) {

            $model->attributes = $_POST['Roles'];
            // set new attributes of user details
            //$model->created_by = 1;

            if (isset($_POST['access_rights'])) {
                $access_rights = isset($_POST['access_rights']) && count($_POST['access_rights']) ? $_POST['access_rights'] : array();
                $model->access_rights = serialize($access_rights);
            }

            // save in employee table & check
            if ($model->save('name,access_rights,created_date,created_by', true)) {

//				#--------------------------------------------
//				# Set FlasMessage And Redirect on Listing page
//				#--------------------------------------------
                CHelper::setFlashSuccess("Roles created Successfully.");
                $this->redirect(array('index'));
            }
        }
        $this->render('create', array(
            'model' => $model,
        ));
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

        if (isset($_POST['Roles'])) {
            $model->attributes = $_POST['Roles'];
            // set new attributes of user details
            //$model->created_by = 1;

            if (isset($_POST['access_rights'])) {
                $access_rights = isset($_POST['access_rights']) && count($_POST['access_rights']) ? $_POST['access_rights'] : array();
                $model->access_rights = serialize($access_rights);
            }

            // save in employee table & check
            if ($model->save('name,access_rights,updated_date,modified_by', true)) {

//				#--------------------------------------------
//				# Set FlasMessage And Redirect on Listing page
//				#--------------------------------------------
                CHelper::setFlashSuccess("Roles Updated Successfully.");
                $this->redirect(array('index'));
            }
        }// End User POST

        $this->render('update', array(
            'model' => $model
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
            echo CHelper::setFlashSuccess("Role record deleted.");
            Yii::app()->end();
        } else {
            // For normal request page will redirect and flash message will set                    
            CHelper::setFlashSuccess("Role record deleted.");
            $this->redirect(array('/roles/index/'));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $model = new Roles('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Roles']))
            $model->attributes = $_GET['Roles'];

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
        $model = Roles::model()->findByPk($id);
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
        $model = Roles::model()->findAll($criteria);

        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionDuplicate($id = '') {
        $model = new Roles('duplicate');
        $this->performAjaxValidation($model);
        if (Yii::app()->request->isAjaxRequest) {
            $model->id = $id;
            Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            echo CJSON::encode(array(
                'status' => 'failure',
                'html' => $this->renderPartial('duplicate', array('model' => $model), true, true)));
            exit;
        }
    }

    public function actionDuplicateSave() {
        $model = new Roles('duplicate');
        $this->performAjaxValidation($model);
        if (!empty($_POST)) {
            $model->name = $_POST['Roles']['name'];
            $pre_model = $this->loadModel($_POST["Roles"]['id']);
            $model->access_rights = $pre_model->access_rights;
            $model->created_by = Yii::app()->session["login"]["user_id"];
            if ($model->save(false)) {
                Yii::app()->user->setFlash('success', "Roles Name " . $_POST['Roles']['name'] . " Created.");
                $this->redirect("update/$model->id");
            }
        } else {
            $this->redirect("update/" . $_POST['Roles']['id']);
        }
    }



}
