<?php

/* * ************************************************************
 *  File Name : Admin.php
 *  File Description: Admin Model File to manage database related functionality and define access rule.
 *  Author: Benchmark, 
 *  Created Date: 17	/2/2014
 *  Created By: Yogesh Jadhav
 * ************************************************************* */
/* Initializing $action variable to match the case depend on action */

/**
 * This is the model class for table "tbl_user".
 *
 * The followings are the available columns in table 'tbl_user':
 * @property string $user_id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $suffix
 * @property string $created_date
 * @property string $modified_date
 * @property integer $is_active
 * @property integer $is_deleted
 *
 * The followings are the available model relations:
 * @property LawfirmDetail[] $lawfirmDetails
 * @property UserAccess[] $userAccesses
 * @property UserDetail[] $userDetails
 */
class Folder extends CFormModel {

    public $folder = '';
    public $host_name = '';
    public $ip_address = '';

    const ipCheck = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('folder', 'required'),
            array('host_name, ip_address', 'required', 'on' => 'csvcupload'),
            array('ip_address', 'required', 'on' => 'addNewCspcDAV'),
            array('host_name', 'length', 'min' => 14, 'max' => 40, 'on' => 'csvcupload'),
            array('ip_address', 'length', 'min' => 10, 'max' => 40, 'on' => 'csvcupload'),
            array('ip_address', 'length', 'min' => 10, 'max' => 40, 'on' => 'addNewCspcDAV'),
            array('ip_address', 'ipValidation', 'strength' => self::ipCheck, 'on' => 'csvcupload'),
            array('folder, host_name,ip_address', 'safe')
        );
    }

    /**
     * check if the valid IP Address
     * This is the 'ipValidation' validator as declared in rules().
     */
    public function ipValidation($attribute, $params) {
        if ($params['strength'] === self::ipCheck)
            $pattern = '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/';

        if (!preg_match($pattern, $this->$attribute))
            $this->addError($attribute, 'IP address not valid');
    }

    public static function readFolderOrderByASC($folder = '', $pattern = null, $date = null) {
        $files = array();
        $folder_path = "";
        if ($folder != '' && file_exists($folder)) {
            //$folder='test_plan_unprocess_files/';
            $folder_path = $folder;
            if ($handle = opendir($folder)) {
                $files = array();
                if (!empty($date)) {
                    $absolute_path = dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR;

                    $modify_date = date("M d", strtotime($date));
                    $date_ary = explode(" ", $modify_date);
                    if (preg_match("/^0.*/", $date_ary[1])) {
                        $date_ary[1] = str_replace("0", " ", $date_ary[1]);
                    }
                    $modify_date = $date_ary[0] . " " . $date_ary[1];
                    $folder_base_path = $absolute_path . $folder;
//                    $command = "ls -lt --time-style=full-iso {$folder} | grep '" . $date . "' | awk '{print $9}'";
//                    find /var/www/html/test_plan_unprocess_files/AG1/Network_1/ -type d -ls -maxdepth 1 | grep 'Nov 4' | awk '{print $11}'
                    $command = "find " . $folder_base_path . " -type d -ls -maxdepth 1 | grep '" . $modify_date . "' | awk '{print $11}'";
                } else {
                    echo $command = "ls -lt --time-style=full-iso {$folder} | awk '{print $9}'";
                }
                @exec($command, $folders);
                if (PHP_OS == "WINNT") {
                    $folders = scandir($folder, 0);
                }
                //$folders = scandir($folder, 0);
                foreach ($folders as $folder) :
//                    if ($folder != "." && $folder != ".." && $folder != 'empty') {
//                        if (file_exists($folder_path . $folder . '/CLI/')) {
//                            $files[$folder] = $folder;
//                        }
//                    }

                    $folder_name = str_replace($folder_base_path, "", $folder);
                    if ($folder_name != "." && $folder_name != ".." && $folder_name != 'empty') {
                        if (file_exists($folder_path . $folder_name . '/CLI/')) {
                            $files[$folder_name] = $folder_name;
                        }
                    }
                endforeach;
                echo count($files);
//                $files = rsort($files);
                closedir($handle);
            }
        }
        $tst_files = krsort($files);
        return $files;
    }

    public static function readShowRunFolderOrderByASC($folder = '', $pattern = null) {
        $files = array();
        $folder_path = "";
        if ($folder != '' && file_exists($folder)) {
            //$folder='test_plan_unprocess_files/';
            $folder_path = $folder;
            if ($handle = opendir($folder)) {
                $files = array();
                // $folders = scandir($folder, 0); //0 for ascending order, and 1 for descending order
                $folders = scandir($folder, 0);
                foreach ($folders as $folder) :
                    if ($folder != "." && $folder != ".." && $folder != 'empty') {
                        if (file_exists($folder_path . $folder . '/CLI/')) {

                            if (!preg_match('/^NetworkDevice_/', $folder)) {
                                continue;
                            }

                            $ipMaster = new IpMaster();
                            $details = $ipMaster->getNIPHostDetails($folder_path . $folder . '/CLI/');

                            $newFolderName = $details['hostname'];
                            $newFolderPath = str_replace("Network_1/", "", $folder_path . $newFolderName);
                            $folder_val = $details['hostname'] . "_" . $details['ip_address'];

                            $files[$newFolderName] = $folder_val;

                            CFileHelper::copyDirectory($folder_path . $folder, $newFolderPath, array('newFileMode' => 0777));
                        }
                    }
                endforeach;

                shell_exec("rm " . $folder_path . " -R");

                closedir($handle);
            }
        }
        return $files;
    }

    /*
     * L2 Parser
     */

    public static function readL2ShowRunFolderOrderByASC($folder = '', $pattern = null) {
        $files = array();
        $folder_path = "";
        if ($folder != '' && file_exists($folder)) {
            //$folder='test_plan_unprocess_files/';
            $folder_path = $folder;
            if ($handle = opendir($folder)) {
                $files = array();
                // $folders = scandir($folder, 0); //0 for ascending order, and 1 for descending order
                $folders = scandir($folder, 0);

                foreach ($folders as $folder) :
                    if ($folder != "." && $folder != ".." && $folder != 'empty') {

                        if (file_exists($folder_path . $folder . '/CLI/')) {

                            if (!preg_match('/^NetworkDevice_/', $folder)) {
                                continue;
                            }

                            $ipMaster = new IpMaster();
                            $details = $ipMaster->getL2NIPHostDetails($folder_path . $folder . '/CLI/');

                            $newFolderName = $details['hostname'];
                            $newFolderPath = str_replace("Network_1/", "", $folder_path . $newFolderName);
                            $folder_val = $details['hostname'] . "_" . $details['ip_address'];

                            $files[$newFolderName] = $folder_val;

                            CFileHelper::copyDirectory($folder_path . $folder, $newFolderPath, array('newFileMode' => 0777));
                        }
                    }
                endforeach;

                shell_exec("rm " . $folder_path . " -R");

                closedir($handle);
            }
        }
        return $files;
    }

    /*
     * L2 Switch Parser For Reliance
     */

    public static function readL2SwitchShowRunFolderOrderByASC($folder = '', $device_type = null) {
        $files = $file_status = array();
        $folder_path = "";
        if ($folder != '' && file_exists($folder)) {
            //$folder='test_plan_unprocess_files/';
            $folder_path = $folder;
            if ($handle = opendir($folder)) {
                $files = array();
                // $folders = scandir($folder, 0); //0 for ascending order, and 1 for descending order
                $folders = scandir($folder, 0);

                foreach ($folders as $folder) :
                    if ($folder != "." && $folder != ".." && $folder != 'empty') {

                        if (file_exists($folder_path . $folder . '/CLI/')) {

                            if (!preg_match('/^NetworkDevice_/', $folder)) {
                                continue;
                            }

                            $l2Switch = new L2SwitchPlans();
                            $file_status = $l2Switch->createShRunnFileForL2Switch($folder_path . $folder . DIRECTORY_SEPARATOR . 'CLI' . DIRECTORY_SEPARATOR, $device_type); //Create sh runn file from "show file startup-config"
                            $details = $l2Switch->getL2SwitchNIPHostDetails($folder_path . $folder . DIRECTORY_SEPARATOR . 'CLI' . DIRECTORY_SEPARATOR, $device_type);

                            $newFolderName = $details['hostname'];
                            $newFolderPath = str_replace("Network_1" . DIRECTORY_SEPARATOR, "", $folder_path . $newFolderName);
                            if (!empty($newFolderName)) {
                                $folder_val = $details['hostname'] . "_" . $details['ip_address'];

                                $files[$newFolderName] = $folder_val;

                                CFileHelper::copyDirectory($folder_path . $folder, $newFolderPath, array('newFileMode' => 0777));
                            }
                        }
                    }
                endforeach;

                shell_exec("rm " . $folder_path . " -R");

                closedir($handle);
            }
        }
        return $files;
    }

    public static function readFolder($folder = '', $pattern = null) {
        $files = array();

        if ($folder != '' && file_exists($folder)) {
            //$folder='test_plan_unprocess_files/';
            if ($handle = opendir($folder)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != ".." && $entry != 'empty') {
                        if (file_exists($folder . $entry . '/CLI/')) {
                            if ($pattern) {
                                if (preg_match($pattern, $entry)) {
                                    $files[$entry] = $entry;
                                }
                            } else {
                                $files[$entry] = $entry;
                            }
                        }
                    }
                }
                closedir($handle);
            }
        }
        return $files;
    }

    public static function readBlankFolder($folder = '', $pattern = null) {
        $directories = array();
        if ($folder != '' && file_exists($folder)) {
            if ($handle = opendir($folder)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != ".." && $entry != 'empty') {
                        if (!file_exists($folder . $entry . '/CLI/')) {
                            $foldername = explode("/", $folder . $entry);
                            $foldername = $foldername[3];

                            if (preg_match('/^NetworkDevice_/', $foldername))
                                $directories[$foldername] = $foldername;
                        }
                    }
                }
                closedir($handle);
            }
        }
        return $directories;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'folder' => 'Router Ip Files',
            'host_name' => 'Host name',
            'ip_address' => 'IP address',
        );
    }

    public function search($is_duplicate = 0) {
        
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Admin the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function readDirectoryFiles($folder = '', $with_full_path = false) {
        $files = array();

        if ($folder != '' && file_exists($folder)) {
            //$folder='test_plan_unprocess_files/';
            if ($handle = opendir($folder)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != ".." && $entry != 'empty') {
                        if ($with_full_path) {
                            $files[] = $folder . DIRECTORY_SEPARATOR . $entry;
                        } else {
                            $files[] = $entry;
                        }
                    }
                }
                closedir($handle);
            }
        }
        return $files;
    }

    /**
     * Get Ip address and hostname from NIP File
     * 
     * @param string $dirPath
     * @return array
     */
    public function getL2SwitchNIPHostDetails($dirPath) {
        $dirPath = rtrim($dirPath, "/");
        $_parser = new NIPParser();
        $_parser->setFile($dirPath . "/" . NIPParser::NIP_FILE_NAME);
        $_parser->parseL2ShowRun();
        $ip_address = $_parser->getIpv4Address();
        $hostname = $_parser->getHostname();
        return array('hostname' => $hostname, 'ip_address' => $ip_address, 'result' => $_parser->getResult());
    }

}
