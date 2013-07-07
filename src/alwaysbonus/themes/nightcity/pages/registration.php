<?php 
if($_SESSION["userid"])
{
	url_redirect(DOCROOT."profile.html");
}

$url_array = explode('=',$_SERVER['REQUEST_URI']);
if(isset($url_array[1]))
{
        $_SESSION['referral_id'] = $url_array[1];
}

	if($_POST['signup'] == $language['signup'])
	{


		if($_POST["username"]==$language['valid_username'] || $_POST["password"]==$language['valid_password'] || $_POST["firstname"]==$language['valid_firstname'] || $_POST["lastname"]==$language['valid_lastname'] || $_POST["email"]==$language['valid_email']){

				set_response_mes(-1,'All fields are mandatory');
				url_redirect(DOCROOT."registration.html");

		}

		$username = htmlentities($_POST["username"], ENT_QUOTES);
		$password = md5($_POST["password"]);
		$firstname = htmlentities($_POST["firstname"], ENT_QUOTES);
		$lastname = htmlentities($_POST["lastname"], ENT_QUOTES);
		$email = $_POST["email"];
		$mobile = htmlentities($_POST["mobile"], ENT_QUOTES);
		
		//check user availability 
		$result = mysql_query("select * from coupons_users where username='$username'");
		if(mysql_num_rows($result) == 0)
		{
			//check email address already exist
			$resultSet = mysql_query("select * from coupons_users where email='$email' ");
			if(mysql_num_rows($resultSet) > 0)
			{	
			    if(!empty($_SESSION['referral_id']))
			    {	
			     set_response_mes(-1, $language['reg_email_exist']);
			     url_redirect(DOCROOT."ref.html?id=".$_SESSION['referral_id']);	
			    }
			    else
			    {
			     set_response_mes(-1, $language['reg_email_exist']);
			     url_redirect(DOCROOT."registration.html");
			    }	
			}		

			$ranval = referral_ranval();
			$query = "insert into coupons_users(username,password,firstname,lastname,email,mobile,user_role,user_status,referral_id) values('$username','$password','$firstname','$lastname','$email','$mobile','4','A','$ranval')";
			$res = mysql_query($query) or die(mysql_error());
			$last_insert_id = mysql_insert_id();
			
			//insert referal list
			if(!empty($_SESSION['referral_id']))
			{
				$referral_id = $_SESSION['referral_id'];
				$resultSet = mysql_query("select * from coupons_users where referral_id='$referral_id'");
				
				if(mysql_num_rows($resultSet) > 0)
				{
				
					while($row = mysql_fetch_array($resultSet))
					{
						$userid = $row['userid'];
					}

					mysql_query("insert into referral_list (reg_person_userid,referred_person_userid,deal_bought_count) values ('$last_insert_id','$userid','0')");

					$_SESSION['referral_id']='';	
											
				}
				

			}
			
			
			if($last_insert_id)
			{
			
			        $logo = DOCROOT."site-admin/images/logo.png";
				// Send mail to user regarding successfull registration
				$from = SITE_EMAIL;
				$to = $_POST["email"];
				$name = $_POST["firstname"];

				//getting subject and description variables
				$subject = $email_variables['registration_subject'];
				$description = 	$email_variables['registration_description'];
                                $description = str_replace("USERNAME",$_POST["username"],$description);
                                $description = str_replace("PASSWORD",$_POST["password"],$description);
	  
	                        /* GET THE EMAIL TEMPLATE FROM THE FILE AND REPLACE THE VALUES */
	                        	
                                $str = implode("",file(DOCUMENT_ROOT.'/themes/_base_theme/email/email_all.html'));
                                
                                $str = str_replace("SITEURL",$docroot,$str);
                                $str = str_replace("SITELOGO",$logo,$str);
                                $str = str_replace("RECEIVERNAME",ucfirst($name),$str);
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
					$headers .= 'From: '.$from.'' . "\r\n";
					mail($to,$subject,$message,$headers);	
				}
			
				set_response_mes(1,$language['registration_success']);
				url_redirect(DOCROOT."login.html");
			}
		}
		else
		{
			    if(!empty($_SESSION['referral_id']))
			    {	
			     set_response_mes(-1, $language['username_exists']);
			     url_redirect(DOCROOT."ref.html?id=".$_SESSION['referral_id']);	
			    }
			    else
			    {
			     set_response_mes(-1, $language['username_exists']);
			     url_redirect(DOCROOT."registration.html");
			    }	
		}
	}
?>
<script type = "text/javascript">
/* validation */
$(document).ready(function(){$("#registration").validate();});

</script>

<ul>
  <li><a href="/" title="<?php echo $language['home']; ?>"><?php echo $language['home']; ?> </a></li>
  <li><span class="right_arrow"></span></li>
  <li><a href="javascript:;" title="<?php echo $language['signup']; ?>"><?php echo $language['signup']; ?></a></li>
</ul>
<h1><?php echo $page_title; ?></h1>
<div class="work_bottom1">
  <form name="registration" id="registration" action="" method="post"  >
    <table border="0" cellpadding="5" cellspacing="5" >
      <tr>
        <td align="right" valign="middle"><label><?php echo $language['username']; ?> : </label></td>
        <td><input type="text" value="" class="input_box required nospecialchars" name="username" id="username" title="<?php echo $language['valid_username']; ?>" /></td>
      </tr>
      <tr>
        <td align="right" valign="middle"><label><?php echo $language['password']; ?> : </label></td>
        <td><input type="password" value="" class="input_box required" name="password" id="password" title="<?php echo $language['valid_password']; ?>" /></td>
      </tr>
      <tr>
        <td align="right" valign="middle"><label><?php echo $language['first_name']; ?> : </label></td>
        <td><input type="text" class="input_box required nospecialchars" name="firstname" id="firstname" title="<?php echo $language['valid_firstname']; ?>" /></td>
      </tr>
      <tr>
        <td align="right" valign="middle"><label><?php echo $language['last_name']; ?> : </label></td>
        <td><input type="text" class="input_box required nospecialchars" name="lastname" id="lastname" title="<?php echo $language['valid_lastname']; ?>" /></td>
      </tr>
      <tr>
        <td align="right" valign="middle"><label><?php echo $language['email']; ?> : </label></td>
        <td><input type="text" class="input_box required email" name="email" id="email" title="<?php echo $language['valid_email']; ?>" /></td>
      </tr>
      <tr>
        <td align="right" valign="middle"><label><?php echo $language['mobile']; ?> : </label></td>
        <td><input type="text" class="input_box" name="mobile" id="mobile" title="<?php echo $language['valid_mobile']; ?>" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><span class="quit"><?php echo $language['optional_mobile']; ?></span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div class="signup_now"> <span class="submit">
            <input type="submit" name="signup" value="<?php echo $language['signup']; ?>" class="bnone"/>
            </span> <span class="reset ">
            <input type="reset" class="bnone" value="<?php echo $language['reset']; ?>" />
            </span> </div></td>
      </tr>
    </table>
  </form>
</div>
