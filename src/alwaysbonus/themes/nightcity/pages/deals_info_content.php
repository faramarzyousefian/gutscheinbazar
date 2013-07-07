<?php
/******************************************
* @Created on March, 2011 * @Package: Ndotdeals unlimited v2.2
* @Author: NDOT
* @URL : http://www.NDOT.in
********************************************/
?>
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
if(mysql_num_rows($result) == 0) 
{ ?>
<div class="left1 fr ">
<div class="content_top fl clr"></div>
<!--left corner-->
<div class="content_center fl clr">
  <div class="no_data"><?php echo $language['no_deals_avail']; ?></div>
</div>
<div class="content_bottom fl clr"></div>
<?php 
}
else
{
	
	while($row = mysql_fetch_array($result))
	{

		if($row["timeleft"] > "00:00:01")
		{
            $val_timer=$row["timeleft"];
		//get the purchased coupon's count
		$purchased_count = $row["pcounts"];
	    if($purchased_count > 0)
	    {
                if($row["coupon_minuserlimit"] > 0)
                        $progressbar_value = $purchased_count * (100/$row["coupon_minuserlimit"]);
                else
                     $progressbar_value = $purchase_count;         
	    }
	    else
		    $progressbar_value = 0;
?>
<?php  // for timer start here
$year=date("Y", strtotime($row["coupon_enddate"]));
$month=date("m", strtotime($row["coupon_enddate"]));
$date=date("d", strtotime($row["coupon_enddate"]));
$hour=date("H", strtotime($row["coupon_enddate"]));
 $minute=date("i", strtotime($row["coupon_enddate"]));
$sec=date("s", strtotime($row["coupon_enddate"]));

// get timezone
$timezoneOffset = date('P'); 
$timezoneOffset = str_replace(':','.',$timezoneOffset);
//exit; // for timer Ends here
?>
<script type="text/javascript">
  $(document).ready(function() {
    $("#progressbar").progressbar({ value: <?php echo $progressbar_value; ?> });
  });
  </script>
<div class="left1 fr ">
  <div class="fl clr" style="width:auto;">
    <div class="content_top fl clr">
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
        <a  href="<?php echo DOCROOT;?>purchase.html?cid=<?php echo $row["coupon_id"];?>" class="buy_img" title="buy"></a> </div>
    </div>
    <div class="content_center fl clr"> <span id="generaldate" style="display:none;"></span>
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
          <h2> <span class="color000 font25" id="d1tot_days2"></span> <span class="color666 font25n mr10" id="days"></span>
            <div class="hrs"> <span class="tot_hrs2 fl" id="d1tot_hrs2"><?php echo $default_hr; ?> </span> <span class="hr">hours</span> </div>
            <span class="color333 fl font18" id="hrs"> </span>
            <div class="min"> <span class="tot_mins2 fl" id="d1tot_mins2"><?php echo $default_min; ?></span> <span class="hr">minutes</span> </div>
            <span class="color333 fl font18" id="mins"> </span>
            <div class="sec"> <span class="tot_mins2 fl " id="d1tot_secs2" style="margin-right:0px !important"><?php echo $default_sec; ?></span> <span class="hr">seconds</span> </div>
          </h2>
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
      <div class="deal_top fl clr" >
        <?php ?>
        <!--cnt_left-->
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
                                    for($i = 1;$i <= $slider;$i++)
                                    {
                                            if(file_exists('uploads/slider_images/'.$row['coupon_id'].'_'.$i.'.jpg'))
                                            {
                                                    $slider_url = DOCROOT.'uploads/slider_images/'.$row['coupon_id'].'_'.$i.'.jpg';
                                                    ?>
                  <img width="660" height="283" class="" src="<?php echo $slider_url; ?>" alt="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" title="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" />
                  <?php   
                                            }
                                    }
                                    ?>
                </div>
              </div>
              <p class="slide_pagination"> <a href="javascript:;" class="fl previous"></a> <a href="javascript:;" class="fr next"></a> </p>
            </div>
            <script>
                                $(function(){
                                    $('#loopedslider').loopedSlider({
                                        addPagination: true,
                                        autoStart: 3000,
                                        pagination : "slider-pagination"
                                    });
                                });
                            </script>
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
          </div>
          <div class="fl clr img_btm">
            <div class="img_left_btm">
              <p><?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?> </p>
              <div class="share_img">
                <ul>
                  <?php $title = html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>
                  <?php $share_link = DOCROOT.'deals/'.html_entity_decode($row["deal_url"], ENT_QUOTES).'_'.$row['coupon_id'].'.html'; ?>
                  <?php /*  <li>
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
            </div>
            <div class="img_right_btm">
              <div class="bought">
                <?php 
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
                <p style="color:#666;font:normal 12px arial;padding-top:5px;width:190px!important;text-align:center;padding-left:10px;" class="bg_tick"><?php echo $language['lim_quantity']; ?></p>
                <p style="color:#83AF43;font-size:12px;padding:5px 0px;font-weight:bold;width:200px!important;text-align:center"><?php echo $language['deal_on']; ?>!</p>
                <?php
                                }
                                ?>
              </div>
            </div>
          </div>
        </div>
        <!--end of cnt_left-->
      </div>
    </div>
    <div class="content_bottom fl clr"></div>
  </div>
  <div class="fl clr" style="width:auto;">
    <div class="content_bottom1 fr clr ">
      <?php /*?>	<div class="cnt_topborder fl clr"></div><?php */?>
      <div class="cnt_midd_con">
        <div class="tab_contianer">
          <ul>
            <li class="tab_active" id="lidealdetails">
              <div class="tab_left"></div>
              <div class="tab_mid"><a href="javascript:;"  id="dealdetails"  title="Details of the Deal"><?php echo $language['deal_of_the_deal']; ?></a></div>
              <div class="tab_right"></div>
            </li>
            <li id="lifineprints">
              <div class="tab_left"></div>
              <div class="tab_mid"> <a href="javascript:;" id="fineprints" title="The Fine Print"><?php echo $language['fine_print']; ?></a> </div>
              <div class="tab_right"></div>
            </li>
            <li id="licontactdetails">
              <div class="tab_left"></div>
              <div class="tab_mid"> <a href="javascript:;" id="contactdetails" title="Contact Details"><?php echo $language['contactdetails']; ?></a> </div>
              <div class="tab_right"></div>
            </li>
            <li id="lireviews">
              <div class="tab_left"></div>
              <div class="tab_mid"> <a href="javascript:;" id="reviews" title="Reviews"><?php echo $language['review']; ?></a> </div>
              <div class="tab_right"></div>
            </li>
          </ul>
        </div>
        <div class="cntbtm_inner dealdetails fl clr ">
          <h3><?php echo nl2br(html_entity_decode($row["coupon_name"], ENT_QUOTES)); ?></h3>
          <div class="btm_left fl">
            <ul>
              <li><?php echo nl2br(html_entity_decode($row["coupon_description"], ENT_QUOTES)); ?></li>
            </ul>
          </div>
          <div class="btm_left fl">
            <ul>
            </ul>
          </div>
        </div>
        <div class="cntbtm_inner fineprints fl clr ">
          <div class="img_btm_fine fl">
            <h2><?php echo $language['fine_print']; ?></h2>
            <ul>
              <?php echo nl2br(html_entity_decode($row["coupon_fineprints"], ENT_QUOTES)); ?>
            </ul>
          </div>
          <div class="img_btm_fine fl">
            <h2><?php echo $language['highlights']; ?></h2>
            <ul>
              <?php echo nl2br(html_entity_decode($row["coupon_highlights"], ENT_QUOTES)); ?>
            </ul>
          </div>
        </div>
        <div class="cntbtm_inner contactdetails fl clr ">
          <h3>Contact Details</h3>
          <div class="shop_detail fl clr">
            <table width="180" class="fl clr">
              <?php
						  if(file_exists('uploads/logo_images/'.$row['shopid'].'.jpg')) 
						  {
						  ?>
              <tr>
                <td colspan="2" align="center" valign="top"><img class="p2" src="<?php echo DOCROOT; ?>system/plugins/imaging.php?width=75&height=85&cropratio=1:1&noimg=100&image=<?php echo DOCROOT.'uploads/logo_images/'.$row['shopid'].'.jpg'; ?>" alt="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" title="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" /> </td>
              </tr>
              <?php
						  }
						  ?>
              <tr>
                <td align="right" width="80" valign="top"><label><?php echo $language['shop_name']; ?> :</label>
                </td>
                <td align="left" width="120"><p><?php echo ucfirst(html_entity_decode($row["shopname"], ENT_QUOTES));?></p></td>
              </tr>
              <tr>
                <td align="right" valign="top" width="80"><label><?php echo $language['address']; ?> :</label>
                </td>
                <td align="left" width="120"><p><?php echo ucfirst(html_entity_decode($row["shop_address"], ENT_QUOTES));?></p></td>
              </tr>
              <tr>
                <td align="right" width="80"><label><?php echo $language['city']; ?> :</label>
                </td>
                <td align="left" width="120"><p><?php echo ucfirst(html_entity_decode($row["cityname"], ENT_QUOTES));?></p></td>
              </tr>
              <tr>
                <td align="right" width="80"><label><?php echo $language['country']; ?> :</label>
                </td>
                <td width="120"align="left"><p><?php echo ucfirst(html_entity_decode($row["countryname"], ENT_QUOTES));?></p></td>
              </tr>
              <?php
						  if(!empty($row['shop_url']))
						  {
						  ?>
              <tr>
                <td colspan="2"><label style="width:200px!important;float:left;"><?php echo $language['api_website']; ?> :</label>
                </td>
              </tr>
              <tr>
                <td colspan="2"><p style="width:200px!important;"><a style="width:100%!important;overflow:hidden;" href="<?php echo html_entity_decode($row["shop_url"], ENT_QUOTES);?>" target="_blank"> <?php echo html_entity_decode($row["shop_url"], ENT_QUOTES);?></a></p></td>
              </tr>
              <?php
						  }
						  ?>
            </table>
          </div>
          <div class="map">
            <h3>Find us on:</h3>
            <iframe width="640" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="<?php echo $docroot; ?>/system/plugins/gmaps.php?address=<?php echo html_entity_decode($row["shop_address"], ENT_QUOTES);?>&city=<?php echo html_entity_decode($row["cityname"], ENT_QUOTES);?>&country=<?php echo html_entity_decode($row["countryname"], ENT_QUOTES);?>&lat=<?php echo html_entity_decode($row["shop_latitude"], ENT_QUOTES);?>&lang=<?php echo html_entity_decode($row["shop_longitude"], ENT_QUOTES);?>&map_api=<?php echo GMAP_API;?>"> </iframe>
          </div>
        </div>
        <div class="cntbtm_inner reviews fl clr ">
          <p class="mt10"> </p>
          <div class="cnt_review fl clr">
            <?php /*
						if(REVIEW_TYPE == 1)
						{
							social_media_comment(); //include the social commetning system 
						}else
						{
							get_deal_comment($row["coupon_id"]);
						}*/
						?>
          </div>
          <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
          <div class="ml10">
            <fb:comments href="<?php echo DOCROOT; ?>deals/<?php echo $row['deal_url'].'_'.$row['coupon_id'];?>.html" num_posts="2" width="610" border="1"></fb:comments>
          </div>
        </div>
      </div>
      <div class="cnt_btmborder fl clr"></div>
    </div>
  </div>
  <!--content left bottom-->
  <?php 		}				
			else
			{
			
				url_redirect(DOCROOT);

			}

}
}
?>
</div>
<?php if(empty($_SESSION['defaultcityname']))
{ 
if($_POST["menupopup"] == "popvalue")
{       
	$email = $_POST['email'];
	$city = $_POST['city_name'];
	
	if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$email))
	{
		set_response_mes(-1,$language['invalid_email']);
		url_redirect($_SERVER['REQUEST_URI']);
	} 
	
	if(!empty($email))
	{
		$val = add_subscriber($email,$city);
		if($val)
		{
		        /* GET THE EMAIL TEMPLATE FROM THE FILE AND REPLACE THE VALUES */
	                        $to = $email;
	                        $From = SITE_EMAIL;
	                        $subject = $email_variables['subscription_subject'];	
	                        $description = $email_variables['subscription_thankyou'];	
                                $str = implode("",file(DOCUMENT_ROOT.'/themes/_base_theme/email/email_all.html'));
                                
                                $str = str_replace("SITEURL",$docroot,$str);
                                $str = str_replace("SITELOGO",$logo,$str);
                                $str = str_replace("RECEIVERNAME","Subscriber",$str);
                                $str = str_replace("MESSAGE",ucfirst($description),$str);
                                $str = str_replace("SITENAME",SITE_NAME,$str);

				$message = $str;
				
				$SMTP_USERNAME = SMTP_USERNAME;
				$SMTP_PASSWORD = SMTP_PASSWORD;
				$SMTP_HOST = SMTP_HOST;
				$SMTP_STATUS = SMTP_STATUS;	
				
				if($SMTP_STATUS==1)
				{
	
					include(DOCUMENT_ROOT."/system/modules/SMTP/smtp.php"); //mail send thru smtp
				}
				else
				{
			     		// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					// Additional headers
					$headers .= 'From: '.$From.'' . "\r\n";
					mail($to,$subject,$message,$headers);	
				}
			$queryforset = mysql_query("select * from coupons_cities where cityid ='".$city."'");
			while($fetchrow = mysql_fetch_array($queryforset))
			{
			session_start();
			$_SESSION['defaultcityId'] = $fetchrow['cityid'];
			$_SESSION['defaultcityname'] = $fetchrow['cityname'];
			$_SESSION['default_city_url'] = $fetchrow['city_url'];
			}
			set_response_mes(1,$language['subscribe_success']);
			url_redirect($_SERVER['REQUEST_URI']);
		}
		else
		{
			set_response_mes(-1,$language['email_exist']);
			url_redirect($_SERVER['REQUEST_URI']);			
		}
	}
	else
	{
		set_response_mes(-1,$language['try_again']);
		url_redirect($_SERVER['REQUEST_URI']);

	}
	
	
}

//get the categpry list
$category_list = mysql_query("select * from coupons_cities where status='A' order by cityname");

?>
<div id="pop" class='popup_block'>
  <div class="subscription_form">
    <div class="subscription">
      <!--bg top start-->
      <div class="bg_top fl">
        <div class="bg_top_left fl"></div>
        <div class="bg_top_middle fl"></div>
        <div class="bg_top_right fl"></div>
      </div>
      <!--bg top end-->
      <!--bg middle start-->
      <div class="bg_middle fl">
        <!--title start-->
        <div class="title fl">
          <div class="exit fr"> <a href="<?php echo DOCROOT;?>subscribe.html?ref=Skip" title="Exit" class="fl"><img src="<?php echo DOCROOT;?>themes/<?php echo CURRENT_THEME; ?>/images/exit.png" width="24" height="34" border="0" class="fl" /></a> </div>
          <h1 class="fl">Get <span>NDOT</span> deals in Vancouver at 50-90% off!</h1>
          <h2 class="fl">Delivered directly to your inbox.</h2>
        </div>
        <!--title end-->
        <!--subscribe content start-->
        <div class="subscribe_content fl">
          <div class="subscribe_content_left fl"></div>
          <div class="subscribe_content_middle fl">
            <div class="subscribe_options ">
              <form action="" method="post" name="subscribe_updates" id="subscribe_updates">
                <div class="subscribe_input fl">
                  <input type="text" title="<?php echo $language['valid_email']; ?>" name="email" value="" class="fl" />
                </div>
                <div class="subscribe_select fl">
                  <select name="city_name" id="city" class="fl m15 city_sel">
                    <?php while($city_row = mysql_fetch_array($category_list)) { ?>
                    <option value="<?php echo $city_row["cityid"];?>" <?php if($_COOKIE['defaultcityId'] == $city_row["cityid"]){ echo "selected";} ?>><?php echo html_entity_decode($city_row["cityname"], ENT_QUOTES);?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="subscribe_submit fr">
                  <input type="hidden" name="menupopup" value="popvalue">
                  <input type="submit" title="subscribe" name="subsciberdeal" value="" class="fl" />
                </div>
              </form>
            </div>
          </div>
          <div class="subscribe_content_right fl"></div>
        </div>
        <!--subscribe content end-->
        <!--privacy policy start-->
        <div class="privacy_policy fl">
          <!--read privacy policy stat-->
          <div class="read_privacy_policy fl">
            <p class="fl">We'll never share your email address.</p>
            <a href="<?php echo DOCROOT;?>subscribe.html?ref=privacy" title="Read our privacy policy" class="fl ml5">Read Our Privacy Policy</a> </div>
          <!--read privacy policy end-->
          <!--skip start-->
          <div class="skip fr mt2"> <a href="<?php echo DOCROOT;?>subscribe.html?ref=Skip" title="<?php echo $language['skip']; ?>" class="fl"><?php echo $language['skip']; ?></a> </div>
          <!--skip end-->
        </div>
        <!--privacy policy end-->
        <!--deals start-->
        <div class="deals fl">
          <ul class="fl">
            <li class="fl pr5"> <img src="<?php echo DOCROOT;?>themes/<?php echo CURRENT_THEME; ?>/images/subscribe_deal_img1.jpg" width="167" height="108" border="0" class="fl" />
              <p class="fl">Restaurents</p>
            </li>
            <li class="fl pr5"> <img src="<?php echo DOCROOT;?>themes/<?php echo CURRENT_THEME; ?>/images/subscribe_deal_img2.jpg" width="167" height="108" border="0" class="fl" />
              <p class="fl">Fitness</p>
            </li>
            <li class="fl pr5"> <img src="<?php echo DOCROOT;?>themes/<?php echo CURRENT_THEME; ?>/images/subscribe_deal_img3.jpg" width="167" height="108" border="0" class="fl" />
              <p class="fl">Spa</p>
            </li>
            <li class="fl"> <img src="<?php echo DOCROOT;?>themes/<?php echo CURRENT_THEME; ?>/images/subscribe_deal_img4.jpg" width="167" height="108" border="0" class="fl" />
              <p class="fl">Events</p>
            </li>
          </ul>
        </div>
        <!--deals end-->
      </div>
      <!--bg middle end-->
      <!--bg bottom start-->
      <div class="bg_bottom fl">
        <div class="bg_bottom_left fl"></div>
        <div class="bg_bottom_middle fl"></div>
        <div class="bg_bottom_right fl"></div>
      </div>
      <!--bg bottom end-->
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
					   		   
	//When you click on a link with class of poplight and the href starts with a # 
		var popID = 'pop'; //Get Popup Name
		var popWidth ='900'; //Gets the first query string value

		//Fade in the Popup and add close button
		$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="http://<?php echo $_SERVER["HTTP_HOST"] ;?>/" class="close"></a>');
		
		
		
		//Define margin for center alignment (vertical + horizontal) - we add 80 to the height/width to accomodate for the padding + border width defined in the css
		var popMargTop = ($('#' + popID).height() + 80) / 2;
		var popMargLeft = ($('#' + popID).width() + 80) / 2;
		
		//Apply Margin to Popup
		$('#' + popID).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		//Fade in Background
		$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
		$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer 
		
		return false;
	});
	
	
	//Close Popups and Fade Layer
	$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...

	});

	
</script>
<script type="text/javascript">
$('input[type="text"]').each(function(){
 
    this.value = $(this).attr('title');
    $(this).addClass('text-label');
 
    $(this).focus(function(){
        if(this.value == $(this).attr('title')) {
            this.value = '';
            $(this).removeClass('text-label');
        }
    });
 
    $(this).blur(function(){
        if(this.value == '') {
            this.value = $(this).attr('title');
            $(this).addClass('text-label');
        }
    });
});
</script>
<?php } ?>
<!--Countdown Timer starts here-->
<script type="text/javascript">
//dateFuture = new Date('<?php echo date("Y", strtotime($row["coupon_enddate"])); ?>'); //pass the date format similar to date
//alert(dateFuture);
var yyear ="<?php echo $year ?>";
var ymonth ="<?php echo $month ?>";
var yday ="<?php echo $date ?>";
var yhour ="<?php echo $hour ?>";
var yminute = "<?php echo $minute?>";
var ysec = "<?php echo $sec ?>";
var timezone = "<?php echo $timezoneOffset; ?>";
//alert(yyear+ymonth+yday+yhour+yminute+'d1'+'<?php echo DOCROOT; ?>');
countdown(yyear,ymonth,yday,yhour,yminute,'d1','<?php echo DOCROOT; ?>',timezone);
</script>
