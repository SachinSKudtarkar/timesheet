<?php 
/* * ************************************************************
*  File Name : blockuser.php
*  File Description: This File is use to display block user message .
*  Author: Benchmark, 
*  Created Date: 17	/2/2014
*  Created By: Yogesh Jadhav
* ************************************************************* */
setcookie ("login", "", time() - 3600); //Unset the cookie
?>
<div class="row-fluid" style="min-height:555px;">
	<div class="login-box clearfix" style="min-height:555px;">
		<div class="well span6 offset3" style="padding-top:20px; margin-top:9%;">
			<h2  class="text-info" style="font-size:12px;">Access Denied !!</h2>
		</div>
	</div>   
</div>