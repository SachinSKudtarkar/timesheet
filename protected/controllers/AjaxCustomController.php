<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AjaxCustomController extends BaseController
{
    public function actionFetchCommandsFiles()
    {
       
       $class_to_created = trim($_REQUEST['device_type']);
       $ntw_device = trim($_REQUEST['ntw_device']);
       $new_class = 'Device_'.str_replace(' ', '_', $class_to_created);
       $parserClassFilePath = __DIR__ . '/../components/TelnetClass/' . $new_class . ".php";
       
       
       if (file_exists($parserClassFilePath))
       {
            include_once $parserClassFilePath;
            $class_object = new $new_class;
            $class_object->testCase();
       }
       else
       {
           $data['result'] = "FAILED";
           $data['description'] = "Class file missing";
           echo json_encode($data);die();
       }
    }
    
    public function actionCompare()
    {
        $id = $_POST['compare_id'];
        
        $data = IpMaster::model()->getQueryData($id);
        
        if (file_exists($filename)) {
            echo "The file $filename exists";
        } else {
            $fileNo = $_POST['compare_id'];
            exec("java -cp ../cisco/configcompare1.jar com.cisco.cstg.scrilib.SingleCompare ../cisco/uploads/showrun/".$data[0]['showrun_doc']."  ../cisco/uploads/nip/".$data[0]['nip_doc']."    ../cisco/uploads/bomtemplates/out/file_'".$fileNo."'.html");
            $data = readfile("../cisco/uploads/bomtemplates/out/file_".$fileNo.".html");exit;
        }       
    }
    
    
}
