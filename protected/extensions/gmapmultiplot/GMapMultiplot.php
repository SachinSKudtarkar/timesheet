<?php
/**
 * GmapMultiplot class file.
 *
 * @author Jason Vickers <jvickers@fatlynxmedia.com>
 * @link http://www.fatlynxmedia.com/                
 * @license http://www.yiiframework.com/license/
 * 
 * This PHP (GMap.php) and accompanying Javascript (gmap.js) code are offered under the Open Source BSD license.
 * 
 * GMapMultiplot is based on Yii Gmap extension written by Konstantin Mirin (konstantin@takeforce.net)
 * Mirin's extension can be found at http://www.yiiframework.com/extension/gmap/
 * 
 * gmapmultiplot.js uses GMap API v3
 */
 
 /**
 * GMap works by:
 * 1. Accepts an array of ActiveRecord Objects
 * 2. Loops through the array, feeding the address and window data to gmap.js
 * 3. gmapmultiplot.js takes the address and sends it go maps.google.com, which returns a geocoded Latitude and Longitude
 * 4. Mapmarker is created with the Lat/Lng and a window(bubble) is created with name, address and phone number
 * 5. For multiple address, the map zooms out and centers so that all MapMarkers are visible.
 * 
 * Copy gmap directory into protected/extensions/
 *
 * // User the following code to call your gmap
 * $this->widget('application.extensions.gmapmultiplot.GMapMultiplot', array(
 *      'id' => 'gmap',//id of the <div> container created
 *      'label' => $binfo->tradename, //Title for bubble. Used if you are plotting multiple locations of same business
 *      'address' =>  $locationArray, //Array of AR objects
 * ));
 * 
 * If you have any questions or comments, please feel free to contact me at jvickers@fatlynxmedia.com ..... enjoy :)
 * See the map in action at www.rebateshamptonroads.com
 * 
 */

class GMapMultiplot extends CWidget
{ 
    /**
     * ID of the <div> element created fro the map
     *
     * @var string
     */
    public $id;
    /**
     * Google key to access API
     *
     * @var unknown_type
     */
    public $key;
    /**
     * Text to write on the marker's label
     *
     * @var string
     */
    public $label;
    /**
     * Default zoom when centering the map (0..17)
     *
     * @var int
     */
    public $zoom;
    /**
     * Error message to display if nothing was found
     *
     * @var string
     */
    public $errorMessage;
    /**
     * Holds address of the place
     *
     * @var string
     */
    private $_address;
        
    /**
     * Sets AR array for the widget
     * @return unknown
     */
    public function setAddress($a)
    {
        $this->_address = $a;
    }
    
    /**
     * Returns AR array of the widget
     *
     * @return unknown
     */
    public function getAddress()
    {
        return $this->_address;
    }
    
    public function init()
    {
        //translation (Yii::t()) may be added here if needed
        if (empty($this->errorMessage)) $this->errorMessage = 'Sorry, location is not found';
        
        $cs = Yii::app()->clientScript;
        $cs->registerMetaTag('initial-scale=1.0, user-scalable=no', 'viewport');
        $cssFile = CHtml::asset(dirname(__FILE__).DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'gmap.css');
        $cs->registerCssFile($cssFile);
    }
    
    public function run()
    {
        // The map that I needed was 600px wide. You can change this to whatever you need.
        echo '<div style="width: 600px" id="'.$this->id.'"></div>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script src="'.CHtml::asset(dirname(__FILE__).DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'gmapmultiplot.js').'" type="text/javascript"></script>
<script language="javascript">
'.$this->js.'
</script>';
    }
    
    /**
     * Renders the contents of the <script> tag
     *
     * @return string
     */
    public function getJs()
    {
        $counter = 0;
        $only = 0;
        
        $ret = '$(document).ready(function() {
            var a = new Array();';
        $ret .= 'initialize("'.$this->id.'");';
        
         // Sets trigger so that if only one address is passed through, the GMap will zoom correctly
         if(!(sizeof($this->_address) > 1))
         {
            $only = 1;    
         }
          
        // Loop through array of AR objects  
        foreach($this->_address as $item)
        {   
            $address = '';
            if(!empty($item->address1)){$address.=$item->address1.' ';}
            if(!empty($item->address2)){$address.=$item->address2;}
            if(!empty($item->city)){$address.=', '.$item->city.', ';}
            if(!empty($item->state)){$address.=$item->state.' ';}
            if(!empty($item->zip)){$address.=$item->zip;}
            
            // If you need a different title for each MapMarker, simply replace $this->label with appropriate attribute
            $ret .= 'geocode("'.$address.'", "'.$item->bphone.'", "'.$this->label.'", '.$only.');';
        }
        
        
        $ret .= '})';
        
        return $ret;
    }
}
?>


