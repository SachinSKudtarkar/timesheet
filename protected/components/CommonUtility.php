<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CommonUtility extends CApplicationComponent {

    public function isValidSapid($sapid = '') {
        return (strlen($sapid) == 18) ? true : false;
    }

    public function isBlank($fieldVal = '') {
        $newval = preg_replace('/^\s*|\s*$/', '', $fieldVal);
        if ($newval != "") {
            return false;
        } else {
            return true;
        }
    }

    public function uploadFile($model, $fromRow = 1, $path = 'uploads') {
        $model->file_name = CUploadedFile::getInstance($model, 'file_name');
        $filename = date('dmYhis') . '_' . $model->file_name;
        $filePath = $path . "/" . $filename;

        $model->file_name->saveAs($filePath, true);

        $phpExcelPath = Yii::getPathOfAlias('ext.PHPExcel.Classes');
        // Turn off our amazing library autoload
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        /* Call the excel file and read it */
        $inputFileType = PHPExcel_IOFactory::identify($filePath);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($filePath);
        //$total_sheets = $objPHPExcel->getSheetCount();
        //$allSheetName = $objPHPExcel->getSheetNames();
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        for ($row = $fromRow; $row <= $highestRow; ++$row) {
            for ($col = 0; $col < $highestColumnIndex; ++$col) {
                $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getFormattedValue();
                $arraydata[$row - 1][$col] = $value;
            }
        }
        //echo "<pre>",print_r($arraydata),"</pre>"; die();
        spl_autoload_register(array('YiiBase', 'autoload'));
        return array('fileName' => $filename, 'data' => $arraydata);
    }

    public function uploadFileCSV($model, $fromRow = 1, $path = 'uploads') {
        $model->file_name = CUploadedFile::getInstance($model, 'file_name');
        $filename = date('dmYhis') . '_' . $model->file_name;
        $filePath = $path . "/" . $filename;
        $model->file_name->saveAs($filePath, true);
        $row = 0;
        $arraydata = array();
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row < $fromRow) {
                    $row++;
                    continue;
                }
                $arraydata[$row] = $data;
                $row++;
            }
            fclose($handle);
            return array('fileName' => $filename, 'data' => $arraydata);
            //echo "<pre>",print_r($arraydata),"</pre>"; die();
        } else {
            return array('fileName' => $filename, 'data' => array());
        }

        // Turn off our amazing library autoload
    }

    public static function debug($data) {
        echo '<div><pre>';
        var_dump($data);
        echo '</pre>';
        die;
    }

  

    public static function createUrl($route, $params = array(), $ampersand = '&') {
        return Yii::app()->controller->createUrl($route, $params, $ampersand);
    }

   

    public static function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {

        $file = $filename;
        //die($filename);
        $file_size = filesize($file);
        $handle = fopen($file, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        $content = chunk_split(base64_encode($content));
        $uid = md5(uniqid(time()));
        $name = basename($file);
        $header = "From: " . $from_name . " <" . $from_mail . ">\r\n";
        $header .= "Reply-To: " . $replyto . "\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";
        $header .= "This is a multi-part message in MIME format.\r\n";
        $header .= "--" . $uid . "\r\n";
        $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
        $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $header .= $message . "\r\n\r\n";
        $header .= "--" . $uid . "\r\n";
        $header .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"\r\n"; // use different content types here
        $header .= "Content-Transfer-Encoding: base64\r\n";
        $header .= "Content-Disposition: attachment; filename=\"" . $filename . "\"\r\n\r\n";
        $header .= $content . "\r\n\r\n";
        $header .= "--" . $uid . "--";
        if (mail($mailto, $subject, "", $header)) {
            //echo "mail send ... OK"; // or use booleans here
            return true;
        } else {
            //echo "mail send ... ERROR!";
            return false;
        }
    }

    public static function sendmailWithAttachment($to, $to_name, $from, $from_name, $subject, $message, $attachment_path, $file_name, $cc = '') {

        Yii::import('application.extensions.phpmailer.JPhpMailer', true);
        try {

            $mail = new JPhpMailer;
            $mail->IsSMTP();

            $mail->Host = 'mail.cnaap.net';
            $mail->Port = 25;
            $mail->SMTPAuth = true;
            //$mail->SMTPSecure = 'ssl';
            $mail->Username = 'ndd-css';
            $mail->Password = 'cisco123';
            $mail->SMTPDebug = 2;
            $mail->SetFrom($from, $from_name);
            $mail->Subject = $subject;
            $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
            $mail->MsgHTML($message);
            if (is_array($file_name)) {
                foreach ($file_name as $fileDtl) {
                    $mail->AddAttachment($fileDtl['file_path'], $fileDtl['file_name'], $encoding = 'base64', $type = 'application/pdf');
                }
            } else {
                $mail->AddAttachment($attachment_path, $file_name, $encoding = 'base64', $type = 'application/pdf');
            }
            if (is_array($to)) {
                foreach ($to as $toEmail) {
                    $mail->AddAddress($toEmail['email'], $toEmail['name']);
                }
            } else {
                $mail->AddAddress($to, $to_name);
            }

            if (!empty($cc)) {
                if (is_array($cc)) {
                    foreach ($cc as $ccData) {
                        $mail->AddCC($ccData['email'], $ccData['name']);
                    }
                } else {
                    $mail->AddCC($cc, $cc_name);
                }
            }

            return 'asdasd'.$mail->Send();
        } catch (phpmailerException $e) {
            Yii::log($e->errorMessage()); //Pretty error messages from PHPMailer
        }
        return false;    
    }

    public static function sendmail($to, $to_name, $from, $from_name, $subject, $message, $cc = '', $cc_name = '', $replyto = '') {
        Yii::import('application.extensions.phpmailer.JPhpMailer');
        try {
            $mail = new JPhpMailer;
            $mail->IsSMTP(); 
            $mail->Host = 'mail.cnaap.net';
            $mail->Port = 25;
            $mail->SMTPAuth = true;
            //$mail->SMTPSecure = 'ssl';
            $mail->Username = 'ndd-css';
            $mail->Password = 'cisco123';


            if (!empty($replyto)) {
               $mail->AddReplyTo($replyto, 'User');
                
              $autoReply = 0;
            } else {
                $autoReply = 1;
            }
            $mail->SetFrom($from, $from_name, $autoReply);
            $mail->Subject = $subject;
            $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
            $mail->MsgHTML($message);

            if (is_array($to)) {
                foreach ($to as $toData) {
                    $mail->AddAddress($toData['email'], $toData['name']);
                }
            } else {
                $mail->AddAddress($to, $to_name);
            }
            if (is_array($cc)) {
                foreach ($cc as $ccData) {
                    $mail->AddCC($ccData['email'], $ccData['name']);
                }
            } else {
                $mail->AddCC($cc, $cc_name);
            }

            if(!$mail->Send())
            {
                print_r($mail->Errorinfo);
            }
            // return $mail->Send();
        } catch (phpmailerException $e) {
            Yii::log($e->errorMessage()); //Pretty error messages from PHPMailer
        }
        return false;
    }

    public static function sendmailWithOutAttachment($to, $to_name, $from, $from_name, $subject, $message) {
        Yii::import('application.extensions.phpmailer.JPhpMailer');
        try {
            $mail = new JPhpMailer;
            $mail->IsSMTP(); 
            $mail->Host = 'mail.cnaap.net';
            $mail->Port = 25;
            $mail->SMTPAuth = true;
            //$mail->SMTPSecure = 'ssl';
            $mail->Username = 'ndd-css';
            $mail->Password = 'cisco123';
            $mail->SetFrom($from, $from_name);
            $mail->Subject = $subject;
            $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
            $mail->MsgHTML($message);

            if (is_array($to)) {
                foreach ($to as $toEmail) {
                    $mail->AddAddress($toEmail, '');
                }
            } else {
                $mail->AddAddress($to, $to_name);
            }
            //$mail->AddCC('maheshjagadale@benchmarkitsolutions.com', 'Mahesh Jagdale');
            //$mail->AddCC('pratikgotmare@benchmarkitsolutions.com', 'Pratik Gotmare');
            //self::debug(  $_SERVER['DOCUMENT_ROOT'].'ciscondd/uploads' );

            /* var_dump($mail);
              die; */
            return $mail->Send();
        } catch (phpmailerException $e) {
            Yii::log($e->errorMessage()); //Pretty error messages from PHPMailer
        }
    }

#####################################################################
# Function name getPath
# Description: This function returns the relative path of the file name passed to it.
# Parametersare 1. file_name=> file name for which path is required
#               2. is_upload_download=> this parameters is for the path is required to upload/download file
#                   1=>upload, 0=>Download

    public static function getFilePath($file_name, $is_upload_download, $file_type = 'ndd') {
        $path = (!$is_upload_download ) ? 'downloads/' : 'uploads/';
        $path .= $file_type . '/' . $file_name;
        return Yii::app()->baseUrl . '/' . $path;
    }

    /* Created by Bhalchandra
     * Date : 09/10/2014
     * Purpose : Generate Excel
     */

    public static function generateExcel($header = array(), $arraydata = array(), $fileName = null) {
        $phpExcelPath = Yii::getPathOfAlias('ext.PHPExcel.Classes');
        // Turn off our amazing library autoload
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("CISCO");

        $objPHPExcel->setActiveSheetIndex(0);
        if (!empty($header)) {
            $cell_name = 'A';
            foreach ($header as $headerName) {
                $prev_cell_name = $cell_name;
                $objPHPExcel->getActiveSheet()->SetCellValue($cell_name . '1', $headerName);
                $cell_name++;
            }
            $objPHPExcel->getActiveSheet()->getStyle('A1:' . $prev_cell_name . '1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('CCCCCCCC');
            $objPHPExcel->getActiveSheet()->getStyle('A1:' . $prev_cell_name . '1')->getFont()->setBold(true);
        }
        $rowNo = 1;
        foreach ($arraydata as $data) {
            $cell_name = 'A';
            $rowNo++;
            foreach ($data as $key => $value) {
                $objPHPExcel->getActiveSheet()->SetCellValue($cell_name . $rowNo, $value);
                $cell_name++;
            }
        }
        // Rename sheet
        // $objPHPExcel->getActiveSheet()->setTitle('Sheet');
        ob_get_clean();
        if (empty($fileName))
            $fileName = 'File_' . date("Y-m-d") . '.xls';
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function generateExcelCircleWise($header, $arraydata, $circle) {
        $phpExcelPath = Yii::getPathOfAlias('ext.PHPExcel.Classes');
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("CISCO");
        $objPHPExcel->setActiveSheetIndex(0);
        $i = 0;
        foreach ($arraydata as $data) {
            $objPHPExcel->createSheet();
            $objPHPExcel->setActiveSheetIndex($i);
            $objPHPExcel->getActiveSheet()->setTitle($circle[$i]);
            if (!empty($header)) {
                $cell_name = 'A';
                foreach ($header as $headerName) {
                    $prev_cell_name = $cell_name;
                    $objPHPExcel->getActiveSheet()->SetCellValue($cell_name . '1', $headerName);
                    $cell_name++;
                }
                $objPHPExcel->getActiveSheet()->getStyle('A1:' . $prev_cell_name . '1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('CCCCCCCC');
                $objPHPExcel->getActiveSheet()->getStyle('A1:' . $prev_cell_name . '1')->getFont()->setBold(true);
            }

            $i++;
            $rowNo = 1;
            foreach ($data as $key => $result) {
                $rowNo++;
                $cell_name = 'A';
                foreach ($result as $value) {
                    $objPHPExcel->getActiveSheet()->SetCellValue($cell_name . $rowNo, $value);
                    $cell_name++;
                }
            }
        }

        ob_get_clean();
        if (empty($fileName))
            $fileName = 'MetroAg1CircleReport_' . date("Y-m-d") . '.xls';
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public static function generateExcelMultipleTab($arraydata, $fileName) {
        $phpExcelPath = Yii::getPathOfAlias('ext.PHPExcel.Classes');
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("CISCO");
        $objPHPExcel->setActiveSheetIndex(0);
        $i = 0;
        foreach ($arraydata as $tabName => $tabData) {
            $objPHPExcel->createSheet();
            $objPHPExcel->setActiveSheetIndex($i);
            $objPHPExcel->getActiveSheet()->setTitle(ucfirst(str_replace('_', ' ', $tabName)));
            if (!empty($tabData['header'])) {
                $cell_name = 'A';
                foreach ($tabData['header'] as $headerName) {
                    $prev_cell_name = $cell_name;
                    $objPHPExcel->getActiveSheet()->SetCellValue($cell_name . '1', $headerName);
                    $cell_name++;
                }
                $objPHPExcel->getActiveSheet()->getStyle('A1:' . $prev_cell_name . '1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('CCCCCCCC');
                $objPHPExcel->getActiveSheet()->getStyle('A1:' . $prev_cell_name . '1')->getFont()->setBold(true);
            }

            $i++;
            $rowNo = 1;
            if (isset($tabData['rows']) && is_array($tabData['rows'])) {
                foreach ($tabData['rows'] as $key => $result) {
                    $rowNo++;
                    $cell_name = 'A';
                    foreach ($result as $value) {
                        $objPHPExcel->getActiveSheet()->SetCellValue($cell_name . $rowNo, $value);
                        $cell_name++;
                    }
                }
            }
        }

        ob_get_clean();
        if (empty($fileName))
            $fileName = 'report_' . date("Y-m-d");
        $fileName .= '.xls';
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

 

  



    public static function getUerName($created_by = '') {
        if (!empty($created_by)) {
            $criteria = new CDbCriteria;
            $criteria->select = "CONCAT(first_name ,' ', last_name) AS first_name";
            $criteria->addCondition("emp_id = {$created_by}");
            $model = Employee::model()->find($criteria);
            return $model->first_name;
        }
        return '';
    }

    public static function getCreatedByEmail($created_by = '') {
        if (!empty($created_by)) {
            $criteria = new CDbCriteria;
            $criteria->select = "email";
            $criteria->addCondition("emp_id = {$created_by}");
            $model = Employee::model()->find($criteria);
            return $model->email;
        }
        return '';
    }

    public static function getmodifierName($modified_by = '') {
        if (!empty($modified_by)) {
            $criteria = new CDbCriteria;
            $criteria->select = "CONCAT(first_name ,' ', last_name) AS first_name";
            $criteria->addCondition("emp_id = {$modified_by}");
            $model = Employee::model()->find($criteria);
            return $model->first_name;
        }
        return '';
    }

    public static function getApprovedDropdown($tableName = '') {
        $criteria = new CDbCriteria;
        $criteria->select = "CONCAT(t.first_name ,' ', t.last_name) AS first_name, t.emp_id";
        $criteria->join = "INNER JOIN {$tableName} AS e ON (t.emp_id = e.approved_by)";
        return Employee::model()->findAll($criteria);
    }

    public static function getUerNameDropdown($tableName = '') {
        $criteria = new CDbCriteria;
        $criteria->select = "CONCAT(t.first_name ,' ', t.last_name) AS first_name, t.emp_id";
        $criteria->join = "INNER JOIN {$tableName} AS e ON (t.emp_id = e.created_by)";
        return Employee::model()->findAll($criteria);
    }

    public function getUerNameView($data, $row) {
        return self::getUerName($data->created_by);
    }

    public static function getCreaterDropdown($tableName = '') {
        $criteria = new CDbCriteria;
        $criteria->select = "CONCAT(t.first_name ,' ', t.last_name) AS first_name, t.emp_id";
        $criteria->join = "INNER JOIN {$tableName} AS e ON (t.emp_id = e.created_by)";
        return Employee::model()->findAll($criteria);
    }

    public static function getUploaderDropdown($tableName = '') {
        $criteria = new CDbCriteria;
        $criteria->select = "CONCAT(t.first_name ,' ', t.last_name) AS first_name, t.emp_id";
        $criteria->join = "INNER JOIN {$tableName} AS e ON (t.emp_id = e.uploaded_by)";
        return Employee::model()->findAll($criteria);
    }

    public static function getModifierDropdown($tableName = '') {
        $criteria = new CDbCriteria;
        $criteria->select = "CONCAT(t.first_name ,' ', t.last_name) AS first_name, t.emp_id";
        $criteria->join = "INNER JOIN {$tableName} AS e ON (t.emp_id = e.modified_by)";
        return Employee::model()->findAll($criteria);
    }

    public function getCreatedByName($model, $row) {
        if ($model->Creater instanceof Employee) {
            return $model->Creater->first_name . " " . $model->Creater->last_name;
        }
        return null;
    }

    public function getModifiedByName($model, $row) {
        if ($model->Modifier instanceof Employee) {
            return $model->Modifier->first_name . " " . $model->Modifier->last_name;
        }
        return null;
    }

    public function getApprovedByName($model, $row) {
        if ($model->Approved instanceof Employee) {
            return $model->Approved->first_name . " " . $model->Approved->last_name;
        }
        return null;
    }

//
    public function curlGet($urlMethod, $data) {

        $url = "https://api-34166df4.duosecurity.com";


        $url.=$urlMethod;

        $fields_string = '';
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $fields_string[] = $key . '=' . urlencode($value) . '&amp;';
            }
            $urlStringData = $url . '?' . implode('&amp;', $fields_string);
        } else {
            $urlStringData = $url;
        }

        //echo $urlStringData;
        //hash_hmac('sha1', "DIT3P0J8VRJF1H462TZP", "Q5WFXoqFocK7aA5yVZMM5oU6oisaoPeBe2qaZVZZ");
        //$s = hash_hmac('sha1', 'DIT3P0J8VRJF1H462TZP', 'Q5WFXoqFocK7aA5yVZMM5oU6oisaoPeBe2qaZVZZ');
        //echo base64_encode($s);
        //die;

        $ch = curl_init();
        $header = array();
        $date = date('D, d F Y H:i:s +0530');
        $header[] = "Date: " . $date;
        $header[] = 'method: GET';
        $header[] = "Host: api-34166df4.duosecurity.com";
        $header[] = "Content-Type: application/text";
        //$header[] = "Authorization: Basic $s";
        $head_str = $date . "\n";
        $head_str.= 'GET' . "\n";
        $head_str.= "api-34166df4.duosecurity.com" . "\n";
        $head_str.= $urlMethod . "\n";
        //echo $head_str;
        //$head_str.= "Content-Type: application/x-www-form-urlencoded"."\n";
        $hmac_key = hash_hmac('sha1', $head_str, 'VyKUFp9czcWi66fAUwJyPUJgXNDMnTxBjEgmTBwZ');
        //$hmac_key =  base64_encode($hmac_key);
        $header[] = "Authorization: Basic " . base64_encode("DINBQVA44CRZO9UTSA74:$hmac_key");
        //echo "Authorization: Basic ". base64_encode("DIT3P0J8VRJF1H462TZP:$hmac_key");
        //$headers = implode("\n",$header);
        //echo $headers;
        //echo $urlStringData;
        //print_r($header);
        //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($ch, CURLOPT_USERPWD, "DIT3P0J8VRJF1H462TZP:$hmac_key");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
        //curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt($ch, CURLOPT_URL, $urlStringData);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $return = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //echo $return,$httpCode;
        //print curl_error($ch);
        //die;
        curl_close($ch);

        return $return;
    }

    public static function downloadTableData($table = '') {
        if (!empty($table)) {

            $fields = Yii::app()->db->createCommand("SHOW COLUMNS FROM {$table}")->queryAll();
            $headerArr = array();
            foreach ($fields as $field) { //print_r($field);
                $fieldName = ucwords(str_replace('_', ' ', $field['Field']));
                array_push($headerArr, $fieldName);
            }
            $data = Yii::app()->db->createCommand("SELECT t.*, CONCAT(e.first_name ,' ', e.last_name) AS created_by, CONCAT(m.first_name ,' ', m.last_name) AS modified_by FROM {$table} AS t
                                                  LEFT JOIN tbl_employee AS e ON(e.emp_id = t.created_by)
												  LEFT JOIN tbl_employee AS m ON(m.emp_id = t.modified_by)")->queryAll();
            //self::generateExcel($headerArr, $data);
            ob_get_clean();
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename={$table}.csv");
            header("Pragma: no-cache");
            header("Expires: 0");
            $file = fopen('php://output', 'w');
            fputcsv($file, $headerArr);
            //fputcsv($file, array(1, 2, 4));
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            $file = fopen('php://output', 'w');
            exit();
        }
    }

   

    public static function convertHtmlToText($html, $encoding = 'UTF-8') {
        $html = str_replace("&nbsp;", "[[SPACE]]", $html);
        $textContent = strip_tags($html);
        $textContent = array_map('trim', explode("\n", $textContent));
        $textContent = str_replace("[[SPACE]]", chr(32), $textContent);
        $textContent = implode("\n", $textContent);
        $textContent = html_entity_decode($textContent, ENT_QUOTES, $encoding);
        return $textContent;
    }

    public static function convertCSSNIPHtmlToText($html, $sapid, $encoding = 'UTF-8', $keepIndentation = false) {
        $textContent = self::convertHtmlToText($html, $encoding);
        $textContent = str_replace(array("\r\n", "\r"), "\n", $textContent);
        $textContent = str_replace("Network Implementation Plan - CSS {$sapid}", "", $textContent);
        $textContent = str_replace("<Document No.>", "", $textContent);
        $lines = explode("\n", $textContent);
        $new_lines = array();

        foreach ($lines as $i => $line) {
            if (!empty($line)) {
                if (!$keepIndentation) {
                    $line = trim($line);
                }
                $new_lines[] = $line;
            }
        }
        $new_lines = array_values(array_filter($new_lines));
        if ($new_lines['0'] == 'Configurations') {
            unset($new_lines['0']);
        }
        $textContent = implode("\r\n", $new_lines);
        return $textContent;
    }

    public function startStream() {
        try {
            //try to change the server functions first
            // Turn off output buffering
            ini_set('output_buffering', 'off');
            // Turn off PHP output compression
            ini_set('zlib.output_compression', false);
            // Implicitly flush the buffer(s)
            ini_set('implicit_flush', true);
        } catch (Exception $e) {
            
        }
        //Flush (send) the output buffer and turn off output buffering
        while (@ob_end_flush());
        ob_implicit_flush(true);

        //now add browser tweaks
        //echo str_pad("",1024," ");
        //echo "<br />";
        ob_flush();
        flush();
        //sleep(1);
    }

    /**
     * Function to send the content to be streamed, adding special end character
     * @param $out ANY
     * Any kinda output
     */
    public function sendStream($out) {
        //send the output
        echo $out;
        //flush just to be sure
        ob_flush();
        flush();
    }

    public static function generateExcelSaveFileOnServer($header = array(), $arraydata = array(), $fileName = null, $filePath = '/var/www/html/uploads/IPSupportMailAttachment/') {
        $phpExcelPath = Yii::getPathOfAlias('application.extensions.PHPExcel.Classes');
        // Turn off our amazing library autoload
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include_once($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("CISCO");

        $objPHPExcel->setActiveSheetIndex(0);
        if (!empty($header)) {
            $cell_name = 'A';
            foreach ($header as $headerName) {
                $prev_cell_name = $cell_name;
                $objPHPExcel->getActiveSheet()->SetCellValue($cell_name . '1', $headerName);
                $cell_name++;
            }
            $objPHPExcel->getActiveSheet()->getStyle('A1:' . $prev_cell_name . '1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('CCCCCCCC');
            $objPHPExcel->getActiveSheet()->getStyle('A1:' . $prev_cell_name . '1')->getFont()->setBold(true);
        }
        $rowNo = 1;
        foreach ($arraydata as $data) {
            $cell_name = 'A';
            $rowNo++;
            foreach ($data as $key => $value) {
                $objPHPExcel->getActiveSheet()->SetCellValue($cell_name . $rowNo, $value);
                $cell_name++;
            }
        }

        ob_get_clean();
        if (empty($fileName))
            $fileName = 'File_' . date("YmdHis") . '.xls';
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $file_path = $filePath . $fileName;

        $objWriter->save($file_path);
        return $fileName;
        exit;
    }

    public static function GetProjName($pid = null) {
        $query = "SELECT project_name FROM tbl_project_management WHERE pid =" . $pid;
        $projectDetails = Yii::app()->db->createCommand($query)->queryRow();
        return $projectDetails['project_name'];
    }

    public static function GetRequestorName($pid = null) {
        $query = "SELECT requester FROM tbl_project_management WHERE pid =" . $pid;
        $projectDetails = Yii::app()->db->createCommand($query)->queryRow();
        return $projectDetails['requester'];
    }

    public static function GetProjManager($pid = null) {
        $query = "SELECT CONCAT(e.first_name,' ',e.last_name) as managerName FROM tbl_employee e INNER JOIN tbl_project_management pm ON (e.emp_id = pm.created_by) WHERE pm.pid =" . $pid;
        $projectDetails = Yii::app()->db->createCommand($query)->queryRow();
        return $projectDetails['managerName'];
    }

    public static function GetProjManagerEmail($pid = null) {
        $query = "SELECT e.email as ProjectManagerEmail FROM tbl_employee e INNER JOIN tbl_project_management pm ON (e.emp_id = pm.created_by) WHERE pm.pid =" . $pid;
        $projectDetails = Yii::app()->db->createCommand($query)->queryRow();
        return $projectDetails['ProjectManagerEmail'];
    }
    public function getProject_byEmp_id($emp_id){
        $result =[];
        if($emp_id){
         $query = "SELECT project_name,rap.pid FROM tbl_resource_allocation_project_work as rap INNER JOIN tbl_project_management pm ON (rap.pid = pm.pid) WHERE is_deleted=0 and rap.allocated_resource in ($emp_id)";
        $projectDetails = Yii::app()->db->createCommand($query)->queryAll();
        foreach ($projectDetails as $key => $value) {
           $result[$value['pid']]=  $value['project_name'];
        }
        
        return $result;
        }
          
    }

    public static function getSubProjectByProjectId($pid){
        $newData = $da = $nn = $result = array();
        if ($pid) {
            $userId = Yii::app()->session['login']['user_id'];
           $query ="select st.sub_project_id,sp.sub_project_name,st.stask_id  from tbl_sub_task as st inner join tbl_pid_approval as pa on (st.pid_approval_id = pa.pid_id) inner join tbl_sub_project as sp on (sp.spid = st.sub_project_id)
where st.project_id = {$pid} and st.emp_id = {$userId} group by st.sub_project_id";

        $res = Yii::app()->db->createCommand($query)->queryAll();

        $hours = array();
            if (isset($res)) {
            
                    foreach (array_filter($res) as $key => $val) {
                    
                    $result[$val['sub_project_id']] = $val['sub_project_name'];
                }
            }
        }
        return $result;
    }

    public static function downloadDataInCSV($header = array(), $data = array(), $fileName = 'datafile') {
        ob_get_clean();
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename={$fileName}.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        $file = fopen('php://output', 'w');
        fputcsv($file, $header);
//fputcsv($file, array(1, 2, 4));
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        $file = fopen('php://output', 'w');
        exit();
    }


}
