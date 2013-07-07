<link rel="stylesheet" type="text/css" href="<?php echo DOCROOT;?>themes/<?php echo CURRENT_THEME;?>/css/<?php if($_SESSION["site_language"]){ echo $_SESSION["site_language"];}else{ echo "en";}?>_style.css" />
<style>
body{background:none!important;}
</style>
<script type="text/javascript">
window.onload=window.print();
</script>
<script type="text/javascript">
function print_coupon()
{
document.getElementById('back_link').style.display = "none";
window.print();
document.getElementById('back_link').style.display = "block";
window.location = '/my-coupons.html';
}
</script>

<?php
$deal = explode('_',$sub1);
$deal_id = $deal[1];
$purchase_id = $deal[2];
$userid = $_SESSION["userid"];
if(is_numeric($deal_id))
{
	//select the deal
	$query = "select *,TIMEDIFF(coupons_coupons.coupon_enddate,now())as timeleft,( SELECT count( p.coupon_purchaseid ) FROM coupons_purchase p WHERE p.couponid = coupons_coupons.coupon_id and p.Coupon_amount_Status='T' ) AS pcounts from coupons_coupons left join coupons_shops on coupons_coupons.coupon_shop = coupons_shops.shopid left join coupons_cities on coupons_shops.shop_city = coupons_cities.cityid left join coupons_country on coupons_shops.shop_country = coupons_country.countryid left join coupons_purchase on coupons_purchase.couponid=coupons_coupons.coupon_id left join coupons_users on coupons_purchase.coupon_userid=coupons_users.userid  left join transaction_details on transaction_details.ID=coupons_purchase.transaction_details_id where coupon_id = '$deal_id' AND coupon_expirydate > now() and coupons_purchase.coupon_userid='$userid' and coupons_purchase.coupon_purchaseid='$purchase_id'  and transaction_details.CAPTURED=1";
	
	$result = mysql_query($query);
	
	
}
if(mysql_num_rows($result)>0)
{
        while($row = mysql_fetch_array($result))
        {
        ?>
        <?php is_login(DOCROOT."login.html"); ?>
                               
                <div class="work_bottom mt10" style="width:600px;">
                        <div class="mt10" id="print_coupon">
                                <div class="fl">
                                        <img src="<?php echo DOCROOT;?>themes/<?php echo CURRENT_THEME; ?>/images/logo.png"/>
                                </div>
                                <div class="fr width230">
                                        <div class="fl">
                                        <strong class="font18 color333"><?php echo ucfirst($row["firstname"]).' '.ucfirst($row["lastname"]); ?><br/>
                                        <?php echo $row["coupon_validityid"]; ?></strong>
                                        </div>
                                        <div class="fl clr mt10" style="margin-bottom:10px;">
                                        <?php
                                        $coupon_name = friendlyurl(html_entity_decode($row['coupon_name']));
	                            if(file_exists($row["coupon_image"]))
                                    {
                                    
                                           $image = '<img src="'.DOCROOT.'system/plugins/imaging.php?width=70&height=70&cropratio=1:1&noimg=100&image='.DOCROOT.$row["coupon_image"].'" alt="'.$row["coupon_name"].'" title="'.$row["coupon_name"].'" />';
                                            }
                                            else
                                            {
                                               $image = '<img src="'.DOCROOT.'system/plugins/imaging.php?width=70&height=70&cropratio=1:1&noimg=100&image='.DOCROOT.'themes/'.CURRENT_THEME.'/images/no_image.jpg" alt="'.$row["coupon_name"].'" title="'.html_entity_decode($row["coupon_name"], ENT_QUOTES).'" />
                                            ';
                                            }
                                            
                                       echo $image;                                           
                                        ?>
  
                                        </div>
                                </div>
                                <div class="font18 clr" align="center" id="coupon_name" style="font-size:18px;"><h1 class="mt10"><?php echo html_entity_decode($row["coupon_name"],ENT_QUOTES); ?></h1></div>
                                <div class="fl clr mt10">
                                        <strong><?php echo $language['expires']; ?> : </strong><?php echo date('d/m/Y', strtotime($row["coupon_expirydate"])); ?>
                                </div>
                                <div class="fl clr mt10">
                                        <strong><?php echo $language['description']; ?> : </strong><?php echo  html_entity_decode($row["coupon_description"],ENT_QUOTES); ?>
                                </div>
                                <div class="fl clr  mt10">
                                        <strong><?php echo $language['fine_print']; ?> : </strong><?php echo  html_entity_decode($row["coupon_fineprints"],ENT_QUOTES); ?>
                                </div>
                                <div class="fl clr mt10">
                                        <strong><?php echo $language['terms_and_condition']; ?> : </strong><?php echo  html_entity_decode($row["terms_and_condition"],ENT_QUOTES); ?>
                                </div>
                                <div class="fr clr mt10">
                                        <span class="font14 color333"><?php echo htmlspecialchars_decode($row["shopname"]); ?><br/></span>
                                        <?php echo nl2br( html_entity_decode($row["shop_address"],ENT_QUOTES)); ?>
                                </div>
                                <div class="clr">
                                        <input type="submit" value="" onclick="print_coupon();" title="Print Coupon" class="print_but"/>
                                        <a href="/my-coupons.html" id="back_link" title="Back to My Coupons" style="color:#2D82C8;font-size:14px;font-weight:bold;" class="ml10">Back to My Coupons</a>
                                </div>
                        </div>
                </div>
               <?php
        }
}
else
{
        set_response_mes(1,$language['cannot_print']);
        url_redirect(DOCROOT."my-coupons.html");
}
?>
