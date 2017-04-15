<?php
/** ************************************************************
 *  File Name		: ManageUrl.php
 *  Class Name		: ManageUrl
 *  File Description    : for casesensitive url.
 *  Author		: Benchmark, 
 *  Created Date	: 03th March 2014 12:23:00 PM IST
 *  Develop By		: Anand Rathi
 * ************************************************************* */
class ManageUrl extends CWebApplication
{
	public $mail;
    // Overwrite createController function of CWebApplication class 
    public function createController($route,$owner=null)
    {
        if($owner===null)
                $owner=$this;
        if(($route=trim($route,'/'))==='')
                $route=$owner->defaultController;
        $caseSensitive=$this->getUrlManager()->caseSensitive;

        $route.='/';
        while(($pos=strpos($route,'/'))!==false)
        {
            $id=substr($route,0,$pos);
            if(!preg_match('/^\w+$/',$id))
                    return null;
            if(!$caseSensitive)
                    $id=strtolower($id);
            $route=(string)substr($route,$pos+1);
            if(!isset($basePath))  // first segment
            {
                    if(isset($owner->controllerMap[$id]))
                    {
                            return array(
                                    Yii::createComponent($owner->controllerMap[$id],$id,$owner===$this?null:$owner),
                                    $this->parseActionParams($route),
                            );
                    }

                    if(($module=$owner->getModule($id))!==null)
                            return $this->createController($route,$module);

                    $basePath=$owner->getControllerPath();
                    $controllerID='';
            }
            else
                    $controllerID.='/';
            $className=ucfirst($id).'Controller';
            $classFile=$basePath.DIRECTORY_SEPARATOR.$className.'.php';

            if($owner->controllerNamespace!==null)
                    $className=$owner->controllerNamespace.'\\'.str_replace('/','\\',$controllerID).$className;
            // Added by Anand to manage casesensitive URL
            if(!$caseSensitive) $classFile = $this->file_iexists ($classFile);
            if(is_file($classFile))
            {
                    if(!class_exists($className,false))
                            require($classFile);
                    if(class_exists($className,false) && is_subclass_of($className,'CController'))
                    {
                            $id[0]=strtolower($id[0]);
                            return array(
                                    new $className($controllerID.$id,$owner===$this?null:$owner),
                                    $this->parseActionParams($route),
                            );
                    }
                    return null;
            }
            $controllerID.=$id;
            $basePath.=DIRECTORY_SEPARATOR.$id;
        }
    }
    
    /**
    *  	Usage       :   cheking if file exists exactly class name
    *	Parameters  :
    *		- path   -  path of class file
    *	How to Use  :   file_iexists( $path );
    **/
    private function file_iexists($path) {
        $dirname = dirname($path);
        $filename = basename($path);
        $dir = dir($dirname);
        while ( ($file = $dir->read()) !== false) {
            if (strtolower($file) == strtolower($filename)) {
                $dir->close();
                return $dirname.DIRECTORY_SEPARATOR.$file;
            }
        }
        $dir->close();
        return false;
    }    
}
?>