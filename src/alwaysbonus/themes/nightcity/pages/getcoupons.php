<link href="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/css/tabs.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/scripts/tabs.js" type="text/javascript"></script>
<link href="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/css/subscribe.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/css/invite.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/scripts/jquery-ui.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/css/slider.css" type="text/css" media="screen" />
<script src="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/scripts/loopedslider.js" type="text/javascript"></script>
<script src="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/scripts/common.js" type="text/javascript"></script>
<?php 
$lang = $_SESSION["site_language"];
if($lang)
{
        include(DOCUMENT_ROOT."/system/language/".$lang.".php");
}
else
{
        include(DOCUMENT_ROOT."/system/language/en.php");
}
?>
<?php 

//pagination

$pagination = new pagination();

$pagination->createPaging($queryString,10);

$resultSet = $pagination->resultpage;
	
	if(mysql_num_rows($resultSet)>0)
	{	$i=1;
		while($row = mysql_fetch_array($resultSet))     
		{
				if($type=="P" || $row["timeleft"] > "00:01:00")
				{
					$coupon_value = $row["coupon_value"]; 
				?>
<div class="content_top fl clr mt10">
  <div class="con_top_left">
    <h2>
      <?php 
	if(strlen($row["coupon_name"])>80)
	{
		echo html_entity_decode(substr($row["coupon_name"],0,80), ENT_QUOTES)."...";
	}else
	{
		echo html_entity_decode($row["coupon_name"], ENT_QUOTES);
	}
	?>
    </h2>
    <!--<p>Wheatgrass Love</p>-->
  </div>
  <div class="con_top_right">
    <?php 
        $coupon_value = $row["coupon_value"]; 
        ?>
    <?php if($type != 'P')
		    {
		    ?>
    <p> <?php echo CURRENCY;?>
      <?php 
            if(ctype_digit($coupon_value)) { 
            echo $coupon_value;
            } 
            
            else { 
            
            $coupon_value = number_format($coupon_value, 2,',','');
            $coupon_value = explode(',',$coupon_value);
            echo $coupon_value[0].'.'.$coupon_value[1];
            
            }							
            ?>
    </p>
    <?php }
		else {
		?>
    <div class="coupon_val">
      <p> <?php echo CURRENCY;?>
        <?php 
            if(ctype_digit($coupon_value)) { 
            echo $coupon_value;
            } 
            
            else { 
            
            $coupon_value = number_format($coupon_value, 2,',','');
            $coupon_value = explode(',',$coupon_value);
            echo $coupon_value[0].'.'.$coupon_value[1];
            
            }							
            ?>
      </p>
    </div>
    <?php } ?>
    <?php if($type != 'P')
		    {
		    ?>
    <a  href="<?php echo DOCROOT;?>purchase.html?cid=<?php echo $row["coupon_id"];?>" title="buy"><img src="<?php echo DOCROOT; ?>themes/<?php echo CURRENT_THEME; ?>/images/buy.png" /></a>
    <?php } 
                    else
                    {?>
    <?php } ?>
  </div>
</div>
<div class="content_center">
  <div class="today_content ">
    <div class="discount_value">
      <div class="times">
        <?php //display timeleft page onload
			  
				  $timeleft = dateDiff($row["coupon_enddate"],date('Y-m-d H:i:s' ));
				  //print_r($timeleft);
				  
				  if(isset($timeleft['days']))
				  {
					  $default_hr = $timeleft['days'] * 24; //calculate days into hours
					  
					  if(isset($timeleft['hours']))
							$default_hr = $default_hr + $timeleft['hours'];
				  }
				  else
					  $default_hr = '';										  


				  if(isset($timeleft['minutes']))
				  {
					  $default_min = $timeleft['minutes'];
				  }
				  else
					  $default_min = '';

				  if(isset($timeleft['seconds']))
				  {
					  $default_sec = $timeleft['seconds'];
				  }
				  else
					  $default_sec = '';
															  
				  ?>
        <?php if($type != 'P') {?>
        <h2> <span class="color000 font25" id="tot_days2"></span> <span class="color666 font25n mr10" id="days"> </span>
          <div class="hrs"> <span class="tot_hrs2 fl" id="d<?php echo $i; ?>tot_hrs2"><?php echo $default_hr; ?> </span> <span class="hr">hours</span> </div>
          <span class="color333 fl font18" id="hrs"> </span>
          <div class="min"> <span class="tot_mins2 fl" id="d<?php echo $i; ?>tot_mins2"><?php echo $default_min; ?></span> <span class="hr">minutes</span> </div>
          <span class="color333 fl font18" id="mins"> </span>
          <div class="sec"> <span class="tot_mins2 fl " id="d<?php echo $i; ?>tot_secs2" style="margin-right:0px !important"><?php echo $default_sec; ?></span> <span class="hr">seconds</span> </div>
        </h2>
        <?php } ?>
      </div>
      <div class="timetop">
        <div class="value"> <span><?php echo $language['value']; ?>:</span>
          <p><?php echo CURRENCY;?>
            <?php 
                    if(ctype_digit($row['coupon_realvalue'])) { 
                        echo $row["coupon_realvalue"];
                    } 					  
              
                        else { 
    
					$coupon_realvalue = number_format($row['coupon_realvalue'], 2,',','');
					$coupon_realvalue = explode(',',$coupon_realvalue);
					 $coupon_realvalue[0].'.'.$coupon_realvalue[1];
    
                    }
                    ?>
          </p>
        </div>
        <div class="Discount"> <span><?php echo $language['discount']; ?>:</span>
          <p>
            <?php $discount = get_discount_value($row["coupon_realvalue"],$row["coupon_value"]); echo round($discount); ?>
            %</p>
        </div>
        <div class="you_save"> <span><?php echo $language['you_save']; ?>:</span>
          <p><?php echo CURRENCY;?>
            <?php $value = $row["coupon_realvalue"] - $row["coupon_value"]; 
                      
                            if(ctype_digit($value)) { 
                                echo $value;
                            } 					  
                      
                                else { 
        
                                $value = number_format($value, 2,',','');
                                $value = explode(',',$value);
                                echo $value[0].'.'.$value[1];
        
                            }?>
          </p>
        </div>
      </div>
    </div>
    <?php
                   //slide show starts 
                   $couponid = $row["coupon_id"];
                   $slider = 0;
                   $slider_result = mysql_query("select * from slider_images where coupon_id='$couponid'");
                   if(mysql_num_rows($slider_result)>0)
                   {
                        $slider = mysql_num_rows($slider_result);
                   }
                   ?>
    <div class="cnt_left fl">
      <div class="img_left deal_video">
        <?php
		    if($type != 'P')
		    {
		    ?>
        <a href="<?php echo DOCROOT;?>deals/<?php echo html_entity_decode($row["deal_url"]);?>_<?php echo $row["coupon_id"];?>.html" title="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES);?>">
        <?php
		    }
		    else
		    {
		    ?>
        <a href="<?php echo DOCROOT;?>deals/past/<?php echo html_entity_decode($row["deal_url"]);?>_<?php echo $row["coupon_id"];?>.html" title="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES);?>">
        <?php
		    }
	       
		    ?>
        <?php
                       if($row['is_video'] == 1)
                       { 

				//get the video url
				$split_video = make_links(html_entity_decode($row['embed_code'], ENT_QUOTES));
				$video_1 = explode("\"",$split_video); //print_r($video_1);
				?>
        <object width="420" height="285">
          <iframe width="420" height="285" src="<?php echo $video_1[0]; ?>" frameborder="0" allowfullscreen> </iframe>
        </object>
        <?php
			}
			else if($slider > 0)
			{
			?>
        <div id="loopedslider">
          <div class="slider-container">
            <div class="slides">
              <?php if(file_exists($row["coupon_image"]))
                                   {
                                   ?>
              <img width="660" height="283" class=""src="<?php echo DOCROOT.$row["coupon_image"]; ?>" alt="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" title="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" />
              <?php
                                    }
                                    else
                                    {
                                    ?>
              <img width="660" height="283" class="" src="<?php echo DOCROOT; ?>themes/<?php echo CURRENT_THEME; ?>/images/no_image.jpg" alt="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" title="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" />
              <?php
                                    }
                                    ?>
            </div>
          </div>
        </div>
        <?php
                                   }
                                   else
                                   {
                                   ?>
        <?php
                                       if(file_exists($row["coupon_image"]))
                                       {
                                       ?>
        <img width="660" height="283" class="" src="<?php echo DOCROOT.$row["coupon_image"]; ?>" alt="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" title="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" />
        <?php 
                                              }
                                              else
                                              {?>
        <img width="660" height="283" class="" src="<?php echo DOCROOT; ?>themes/<?php echo CURRENT_THEME; ?>/images/no_image.jpg" alt="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" title="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" />
        <?php
                                              }
                                            
                                           }                  
                                           ?>
        </a> </div>
      <div class="fl clr img_btm">
        <div class="img_left_btm">
          <p><?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?> </p>
          <?php if($type !='P') { ?>
          <div class="share_img">
            <ul>
              <?php $title = html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>
              <?php $share_link = DOCROOT.'deals/'.html_entity_decode($row["deal_url"], ENT_QUOTES).'_'.$row['coupon_id'].'.html'; ?>
              <?php /* <li>
        <a href="http://www.facebook.com/sharer.php?u=<?php echo $share_link;?>&t=<?php echo $title;?>" title="facebook" target="_blank">
        <img src="<?php echo DOCROOT;?>themes/<?php echo CURRENT_THEME; ?>/images/share_img.png" />
        </a></li> */ ?>
              <li>
                <?php   $_SESSION['displaynone'] =  $row['coupon_id']; 
                  ?>
                <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo $share_link; ?>&amp;layout=button_count&amp;show_faces=true&amp;width=450&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:25px; overflow:hidden; margin-left:10px;margin-top:2px;float:left; " allowTransparency="true"></iframe>
              </li>
            </ul>
          </div>
          <?php  } ?>
        </div>
        <div class="img_right_btm">
          <div class="bought">
            <?php 

			$dealid = $row["coupon_id"];
			$pcounts = mysql_query("SELECT * FROM coupons_purchase WHERE couponid='$dealid'");                            
			$purchased_count = mysql_num_rows($pcounts);

                            $min_user = $row["coupon_minuserlimit"] - $purchased_count;
                            $max_user = $row["coupon_maxuserlimit"] - $purchased_count;

                            if($min_user > 0)
                            { ?>
            <h3><?php echo $purchased_count; ?> <span class="ft20 colorfff"> <?php echo $language['bought']; ?> </span> </h3>
            <div class="clr " style="width:246px;">
              <div class="pbar ">
                <div id="progressbar" class="mt10" style="margin:0px;"></div>
              </div>
              <div class="pbarval clr "> <span class="fl mt5 colorfff fontbold mr5"><?php echo $startvalue = 0; ?></span> <span class="fr mt5 colorfff fontbold"><?php echo $row["coupon_minuserlimit"]; ?></span> </div>
            </div>
            <p><?php echo $min_user; ?>&nbsp;<?php echo $language['need_to_get']; ?></span></p>
            <?php
                                }
                                else
                                {
                                ?>
            <h3><?php echo $purchased_count; ?> <?php echo $language['bought']; ?></h3>
            <?php
                                }
                                ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="content_bottom fl clr"></div>
<?php  
	// for timer start here
	$year=date("Y", strtotime($row["coupon_enddate"]));
	$month=date("m", strtotime($row["coupon_enddate"]));
	$date=date("d", strtotime($row["coupon_enddate"]));
	$hour=date("H", strtotime($row["coupon_enddate"]));
	 $minute=date("i", strtotime($row["coupon_enddate"]));
	 //$minute+=$minute+1;
	$sec=date("s", strtotime($row["coupon_enddate"]));


	// get timezone
	$timezoneOffset = date('P'); 
	$timezoneOffset = str_replace(':','.',$timezoneOffset);
	// for timer Ends here

	?>
<?php if($type != 'P') {?>
<script type="text/javascript">
//dateFuture = new Date('<?php echo date("Y", strtotime($row["coupon_enddate"])); ?>'); //pass the date format similar to date
//alert(dateFuture);
var yyear ="<?php echo $year ?>";
var ymonth = "<?php echo $month ?>";
var yday ="<?php echo $date ?>";
var yhour ="<?php echo $hour ?>";
var yminute = "<?php echo $minute?>";
var ysec = "<?php echo $sec ?>";
var timezone = "<?php echo $timezoneOffset; ?>";
// Include common.js file
countdown(yyear,ymonth,yday,yhour,yminute,'d<?php echo $i; ?>','<?php echo DOCROOT."hot-deals.html"; ?>',timezone);
</script>

<?php  } $i++; ?>
<!--Countdown Timer ends here-->  
<?php 
				} //end of if 

		}
		 
	echo '<table border="0" width="650" align="center" class="clr" cellpadding="10">';
	echo '<tr><td align="center"><div class="pagenation">';
	$pagination->displayPaging();
	echo '</div></td></tr>';
	echo '</table>';
		  
	}
	else
	{?>
<div class="left1 fr ">
  <div class="content_top fl clr mt10">
    <div class="con_top_left">
      <h2><?php echo $language['no_deals_avail']; ?></h2>
    </div>
  </div>
  <div class="content_center fl clr">
    <?php /* <div class="no_data"><?php echo $language['no_deals_avail']; ?></div> */ ?>
  </div>
  <div class="content_bottom fl clr"></div>
</div>
<?php 
	}
	?>
