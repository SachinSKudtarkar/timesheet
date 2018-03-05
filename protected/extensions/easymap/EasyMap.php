<?php
class EasyMap extends CWidget {

public $key;
public $id = 'map-canvas';
public $latitude = '15.299326500000000000';
public $longitude = '74.123996000000030000';
public $maptype = 'ROADMAP';
public $zoom = '7';
public $width = '300';
public $height = '250';
public $markertitle = '';
public $style = array();


public function init() {
echo "<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?key=".$this->key."&sensor=true'></script>";
echo "<script language='javascript'>
".$this->mapScript."
google.maps.event.addDomListener(window, 'load', initialize); 
</script>";
echo "<div id='".$this->id."' style='width:".$this->width."px; height:".$this->height."px;'></div>";
}

protected function getMapScript(){
$script = 'function initialize() {';

if(!empty($this->style)){
$script .= $this->mapStyle;
}

$script .= 'var myLatlng = new google.maps.LatLng("'.$this->latitude.'","'.$this->longitude.'");';

if(!empty($this->style)){
$script .= 'var mapOptions = {
zoom:'.$this->zoom.',
center: myLatlng,
mapTypeId: google.maps.MapTypeId.'.$this->maptype.',
styles: styles,
};';
}else{
$script .= 'var mapOptions = {
zoom:'.$this->zoom.',
center: myLatlng,
mapTypeId: google.maps.MapTypeId.'.$this->maptype.',
};';
}
$script .= 'var map = new google.maps.Map(document.getElementById("'.$this->id.'"), mapOptions);';
$script .=  'var marker = new google.maps.Marker({
position: myLatlng,
map: map,
title: "'.$this->markertitle.'"
});';
$script .= '}';
return $script;	
}


protected function getMapStyle(){
$js_array = json_encode($this->style);
$code = "var styles = ". $js_array . ";\n";
return $code;
}

	
}

?>

