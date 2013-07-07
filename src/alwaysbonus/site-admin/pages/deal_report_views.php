<script type="text/javascript">
function forceclosecoupon(id,refid)
{	
	var sure=confirm("Are you sure want to close this Coupon?");
	if(sure)
	{
		window.location='<?php echo DOCROOT; ?>site-admin/pages/force_coupon_close.php?couponid='+id+'&refid='+refid;
	}

}
</script>
<?php
$current_url = explode('?',$_SERVER["REQUEST_URI"]);
if(isset($current_url[1]))
{
        $type = explode('=',$current_url[1]);
        if($type[0] == 'type' && !empty($type[1]))
        {
                $select = $type[1];
        }
}
$dealid = $_REQUEST['sub1'];
$user_role = $_SESSION['userrole'];

$queryString = "SELECT (
		
		SELECT count( p.coupon_purchaseid )
		FROM coupons_purchase p
		WHERE p.couponid = c.coupon_id  and p.Coupon_amount_Status='T'
		) AS pcounts, u.firstname, u.lastname, c.coupon_id, c.coupon_name,c.coupon_description,cc.category_name as couponstype, DATE_FORMAT( c.coupon_startdate, '%d %M %Y' ) AS startdate, DATE_FORMAT( c.coupon_enddate, '%d %M %Y' ) AS enddate, c.coupon_createdby,(
		if( LENGTH( concat( u.firstname, u.lastname ) ) =0, u.username, concat( u.firstname, space(5), u.lastname ) )
		) AS name,c.coupon_status,c.coupon_minuserlimit as minuserlimit,c.coupon_maxuserlimit as maxuserlimit
		,c.coupon_realvalue,c.coupon_value,c.coupon_image FROM coupons_coupons c left join coupons_category cc on cc.category_id=c.coupon_category left join coupons_users u on u.userid=c.coupon_createdby where c.coupon_id='$dealid'";
		
		//checking whether user is not admin
		if($user_role != 1)
		{
		        if($user_role == 2)
		        {
		                $queryString .= " and (c.coupon_createdby ='".$_SESSION['userid']."' or c.coupon_createdby in (select userid from coupons_users where created_by = '".$_SESSION['userid']."')) ";
		        }
		        else
		        {
			        $queryString .= " and c.coupon_createdby ='".$_SESSION['userid']."'";
			}
		}
		$resultSet = mysql_query($queryString)or die(mysql_error());
		
            if(mysql_num_rows($resultSet)>0)
            {     
              $i=0;
              
                if($cdetails=mysql_fetch_array($resultSet))
                { 
		    
		    echo '<h1 class="inner_title fwb" style="padding:5px;">'.html_entity_decode($cdetails["coupon_name"], ENT_QUOTES).'!</h1>'; 
		    echo '<div  class=" full_page_content">';
		    echo '<div  class="vclosed_coopen mt10">';
		    
		    echo '<div class="fontbold mb10" style="margin-left:5px;" >';
		    echo '<i class="fwb color666">';
		    echo "Category --- ".$cdetails["couponstype"];
		    echo '</i>';
		    echo '</div>';  
	
	?>
	
    <div class="mt10 fl width780">
	
		<div class="width430 fl"> <!-- left -->
			<?php 
			if($cdetails["coupon_image"]!='' && file_exists($_SERVER["DOCUMENT_ROOT"].'/'.$cdetails["coupon_image"]))
			{
			
				echo '<img class="clr  borderE3E3E3" src="/'.$cdetails["coupon_image"].'"/>';
			}
			else
			{
				echo '<img src="'.$docroot.'themes/'.CURRENT_THEME.'/images/no_image.jpg" style="margin-top:20px;" />';
			}
			?>
	   </div> <!-- left -->
	  
	  <!-- right -->
	  <div class="width340 fl">
	  
		<p class="fl p5 w300"> 
		<?php echo html_entity_decode($cdetails["coupon_description"], ENT_QUOTES);?>
		</p>

					  <div class="discount_value mt-10 fl">
					  <span class="color333 font12 ml25 mt-10 fl"><?php echo $admin_language['couponvalue']; ?></span><br /><span class="color344F86 font18 ml25 fl clr"><?php echo CURRENCY;?><?php $coupon_value = $cdetails["coupon_value"]; 
							if(ctype_digit($coupon_value)) { 
								echo $coupon_value;
							} 
							
							else { 

								$coupon_value = number_format($coupon_value, 2,',','');
								$coupon_value = explode(',',$coupon_value);
								echo $coupon_value[0].'.'.$coupon_value[1];

							}							
							?></span>
					  </div>		
		
			  <div class="discount_value mt-10 fl">
			  <div class="timetop">
				  <div class="value">
				  <span class="color333 font12"><?php echo $admin_language['value']; ?></span><br /><span class="color344F86 font18"><?php echo CURRENCY;?><?php 
							if(ctype_digit($cdetails['coupon_realvalue'])) { 
								echo $cdetails["coupon_realvalue"];
							} 					  
					  
						        else { 

								$coupon_realvalue = number_format($cdetails['coupon_realvalue'], 2,',','');
								$coupon_realvalue = explode(',',$coupon_realvalue);
								echo $coupon_realvalue[0].'.'.$coupon_realvalue[1];

							}
							?></span>
				  </div>
				  
				  <div class="Discount">
				  <span class="color333 font12"><?php echo $admin_language['discount']; ?></span><br /><span class="color344F86 font18"><?php $discount = get_discount_value($cdetails["coupon_realvalue"],$cdetails["coupon_value"]); echo round($discount); ?>%</span>
				  </div>
				  
				  <div class="Save">
				  <span class="color333 font12"><?php echo $admin_language['yousave']; ?></span><br /><span class="color344F86 font18"><?php echo CURRENCY;?><?php $value = $cdetails["coupon_realvalue"] - $cdetails["coupon_value"]; 
					  
							if(ctype_digit($value)) { 
								echo $value;
							} 					  
					  
						        else { 

								$value = number_format($value, 2,',','');
								$value = explode(',',$value);
								echo $value[0].'.'.$value[1];

							}?></span>
				  </div>
			  </div>
	   </div>

           <!-- Coupon Status-->
	   <p class="fl clr fwb">
	   <?php
	   echo $admin_language['status']; 
	   if($cdetails["coupon_status"] == 'A')
	        echo ': ACTIVE';
	   else if($cdetails["coupon_status"] == 'C')
	        echo ': CLOSED';
	   else
	        echo ': PENDING';
	   ?>
	   </p>

<?php if(($cdetails['pcounts'] >0) && $cdetails['coupon_status']=='A' && $_SESSION['userrole']=='1') { ?>
	   
	   <!-- Force close-->
	   <p class="fl clr fwb">
	   <a href="javascript:;" title="<?php echo $admin_language['force_close']; ?>" onclick="forceclosecoupon('<?php echo $cdetails['coupon_id']; ?>','<?php echo urlencode(DOCROOT.substr($_SERVER['REQUEST_URI'],1)); ?>')"><?php echo $admin_language['force_close']; ?></a>
	   </p>

<?php } ?>
	   
	  </div> <!-- right end -->
	  		
	</div>  	
	        

		   
 
    </div >
	</div>
    
    <p>
	<?php echo $admin_language['createdby']; ?>
	<?php  
	if(!empty($cdetails["name"]))
	{ 
		echo $cdetails["name"];
	}
	else
	{ 
		echo $cdetails["username"];
	}
	?>
    </p>
    <p><?php echo $admin_language['quantity']; ?>&nbsp;<?php echo $admin_language['target']; ?>&nbsp;:&nbsp;<?php  echo $cdetails["minuserlimit"]; ?></p>
    <p><?php echo $admin_language['quantity']; ?>&nbsp;<?php echo $admin_language['achieved']; ?>&nbsp;:&nbsp;<?php  echo $cdetails["pcounts"]; ?></p>
    <p><?php echo $admin_language['amount']; ?>&nbsp;<?php echo $admin_language['target']; ?>&nbsp;:&nbsp;<?php  echo $cdetails["minuserlimit"]*$cdetails["coupon_value"]; ?></p>
    <p><?php echo $admin_language['amount']; ?>&nbsp;<?php echo $admin_language['achieved']; ?>&nbsp;:&nbsp;<?php  echo $cdetails["pcounts"]*$cdetails["coupon_value"]; ?></p>
    
    <?php	
                }
             }
             else
             {
                        set_response_mes(-1,"Invalid coupon information");
                        url_redirect(DOCROOT."admin/view/rep/all");
             }
	?>

	 <div class="mt10 fl width780">
	 <div class=" clr fr p5 mr-8">
			 <?php echo $admin_language['export']; ?> : <a href="<?php echo DOCROOT;?>site-admin/pages/export.php?type=deals_transaction&deal_id=<?php echo $dealid; ?>" title="<?php echo $admin_language['all']; ?>"><?php echo $admin_language['all']; ?></a>, <a href="<?php echo DOCROOT;?>site-admin/pages/export.php?type=deals_transaction&deal_id=<?php echo $dealid; ?>&status=success" title="<?php echo $admin_language['success']; ?>"><?php echo $admin_language['success']; ?></a>, <a href="<?php echo DOCROOT;?>site-admin/pages/export.php?type=deals_transaction&deal_id=<?php echo $dealid; ?>&status=failed" title="<?php echo $admin_language['failed']; ?>"><?php echo $admin_language['failed']; ?></a>, <a href="<?php echo DOCROOT;?>site-admin/pages/export.php?type=deals_transaction&deal_id=<?php echo $dealid; ?>&status=hold" title="<?php echo $admin_language['hold']; ?>"><?php echo $admin_language['hold']; ?></a>
	</div>
	 <h1 class="font18"><?php echo $admin_language['transactionlist']; ?></h1>
	<?php 
	
	//transaction list
	$query = "select ID,TIMESTAMP,CAPTURED_ACK, coupons_userstatus, coupon_validityid, coupons_users.userid, transaction_details.COUPONID, AMT, username, coupon_name, CAPTURED,L_QTY0 from transaction_details left join coupons_coupons on transaction_details.COUPONID = coupons_coupons.coupon_id left join coupons_users on transaction_details.USERID = coupons_users.userid left join coupons_purchase on coupons_purchase.transaction_details_id=transaction_details.ID where transaction_details.COUPONID = '$dealid' ";
	
	if($select == 'success')
	{
	        $query .= "and CAPTURED=1 ";
	}
	else if($select == 'hold')
	{
	        $query .= "and CAPTURED=0 and CAPTURED_ACK='' ";
	}
	else if($select == 'failed')
	{
	        $query .= "and CAPTURED=0 and CAPTURED_ACK='Failed' ";
	}
	$query .= "order by TIMESTAMP desc";
	
	
	/* GET TOTAL AMOUNT OF SUCCESS TRANSACTION */
	$success_query = "SELECT SUM(AMT) FROM transaction_details where transaction_details.COUPONID = '$dealid' and CAPTURED=1";
	$success_result = mysql_query($success_query);
	$success_amount = CURRENCY.round(mysql_result($success_result,0,0), 2);
	
	/* GET TOTAL AMOUNT OF HOLD TRANSACTION */
	$hold_query = "SELECT SUM(AMT) FROM transaction_details where transaction_details.COUPONID = '$dealid' and CAPTURED=0 and CAPTURED_ACK=''";
	$hold_result = mysql_query($hold_query);
	$hold_amount = CURRENCY.round(mysql_result($hold_result,0,0), 2);
	
	/* GET TOTAL AMOUNT OF FAILED TRANSACTION */
	$failed_query = "SELECT SUM(AMT) FROM transaction_details where transaction_details.COUPONID = '$dealid' and CAPTURED=0 and CAPTURED_ACK='Failed'";
	$failed_result = mysql_query($failed_query);
	$failed_amount = CURRENCY.round(mysql_result($failed_result,0,0), 2);
	
	$pagination = new pagination();
	$pagination->createPaging($query,20);
	$resultSet = $pagination->resultpage;


	if(mysql_num_rows($resultSet)>0)
	{
	 ?>
			 <table cellpadding="10" cellspacing="0"  style="border:1px; margin:5px;">
			 <tr>
			 <th><?php echo $admin_language['date']; ?></th>
			 <th><?php echo $admin_language['user']; ?></th>
			 <th><?php echo $admin_language['description']; ?></th>
			 <th><?php echo $admin_language['quantity']; ?></th>
			 <th><?php echo $admin_language['amount']; ?>(<?php echo CURRENCY;?>)</th>
			 <th><?php echo $admin_language['status']; ?></th>
			 <th>Coupon Code</th>
			 <th>Coupon used status</th>
			 </tr>
	
	<?php 
		while($row = mysql_fetch_array($resultSet))
		{ 
			?>
			<tr>
			<td><?php echo $row["TIMESTAMP"];?></td>
			<td><?php echo $row["username"];?></td>
			<td><?php echo $row["username"];?> <?php echo $admin_language['bought']; ?> <?php echo $row["coupon_name"];?></td>
			<td><?php echo $row["L_QTY0"];?></td>
			<td><?php echo round($row["AMT"], 2);?></td>
			<td><?php 
			if($row["CAPTURED"] == 1)
			{
				?><a href="<?php echo DOCROOT; ?>admin/deal_report_view/<?php echo $dealid; ?>?type=success"><?php echo "Success"; ?></a><?php
			}
			else
			{
			        if($row["CAPTURED_ACK"] == 'Failed')
			        {
				        ?><a href="<?php echo DOCROOT; ?>admin/deal_report_view/<?php echo $dealid; ?>?type=failed"><?php echo "Failed"; ?></a><?php
				}
				else
				{
                                        ?><a href="<?php echo DOCROOT; ?>admin/deal_report_view/<?php echo $dealid; ?>?type=hold"><?php echo "Hold"; ?></a><?php
                                 }
			}	
				?></td>
		        <td>
		        <?php
		        if(!empty($row["coupon_validityid"]))
		        {
		                echo $row["coupon_validityid"];
		        }
		        else
		        {
		                echo '-';
		        }
		        ?>
		        </td>
		        <td><?php echo $row["coupons_userstatus"];?></td>
			</tr>
			<?php 
		} 
		 
		if(empty($select) || $select == 'success')
		{
		        ?>
		        <tr>
		        <td colspan="4" align="right"><?php echo $admin_language['successtransactionamount']; ?></td>
		        <td align="left">
		        <?php echo $success_amount; ?>
		        </td>
		        </tr>
		        <?php
		}
		if(empty($select) || $select == 'hold')
		{
		        ?>
		        <tr>
		        <td colspan="4" align="right"><?php echo $admin_language['holdtransactionamount']; ?></td>
		        <td align="left">
		        <?php echo $hold_amount; ?>
		        </td>
		        </tr>
		        <?php
		}
		if(empty($select) || $select == 'failed')
		{
		        ?>
		        <tr>
		        <td colspan="4" align="right"><?php echo $admin_language['failedtransactionamount']; ?></td>
		        <td align="left">
		        <?php echo $failed_amount; ?>
		        </td>
		        </tr>
		        <?php
		}
		if(empty($select))
		{
		        $sum = mysql_query("select SUM(AMT) as total_amount from transaction_details left join coupons_coupons on transaction_details.COUPONID = coupons_coupons.coupon_id left join coupons_users on transaction_details.USERID = coupons_users.userid where COUPONID = '$dealid'");
		        ?>
		        <tr>
		        <td colspan="4" align="right"><?php echo $admin_language['totaltransactionamount']; ?></td>
		        <td align="left"><?php 
		        while($row = mysql_fetch_array($sum)) { 
			        echo CURRENCY.round($row["total_amount"], 2);
		        } ?>
		        </td>
		        </tr>
		        <?php
		}
		?>
		</table>
		
		<table border="0" width="400" align="center">
			<tr>
			<td align="center">
			<?php echo $pagination->displayPaging(); ?>
			</td>
			</tr>
		</table>
		
		<?php 
	}
	else
	{
		?>
			<div class="no_data">
			<?php echo $admin_language['notransactionavailable']; ?>
			</div>
		<?php 
	}
	?>
	</div>
