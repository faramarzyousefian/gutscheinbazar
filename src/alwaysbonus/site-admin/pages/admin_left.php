<?php
is_login(DOCROOT."admin/login/"); //checking whether admin logged in or not.
if($_SESSION['userrole']=='1')
{
?>

	<?php
	if($_POST["site_mode"] == $admin_language['submit'])
	{ 
		$id = $_POST["id"];
		$site_in = $_POST['site_in'];
		$query = "update general_settings set site_in='$site_in' where id='$id'";
		mysql_query($query);
		set_response_mes(1,$admin_language['site_mode_change']);
		url_redirect($_SERVER['REQUEST_URI']);
	}
	?>

	<div class="menu_container">
				<!-- user info -->
                <div class="menu_user">
                    <div class="user_detail">
                    	<label><?php echo $admin_language['loginas']; ?></label>
                        <a href="<?php echo DOCROOT.'admin/profile/'; ?>" title="<?php echo ucfirst($_SESSION['username']); ?>" class="user_name"><?php echo ucfirst($_SESSION['username']); ?></a>
						
						
                        <div class="menu_buttons">
                        	<div class="fl"><div class="admi_lft fl"></div><a href="<?php echo DOCROOT.'admin/profile/'; ?>" class="admi_mid fl" title="<?php echo $admin_language['profile']; ?>"><?php echo $admin_language['profile']; ?></a><div class="admi_rgt fl"></div></div>
                            <div class="fl ml10"><div class="admi_lft fl"></div><a href="<?php echo DOCROOT.'admin/logout/'; ?>" class="admi_mid fl" title="<?php echo $admin_language['logout']; ?>"><?php echo $admin_language['logout']; ?></a><div class="admi_rgt fl"></div></div>
                        </div>
						
						<?php 
						$userid = $_SESSION["userid"];
						$get_bal_amount = mysql_query("select account_balance from coupons_users where userid='$userid'");
						if(mysql_num_rows($get_bal_amount)>0)
						{
						while($row = mysql_fetch_array($get_bal_amount))
						{
							$current_user_balance_amount = $row["account_balance"];
							
							if($_SESSION['userrole'] != '1')
							{
						?>
								<div class="fund_request mt10 fl">
								<label><?php echo $admin_language['balance']; ?> <?php echo CURRENCY;?> <?php echo $current_user_balance_amount;?></label>
								</div>
						<?php
							}
						 } 
						}
						?>
						
						
                    </div>
                    
                </div>
				<!-- user info end -->
				
                <ul>
			<?php if($_SESSION['userrole']=='1') {?>

					<!-- deal management -->
					<li onclick="toggle(104)">
						<div class="menu_lft"></div>
						<a class="menu_rgt"  href="javascript:;" title="<?php echo $admin_language['deals_m']; ?>">
						<span class="fl deals_menu"><?php echo $admin_language['deals_m']; ?></span><img id="left_menubutton_104" src="<?php echo DOCROOT; ?>site-admin/images/plus_but.png" />
						</a>
						
						<ul class="toggleul_104">
						<li>
						<div class="menu_lft1"></div>
						<a class="menu_rgt1" href="<?php echo DOCROOT; ?>admin/couponsupload/" title="<?php echo $admin_language['deals_add']; ?>"><span class="pl15 fl"><?php echo $admin_language['deals_add']; ?></span></a>
						</li>
	
						<li>
						<div class="menu_lft1"></div>
						<a class="menu_rgt1" href="<?php echo DOCROOT; ?>admin/view/rep/all" title="<?php echo $admin_language['deals_all']; ?>">
						<span class="pl15 fl"><?php echo $admin_language['deals_all']; ?></span>
						</a>
						</li>
	
						<li>
						<div class="menu_lft1"></div>
						<a class="menu_rgt1" href="<?php echo DOCROOT; ?>admin/view/rep/active" title="<?php echo $admin_language['deals_active']; ?>"><span class="pl15 fl"><?php echo $admin_language['deals_active']; ?></span></a>
						</li>
	
						<li><div class="menu_lft1"></div>
						<a class="menu_rgt1" href="<?php echo DOCROOT; ?>admin/view/rep/closed" title="<?php echo $admin_language['deals_closed']; ?>">
						<span class="pl15 fl"><?php echo $admin_language['deals_closed']; ?></span></a></li>
						<li><div class="menu_lft1"></div>
						<a class="menu_rgt1" href="<?php echo DOCROOT; ?>admin/view/rep/pending" title="<?php echo $admin_language['deals_pending']; ?>">
						<span class="pl15 fl"><?php echo $admin_language['deals_pending']; ?></span></a></li>
						</ul>
					</li>
					<!-- deal management end -->				
					
			<?php }
			?>



					<li>
					<div class="menu_lft"></div>
					<a class="menu_rgt" href="<?php echo DOCROOT; ?>admin/shopdetails" title="Shop Details">
					<span class="pl15 fl">Shop Details</span>
					</a>
					</li>	 


					<li>
					<div class="menu_lft"></div>
					<a class="menu_rgt" href="<?php echo DOCROOT; ?>admin/couponvalidate" title="Validate Coupon Code">
					<span class="pl15 fl">Validate Coupon Code</span>
					</a>
					</li>	 

			
			<?php if($_SESSION['userrole']=='1') { ?>                
					
			<!-- transaction -->
			<li>
			<div class="menu_lft"></div>
			<a class="menu_rgt" href="<?php echo DOCROOT; ?>admin/transaction/all" title="<?php echo $admin_language['transaction_m']; ?>" title="<?php echo $admin_language['transaction_m']; ?>">
			<span class="pl15 trans_menu fl"><?php echo $admin_language['transaction_m']; ?></span>
			</a>
			</li>


		   <!-- User management -->
		   <li onclick="toggle(101)">
			   <div class="menu_lft"></div><a class="menu_rgt"  href="javascript:;" title="Country"><span class="users_menu fl"><?php echo $admin_language['user']; ?></span><img id="left_menubutton_101" src="<?php echo DOCROOT; ?>site-admin/images/plus_but.png" /></a>
				<ul class="toggleul_101">
				<li><div class="menu_lft1"></div><a class="menu_rgt1" href="<?php echo DOCROOT; ?>admin/rep/general" title="<?php echo $admin_language['user']; ?>"><span class="pl15 fl">General</span></a></li>

				<li><div class="menu_lft1"></div><a class="menu_rgt1" href="<?php echo DOCROOT; ?>admin/rep/admin" title="<?php echo $admin_language['users_admin']; ?>"><span class="pl15 fl"><?php echo $admin_language['users_admin']; ?></span></a></li>

				</ul>
		  </li>
			<!-- user management end -->

			<!-- general settings -->
			<li onclick="toggle(4)">
				<div class="menu_lft"></div><a class="menu_rgt"  href="javascript:;" title="<?php echo $admin_language['general']; ?>">
				<span class="general_menu fl"><?php echo $admin_language['general']; ?></span>
				<img id="left_menubutton_4" src="<?php echo DOCROOT; ?>site-admin/images/plus_but.png" />
				</a>
				<ul class="toggleul_4">
				
				<li><div class="menu_lft1"></div>
				<a class="menu_rgt1" href="<?php echo DOCROOT; ?>admin/general/" title="<?php echo $admin_language['general_m']; ?>"><span class="pl15 fl"><?php echo $admin_language['general_m']; ?></span></a></li>

				<li><div class="menu_lft1"></div>
				<a class="menu_rgt1" href="<?php echo DOCROOT; ?>admin/module/" title="<?php echo $admin_language['general_module']; ?>">
				<span class="pl15 fl"><?php echo $admin_language['general_module']; ?></span></a></li>
				
				<li><div class="menu_lft1"></div><a class="menu_rgt1" href="<?php echo DOCROOT; ?>add/country/" title="<?php echo $admin_language['country_add']; ?>"><span class="pl15 fl"><?php echo $admin_language['country_add']; ?></span></a></li>

				<li><div class="menu_lft1"></div><a class="menu_rgt1" href="<?php echo DOCROOT; ?>manage/country/" title="<?php echo $admin_language['country_manage']; ?>"><span class="pl15 fl"><?php echo $admin_language['country_manage']; ?></span></a></li>

				<li><div class="menu_lft1"></div><a class="menu_rgt1" href="<?php echo DOCROOT; ?>add/city/" title="<?php echo $admin_language['city_add']; ?>"><span class="pl15 fl"><?php echo $admin_language['city_add']; ?></span></a></li>

				<li><div class="menu_lft1"></div><a class="menu_rgt1" href="<?php echo DOCROOT; ?>manage/city/" title="<?php echo $admin_language['city_manage']; ?>"><span class="pl15 fl"><?php echo $admin_language['city_manage']; ?></span></a></li>
				
				<li><div class="menu_lft1"></div><a class="menu_rgt1" href="<?php echo DOCROOT; ?>add/category/" title="<?php echo $admin_language['addcategory']; ?>"><span class="pl15 fl"><?php echo $admin_language['addcategory']; ?></span></a></li>

				<li><div class="menu_lft1"></div><a class="menu_rgt1" href="<?php echo DOCROOT; ?>manage/category/" title="<?php echo $admin_language['managecategory']; ?>"><span class="pl15 fl"><?php echo $admin_language['managecategory']; ?></span></a></li>				

			</ul>
			</li>
			<!--  general settings end -->
			
			<?php }?>

                
			<?php if($_SESSION['userrole']=='1') {
			?> 
                                <!-- Email & SMS marketing -->
                                
                                
                                <li onclick="toggle(12)">
                                        <div class="menu_lft"></div>
                                        <a class="menu_rgt"  href="javascript:;" title="Site Configuration">
                                                <span class="email_menu fl"><?php echo $admin_language['email_sms']; ?></span>
			                        <img id="left_menubutton_12" src="<?php echo DOCROOT; ?>site-admin/images/plus_but.png" />
			                </a>
			                <ul class="toggleul_12">

		                        <li>
		                                <div class="menu_lft1"></div>
		                                <a class="menu_rgt1" href="<?php echo DOCROOT; ?>admin/emailall/" title="<?php echo $admin_language['emailuser']; ?>"><span class="pl15 fl"><?php echo $admin_language['emailuser']; ?></span></a>
		                        </li>
					<li>
					        <div class="menu_lft1"></div>
					        <a class="menu_rgt1" href="<?php echo DOCROOT; ?>admin/newsletter/" title="<?php echo $admin_language['newsletter']; ?>"><span class="pl15 fl"><?php echo $admin_language['newsletter']; ?></span></a>
					</li>
				
		                        </ul>
		               </li>
		               
                                <!-- Email & SMS marketing END-->			
                </ul>
                <?php }?>

            </div>
            
            
	<script type="text/javascript">
	function toggle(ids){
	
		$(".toggleul_"+ids).slideToggle();
		var imgSrc = document.getElementById("left_menubutton_"+ids).src;
		imgSrc = imgSrc.substr(-13, 13);
		if(imgSrc == "minus_but.png"){
			document.getElementById("left_menubutton_"+ids).src = "<?php echo DOCROOT; ?>site-admin/images/plus_but.png"
		}
		else{
			document.getElementById("left_menubutton_"+ids).src = "<?php echo DOCROOT; ?>site-admin/images/minus_but.png"
		}	
		
	}
	</script>

<?php
}
else
{
	url_redirect(DOCROOT);	
}?>
