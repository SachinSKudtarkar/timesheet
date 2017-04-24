<?php?>
<form class="form" action="<?php echo Yii::app()->baseUrl; ?>/daycomment/addcomment" method="post" id="addcommentfrm">
    <input type="hidden" name ="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken; ?>"  />

    <h1>Day Comments</h1>
    <div class="row" >
        <div class="row" >
            <div class="span5">
                
                <?php
					$selecting_date = "";
					if (isset($_GET['selecting_date'])) {
						$selecting_date = $_GET['selecting_date'];
						$selecting_date = date('Y-m-d', strtotime($selecting_date));
					} else {
						$selecting_date = date('Y-m-d', strtotime("monday this week"));
					}
					echo CHTML::label('Select week :', '', array('style' => 'width:80px; font-weight:bold;'));
				
					for ($mondayCounter = 4; $mondayCounter >= 0; $mondayCounter--) {
						$da = date('d-m-Y', strtotime(" -" . $mondayCounter . "monday this week"));
						$mondayList[date('Y-m-d', strtotime(" -" . $mondayCounter . "monday this week"))] = $da . " to " . date('d-m-Y', strtotime("+6 day", strtotime($da)));
					}
                                        
					//add 1 week additional
					$da = date('d-m-Y', strtotime("monday next week"));
					$mondayList[date('Y-m-d', strtotime("monday next week"))] = $da . " to " . date('d-m-Y', strtotime("+6 day", strtotime($da)));

					echo CHtml::dropDownList('selecting_weeks', $da, $mondayList, array('class' => 'selecting_weeks', 'empty' => 'Select Value', 'options' => array($selecting_date => array('selected' => true))));

					$btnShow = FALSE;
					//$is_submitted = TRUE;
     
					if (strtotime($selecting_date) >= strtotime("monday this week") ) {
						$btnShow = TRUE;
					}else if (strtotime($selecting_date) <= strtotime("monday this week") ) {
						$is_submitted = TRUE;
					}
					
					$is_submitted = false;
					$btnShow = true;
				?>
                
            </div>
        </div>
    </div>
    <?php
    echo "<input type='hidden' name='selected_date' value='{$selecting_date}' />";
    $img_path = Yii::app()->theme->baseUrl . "/img/add_image.png";
    
    for ($k = 0; $k <= 6; $k++) {
        $generte_date = strtotime("+{$k} day", strtotime($selecting_date));
        $date = date("Y-m-d", $generte_date);
        $date_id = date("Y_m_d", $generte_date);

		if(key_exists($date,$arrSubmitted))
			$is_submitted = $arrSubmitted[$date];
		
		if(key_exists($date_id, $arrData)) {
               ?>
			<div class='main_daycomments'>;
			
		<?php	$i = 0;
			
			foreach ($arrData[$date_id] as $key=>$eachproject) {
                           
				
				$tmpcls = ($i > 0) ? '' : '';
				$nxt = $i + 1;
                                
                                
					?>

				<?php
				$i++;
			}
			?>
			</div>
                <?php }else { ?>
                    <div class="main_daycomments">
			<?php
			$i = 0;
			echo "<pre>";
                        print_r($allProjects);
			foreach ($allProjects as $eachproject) {
				$tmpcls = ($i > 0) ? '' : '';
				$nxt = $i + 1;
				?>
					
				<?php
                                        
				$i++;
				break;
			}
    }
    
                }?>
                    </div>
</form>