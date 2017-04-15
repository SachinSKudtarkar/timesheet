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

    public static function isSapidExists($sap_id) {
        $model_sapid_manager = NddSapidManager::model()->findByAttributes(array('hm_oldsapid' => $sap_id));
        if (!empty($model_sapid_manager)) {
            //$model_sapid_manager->status = 0;
            $model_sapid_manager->modified_at = date('Y-m-d h:i:s');
            $model_sapid_manager->save();
            return 1;
        } else {
            return 0;
        }
    }

    public static function createUrl($route, $params = array(), $ampersand = '&') {
        return Yii::app()->controller->createUrl($route, $params, $ampersand);
    }

    public function fetchSAPCircle($sapid = '') {
        $circle = "";
        if (!empty($sapid)) {
            $getCircle = explode("-", $sapid);
            $circle = $getCircle[1];
        }
        return $circle;
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
            $mail->Host = 'smtpout.secureserver.net';
            $mail->Port = 25;
            $mail->SMTPAuth = true;
            //$mail->SMTPSecure = '';
            $mail->Username = 'techsupport@infinitylabs.in';
            $mail->Password = 'india@123';
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

            return $mail->Send();
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
            $mail->Host = 'smtpout.secureserver.net';
            $mail->Port = 25;
            $mail->SMTPAuth = true;
            //$mail->SMTPSecure = 'ssl';
            $mail->Username = 'techsupport@infinitylabs.in';
            $mail->Password = 'india@123';

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

            return $mail->Send();
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
            $mail->Host = 'smtpout.secureserver.net';
            $mail->Port = 25;
            $mail->SMTPAuth = true;
            // $mail->SMTPSecure = 'ssl';
            $mail->Username = 'techsupport@infinitylabs.in';
            $mail->Password = 'india@123';
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

    public function modifySAPID($sapid = NULL) {
        $modifiedSapID = $sapid;
        if (!empty($sapid)):
            $oldSapidParam = explode("-", $sapid);
            if (count($oldSapidParam) == 5):
                $result = Yii::app()->db->createCommand("SELECT count(*) as cntrec FROM ndd_enbiladump WHERE sapid='{$sapid}'")->queryAll();
                if (isset($result[0]['cntrec']) && $result[0]['cntrec'] == 0):
                    $oldSapidParam = explode("-", $sapid);
                    $convert_device_array = array('AG1', 'ILA');
                    if (count($oldSapidParam) === 5):
                        if ((!empty($oldSapidParam)) && (in_array($oldSapidParam[3], $convert_device_array))) :
                            if ((($this->isStandAlonecase($sapid)) == FALSE) || (strpos($oldSapidParam[4], '44'))):
                                $modifiedSapID = $oldSapidParam[0] . '-' . $oldSapidParam[1] . '-' . $oldSapidParam[2] . '-ENB' . '-' . $oldSapidParam[4];
                            endif;
                        endif;
                    else:
                        $modifiedSapID = $sapid;
                    endif;
                endif;
            endif;
        endif;
        return $modifiedSapID;
    }

    public function isValidSapIdAG1($sapID = '') {
        /* if($sapID == ''){
          return FALSE;
          } */
        $hyphen_cnt = substr_count($sapID, '-');
        return ($hyphen_cnt == 4 || $sapID == '' || $sapID == 'x' || $sapID == 'X') ? true : false;
    }

    public function generateGISHostname($facid = null, $neid = null) {
        $system_hostname = '';
//        if ((!empty($facid) && !strlen($facid) == 18) && (!empty($neid) && !strlen($neid) == 27)) {
        $data_append = '';
        if (!empty($facid) && !empty($neid)) {
            $device = substr($neid, -6, -3);
            $accepted_device = array('PAR', 'ESR');
            if (in_array($device, $accepted_device)):
                $fac_part1 = substr($facid, 4, 4);
                $fac_part2 = substr($facid, 8, 4);
                for ($i = 1; $i < 1000; $i++) :
                    $system_hostname = ($fac_part2 == 'XXXX' || $fac_part2 == 'xxxx') ? $fac_part1 . $fac_part1 . $device : $fac_part1 . $fac_part2 . $device;
                    $data_append = ( strlen($i) == 1 && strlen($i) != 3) ? '00' . $i : '0' . $i;
                    $system_hostname .= $data_append;

                    $model = NddGis::model()->findByAttributes(array('hostname' => $system_hostname));
                    if (!empty($model)) {
                        continue;
                    } else {
                        break;
                    }
                endfor;
            endif;
        }
        return $system_hostname;
    }

    public function getToIp($from_addr = '') {
        if ($from_addr == '')
            return '';
        $lastOctet = ltrim(strrchr($from_addr, "."), ".");
        if ($lastOctet <= 254) {
            $lastOctetPos = strrpos($from_addr, ".");
            $newLastOctet = $lastOctet + 1;
            if ($newLastOctet > 255)
                return '';

            $ipWithoutLastOctet = substr($from_addr, 0, $lastOctetPos);
            $to_addr = $ipWithoutLastOctet . "." . $newLastOctet;
            return $to_addr;
            //echo $to_ip = substr($ip, 0, $lastOctet);
        }
        else {
            return '';
        }
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

    public static function downloadNDDData($table = '', $filterType = '') {
        if (!empty($table)) {

            $fields = Yii::app()->db->createCommand("SHOW COLUMNS FROM {$table}")->queryAll();
            $headerArr = array();
            foreach ($fields as $field) { //print_r($field);
                $fieldName = ucwords(str_replace('_', ' ', $field['Field']));
                array_push($headerArr, $fieldName);
            }
            $sql = "SELECT t.*, CONCAT(e.first_name ,' ', e.last_name) AS created_by, CONCAT(m.first_name ,' ', m.last_name) AS modified_by FROM {$table} AS t
                                                  LEFT JOIN tbl_employee AS e ON(e.emp_id = t.created_by)
                                                  LEFT JOIN tbl_employee AS m ON(m.emp_id = t.modified_by) WHERE t.is_deleted = 0";
            if ($filterType == 'released') {
                $filename = 'ReleasedNddList_' . date("YmdHis");
                $sql .= " AND t.ndd_status = 1";
            } else if ($filterType == 'notreleased') {
                $sql1 = "SELECT DISTINCT(`naom`.`modified_sapid`) FROM `ndd_ag1_outputmaster` AS `naom` INNER JOIN `ndd_ag1_input` AS `nai` ON (`naom`.`input_id` = `nai`.`id` AND `nai`.`is_active` = 1)";
                $sql2 = "SELECT DISTINCT(`nom`.`enode_b_sapid`) FROM `ndd_output_master` AS `nom` INNER JOIN `ndd_request_master` AS `nrm` ON (`nrm`.`sapid` = `nom`.`enode_b_sapid` AND `nom`.`request_id` = `nrm`.`request_id` AND `nrm`.`is_disabled` = 0)";
                $resultArr1 = self::getComaSepratedSapIds($sql1, 'modified_sapid');
                $resultArr2 = self::getComaSepratedSapIds($sql2, 'enode_b_sapid');
                $searchStr = $resultArr1 . ', ' . $resultArr2;
                $filename = 'NotReleasedNddList_' . date("YmdHis");
                $sql .= " AND t.ndd_status = 0 AND SUBSTRING(t.sapid, 11, 3) != 'AG2' AND SUBSTRING(t.sapid, 11, 3) != 'AG3' AND t.sapid NOT IN(" . $searchStr . ")";
            } else if ($filterType == 'missingneid') {
                $filename = 'MissingNeid_' . date("YmdHis");
                $sql .= " AND t.ndd_status = 1 AND (t.gne_id ='' OR t.gne_id =null)";
            } else if ($filterType == 'esrtopar') {
                $filename = 'ESRToParAndParToESR_' . date("YmdHis");
                $sql .= " AND ndd_status = 1 AND ((SUBSTRING(t.host_name, 9, 3) = 'ESR' AND SUBSTRING(t.gne_id, 22, 3) = 'PAR') OR (SUBSTRING(t.host_name, 9, 3) = 'PAR' AND SUBSTRING(t.gne_id, 22, 3) = 'ESR'))";
            } else if ($filterType == 'esrduplicate') {
                $filename = 'DuplicateHostnameESR_' . date("YmdHis");
                $duplicateSapList = self::getComaSepratedSapIds("SELECT `modified_sapid` FROM `ndd_host_name` WHERE `is_deleted` = 0 AND `ndd_status` = 1 AND SUBSTRING(`host_name`, 9, 3) = 'ESR' GROUP BY `modified_sapid` HAVING COUNT(`modified_sapid`) > 1");
                $duplicateSapList = ($duplicateSapList !== false) ? $duplicateSapList : "'No Records Found'";
                $sql .= " AND ndd_status = 1 AND t.modified_sapid IN(" . $duplicateSapList . ")";
            } else if ($filterType == 'esrparduplicate') {
                $filename = 'DuplicateHostnameESRnPAR_' . date("YmdHis");
                $duplicateSapIdList = self::getDuplicateSapWithDiffNeid();
                $duplicateSapIdList = ($duplicateSapIdList !== false) ? $duplicateSapIdList : "'No Records Found'";
                $sql .= " AND ndd_status = 1 AND t.modified_sapid IN(" . $duplicateSapIdList . ")";
            }

            $data = Yii::app()->db->createCommand($sql)->queryAll();
            //self::generateExcel($headerArr, $data);
            ob_get_clean();
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename={$filename}.csv");
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

    public static function getDuplicateSapWithDiffNeid() {
        $sql = "SELECT `modified_sapid`, `gne_id`
                FROM `ndd_host_name`
                WHERE `is_deleted` = 0
                AND `ndd_status` = 1
                GROUP BY `modified_sapid`
                HAVING COUNT(`modified_sapid`) > 1";

        $duprec = self::getComaSepratedSapIds($sql);
        if ($duprec !== false) {
            $sql = "SELECT `modified_sapid`, `gne_id`
                    FROM `ndd_host_name`
                    WHERE `is_deleted` = 0
                    AND `ndd_status` = 1
                    AND `modified_sapid` IN(" . $duprec . ")";
            $data = Yii::app()->db->createCommand($sql)->queryAll();
            $sapId = '';
            $sapIdArr = array();
            foreach ($data as $row) {
                if ($sapId == '' || $sapId != $row['modified_sapid']) {
                    $sapId = $row['modified_sapid'];
                    $routerType1 = substr($row['gne_id'], 21, 3);
                } else if ($sapId == $row['modified_sapid']) {
                    $routerType2 = substr($row['gne_id'], 21, 3);
                    if ($routerType1 != $routerType2) {
                        $sapIdArr[] = "'" . $row['modified_sapid'] . "'";
                    }
                }
            }

            if (count($sapIdArr) > 0) {
                return implode(",", $sapIdArr);
            } else {
                return false;
            }
        }
        return false;
    }

    public static function getComaSepratedSapIds($sql, $field = 'modified_sapid') {
        $valueArr = array();
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        if (!empty($data)) {
            foreach ($data as $value) {
                $valueArr[] = "'" . $value[$field] . "'";
            }
            return implode(',', $valueArr);
        }
        return false;
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

    public function isStandAlonecase($sapid) {
        $criteria = new CDbCriteria;
        $criteria->condition = "sapid=:sapid";
        $criteria->params = array(":sapid" => $sapid);
        $countResult = NddStandaloneSapid::model()->count($criteria);
        if ($countResult > 0) {
            return true;
        }
        return false;
    }

    /* Function to remove starting 0 from 1st four octets of ipv6 address and v6 prefix.
     * Params : IPV6 address
     * return : Formatted IPV6 address
     * @author Rakhi Kasat
     */

    public static function getFormattedIpv6($ipv6_address) {
        $addr = null;
        $formattedAddr = "";
        if (!empty($ipv6_address)) {
            $maskRemoved = IpAddressHelper::removeMaskFromIpv6Address($ipv6_address);
            // php function to check if ipv6 address is valid or invalid
            if (!filter_var($maskRemoved, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
                //Valid
                $addr = inet_pton($maskRemoved);
                $formattedAddr = inet_ntop($addr);
            } else {
                // Invalid
                $formattedAddr = $ipv6_address;
            }
            return $formattedAddr;
        }
    }

    /*
     * Method CSS Ip interface, given Ag1 interface ip returns css ip (if odd - 1 , if even + 1)
     * Updated Model    : NddAg1Outputmaster (ndd_ag1_outputmaster)
     * Input Attributes : AG1 Interface Ip.
     * Returns          : CSS Interface Ip.
     * Author           : Vaibhav D.Harihar
     * Dated            : 27-05-2015
     */

    public static function getCssInterfaceIP($interfaceIp) {
        if (!empty($interfaceIp)) {
            $ipPartArr = explode('.', $interfaceIp);
            $ipPartArr[3] = ((int) $ipPartArr[3] % 2 > 0) ? $ipPartArr[3] - 1 : $ipPartArr[3] + 1;
            return implode('.', $ipPartArr);
        }
        return $interfaceIp;
    }

    /*
     * Author           : Vaibhav D.Harihar
     * Dated            : 25-06-2015
     */

    public static function getFirstIPPool($interfaceIpPool) {
        $pos = strpos($interfaceIpPool, '/', 0);
        $interfaceIp = substr($interfaceIpPool, 0, $pos);
        if (!empty($interfaceIp)) {
            $ipPartArr = explode('.', $interfaceIp);
            $ipPartArr[3] = $ipPartArr[3] + 1;
            return implode('.', $ipPartArr);
        }
        return $interfaceIp;
    }

    public static function getFirstIPFromPool($interfaceIpPool, $poolIpAdd = 1, $nipType = 'WIFI') {
        $pos = strpos($interfaceIpPool, '/', 0);
        $interfaceIp = substr($interfaceIpPool, 0, $pos);
        if (!empty($interfaceIp)) {
            $ipPartArr = explode('.', $interfaceIp);
            $ipPartArr[3] = ($nipType == 'WIFI') ? ($ipPartArr[3] + $poolIpAdd) : (self::getFirstIPFromPoolByRange($ipPartArr[3], $poolIpAdd));
            return implode('.', $ipPartArr);
        }
        return $interfaceIp;
    }

    /*
     * Author           : Vaibhav D.Harihar
     * Dated            : 06-01-2016
     */

    public static function getManagementIPFromPool($interfaceIpPool, $inst = 2) {
        $poolDiv = self::getSubscriberPoolDivisions($interfaceIpPool);
        $poolAdd = ($inst == 0) ? 0 : (($inst == 2) ? 1 : $poolDiv);
        $pos = strpos($interfaceIpPool, '/', 0);
        $interfaceIp = substr($interfaceIpPool, 0, $pos);
        $notation = substr($interfaceIpPool, $pos + 1);
        if (!empty($interfaceIp)) {
            $ipPartArr = explode('.', $interfaceIp);
            if ($notation >= 0 && $notation <= 8) {
                $ipPartArr[0] = self::getDivIPFromPoolByRange($ipPartArr[0], $poolAdd, $poolDiv);
            } else if ($notation >= 9 && $notation <= 16) {
                $ipPartArr[1] = self::getDivIPFromPoolByRange($ipPartArr[1], $poolAdd, $poolDiv);
            } else if ($notation >= 17 && $notation <= 24) {
                $ipPartArr[2] = self::getDivIPFromPoolByRange($ipPartArr[2], $poolAdd, $poolDiv);
            } else if ($notation >= 25 && $notation <= 32) {
                $ipPartArr[3] = self::getDivIPFromPoolByRange($ipPartArr[3], $poolAdd, $poolDiv);
            }
            return implode('.', $ipPartArr);
        }
        return $interfaceIp;
    }

    /*
     * Method Returns Vlan Mapping for given AG1 Port
     * Updated Model    : NddAg1Outputmaster (ndd_ag1_outputmaster)
     * Input Attributes : AG1 Port Number.
     * Returns          : Vlan Mapping.
     * Author           : Vaibhav D.Harihar
     * Dated            : 27-05-2015
     */

    public static function getAg1PortVlanMapping($ag1port) {
        $ag1PortArr = array('GigabitEthernet0/1/0' => '328', 'GigabitEthernet0/1/1' => '327',
            'GigabitEthernet0/1/2' => '326', 'GigabitEthernet0/1/3' => '325',
            'GigabitEthernet0/1/4' => '324', 'GigabitEthernet0/1/5' => '323',
            'GigabitEthernet0/1/6' => '322', 'GigabitEthernet0/1/7' => '321',
            'GigabitEthernet0/2/0' => '338', 'GigabitEthernet0/2/1' => '337',
            'GigabitEthernet0/2/2' => '336', 'GigabitEthernet0/2/3' => '335',
            'GigabitEthernet0/2/4' => '334', 'GigabitEthernet0/2/5' => '333',
            'GigabitEthernet0/2/6' => '332', 'GigabitEthernet0/2/7' => '331',
            'GigabitEthernet0/3/0' => '366', 'GigabitEthernet0/3/1' => '368',
            'GigabitEthernet0/3/2' => '367', 'GigabitEthernet0/3/3' => '365',
            'GigabitEthernet0/3/4' => '364', 'GigabitEthernet0/3/5' => '363',
            'GigabitEthernet0/3/6' => '362', 'GigabitEthernet0/3/7' => '361',
            'GigabitEthernet0/4/0' => '356', 'GigabitEthernet0/4/1' => 'EnodeB-101,102,103',
            'GigabitEthernet0/4/2' => '357', 'GigabitEthernet0/4/3' => '355',
            'GigabitEthernet0/4/4' => '354', 'GigabitEthernet0/4/5' => '353',
            'GigabitEthernet0/4/6' => '352', 'GigabitEthernet0/4/7' => '351',
            'GigabitEthernet0/5/0' => '348', 'GigabitEthernet0/5/1' => '347',
            'GigabitEthernet0/5/2' => '346', 'GigabitEthernet0/5/3' => '345',
            'GigabitEthernet0/5/4' => '344', 'GigabitEthernet0/5/5' => '343',
            'GigabitEthernet0/5/6' => '342', 'GigabitEthernet0/5/7' => '341');

        if (isset($ag1PortArr[$ag1port])) {
            return $ag1PortArr[$ag1port];
        } else if (isset($ag1PortArr['GigabitEthernet' . $ag1port])) {
            return $ag1PortArr['GigabitEthernet' . $ag1port];
        }
        return 'undefined';
    }

    public static function downloadAllTableData($table = '') {
        if (!empty($table)) {
            $fields = Yii::app()->db->createCommand("SHOW COLUMNS FROM {$table}")->queryAll();
            $headerArr = array();
            foreach ($fields as $field) { //print_r($field);
                $fieldName = ucwords(str_replace('_', ' ', $field['Field']));
                array_push($headerArr, $fieldName);
            }
            $data = Yii::app()->db->createCommand("SELECT * FROM {$table}")->queryAll();
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

    public static function getRegionSetByOuterTag($outerTag) {
        $msTag = array();
        $active = '';
        $passive = '';
        if ($outerTag >= 1001 AND $outerTag <= 1015) {
            $active = 'inst1';
        } else if (($outerTag >= 1035 AND $outerTag <= 1154) OR ( $outerTag >= 1275 AND $outerTag <= 1304)) {
            $active = 'inst2';
            $passive = 'inst3';
        } else if (($outerTag >= 1155 AND $outerTag <= 1274) OR ( $outerTag >= 1335 AND $outerTag <= 1364)) {
            $active = 'inst3';
            $passive = 'inst2';
        }

        if (($outerTag >= 1035 AND $outerTag <= 1064) OR ( $outerTag >= 1155 AND $outerTag <= 1184)) {
            $msTag['vlans'] = array('set' => 'SET1', 'inst2' => '1035-1064', 'inst3' => '1155-1184', 'active' => $active, 'passive' => $passive);
        } else if (($outerTag >= 1065 AND $outerTag <= 1094) OR ( $outerTag >= 1185 AND $outerTag <= 1214)) {
            $msTag['vlans'] = array('set' => 'SET2', 'inst2' => '1065-1094', 'inst3' => '1185-1214', 'active' => $active, 'passive' => $passive);
        } else if (($outerTag >= 1095 AND $outerTag <= 1124) OR ( $outerTag >= 1215 AND $outerTag <= 1244)) {
            $msTag['vlans'] = array('set' => 'SET3', 'inst2' => '1095-1124', 'inst3' => '1215-1244', 'active' => $active, 'passive' => $passive);
        } else if (($outerTag >= 1125 AND $outerTag <= 1154) OR ( $outerTag >= 1245 AND $outerTag <= 1274)) {
            $msTag['vlans'] = array('set' => 'SET4', 'inst2' => '1125-1154', 'inst3' => '1245-1274', 'active' => $active, 'passive' => $passive);
        } else if (($outerTag >= 1275 AND $outerTag <= 1304) OR ( $outerTag >= 1335 AND $outerTag <= 1364)) {
            $msTag['vlans'] = array('set' => 'SET5', 'inst2' => '1275-1304', 'inst3' => '1335-1364', 'active' => $active, 'passive' => $passive);
        }
        return $msTag;
    }

    public static function getSubnetMaskIpAddr($subscriberPool, $addOne = false) {
        $pos = strpos($subscriberPool, '/', 0);
        $notation = substr($subscriberPool, $pos + 1);
        if ($addOne) {
            $notation = $notation + 1;
        }
        $subnetMask = array("32" => "255.255.255.255", "31" => "255.255.255.254", "30" => "255.255.255.252",
            "29" => "255.255.255.248", "28" => "255.255.255.240", "27" => "255.255.255.224",
            "26" => "255.255.255.192", "25" => "255.255.255.128", "24" => "255.255.255.0",
            "23" => "255.255.254.0", "22" => "255.255.252.0", "21" => "255.255.248.0",
            "20" => "255.255.240.0", "19" => "255.255.224.0", "18" => "255.255.192.0",
            "17" => "255.255.128.0", "16" => "255.255.0.0", "15" => "255.254.0.0",
            "14" => "255.252.0.0", "13" => "255.248.0.0", "12" => "255.240.0.0",
            "11" => "255.224.0.0", "10" => "255.192.0.0", "9" => "255.128.0.0",
            "8" => "255.0.0.0", "7" => "254.0.0.0", "6" => "252.0.0.0",
            "5" => "248.0.0.0", "4" => "240.0.0.0", "3" => "224.0.0.0",
            "2" => "192.0.0.0", "1" => "128.0.0.0", "0" => "0.0.0.0");

        if (isset($subnetMask[$notation])) {
            return $subnetMask[$notation];
        }
        return null;
    }

    public static function getWildcardMaskIpAddr($lanPool) {
        $lanPool = trim($lanPool);
        $pos = strpos($lanPool, '/', 0);
        $notation = substr($lanPool, $pos);
        $subnetMask = array("/32" => "0.0.0.0", "/31" => "0.0.0.1", "/30" => "0.0.0.3",
            "/29" => "0.0.0.7", "/28" => "0.0.0.15", "/27" => "0.0.0.31",
            "/26" => "0.0.0.63", "/25" => "0.0.0.127", "/24" => "0.0.0.255",
            "/23" => "0.0.1.255", "/22" => "0.0.3.255", "/21" => "0.0.7.255",
            "/20" => "0.0.15.255", "/19" => "0.0.31.255", "/18" => "0.0.63.255",
            "/17" => "0.0.127.255", "/16" => "0.0.255.255", "/15" => "0.1.255.255",
            "/14" => "0.3.255.255", "/13" => "0.7.255.255", "/12" => "0.15.255.255",
            "/11" => "0.31.255.255", "/10" => "0.63.255.255", "/9" => "0.127.255.255",
            "/8" => "0.255.255.255", "/7" => "1.255.255.255", "/6" => "3.255.255.255",
            "/5" => "7.255.255.255", "/4" => "15.255.255.255", "/3" => "31.255.255.255",
            "/2" => "63.255.255.255", "/1" => "127.255.255.255", "/0" => "255.255.255.255");
        $notation = trim($notation);
        $notation = str_replace(' ', '', $notation);

        if (isset($subnetMask[$notation])) {
            return $subnetMask[$notation];
        }
        return null;
    }

    public static function getSubnetToWildcardMaskMapping($subnetMask) {
        $wildcardMask = array("255.255.255.255" => "0.0.0.0", "255.255.255.254" => "0.0.0.1", "255.255.255.252" => "0.0.0.3",
            "255.255.255.248" => "0.0.0.7", "255.255.255.240" => "0.0.0.15", "255.255.255.224" => "0.0.0.31",
            "255.255.255.192" => "0.0.0.63", "255.255.255.128" => "0.0.0.127", "255.255.255.0" => "0.0.0.255",
            "255.255.254.0" => "0.0.1.255", "255.255.252.0" => "0.0.3.255", "255.255.248.0" => "0.0.7.255",
            "255.255.240.0" => "0.0.15.255", "255.255.224.0" => "0.0.31.255", "255.255.192.0" => "0.0.63.255",
            "255.255.128.0" => "0.0.127.255", "255.255.0.0" => "0.0.255.255", "255.254.0.0" => "0.1.255.255",
            "255.252.0.0" => "0.3.255.255", "255.248.0.0" => "0.7.255.255", "255.240.0.0" => "0.15.255.255",
            "255.224.0.0" => "0.31.255.255", "255.192.0.0" => "0.63.255.255", "255.128.0.0" => "0.127.255.255",
            "255.0.0.0" => "0.255.255.255", "254.0.0.0" => "1.255.255.255", "252.0.0.0" => "3.255.255.255",
            "248.0.0.0" => "7.255.255.255", "240.0.0.0" => "15.255.255.255", "224.0.0.0" => "31.255.255.255",
            "192.0.0.0" => "63.255.255.255", "128.0.0.0" => "127.255.255.255", "0.0.0.0" => "255.255.255.255");

        if (isset($wildcardMask[$subnetMask])) {
            return $wildcardMask[$subnetMask];
        }
        return null;
    }

    public static function getSubStringUptoPosition($string, $char) {
        $pos = strpos($string, $char, 0);
        $subStr = substr($string, 0, $pos);
        return $subStr;
    }

    public static function getRemoteNgbrData($ag1Hostname) {
        $cssRemoteConfig = array();
        $sql = 'SELECT `css_hostname`,`remote_port`,`local_port` FROM `ndd_ag1_css_outputmaster` where `ag1_hostname`=:ag1_hostname';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(":ag1_hostname", $ag1Hostname);
        $records = $command->queryAll();

        if (!empty($records)) {
            foreach ($records as $record) {
                $cssRemoteConfig[$record['local_port']] = array('remote_hostname' => $record['css_hostname'], 'remote_port' => $record['remote_port']);
            }
        }
        return $cssRemoteConfig;
    }

    public static function getHtmlTemplate($filepath, $filename) {
        $final_file_path = $filepath . $filename;

        if (file_exists($final_file_path)) {
            return file_get_contents($final_file_path);
        } else {
            return false;
        }
    }

    public static function downloadNipFile($filename, $finalNipString, $docType = 'PDF') {
        if (!empty($finalNipString)) {
            if ($docType == 'PDF') {
                $_mPDF = Yii::app()->ePdf->mpdf('', 'A4');
                $_mPDF->WriteHTML($finalNipString);
                $_mPDF->Output($filename . '.pdf', 'D');
            } else if ($docType == 'TXT') {
                $textContent = $finalNipString;
                $textContent = strip_tags($textContent);
                $textContent = html_entity_decode($textContent, ENT_QUOTES, 'UTF-8');
                $textContent = str_replace(array("\r\n", "\r"), "\n", $textContent);
                $lines = explode("\n", $textContent);
                $new_lines = array();

                foreach ($lines as $i => $line) {
                    if (!empty($line)) {
                        $new_lines[] = trim($line);
                    }
                }
                $new_lines = array_values(array_filter($new_lines));

                $textContent = implode("\r\n", $new_lines);
                $length = strlen($textContent);

                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($filename . '.txt'));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . $length);
                echo $textContent;
            }
        }
        exit();
    }

    public static function getNNValueBySapID($sapID) {
        $nnValue = '';
        if (!empty($sapID)) {
            $sapIDParam = explode('-', $sapID);
            $criteria = new CDbCriteria;
            $criteria->select = "nn_value";
            $criteria->compare("state_code", $sapIDParam[1]);
            $result = NddNnValueConfiguration::model()->find($criteria);
            if (count($result) >= 1) {
                $nnValue = $result['nn_value'];
            }
        }
        return $nnValue;
    }

    public static function getNNValueByStateCode($stateCode) {
        $nnValue = '';
        if (!empty($stateCode)) {
            $criteria = new CDbCriteria;
            $criteria->select = "nn_value";
            $criteria->compare("state_code", $stateCode);
            $result = NddNnValueConfiguration::model()->find($criteria);
            if (count($result) >= 1) {
                $nnValue = $result['nn_value'];
            }
        }
        return $nnValue;
    }

    public static function getLoopback0BySiteType($hostname, $siteType = 'CSS') {
        $loopback = '';
        if (!empty($hostname)) {
            $criteria = new CDbCriteria;
            if ($siteType == 'CSS') {
                $criteria->select = "ipv4";
                $criteria->compare("host_name", $hostname);
                $result = NddRanLb::model()->find($criteria);
            } elseif ($siteType == 'AG1' || $siteType == 'MAG1' || $siteType == 'NAG1') {
                $criteria->select = "ipv4";
                $criteria->compare("host_name", $hostname);
                $result = NddCoreLb::model()->find($criteria);
            } elseif ($siteType == 'AG2') {
                $criteria->select = "loopback0_ipv4";
                $criteria->compare("hostname", $hostname);
                $result = NddCoreIpMaster::model()->find($criteria);
                if (count($result) >= 1) {
                    $loopback = $result['loopback0_ipv4'];
                }
                return $loopback;
            }
            if (count($result) >= 1) {
                $loopback = $result['ipv4'];
            }
        }
        return $loopback;
    }

    public static function getPseudoWireIdByOlt($oltHostname) {
        $pseudoWireArr = array("olt_mgmt_id" => '', "ont_mgmt_id" => '', "subscriber_id" => '');
        $sql = 'SELECT `olt_mgmt_id`,`ont_mgmt_id`,`subscriber_id` FROM `ndd_bng_pseudoid` where `olt_hostname`=:olt_hostname';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(":olt_hostname", $oltHostname);
        $record = $command->queryRow();

        if (count($record) == 1) {
            $pseudoWireArr = array("olt_mgmt_id" => $record['olt_mgmt_id'], "ont_mgmt_id" => $record['ont_mgmt_id'], "subscriber_id" => $record['subscriber_id']);
        }
        return $pseudoWireArr;
    }

    public static function updateBatchData($updatData, $split = 150) {
        if (!empty($updatData)) {
            $batches = array();
            $batches = array_chunk($updatData, $split);
            $error = false;
            $success = false;
            $errorMessage = null;
            foreach ($batches as $batch) {
                Yii::app()->db->createCommand(implode('; ', $batch))->execute();
                usleep(1000);
            }
        }
    }

    public static function getDivisionIPFromPool($subscriberPool, $flag = true) {
        $poolAdd = self::getSubscriberPoolDivisions($subscriberPool);
        $pos = strpos($subscriberPool, '/', 0);
        $notation = substr($subscriberPool, $pos + 1);
        $interfaceIp = substr($subscriberPool, 0, $pos);
        if (!empty($interfaceIp)) {
            $ipPartArr = explode('.', $interfaceIp);
            if ($notation >= 0 && $notation <= 7) {
                $ipPartArr[0] = $ipPartArr[0] + $poolAdd;
            } else if ($notation >= 8 && $notation <= 15) {
                $ipPartArr[1] = $ipPartArr[1] + $poolAdd;
            } else if ($notation >= 16 && $notation <= 24) {
                $ipPartArr[2] = $ipPartArr[2] + $poolAdd;
            } else if ($notation >= 25 && $notation <= 31) {
                $ipPartArr[3] = $ipPartArr[3] + $poolAdd;
            }

            if ($flag) {
                $ipPartArr[3] = $ipPartArr[3] + 1;
            }
            return implode('.', $ipPartArr);
        }
        return $interfaceIp;
    }

    public static function getSubscriberPoolDivisions($subscriberPool) {
        $pos = strpos($subscriberPool, '/', 0);
        $notation = (int) (substr($subscriberPool, $pos + 1));
        $divisionMap = array(0 => 256, 1 => 128, 2 => 64, 3 => 32, 4 => 16, 5 => 8, 6 => 4, 7 => 2,
            8 => 256, 9 => 128, 10 => 64, 11 => 32, 12 => 16, 13 => 8, 14 => 4, 15 => 2,
            16 => 256, 17 => 128, 18 => 64, 19 => 32, 20 => 16, 21 => 8, 22 => 4, 23 => 2, 24 => 0,
            25 => 256, 26 => 128, 27 => 64, 28 => 32, 29 => 16, 30 => 8, 31 => 4);

        if ($notation >= 0 && $notation <= 7) {
            return $divisionMap[$notation] / 2;
        } else if ($notation >= 8 && $notation <= 15) {
            return $divisionMap[$notation] / 2;
        } else if ($notation >= 16 && $notation <= 24) {
            return $divisionMap[$notation] / 2;
        } else if ($notation >= 25 && $notation <= 31) {
            return $divisionMap[$notation] / 2;
        }
        return $divisionMap[$notation] / 2;
    }

    public static function DownloadBuiltTableData($table = '') {
        if (!empty($table)) {
            $fields = Yii::app()->db->createCommand("SHOW COLUMNS FROM {$table}")->queryAll();
            $headerArr = array();
            foreach ($fields as $field) { //print_r($field);
                $fieldName = ucwords(str_replace('_', ' ', $field['Field']));
                array_push($headerArr, $fieldName);
            }
            $data = Yii::app()->db->createCommand("SELECT * FROM {$table}")->queryAll();
            //self::generateExcel($headerArr, $data);
            ob_get_clean();
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename={$table}.csv");
            header("Pragma: no-cache");
            header("Expires: 0");
            $file = fopen('php://output', 'w');
            fputcsv($file, $headerArr);
            //fputcsv($file, array(1, 2, 4));
            foreach ($data as $key => $row) {
                if (!empty($row['ether_interface'])) {
                    $row['ether_interface'] = " " . $row['ether_interface'] . " ";  //Added space to avoid format change in Display CSV -- Kinjal
                }
                fputcsv($file, $row);
            }
            $file = fopen('php://output', 'w');
            exit();
        }
    }

    public static function addZeroInIPV6($baseIpv6) {
        $array = explode(":", $baseIpv6);
        $output = "";
        foreach ($array as $key => $val) {
            $array[$key] = str_pad($val, 4, "0", STR_PAD_LEFT);
        }
        return $output = implode(":", $array);
    }

    public static function ipv6Generation($baseIpv6, $loopback) {
        $loopback = str_replace(".", ":", $loopback);
        $prefixData = explode(":", $baseIpv6);
        $thridOctate_1 = substr($prefixData[2], 2, 2);
        $thridOctate_2 = substr($prefixData[3], 0, 2);
        return $ipv6 = $prefixData[0] . ":" . $prefixData[1] . ":" . $thridOctate_1 . "" . $thridOctate_2 . ":" . "0000:" . $loopback;
    }

    public static function convertNIPHtmlToText($html, $encoding = 'UTF-8', $keepIndentation = false) {
        $textContent = self::convertHtmlToText($html, $encoding);
        $textContent = str_replace(array("\r\n", "\r"), "\n", $textContent);
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
        $textContent = implode("\r\n", $new_lines);
        return $textContent;
    }

    /*     * *******Added**for***inserting**the**multiple***record*************** */

    public static function multipleInsert($model, $importData, $chunklimit, $transactionSupport = true) {
        if (!empty($importData)) {
            $batches = array();
            $batches = array_chunk($importData, $chunklimit);
            $totalbatches = count($batches);

            // echo "\ntotalbatches are-------".$totalbatches;

            $cnt = 1;
            if ($transactionSupport) {
                $transaction = $model->getDbConnection()->beginTransaction();
                try {
                    foreach ($batches as &$batch) {
                        //echo "batch number is------".$cnt;
                        $query = $model->commandBuilder->createMultipleInsertCommand($model->tableName(), $batch);
                        $query->execute();
                        $cnt++;
                    }
                    $transaction->commit();
                    return true;
                } catch (Exception $ex) {
                    Yii::log('error', $ex->getMessage());
                    if ($transactionSupport)
                        $transaction->rollback();
                    throw $ex;
                }
            }
        }
        return false;
    }

    public static function getFirstIPFromPoolByRange($ipPoolForthPart, $poolIpAdd) {
        if ($ipPoolForthPart >= 0 && $ipPoolForthPart < 32) {
            return $poolIpAdd;
        } else if ($ipPoolForthPart >= 32 && $ipPoolForthPart < 64) {
            return 32 + $poolIpAdd;
        } else if ($ipPoolForthPart >= 64 && $ipPoolForthPart < 96) {
            return 64 + $poolIpAdd;
        } else if ($ipPoolForthPart >= 96 && $ipPoolForthPart < 128) {
            return 96 + $poolIpAdd;
        } else if ($ipPoolForthPart >= 128 && $ipPoolForthPart < 192) {
            return 128 + $poolIpAdd;
        } else if ($ipPoolForthPart >= 192 && $ipPoolForthPart < 224) {
            return 192 + $poolIpAdd;
        } else if ($ipPoolForthPart >= 224 && $ipPoolForthPart < 248) {
            return 224 + $poolIpAdd;
        } else if ($ipPoolForthPart >= 248 && $ipPoolForthPart < 252) {
            return 248 + $poolIpAdd;
        } else if ($ipPoolForthPart >= 252 && $ipPoolForthPart < 254) {
            return 252 + $poolIpAdd;
        } else if ($ipPoolForthPart >= 254) {
            return 254 + $poolIpAdd;
        }

        return 1;
    }

    public static function getDivIPFromPoolByRange($ipPoolOctate, $poolIpAdd, $poolDiv) {
        if ($ipPoolOctate >= 0 && $ipPoolOctate < $poolDiv) {
            return $poolIpAdd;
        } else if ($ipPoolOctate >= $poolDiv && $ipPoolOctate < ($poolDiv * 2)) {
            return $poolDiv + $poolIpAdd;
        } else if ($ipPoolOctate >= ($poolDiv * 2) && $ipPoolOctate < ($poolDiv * 3)) {
            return (($poolDiv * 2) + $poolIpAdd);
        } else if ($ipPoolOctate >= ($poolDiv * 3) && $ipPoolOctate < ($poolDiv * 4)) {
            return (($poolDiv * 3) + $poolIpAdd);
        } else if ($ipPoolOctate >= ($poolDiv * 4) && $ipPoolOctate < ($poolDiv * 5)) {
            return (($poolDiv * 4) + $poolIpAdd);
        } else if ($ipPoolOctate >= ($poolDiv * 5) && $ipPoolOctate < ($poolDiv * 6)) {
            return (($poolDiv * 5) + $poolIpAdd);
        } else if ($ipPoolOctate >= ($poolDiv * 6) && $ipPoolOctate < ($poolDiv * 7)) {
            return (($poolDiv * 6) + $poolIpAdd);
        } else if ($ipPoolOctate >= ($poolDiv * 7) && $ipPoolOctate < ($poolDiv * 8)) {
            return ($poolDiv * 7) + $poolIpAdd;
        } else if ($ipPoolOctate >= ($poolDiv * 8) && $ipPoolOctate < ($poolDiv * 9)) {
            return ($poolDiv * 8) + $poolIpAdd;
        } else if ($ipPoolOctate >= ($poolDiv * 9) && $ipPoolOctate < ($poolDiv * 10)) {
            return ($poolDiv * 9) + $poolIpAdd;
        }
        return 1;
    }

    /**
     * Common function to fetch hostname
     * on the basis of sapid, facid and neid.
     */
    public function fetchhostname($sapid, $facid, $neid) {
        $msapid = Yii::app()->commUtility->modifySAPID($sapid);
        $criteria = new CDbCriteria;
        $criteria->select = "host_name";
        $criteria->condition = "modified_sapid=:modified_sapid AND facid=:facid AND gne_id=:gne_id AND is_deleted=0";
        $criteria->params = array('modified_sapid' => $msapid, 'facid' => $facid, 'gne_id' => $neid);
        $result = NddHostName::model()->find($criteria);
        $hostname = '';
        if (count($result) == 1) {
            $hostname = $result->host_name;
        }
        return (array("hostname" => $hostname));
    }

// END FUNCTION

    public function fetch_host_lb($sapid, $facid, $neid, $device_type) {
        $msapid = Yii::app()->commUtility->modifySAPID($sapid);
        $criteria = new CDbCriteria;
        $criteria->select = "host_name";
        $criteria->condition = "modified_sapid=:modified_sapid AND facid=:facid AND gne_id=:gne_id AND is_deleted=0";
        $criteria->params = array('modified_sapid' => $msapid, 'facid' => $facid, 'gne_id' => $neid);
        $result = NddHostName::model()->find($criteria);
        $hostname = '';
        $loopback0 = '';
        if (count($result) == 1) {
            $hostname = $result->host_name;
            $loopback0 = CommonUtility::getLoopback0BySiteType($hostname, $device_type);
        }
        return (array("hostname" => $hostname, "loopback" => $loopback0));
    }

    public static function removeSpecialChars($str = NULL) {
        if (!empty($str)) {
            $str = str_replace(' ', '-', $str); // Replaces all spaces with hyphens.
            return preg_replace('/[^A-Za-z0-9\-]/', '-', $str); // Removes special char
        }
    }

    public static function ifInSequence($sequenceArr = array(1, 2, 3, 4)) {
        if (!empty($sequenceArr)) {
            $maxCount = count($sequenceArr);
            for ($i = 1, $j = 0; $i <= $maxCount; $i++, $j++) {
                if (in_array($i, $sequenceArr) && ($sequenceArr[$j] == ($j + 1))) {
                    continue;
                } else {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public static function arrayHasDupes($array) {
        return count($array) !== count(array_unique($array));
    }

    public static function fetchhostnameDtls($hostname, $device_type) {
        $criteria = new CDbCriteria;
        $criteria->select = "host_name,modified_sapid,facid,gne_id";
        $criteria->condition = "host_name=:host_name AND is_deleted=0";
        $criteria->params = array('host_name' => $hostname);
        $result = NddHostName::model()->find($criteria);
        $hostname = '';
        $loopback0 = '';
        if (count($result) == 1) {
            $hostname = $result->host_name;
            $modified_sapid = $result->modified_sapid;
            $facid = $result->facid;
            $neid = $result->gne_id;
            $loopback0 = CommonUtility::getLoopback0BySiteType($hostname, $device_type);
        }
        return (array("hostname" => $hostname, "sapid" => $modified_sapid, "facid" => $facid, "neid" => $neid, "loopback" => $loopback0));
    }

    public static function getEmailAndName($created_by = '') {
        if (!empty($created_by)) {
            $criteria = new CDbCriteria;
            $criteria->select = "first_name ,email";
            $criteria->addCondition("emp_id = {$created_by}");
            $model = Employee::model()->find($criteria);
            return array('first_name' => $model->first_name, 'email' => $model->email);
        }
        return '';
    }

    public function getApprover1Name($model, $row) {
        if (!empty($model->approver_1_id)) {
            $criteria = new CDbCriteria;
            $criteria->select = "CONCAT(first_name ,' ', last_name) AS first_name";
            $criteria->addCondition("emp_id = {$model->approver_1_id}");
            $model1 = Employee::model()->find($criteria);
            return $model1->first_name;
        }
        return '';
    }

    public static function getContentType($file_name = '') {
        $ctype = '';
        if (!empty($file_name)) {
            $tmp = explode(".", $file_name);
            switch ($tmp[count($tmp) - 1]) {
                case "pdf": $ctype = "application/pdf";
                    break;
                case "exe": $ctype = "application/octet-stream";
                    break;
                case "zip": $ctype = "application/zip";
                    break;
                case "docx":
                case "doc": $ctype = "application/msword";
                    break;
                case "csv":
                case "xls":
                case "xlsx": $ctype = "application/vnd.ms-excel";
                    break;
                case "ppt": $ctype = "application/vnd.ms-powerpoint";
                    break;
                case "gif": $ctype = "image/gif";
                    break;
                case "png": $ctype = "image/png";
                    break;
                case "jpeg":
                case "jpg": $ctype = "image/jpg";
                    break;
                case "tif":
                case "tiff": $ctype = "image/tiff";
                    break;
                case "psd": $ctype = "image/psd";
                    break;
                case "bmp": $ctype = "image/bmp";
                    break;
                case "ico": $ctype = "image/vnd.microsoft.icon";
                    break;
                default: $ctype = "application/force-download";
            }
        }
        return $ctype;
    }

    public static function getDeviceTypeByCode($code) {
        $type = trim($code);
        $device_type = Yii::app()->params['all_device_type'];
        if (isset($device_type[$type]) && $device_type[$type] != '')
            return $device_type[$type];
    }

    public static function getDeviceDetailByHostname($hostname) {

        $deviceType = substr($hostname, 8, 3);

        switch ($deviceType) {
            case "ESR" : //css
                {
                    $criteria = new CDbCriteria;
                    $criteria->select = "t.pdf_done, t.host_name";
                    $criteria->join = "INNER JOIN ndd_request_master as tu ON t.enode_b_sapid=tu.sapid  and  t.request_id = tu.request_id";
                    $criteria->addCondition("t.host_name = '{$hostname}' AND t.pdf_done = 1 AND tu.is_disabled=0");
                    $model = NddOutputMaster::model()->find($criteria);
                    return $model;
                }
            case "PAR": //ag1
                {
                    $criteria = new CDbCriteria;
                    $criteria->select = "t.pdf_done, t.hostname";
                    $criteria->join = "INNER JOIN ndd_ag1_input as tu ON t.modified_sapid=tu.modified_sapid  AND  t.input_id = tu.id";
                    $criteria->addCondition("t.hostname = '{$hostname}' AND t.pdf_done = 1 AND tu.is_active=1");
                    $model = NddAg1Outputmaster::model()->findByAttributes(array('hostname' => $hostname, 'pdf_done' => 1));
                    return $model;
                }
            case "AAR": //ag2
                {
                    $model = Ag2data::model()->findByAttributes(array('hostname' => $hostname, 'is_deleted' => 0));
                    if (!empty($model) && $model->file_name != '')
                        return $model;
                    else
                        return 0;
                }
            case "CRR": //ag3
                {
                    $model = Ag3data::model()->findByAttributes(array('hostname_1' => $hostname, 'is_deleted' => 0));
                    if (!empty($model) && $model->file_name != '')
                        return $model;
                    else
                        return 0;
                }
            /*
              case "ESR":
              return "css";
              case "PAR":
              return "ag1";
              case "AAR":
              return "ag2";
              case "CRR":
              return "ag3";
              case "CBR":
              return "rtbh";
              case "CSR":
              return "SAR";
              case "IAR":
              return "IBR";
              case "CES":
              case "CAS":
              return "nexus";
              case "CMR":
              return "dcncore";
             */
        }
    }

}
