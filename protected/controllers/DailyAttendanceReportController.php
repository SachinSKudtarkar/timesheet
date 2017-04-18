<?php

class DailyAttendanceReportController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
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
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','admin','upload','downloadReport','getreport'),
				'users'=>array('@'),
			),			 
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new DailyAttendanceReport;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DailyAttendanceReport']))
		{
			$model->attributes=$_POST['DailyAttendanceReport'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DailyAttendanceReport']))
		{
			$model->attributes=$_POST['DailyAttendanceReport'];                        
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
        
        
        public function actionUpload() {
        $model = new DailyAttendanceReport('upload');     

        try {
            if (isset($_POST['DailyAttendanceReport'])) { 
                if ($model->validate()) {
                    $path = "uploads/attendanceReport";
                    $model->file_name = CUploadedFile::getInstance($model, 'file_name');
                    $fileName = date('dmYhis') . '_' . $model->file_name;
                    $filePath = $path . "/" . $fileName;
                    $model->file_name->saveAs($filePath, true);
                    $phpExcelPath = Yii::getPathOfAlias('ext.PHPExcel.Classes');
                    // Turn off our amazing library autoload 
                    spl_autoload_unregister(array('YiiBase', 'autoload'));
                    include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
                    /* Call the excel file and read it */
                    $inputFileType = PHPExcel_IOFactory::identify($filePath);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objReader->setReadDataOnly(true);
                    $objPHPExcel = $objReader->load($filePath);
                    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
                    $highestRow = $objWorksheet->getHighestRow();
                    $highestColumn = $objWorksheet->getHighestColumn();
                    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                    //$objWorksheet->setReadDataOnly(true);
                    $fromRow = 3; // to start getting records from second collumn 

                    $file_contents = $objWorksheet->getCellByColumnAndRow(0, 2)->getValue();  
               
                    
                    $date_from_exls = $objWorksheet->getCellByColumnAndRow(0, 1)->getValue(); 
                 
                    $dtarray = @explode(':',$date_from_exls);
                    $attendance_month_year = $dtarray[1];  
                    
                    if (strpos($file_contents, "Emp Code") === false) {
                        Yii::app()->user->setFlash('error', "Error 301: Template selection incorrect.");
                        $this->redirect(array('DailyAttendanceReport/upload'));
                    }



                    for ($row = $fromRow; $row <= $highestRow; ++$row) {
                        for ($col = 4; $col < $highestColumnIndex; ++$col) {
                            // $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getFormattedValue();
                            $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                            $key = $objWorksheet->getCellByColumnAndRow($col, 2)->getValue();
                            $empCode = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
                            $arraydata[$key][$empCode] = $value;
                        }
                    }

                  

                    spl_autoload_register(array('YiiBase', 'autoload'));
 
                    $importDate = date('Y-m-d H:i:s');
                    $importData = array();
                    $errors = array();
                    $i = 0;
                    $j = 0;                    
                

                    $arraydata = array_filter(array_map('array_filter', $arraydata));  // this will remove rows which are completely empty

                    //$arraydata = array_map("unserialize", array_unique(array_map("serialize", $arraydata)));
                    // above code will remove duplicacy in array
                    
                    
                    
                    $status = "";                     
                    foreach ($arraydata as $key =>$data) { 
                         $attendance_date = $attendance_month_year."-".$key;
                       // $empId = $model->getEmpFromSrNO($sno);
                        foreach($data as $empkey =>$inOutVaule)
                        {     
                            $model = new DailyAttendanceReport;
                            $empId = $model->getEmpFromSrNO($empkey); 
                            $inOutVaule = trim($inOutVaule);  
                            if($inOutVaule == 'A')
                            {
                                $status = 'Absent';
                                $wrkhours ='00:00';
                                $intime = '00:00';
                                $outtime = '00:00';
                            }
                            else if($inOutVaule == 'NA')
                            {
                                $status = 'NA';
                                $wrkhours ='NA';
                                $intime = '00:00';
                                $outtime = '00:00';
                            }
                            else if($inOutVaule == 'WO-I')
                            {
                                $status = 'WO-I';
                                $wrkhours ='WO-I';
                                $intime = '00:00';
                                $outtime = '00:00';
                            }
                            else {
                                
                                $valueArray = @explode("\n",$inOutVaule);                                
                                $intime = isset($valueArray[0])?$valueArray[0]:'00:00';
                                $outtime = isset($valueArray[1])?$valueArray[1]:'00:00';
                                $status = 'Present';
                                $wrkhours = $model->getTimeDiff($outtime,$intime);   
                                $wrkhours = substr($wrkhours, 0, -3);
                                                               
                            }
                            
                            $model->in_time = $intime; //                           
                            $model->out_time = $outtime; //  
                            $model->work_hrs = $wrkhours;
                            $model->infi_emp_id = $empId;  
                            $model->attendance_date = $attendance_date;                              
                            $model->shift_hrs = '9:00'; 
                            $model->work_status = $status; //
                            $model->created_date = $importDate;
                            $model->updated_date = $importDate;
                            $model->created_by = Yii::app()->session['login']['user_id'];                            
                            $i++;                          
                            $importData[] = $model->getAttributes(); 
                        }
                        
                        //$wrkhours = $model->getTimeDiff($intime,$outtime);
                        //$empId = $model->getEmpFromSrNO($sno);
                       
                    }
                    
                   
                     
                 
                    if (!empty($importData)) {     
                    //======================================
                      $truncate_tbl_daily_attendance = Yii::app()->db->createCommand("TRUNCATE tbl_daily_attendance_report")->execute();
                    //====================================
                        $transaction = $model->getDbConnection()->beginTransaction();
                        try {
                            $query = $model->commandBuilder->createMultipleInsertCommand($model->tableName(), $importData);
                            $query->execute();
                            $transaction->commit();
                        } catch (Exception $ex) {
                            $transaction->rollback();
                            throw $ex;
                        }
                        Yii::app()->user->setFlash('success', "File has been uploaded successfully.");
                        $this->redirect(array('DailyAttendanceReport/admin'));
                    } else {
                        Yii::app()->user->setFlash('error', "No records found.");
                        $this->redirect(array('DailyAttendanceReport/upload'));
                    }
                }
            }
        } catch (Exception $ex) {
            Yii::log('Could not import input file.' . $ex->getMessage());
            Yii::app()->user->setFlash('error', 'Due to invalid data could not import input file.' . $ex->getMessage());
            $this->redirect(array('DailyAttendanceReport/upload'));
        }

        $this->render('uploadDailyAttendanceReport', array('model' => $model,));
    }

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('DailyAttendanceReport');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new DailyAttendanceReport('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DailyAttendanceReport']))
			$model->attributes=$_GET['DailyAttendanceReport'];                
		$this->render('admin',array(
			'model'=>$model,
		));
	}
        
        protected function downloadReport($model)
        {
           return  '<a href="' . CHelper::baseUrl(false) . '/dailyattendancereport/getReport/rpt/' . $model->infi_emp_id.'_'.$model->attendance_date . '">Download report</a>';
        }
        
        public function actiongetreport()
        {
            $empId_string =  isset($_GET['rpt'])?$_GET['rpt']:0;
            $str_array = @explode("_",$empId_string);
            $empId = $str_array[0];
            $date = $str_array[1];
            $m = date('m',strtotime($date));
             
                       $idquery = "SELECT tbl_emp_id from tbl_infi_employee WHERE id = $empId";
                       $RSLT = Yii::app()->db->createCommand($idquery)->queryRow();                   
                       $tbl_emp_id =  $RSLT['tbl_emp_id'] ;
                   
            
//            $file="test.xls";
//            $test="<table border=1><tr><td>Cell 1</td><td>Cell 2</td></tr></table>";
//            header("Content-type: application/vnd.ms-excel");
//            header("Content-Disposition: attachment; filename=$file");
//            echo $test;
//             
            
            //$this->render('index', array('model' => $model, 'data' => $dvcdata, 'compliance' => $array_compliance, "noncompliance" => $array_nonCompliance));
            $dataProvider=new CActiveDataProvider('DailyAttendanceReport');
            $model = new DailyAttendanceReport();
            $empData = array();
            $empData = $model->getEmployeeData($empId,$m);    
        
             $empName = $empData[0]['infi_emp_name'];  
             $empName = str_replace(" ", "_", $empName);
             $file=$empName.".xls";
           $content='<table border="1">              
    <tr><td>Project Name</td><td>4G</td></tr>
    <tr><td>Name of Partner Institution</td><td>Cisco</td></tr>
    <tr><td>Name of Employee</td><td><b>'.$empName.'</b></td></tr>
    <tr><td>Role</td><td></td></tr>
</table>';
            // header("Content-type: application/vnd.ms-excel");
            // header("Content-Disposition: attachment; filename=$file");
            header('Content-Type: application/force-download');
            header('Content-disposition: attachment; filename='.$file);
            // Fix for crappy IE bug in download.
            header("Pragma: ");
            header("Cache-Control: ");
            
            $content .='<table border="1">
                <tr><td colspan="7"><center>Time Sheet for the month of '.date('F Y',strtotime($date)).'.</center></td></tr>
                <tr><th>Date</th><th>Day</th><th>Time In</th><th>Time Out</th><th>Hours</th><th>Location</th><th>Activity</th></tr>'; 
                
               $arrCount = count($empData);
               for($i = 0; $i<$arrCount;$i++)
               {
                   $dateAttendance = $empData[$i]['attendance_date'];
                   $squery = "SELECT pm.project_name FROM tbl_project_management pm INNER JOIN tbl_day_comment dc ON (pm.pid = dc.pid) WHERE dc.emp_id = $tbl_emp_id AND dc.day = '$dateAttendance' ";
                    $eresult = Yii::app()->db->createCommand($squery)->queryRow();
                   $activity = isset($eresult['project_name'])?$eresult['project_name']:'';
 
                   $content .='<tr>';
                   $content .= '<td>'.  date("d-F-y",strtotime($empData[$i]['attendance_date'])).'</td>';
                    $content .= '<td>'.date("l",strtotime($empData[$i]['attendance_date'])).'</td>';
                    $content .= '<td>'.$empData[$i]['in_time'].'</td>';
                    $content .= '<td>'.$empData[$i]['out_time'].'</td>';
                    $content .= '<td>'.$empData[$i]['work_hrs'].'</td>';
                    $content .= '<td>LDC, RCP</td>';
                    $content .= '<td>'.$activity.'</td>';
                    $content .='</tr>';
                    
               }
            
               $content .='
                <tr></tr>';
              $content .='</table>';
              
              $content .= '<table border="1"><tr><td>Approved By</td><td>Approved By</td></tr>
                <tr><td>Name/Designation</td><td>Name/Designation</td></tr>
                <tr><td></td><td></td></tr>
                <tr><td></td></tr></table>';
              
               echo $content;
            
//		$this->render('index',array(
//			'empData'=>$employeeData,
//		));
        }
        
      

        /**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DailyAttendanceReport the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=DailyAttendanceReport::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DailyAttendanceReport $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='daily-attendance-report-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
