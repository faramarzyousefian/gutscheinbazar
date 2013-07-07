<?php session_start();?>
<script type="text/javascript">
function calculate_amt(count,price)
{
        var total = parseFloat(Math.round(count * price * 100)/100); //two decimal value
	balance = Math.round(total * 100)/100;

	if(balance < 0)
	{
	        balance = 0;
	}
	
	if(isNaN(count) || (count<=0))
	{
		document.getElementById("quantity").value = '1';
		count = 1;
		var total = parseFloat(Math.round(count * price * 100)/100); //two decimal value
		balance = Math.round(total * 100)/100;   
	}

	document.getElementById("total_amt1").innerHTML=balance;
	document.getElementById("payable_total_amt").innerHTML=balance;     
	document.getElementById("AMT").value = balance;

        var data = document.getElementById("CUSTOM").value;
        var cid = data.split(",",1);
        if(count=='') count = 1;
        var cumm = cid+","+count
        document.getElementById("CUSTOM").value='';
	document.getElementById("CUSTOM").value=cumm;
}      
</script>

<?php is_login(DOCROOT."login.html"); ?>
<ul>
<li><a href="/" title="Home"><?php echo $language['home']; ?> </a></li>
<li><span class="right_arrow"></span></li>
<li><a href="javascript:;" title="Hot Deals"><?php echo $language['your_purchase']; ?></a></li>    
</ul>

<h1><?php echo $page_title; ?></h1>

<?php
//get the coupon amount and code
$uri = $_SERVER['REQUEST_URI'];
$coupon_url = explode('=',$uri);

if(is_numeric($coupon_url[1]))
{
	$coupon_id = $coupon_url[1]; 
}
else
{
	$coupon_url = explode('&',$coupon_url[1]);
	$coupon_id = $coupon_url[0];
}

$coupon_info = get_coupon_code($coupon_id);
if(mysql_num_rows($coupon_info)>0)
{
        while($row = mysql_fetch_array($coupon_info))
        {
		$coupon_code = $row["coupon_id"];
                $total_payable_amount = $row["coupon_value"]; 

		if(ctype_digit($total_payable_amount)) { 
			$total_payable_amount = $total_payable_amount;
		} 					  
	        else { 

			$total_payable_amount = number_format($total_payable_amount, 2,',','');
			$total_payable_amount = explode(',',$total_payable_amount);
			$total_payable_amount = $total_payable_amount[0].'.'.$total_payable_amount[1];

		}                
                $couponname = html_entity_decode($row["coupon_name"], ENT_QUOTES);
	      	$coupondesc = html_entity_decode($row["coupon_description"], ENT_QUOTES);
        }
}
?>

<div class="work_bottom">

  <!-- paypal gateway -->  
    <form action="<?php echo DOCROOT; ?>system/modules/gateway/paypal/ReviewOrder.php" method="POST" name="paypal_form" id="paypal_form">
      <table cellpadding="5" align="left" class="fl" style="width:600px;" border="0">
        <tr style="font-weight:bold;color:#666; ">
          <td><label><?php echo $language['description']; ?></label></td>
          <td><label><?php echo $language['qty']; ?></label></td>
          <td></td>
          <td><label><?php echo $language['price']; ?></label></td>
          <td></td>
          <td><label><?php echo $language['total']; ?></label></td>
        </tr>
		
        <tr>
          <td><label><?php echo 'Payment for '.ucfirst($couponname); ?></label>
          </td>
          <td><input type="text" style="width:48px;" name="quantity" id="quantity" title="Enter quantity in numeric." class="digits required" maxlength="5" value="1" onblur="calculate_amt(this.value,<?php echo round($total_payable_amount,2); ?>)" />
            </label>
          </td>
          <td><label>X</label></td>
          <td><label><?php echo CURRENCY; ?><span id="price_val"><?php echo $total_payable_amount; ?></span></label></td>

          <td><label>=</label></td>
          <td><label><?php echo CURRENCY; ?><span id="total_amt1"><?php echo round($total_payable_amount, 2); ?></span> </label></td>
        </tr>
        <tr>
          <td colspan="6"><label><?php echo $language['total_payable']; ?>:</label>
            <span><?php echo CURRENCY; ?></span> <span id="payable_total_amt" style="font-weight:bold;color:#000;"> <?php echo round($total_payable_amount, 2); ?></span> </td>
        </tr>
        <tr>
          <td colspan="6" align="right">

            <input type="hidden" name=PAYMENTACTION value='Authorization' >
            <input type="hidden" name="AMT" id="AMT" value='<?php echo round($total_payable_amount, 2); ?>' >
            <input type="hidden" name="CUSTOM" id="CUSTOM" value='<?php echo $coupon_id; ?>,1' >
            <input type="hidden" name="CURRENCYCODE" value='<?php echo PAYPAL_CURRENCY_CODE;?>' >
            <input type="image" name="submit"  src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" />

          </td>
        </tr>
      </table>
    </form>

  </div>
 <!-- end of paypal gateway -->

<script type="text/javascript">
/* validation 
$(document).ready(function(){ $("#paypal_form").validate();}); */
</script>
