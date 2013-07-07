<?php
/****************************************** 
* @Created on June, 2011
* @Package: ndotdeals 3.0 (Opensource groupon clone) 
* @Author: NDOT
* @URL : http://www.ndot.in
********************************************/
?>

<?php 
if(mysql_num_rows($result) > 0) 
{
	while($row = mysql_fetch_array($result))
	{

		if($row["timeleft"] > "00:00:00")
		{

		//get the purchased coupon's count
		$purchased_count = $row["pcounts"];
?>


<div style="background:#FF4AFF;width:690px;float:left;padding:10px;font-family:Arial, Helvetica, sans-serif;border:4px solid #FFC4EC;">
	<div style="float:left;width:690px;background:#FF95FF; height:110px;border-bottom:4px solid #970097;clear:both;">
    	<a href="<?php echo DOCROOT; ?>" target="_blank" style="background:url(/site-admin/images/logo.png) no-repeat;float:left;width:189px;height:92px;margin:10px 0px 0px 10px"></a>
        <div style="float:left;width:300px;height:92px;border-left:2px solid #FF4AFF;margin:10px 0px 0px 10px">
        	<p style="width:290px; float:left;clear:both;margin:10px 0px 0px 10px;font-size:12px;font-weight:bold;"><?php echo $admin_language['thedailydealfor']; ?></p>
            <p style="float:left;width:290px;font-size:17px;font-weight:bold;margin:20px 0px 0px 10px"><?php echo html_entity_decode($row['cityname'], ENT_QUOTES);?></p>
        </div>
        
        <div style="float:left;width:150px;">
        	<p style="float:left;clear:both;font-size:12px;font-weight:normal;width:150px;">
			<?php echo date("l, F j, Y"); ?></p>
            <div style="float:left;clear:both;width:150px;"><p style="float:left;margin:0px 10px 0px 0px;font-size:12px;font-weight:bold;"><?php echo $admin_language['followus']; ?></p><a target="_blank" href="http://www.facebook.com/pages/NDOT/125148634186302" style="float:left;"><img src="<?php echo DOCROOT; ?>themes/<?php echo CURRENT_THEME; ?>/images/facebook.png" style="border:none;"/></a><a target="_blank" href="http://twitter.com/ndotindia" style="float:left;margin-left:10px;"><img src="<?php echo DOCROOT; ?>themes/<?php echo CURRENT_THEME; ?>/images/twitter.png" style="border:none;"/></a></div>
        </div>
    </div>
    <div style="float:left;clear:both;width:670px;background:#fff;padding:10px;">
    	<a style="float:left;clear:both;font-size:23px; font-weight:bold;text-decoration:none;color:#480048;width:670px;" target="_blank" href="<?php echo DOCROOT.'deals/'.urlencode(html_entity_decode($row["coupon_name"], ENT_QUOTES)).'_'.$row['coupon_id'].'.html';?>"><?php echo ucfirst(html_entity_decode($row["coupon_name"], ENT_QUOTES)); ?></a>
        <div style="float:left;clear:both;width:670px;margin-top:10px;">
        	
			             <?php if(file_exists($row["coupon_image"])) { ?>
                                      
                                      <img width="330" height="247" style="float:left;" src="<?php echo DOCROOT.$row["coupon_image"]; ?>" alt="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" title="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" />
                                      
                                      <?php }else
                                      {?>

                                      <img width="330" height="247" style="float:left;" src="<?php echo DOCROOT; ?>themes/<?php echo CURRENT_THEME; ?>/images/no_image.jpg" alt="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" title="<?php echo html_entity_decode($row["coupon_name"], ENT_QUOTES); ?>" />
                                                                            
                                      <?php }
                                      ?>            

<?php 
	//discount value
	$discount = ($row["coupon_realvalue"] * ($row["coupon_offer"]/100));
	$current_amount = $row["coupon_realvalue"] - $discount; //current rate of deal
?>               
            
            <div style="float:left;width:282px;">
            <div style="float:left;width:282px;height:83px;background:url(/site-admin/images/right_buy1.png) no-repeat;"><p style="line-height:75px;font-size:bold;color:#fff;font-size:32px;float:left;margin:0px 0px 0px 20px;"><?php echo CURRENCY;?><?php echo round($current_amount); ?></p></div>
                <div style="float:left;clear:both;width:240px;border:1px solid #FF4AFF;padding:10px;">
                	<div style="float:left;width:80px;text-align:center;line-height:25px;">
                    	<span style="font-size:12px;"><?php echo $admin_language['worth']; ?></span><br />
                        <span style="font-weight:bold;color:#480048;font-size:22px;"><?php echo CURRENCY;?><?php echo $row["coupon_realvalue"]; ?></span>
                    </div>
                    <div style="float:left;width:80px;text-align:center;line-height:25px;">
                    	<span style="font-size:12px;"><?php echo $admin_language['discount']; ?></span><br />
                        <span style="font-weight:bold;color:#480048;font-size:22px;"><?php echo $row["coupon_offer"]; ?>%</span>
                    </div>
                    <div style="float:left;width:80px;text-align:center;line-height:25px;">
                    	<span style="font-size:12px;"><?php echo $admin_language['savings']; ?></span><br />
                        <span style="font-weight:bold;color:#480048;font-size:22px;"><?php echo CURRENCY;?><?php echo $discount; ?></span>
                    </div>
                </div>
                <div style="float:left;width:240px;border:1px solid #d1d1d1;padding:10px;">
                    <p style="float:left;width:240px;font-weight:bold;font-size:14px;margin:10px 0px 0px 0px;"><?php echo $admin_language['location']; ?></p>
                    <p style="float:left;width:240px;font-size:12px;margin:10px 0px 0px 0px;"><?php echo html_entity_decode($row["shop_address"], ENT_QUOTES);?>,<br /><?php echo html_entity_decode($row["cityname"], ENT_QUOTES);?>,<br /><?php echo html_entity_decode($row["countryname"], ENT_QUOTES);?>.<br /></p>
                </div>
            </div>
        </div>
        <div style="float:left;clear:both;width:670px;font-size:12px;margin-top:15px;">
	        <?php echo nl2br(html_entity_decode($row["coupon_description"], ENT_QUOTES)); ?>
        </div>
    </div>
    <div style="float:left;width:690px;background:#FF95FF;border-top:3px solid #970097;clear:both;">
    	<p style="font-size:13px;font-weight:bold;color:#333;float:left;width:690px;text-align:center;"><?php echo $admin_language['needhelp']; ?><a target="_blank" href="<?php echo DOCROOT.'contactus.html'; ?>" style="color:#fff;font-weight:bold;"><?php echo $admin_language['contactus']; ?></a></p>
    </div>
</div>

<?php
//$encoded_email = base64_encode($to);
?>

<div style="float:left;width:690px; padding:10px;font-family:Arial, Helvetica, sans-serif;font-size:11px;">
<p style="float:left;width:690px;margin:0px; color:#999;text-align:center;padding-bottom:10px;"><?php echo $admin_language['youarereceivingmail']; ?> <!--If you prefer not to receive the daily Groupon email, you can always <a target="_blank" href="<?php //echo DOCROOT.'/unsubscribe.html?id='.$encoded_email; ?>" style="color:#0066CC;">unsubscribe with one click</a>--></p>
<p style="float:left;width:690px;text-align:center;margin:10px 0px 0px 0px;border-top:1px solid #ddd;padding-top:10px;">Delivered by Ndot deals Groupon Clone </p>
</div>

<?php 		}
	} 
}
?>
