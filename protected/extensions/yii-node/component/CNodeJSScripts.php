<?php
/** * ***********************************************************
 *  File Name		: CNodeJSScripts.php
 *  Class Name		: CNodeJSScripts
 *  File Description    : Script including.
 *                          -   setModel
 *                          -   updateModel
 *                          -   register Yii Node Scripts
 *  Author              : Benchmark, 
 *  Created Date	: 16th April 2014 04:07:55 PM IST.
 *  Develop By		: Anand Rathi.
 * ************************************************************* */
class CNodeJSScripts {

    // collect all model list to set model
    public static $model_list = '';

    /**
     *   Usage	:   Register nodejs script
     *   Parameters  :
     *       - $site_url        - Site url( default : http://localhost )
     *       - $port            - port number( default : 3000 )
     *       - $asset_path      - accest path( default : null )
     *       - $client_script   - client script file name( default : model_update.js )
     *   How to Use  :   CNodeJSScripts::registerYiiNodeScripts( $site_url = null, $port = null, $asset_path = null, $client_script = null );
     * */
    public static function registerYiiNodeScripts($site_url = null, $port = null, $asset_path = null, $client_script = null) {
        //$site_url = $site_url ? $site_url : 'http://localhost';
        //$site_url = $site_url ? $site_url : 'http://192.168.1.56';
        $site_url = $site_url ? $site_url : 'http://71.43.59.189';
        $port = $port ? $port : '9002';
        $client_script = $client_script ? $client_script : 'model_update.js';

        $asset_path = empty( $asset_path ) ? CHelper::basePath() . '/extensions/yii-node/client' : $access_path;
        $baseUrl = Yii::app()->assetManager->publish( $asset_path );

        Yii::app()->clientScript->registerScriptFile($site_url . ':' . $port . '/socket.io/socket.io.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/' . $client_script, CClientScript::POS_HEAD);
    }
    /**
     *   Usage	:   Set model to server
     *   Parameters  :
     *       - $model_name  - Model name( Compulsory )
     *       - $primary_key - primary key value( Compulsory )

     *   How to Use  :   CNodeJSScripts::setModel( $model_name, $primary_key );
     * */
    public static function setModel( $model_name, $primary_key ) {
        self::$model_list .= "setModel('" . $model_name . "','" . $primary_key . "');" . "\n";
        self::registerSetModel( trim( self::$model_list ) );
    }
    
    /**
     *   Usage	:   Register set models as script 
     *   Parameters  :
     *       - $model_list        - model list( Compulsory )
     *   How to Use  :   CNodeJSScripts::registerSetModel( $models );
     * */
    public static function registerSetModel( $model_list ) {
        Yii::app()->clientScript->registerScript( 'setmodels', $model_list, CClientScript::POS_HEAD );
    }
    
    /**
     *   Usage	:   Request to server update model
     *   Parameters  :
     *       - $selector    - Selector( Compulsory )
     *       - $model_name  - Model Name( Compulsory )
     *       - $primary_id  - Primary key( Compulsory )
     *       - $attribute   - Attribute( Compulsory )
     *       - $value       - Attribute value( Compulsory )
     *   How to Use  :   CNodeJSScripts::updateModel( $selector, $model_name, $primary_id, $attribute, $value );
     * */
    public static function updateModel($selector, $model_name, $primary_id, $attribute, $value) {
        Yii::app()->clientScript->registerScript('updatemodels', "
            updateModel({
                selector: '" . $selector . "',
                model: '" . $model_name . "',
                id: '" . $primary_id . "',
                attribute: '" . $attribute . "',
                value: '" . $value . "'
            });
        ", CClientScript::POS_HEAD);
    }
}
?>