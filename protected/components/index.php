<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
require('telnet_new_class.php');

$host = "172.16.44.5";
$name = "takhilesh";
$pass = "Cedar&802";
//$name = "rjil";
//$pass = "rjil123";

echo "<strong>Host Id :".$host."<br/>";
$t = new TELNET();
echo("CONNECT:".$t->Connect($host, $name, $pass)."<br>");
echo("LOGIN:".(int)$t->LogIn()."</strong>");
echo("<br>COMMAND:<br>");
/*
$result = $t->GetOutputOf("sh run int bdi 101");
$formated_data = implode('<br/>',$result);
print_r($formated_data);
*/

$checkpoint = 0;
$t->GetOutputOf("term length 0");

echo "<br/>Output :";
$rows = $t->GetOutputOf("sh ip bgp vpnv6 unicast vrf  RJIL-BEARER-ENB");

foreach ($rows as $key => $val) {
        $line = trim($val);

		if(preg_match("/Route Distinguisher/", $line))
		{
			$checkpoint = 1;
		}
		
		if($checkpoint == 1) {
			if(strpos($line, '*')!==FALSE){
					
					$matchIp = array_pop(explode(' ',$line));

					$command = "sh ip bgp vpnv6 unicast vrf  RJIL-BEARER-ENB ".$matchIp;
					//$commandName = str_replace('/','_',$command);
					$commandName = "sh ip bgp vpnv6 unicast vrf  RJIL-BEARER-ENB";
					$commandName = str_replace(' ','_',$commandName);					
					
					$data = $t->GetOutputOf($command);
					$data1 = implode("\n",$data);

					$unwanted_array = array('&aacute;'=>'á', '&Aacute;'=>'Á', '&agrave;'=>'à', '&Agrave;'=>'À', '&acirc;'=>'â', '&Acirc;'=>'Â', '&aring;'=>'å', '&Aring;'=>'Å', '&atilde;'=>'ã', '&Atilde;'=>'Ã', '&auml;'=>'ä', '&Auml;'=>'Ä', '&aelig;'=>'æ', '&AElig;'=>'Æ', '&ccedil;'=>'ç', '&Ccedil;'=>'Ç', '&eacute;'=>'é', '&Eacute;'=>'É', '&egrave;'=>'è', '&Egrave;'=>'È', '&ecirc;'=>'ê','&Ecirc;'=>'Ê', '&euml;'=>'ë', '&Euml;'=>'Ë', '&iacute;'=>'í', '&Iacute;'=>'Í', '&igrave;'=>'ì', '&Igrave;'=>'Ì', '&icirc;'=>'î', '&Icirc;'=>'Î', '&iuml;'=>'ï', '&Iuml;'=>'Ï', '&ntilde;'=>'ñ', '&Ntilde;'=>'Ñ', '&oacute;'=>'ó', '&Oacute;'=>'Ó', '&ograve;'=>'ò', '&Ograve;'=>'Ò', '&ocirc;'=>'ô', '&Ocirc;'=>'Ô', '&oslash;'=>'ø', '&Oslash;'=>'Ø', '&otilde;'=>'õ', '&Otilde;'=>'Õ', '&ouml;'=>'ö', '&Ouml;'=>'Ö', '&szlig;'=>'ß', '&uacute;'=>'ú', '&Uacute;'=>'Ú','&ugrave;'=>'ù', '&Ugrave;'=>'Ù', '&ucirc;'=>'û', '&Ucirc;'=>'Û', '&uuml;'=>'ü', '&Uuml;'=>'Ü', '&yuml;'=>'ÿ', '&nbsp;' => ' ', '&amp;' => '&', '&cent;' => '¢', '&copy;' => '©', '&divide;' => '÷', '&gt;' => '>', '&lt;' => '<', '&micro;' => 'µ', '&middot;' => '.', '&para;' => '¶', '&plusmn;' => '±', '&euro;' => '€', '&pound;' => '£', '&reg;' => '®', '&sect;' => '§', '&trade;' => '™', '&yen;' => '¥', '&ndash;' => '–', '&mdash;' => '—', '&iexcl;' => '¡', '&iquest;' => '¿', '&quot;' => '"', '&ldquo;' => '“', '&rdquo;' => '”', '&lsquo;' => '‘', '&rsquo;' => '’', '&laquo;' => '«', '&raquo;' => '»', '&#39;' => "'", '&#180;' => '´', '&#96;' => '`', 'Ã©'=>'é', 'Ãª' => 'ê', 'Ã' => 'è', '&deg;' => '°');
					$data1 = strtr($data1, $unwanted_array);					
					//echo $data1;
					
					$fh = fopen($commandName, "w") or die("can't open file");
					chmod($commandName, 0777);
					file_put_contents($commandName, $data1);
					
					break;
					//$content = file_get_contents($commandName);
					//print_r("<br/>Data:>".$r);
			}		
		}
        
    } 
   

$t->GetOutputOf("exit");

?>