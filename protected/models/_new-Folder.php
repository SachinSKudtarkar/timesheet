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
            array('folder', 'safe', 'on' => 'search')
        );
    }

    public static function readFolder($folder = '', $pattern = null) {
        $files = array();

        if ($folder != '' && file_exists($folder)) {
            //$folder='test_plan_unprocess_files/';
            if ($handle = opendir($folder)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != ".." && $entry != 'empty') {
                        if ($pattern) {
                            echo $entry."<br>";
                            if (preg_match($pattern, $entry)) {
                                $files[$entry] = $entry;
                            }
                        } else {
                            $files[$entry] = $entry;
                        }
                    }
                } die();
                closedir($handle);
            }
        }
        return $files;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'folder' => 'Router Ip Files',
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

}
