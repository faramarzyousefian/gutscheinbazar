<?php
$userid = $_SESSION["userid"];
$split =  explode('_',$sub2);
$couponid = $split[1];
$purchase_id = $split[2];
if(is_numeric($couponid) && is_numeric($purchase_id))
{
	//select the deal
	$query = "select *,TIMEDIFF(coupons_coupons.coupon_enddate,now())as timeleft,( SELECT count( p.coupon_purchaseid ) FROM coupons_purchase p WHERE p.couponid = coupons_coupons.coupon_id and p.Coupon_amount_Status='T' ) AS pcounts from coupons_coupons left join coupons_shops on coupons_coupons.coupon_shop = coupons_shops.shopid left join coupons_cities on coupons_shops.shop_city = coupons_cities.cityid left join coupons_country on coupons_shops.shop_country = coupons_country.countryid left join coupons_purchase on coupons_purchase.couponid=coupons_coupons.coupon_id left join coupons_users on coupons_purchase.coupon_userid=coupons_users.userid left join transaction_details on transaction_details.ID=coupons_purchase.transaction_details_id where coupon_id = '$couponid' AND coupons_purchase.coupon_purchaseid='$purchase_id' AND coupon_expirydate > now() and coupons_purchase.coupon_userid='$userid' and transaction_details.CAPTURED=1";
	$result = mysql_query($query);
}
else
{
        set_response_mes(1,$language['cannot_download']);
        url_redirect(DOCROOT."my-coupons.html");
}	
if(mysql_num_rows($result)>0)
{
while($noticia = mysql_fetch_array($result))
{ 
//discount value
$discount = ($noticia["coupon_realvalue"] * ($noticia["coupon_offer"]/100));
$current_amount = $noticia["coupon_realvalue"] - $discount; //current rate of deal
$coupon_name = friendlyurl(html_entity_decode($noticia['coupon_name']));

                    if(file_exists($noticia["coupon_image"]))
                    {
                        $src= DOCROOT.$noticia["coupon_image"];
                    }
                    else
                    {
                        $src= DOCROOT."themes/".CURRENT_THEME."/images/no_image.jpg";
                    }     

$logo = DOCROOT."themes/".CURRENT_THEME."/images/logo.jpg";             
            
$body = '

<div class="work_bottom mt10" style="width:500px; ">
                        <div class="mt10" id="print_coupon">
				<table width="300px" >
				<tr><td width="50%"  valign="top">
                                <div class="fl">
                                        <img src="'.$logo.'" height="100px"/>
										
                                </div>
				</td><td valign="top" width="50%">	
<br/>				</td></tr></table>
				<table width="400px">
				        <tr><td>
                                         '.$language['cust_name'].': <strong class="color333" style="font-size:14px;"> '.ucfirst($noticia["firstname"]).' '.ucfirst($noticia["lastname"]).'</strong>
				        </td></tr>
				        <tr><td>
                                                <strong>'.$language['expires'].': </strong>'.$noticia["coupon_expirydate"].'
				        </td></tr>
				        <tr><td>
                                       '.$language['coupon_code'].': <strong class="font18 color333"> '. $noticia["coupon_validityid"].'</strong>
				        </td></tr>
				</table>
				<table width="100%" border="1px">
				        <tr><td>&nbsp;</td></tr></table>
				        <table width="100%" >
				        <tr>
				        <td colspan=2 >
				        <strong>'.$language['deal'].': <font size="25px"> '.html_entity_decode($noticia["coupon_name"]).'</font></strong>				
				        </td>
				        </tr>
				        <tr>
				        <td width="50%"  valign="top">
                                        <img src="'.$src.'" title="'.ucfirst(html_entity_decode($noticia['coupon_name'], ENT_QUOTES)).'" width="320px" />
				        </td>
				        <td valign="top">
                                                <span class="font14 color333"><strong>'.$language['description'].': </strong>'.html_entity_decode($noticia["coupon_description"],ENT_QUOTES).'<br/></span>

				        </td>
				        </tr>
				</table>
				<table width="100%" border="1px">
				        <tr><td>&nbsp;</td></tr>
				</table>
				<table>
				<tr>
				        <td width="50%"  valign="top"><span class="font14 color333"><strong>'.$language['fine_print'].': </strong>'.html_entity_decode($noticia["coupon_fineprints"],ENT_QUOTES).'<br/></span></td>
				        <td valign="top"><span class="font14 color333"><strong>'.$language['terms_and_condition'].': </strong>'.html_entity_decode($noticia["terms_and_condition"],ENT_QUOTES).'<br/></span></td>
				</tr>
				</table>
                                
				<table width="100%" border="1px"><tr><td>&nbsp;</td></tr></table>
				<table>
				        <tr>
				        <td>
				         <span class="font14 color333">'.$language['thanks'].',</span>	
				        </td>
				        </tr>
				        <tr>
				        <td>
                                        '.ucfirst(html_entity_decode($noticia["shopname"])).'
				        </td>
				        </tr>
				        <tr>
				        <td>
                                        '.ucfirst(nl2br(html_entity_decode($noticia["shop_address"]))).'
				        </td>
				        </tr>
				</table>
                        </div>
                </div>

';

}


require('system/modules/html2pdf/html2fpdf.php');
$pdf = new HTML2FPDF();
$pdf->AddPage();
$pdf->WriteHTML($body);
$pdf->Output(DOCUMENT_ROOT."/themes/_base_theme/common/pdf/".$coupon_name.".pdf");
url_redirect("/themes/_base_theme/common/pdf/".$coupon_name.".pdf");
}
else
{
        set_response_mes(1,$language['cannot_download']);
        url_redirect(DOCROOT."my-coupons.html");
}
