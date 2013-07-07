<?php session_start();
is_login(DOCROOT."admin/login/"); //checking whether admin logged in or not.

$userid = $_SESSION['userid'];
$queryString = "SELECT * FROM coupons_users left join coupons_shops on coupons_shops.shopid=coupons_users.user_shopid left join coupons_cities on coupons_users.city=coupons_cities.cityid 
left join coupons_country on coupons_users.country=coupons_country.countryid 
where userid='$userid'";
$resultSet = mysql_query($queryString);

	while($row = mysql_fetch_array($resultSet))
	{
		$userid=$row['userid'];
		$username=html_entity_decode($row['username'], ENT_QUOTES);
		$firstname=html_entity_decode($row['firstname'], ENT_QUOTES);
		$lastname=html_entity_decode($row['lastname'], ENT_QUOTES);
		$email=html_entity_decode($row['email'], ENT_QUOTES);
		$mobile=html_entity_decode($row['mobile'], ENT_QUOTES);
		$user_role=$row['user_role'];
		$user_shopid=$row['user_shopid'];
		$address=ucfirst(nl2br(html_entity_decode($row['address'], ENT_QUOTES)));
		$city=$row['cityname'];
		$state=$row['state'];
		$country=$row['countryname'];
		$created_by=$row['created_by'];
		$created_date=$row['created_date'];
	}
?>

		<div class="ml50 fwb clr fr" style="padding-bottom:10px;">
		<a  href="<?php echo $docroot; ?>admin/edit/<?php echo getRoleNameUsrInfo($_SESSION['userrole']);?>/<?php echo $_SESSION['userid'];?>/" title="<?php echo $admin_language['dashboard_edit']; ?>"><?php echo $admin_language['dashboard_edit']; ?></a>&nbsp;&nbsp;
		<a   href="<?php echo $docroot; ?>admin/changepassword/<?php echo getRoleNameUsrInfo($_SESSION['userrole']);?>/<?php echo $_SESSION['userid'];?>/" title="<?php echo $admin_language['dashboard_change_pass']; ?>"><?php echo $admin_language['dashboard_change_pass']; ?></a>&nbsp;&nbsp;

		</div>  

<!-- Dashboard -->
<?php
if($_SESSION['userrole'] == '1')
{
        //users
        $query1 = "select * from coupons_users where user_role = 4";
        $result1 = mysql_query($query1);

        //Deals - active
        $query6 = "select * from coupons_coupons where coupon_status='A' and coupon_enddate >= now()";
        $result6 = mysql_query($query6);
        
        //Deals - closed
        $query7 = "select * from coupons_coupons where coupon_status='C' or coupon_enddate < now()";
        $result7 = mysql_query($query7);

        //Deals - pending
        $query8 = "select * from coupons_coupons where coupon_startdate > now() or (coupon_status='D' and coupon_enddate >= now())";
        $result8 = mysql_query($query8);
        
        //Deals - fund request
        $query10 = "select * from transaction_details";
        $result10 = mysql_query($query10);
        
        
        //Admin - Total savings and deals purchased
        $query11 = "select * from coupons_purchase_status";
        $result11 = mysql_query($query11);
        while($admin_saving = mysql_fetch_array($result11))
        {
                $coupons_purchased_count  = $admin_saving['coupons_purchased_count'];
                $coupons_amtsaved = $admin_saving['coupons_amtsaved'];
        }
        
        //Admin - savings
        $query11 = "select account_balance from coupons_users where userid='$userid'";
        $result11 = mysql_query($query11);
        while($admin_saving = mysql_fetch_array($result11))
        {
                $account_balance  = $admin_saving['account_balance'];
        } 
        
        ?>       
        <fieldset class="field" style="margin-left:10px;">         
                <legend class="legend"><?php echo $admin_language['dashboard_title']; ?></legend>
                <div class="fl width240 ">
                        <p class="ml50 fwb width170" style="padding-bottom:10px;" >
		                <?php echo $admin_language['dashboard_users']; ?>
	                </p>
                        <p class="ml50 fwb width170" style="padding-bottom:10px;" >
		                <a  href="<?php echo DOCROOT; ?>admin/rep/general/" title="<?php echo $admin_language['dashboard_gusers']; ?>"><?php echo $admin_language['dashboard_gusers']; ?>(<?php if(mysql_num_rows($result1)>0){ echo mysql_num_rows($result1);}else{ echo '0';} ?>)</a>
		
	                </p>
	        </div>
                <div class="fl width240" >
	                <p class="ml50 fwb width170" style="padding-bottom:10px;" >
		                <?php echo $admin_language["deals_m"]; ?>
	                </p>
	                <p class="ml50 fwb width170" style="padding-bottom:10px;" >
		                <a  href="<?php echo DOCROOT; ?>admin/view/rep/active/" title="<?php echo $admin_language['active']; ?>"><?php echo $admin_language['active']; ?> (<?php if(mysql_num_rows($result6)>0){ echo mysql_num_rows($result6);}else{ echo '0';} ?>)</a>
	                </p>
	                <p class="ml50 fwb width170" style="padding-bottom:10px;" >
		                <a  href="<?php echo DOCROOT; ?>admin/view/rep/closed/" title="<?php echo $admin_language['closed']; ?>"><?php echo $admin_language['closed']; ?> (<?php if(mysql_num_rows($result7)>0){ echo mysql_num_rows($result7);}else{ echo '0';} ?>)</a>
	                </p>
	                <p class="ml50 fwb width170" style="padding-bottom:10px;" >
		                <a  href="<?php echo DOCROOT; ?>admin/view/rep/pending/" title="<?php echo $admin_language['pending']; ?>"><?php echo $admin_language['pending']; ?> (<?php if(mysql_num_rows($result8)>0){ echo mysql_num_rows($result8);}else{ echo '0';} ?>)</a>
	                </p>
	        </div>
	        <div class="fl width240">
	                <p class="ml50 fwb width170" style="padding-bottom:10px;" >
		               <?php echo $admin_language['transact']; ?>
	                </p>

	                <p class="ml50 fwb width170" style="padding-bottom:10px;" >
		                <a  href="<?php echo DOCROOT; ?>admin/transaction/all/" title="<?php echo $admin_language['transact']; ?>"><?php echo $admin_language['transact']; ?> (<?php if(mysql_num_rows($result10)>0){ echo mysql_num_rows($result10);}else{ echo '0';} ?>)</a>
	                </p>
	        </div>
	        <div class="clr">
	                <div class="fl width240">
	                        <p class="ml50 fwb width170" style="padding-bottom:10px;" >
		                        <?php echo $admin_language['dashboard_transaction_summary']; ?>
	                        </p>

	                        <p class="ml50 fwb width170" style="padding-bottom:10px;" >
		                        <?php echo $admin_language['totalcouponpurcharsed']; ?> <?php echo $coupons_purchased_count; ?>
	                        </p>
	                        <p class="ml50 fwb width170" style="padding-bottom:10px;" >
		                        <?php echo $admin_language['totalamountsaved']; ?><?php echo CURRENCY.$coupons_amtsaved; ?>
	                        </p>
	                </div>

	        </div>
        </fieldset>

        <?php
}

?>
		
 <fieldset class="field" style="margin-left:10px;">         
        <legend class="legend"><?php echo $admin_language['dashboard_account_information']; ?></legend>
	<table border="0" cellpadding="5" align="left" class="padd form_table">

	   <tr>
	    <td valign="top" align="right" class="fwb"><label><?php echo $admin_language['dashboard_firstname']; ?></label></td>
	    <td><?php echo ucfirst($firstname);?></td>
	  </tr>

	   <tr>
	    <td valign="top" align="right" class="fwb"><label><?php echo $admin_language['dashboard_lastname']; ?></label></td>
	    <td><?php echo ucfirst($lastname);?></td>
	  </tr>

	   <tr>
	    <td valign="top" align="right" class="fwb"><label><?php echo $admin_language['dashboard_email']; ?></label></td>
	    <td><?php if($email!='') echo $email;  else echo '-'; ?></td>
	  </tr>

	   <tr>
	    <td valign="top" align="right" class="fwb"><label><?php echo $admin_language['dashboard_mobile']; ?></label></td>
	    <td><?php if($mobile!='') echo $mobile;  else echo '-'; ?></td>
	  </tr>

	   <tr>
	    <td valign="top" align="right" class="fwb"><label><?php echo $admin_language['dashboard_address']; ?></label></td>
	    <td><?php if($address!='') echo $address;  else echo '-'; ?></td>
	  </tr>

	   <tr>
	    <td valign="top" align="right" class="fwb"><label><?php echo $admin_language['dashboard_city']; ?></label></td>
	    <td><?php if($city!='') echo $city;  else echo '-'; ?></td>
	  </tr>

	   <tr>
	    <td valign="top" align="right" class="fwb"><label><?php echo $admin_language['dashboard_country']; ?></label></td>
	    <td><?php if($country!='') echo $country;  else echo '-'; ?></td>
	  </tr>

		<?php 
		if($_SESSION['userrole']!=1)
		{
		?>
			   <tr>
			    <td valign="top" align="right" class="fwb"><label><?php echo $admin_language['dashboard_createdby']; ?></label></td>
			    <td><?php echo ucfirst(getUserName($created_by));?></td>
			  </tr>
		<?php
		} 
		?>

	   <tr>
	    <td valign="top" align="right" class="fwb"><label><?php echo $admin_language['dashboard_createddate']; ?></label></td>
	    <td><?php echo $created_date;?></td>
	  </tr>
	      
	</table>
  </fieldset>
