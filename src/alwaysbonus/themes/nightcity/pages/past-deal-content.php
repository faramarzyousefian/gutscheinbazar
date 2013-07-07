<?php
/******************************************
* @Created on March, 2011 * @Package: Ndotdeals unlimited v2.2
* @Author: NDOT
* @URL : http://www.NDOT.in
********************************************/
?>
<link href="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/css/tabs.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/scripts/tabs.js" type="text/javascript"></script>
<link href="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/scripts/jquery-ui.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/css/slider.css" type="text/css" media="screen" />
<script src="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/scripts/loopedslider.js" type="text/javascript"></script>
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
</div>
<?php 
}
else
{
	
	while($row = mysql_fetch_array($result))
	{

		

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
<script type="text/javascript">
  $(document).ready(function() {
    $("#progressbar").progressbar({ value: <?php echo $progressbar_value; ?> });
  });
  </script>
<!--Countdown Timer starts here-->
<script type="text/javascript">

dateFuture = new Date('<?php echo date("D M d Y H:i:s", strtotime($row["coupon_enddate"])); ?>'); //pass the date format similar to date function

function gettimes(){
	var url = '/gettimestamp.php';
	$.post(url,function(e){ 
		document.getElementById("generaldate").innerHTML=e;
	}); 

}

gettimes();

function GetCount(){
	return;
	gettimes();
	var dateNow = new Date(document.getElementById("generaldate").innerHTML); //grab current date
	var amount = dateFuture.getTime() - dateNow.getTime(); //calc milliseconds between dates
	delete dateNow;

		var days=0;var hours=0;var mins=0;var secs=0;var nodays="";var nohrs="";var nomins="";

		var amount = Math.floor(amount/1000);//kill the "milliseconds" so just secs

		var days=Math.floor(amount/86400);//days
		
		var days = days*24;
		
		var amount=amount%86400;

		var hours=days + Math.floor(amount/3600);//hours
		
		var amount=amount%3600;

		var mins=Math.floor(amount/60);//minutes
		
		var amount=amount%60;

		var secs=Math.floor(amount);//seconds

		if(days == 0 && hours == 0 && mins == 0 && secs == 0 )
		{	
			window.location='<?php echo DOCROOT; ?>';exit;
		}

		/*if(days != 0)
		{
		nodays = days +((days!=1)?"":"");
		//alert(nodays);
		document.getElementById('tot_days2').innerHTML=nodays;
		}
		else
		{
		document.getElementById('tot_days2').innerHTML='';		
		document.getElementById('days').innerHTML='';		
		}*/
		 
		if(days != 0 || hours != 0)
		{ 
		nohrs = hours +((hours!=1)?"":"");
		if(nohrs < 10 && nohrs >= 0){nohrs = '0'+ nohrs;}
		//alert(nohrs);
		document.getElementById('tot_hrs2').innerHTML=nohrs;		
		}
		else
		{
		document.getElementById('tot_hrs2').innerHTML='';		
		document.getElementById('hrs').innerHTML='';		
		}
				
		if(days != 0 || hours != 0 || mins != 0)
		{
		nomins = mins +((mins!=1)?"":"");
		if(nomins < 10 && nomins >= 0){nomins = '0'+ nomins;}		
		//alert(nomins);
		document.getElementById('tot_mins2').innerHTML=nomins;				
		}
		else
		{
		document.getElementById('tot_mins2').innerHTML='';		
		document.getElementById('mins').innerHTML='';		
		}		

		if(secs < 10 && secs >= 0){secs = '0'+ secs;}		
		document.getElementById('tot_secs2').innerHTML = secs;  
		setTimeout("GetCount()", 1000);
		
}

window.onload=GetCount;//call when everything has loaded

</script>
<!--Countdown Timer ends here-->
<div class="left1 fr">
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
    </div>
  </div>
  <div class="content_center fl clr">
    <div class="discount_value">
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
          <!--<p>Neutralize mood swings and feel happy, healthy, and alive with a 1-month supply of Happy Girl for $16. It includes more than 12 organic ingredients and is reported to relieve negative emotional signs during your monthly cycle.It includes more than 12 organic ingredients </p>-->
          <p><?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?> </p>
          <div class="share_img">
            <ul>
            </ul>
          </div>
        </div>
      </div>
      <!--end of cnt_left-->
    </div>
  </div>
  <div class="content_bottom fl clr"></div>
  <div class="content_bottom1 fr clr">
    <?php /*?>	<div class="cnt_topborder fl clr"></div><?php */?>
    <div class="tab_contianer">
      <ul>
        <li>
          <div class="tab_left"></div>
          <div class="tab_mid"><a href="javascript:;" id="dealdetails" title="Details of the Deal"><?php echo $language['deal_of_the_deal']; ?></a></div>
          <div class="tab_right"></div>
        </li>
        <li>
          <div class="tab_left"></div>
          <div class="tab_mid"> <a href="javascript:;" id="fineprints" title="The Fine Print"><?php echo $language['fine_print']; ?></a> </div>
          <div class="tab_right"></div>
        </li>
        <li>
          <div class="tab_left"></div>
          <div class="tab_mid"> <a href="javascript:;" id="contactdetails" title="Contact Details"><?php echo $language['contactdetails']; ?></a> </div>
          <div class="tab_right"></div>
        </li>
        <li>
          <div class="tab_left"></div>
          <div class="tab_mid"> <a href="javascript:;" id="reviews" title="Reviews"><?php echo $language['review']; ?></a> </div>
          <div class="tab_right"></div>
        </li>
      </ul>
    </div>
    <div class="cntbtm_inner dealdetails fl clr ">
      <!--<h3>Juan Juan Salon is like your local neighborhood salon-if your neighborhood were Beverly Hills, and you were an A-lister, that is. </h3>
        <p>It all began in 1948 Switzerland when Mr Ueli Prager opened the first Movenpick restaurant.</p>
        <p>In 1961, Mr Prager wanted to surprise his connoisseur clients with unsurpassed ice cream novelties. Embarking on a voyage of discovery and innovation with the finest chefs, confectioners and pastry chefs, he created refined recipes with exceptional skill and ingenuity. </p>
        <p>The famous Mvenpick of Switzerland was born.</p>-->
      <h3><?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?></h3>
      <div class="btm_left fl">
        <ul>
          <!--<li>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</li>
                        <li>The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.</li>
                        <li>Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy.</li>
                        <li>Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</li>-->
          <li><?php echo html_entity_decode($row["coupon_description"], ENT_QUOTES); ?></li>
        </ul>
      </div>
      <div class="btm_left fl">
        <ul>
          <!--<li>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</li>
                        <li>The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.</li>
                        <li>Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy.</li>
                        <li>Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</li>-->
        </ul>
      </div>
    </div>
    <div class="cntbtm_inner fineprints fl clr ">
      <div class="img_btm_fine fl">
        <h2><?php echo $language['fine_print']; ?></h2>
        <ul>
          <!-- <li>Make that time of the month easier with a one-month supply of Happy Girl, a 100% natural mood enhancer and energizer for women</li>
                       <li>Neutralize mood swings and feel happy, healthy, and alive</li> 
                       <li>Helps metabolize stored fat, promotes feelings of body awareness, increases energy and memory retention, improves physical and mental endurance, strengthens the immune system, aids digestion and circulation, and creates a feeling of well-being</li>
                       <li>Make that time of the month easier with a one-month supply of Happy Girl, a 100% natural mood enhancer and energizer for women</li>
                       <li>Neutralize mood swings and feel happy, healthy, and alive</li> 
                       <li>Helps metabolize stored fat, promotes feelings of body awareness, increases energy and memory retention, improves physical and mental endurance, strengthens the immune system, aids digestion and circulation, and creates a feeling of well-being</li>   -->
          <li><?php echo html_entity_decode($row["coupon_fineprints"], ENT_QUOTES); ?></li>
        </ul>
      </div>
      <div class="img_btm_fine fl">
        <h2><?php echo $language['highlights']; ?></h2>
        <ul>
          <!--<li>Make that time of the month easier with a one-month supply of Happy Girl, a 100% natural mood enhancer and energizer for women</li>
                       <li>Neutralize mood swings and feel happy, healthy, and alive</li> 
                       <li>Helps metabolize stored fat, promotes feelings of body awareness, increases energy and memory retention, improves physical and mental endurance, strengthens the immune system, aids digestion and circulation, and creates a feeling of well-being</li>
                       <li>Make that time of the month easier with a one-month supply of Happy Girl, a 100% natural mood enhancer and energizer for women</li>
                       <li>Neutralize mood swings and feel happy, healthy, and alive</li> 
                       <li>Helps metabolize stored fat, promotes feelings of body awareness, increases energy and memory retention, improves physical and mental endurance, strengthens the immune system, aids digestion and circulation, and creates a feeling of well-being</li>-->
          <li><?php echo html_entity_decode($row["coupon_highlights"], ENT_QUOTES); ?></li>
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
        <iframe width="400" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="<?php echo $docroot; ?>/system/plugins/gmaps.php?address=<?php echo html_entity_decode($row["shop_address"], ENT_QUOTES);?>&city=<?php echo html_entity_decode($row["cityname"], ENT_QUOTES);?>&country=<?php echo html_entity_decode($row["countryname"], ENT_QUOTES);?>&lat=<?php echo html_entity_decode($row["shop_latitude"], ENT_QUOTES);?>&lang=<?php echo html_entity_decode($row["shop_longitude"], ENT_QUOTES);?>&map_api=<?php echo GMAP_API;?>"> </iframe>
      </div>
    </div>
    <div class="cntbtm_inner reviews fl clr ">
      <!-- deal description -->
      <div class="deal_description">
        <?php 
						echo nl2br(html_entity_decode($row["coupon_description"], ENT_QUOTES));
						$coupon_desc = html_entity_decode($row["coupon_description"], ENT_QUOTES);
												
						//get the video url
						$split_video = make_links($coupon_desc);
						$video_1 = end(explode("?",$split_video));
						$video_2 = current(explode("&",$video_1));
						$video_3 = trim(end(explode("=",$video_2)));

						if(!empty($video_3))
						{
							?>
        <br />
        <br />
        <object width="425" height="349">
          <param name="movie" value="http://www.youtube.com/v/<?php echo $video_3; ?>?fs=1&hl=en_US&rel=0">
          </param>
          <param name="allowFullScreen" value="true">
          </param>
          <param name="allowscriptaccess" value="always">
          </param>
          <embed src="http://www.youtube.com/v/<?php echo $video_3; ?>?fs=1&hl=en_US&rel=0" 
                            type="application/x-shockwave-flash" width="425" height="349" allowscriptaccess="always" allowfullscreen="true"> </embed>
        </object>
        <?php 
						}
						?>
      </div>
      <h2 class="fontb18 mt10 color333 fl clr"><?php echo $language['terms_and_condition']; ?></h2>
      <p class="mt10"> <?php echo nl2br(html_entity_decode($row["terms_and_condition"], ENT_QUOTES)); ?> </p>
      <div class="cnt_review fl clr">
        <?php 
						if(REVIEW_TYPE == 1)
						{
							social_media_comment(); //include the social commetning system 
						}else
						{
							get_deal_comment($row["coupon_id"]);
						}
						?>
      </div>
    </div>
    <span id="generaldate" style="display:none;"></span>
    <div class="cnt_btmborder fl clr"></div>
  </div>
  <!--content left bottom-->
  <?php 		

}
}
?>
</div>
