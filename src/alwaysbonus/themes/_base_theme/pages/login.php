<?php
/****************************************** 
* @Created on June, 2011
* @Package: ndotdeals 3.0 (Opensource groupon clone) 
* @Author: NDOT
* @URL : http://www.NDOT.in
********************************************/
?>

<script type = "text/javascript">
/* validation */
$(document).ready(function(){$("#login").validate();});

</script>

<?php 

if($_SESSION["userid"])
{
	url_redirect(DOCROOT."profile.html");
}

if($_POST)
{
	$username = $_POST["username"];
	$password = md5($_POST["password"]);
	$result = loginCheck($username,$password);

	if($result == "Success")
	{
		set_response_mes(1,$language['login_success']);
		$reference_url = $_SESSION["ref"];
		$_SESSION["ref"] = "";
		
		if($reference_url)
		{
			url_redirect($reference_url);
		}
		else
		{
			url_redirect(DOCROOT."profile.html");
		}
	}
	else
	{
		set_response_mes(-1,$language['password_incorrect']);
		url_redirect(DOCROOT."login.html");	
	}
}



?>


<ul>
<li><a href="/" title="<?php echo $language['home']; ?>"><?php echo $language['home']; ?> </a></li>
<li><span class="right_arrow"></span></li>
<li><a href="javascript:;" title="<?php echo $language['login']; ?>"><?php echo $language['login']; ?></a></li>    
</ul>

<h1><?php echo $page_title; ?></h1>


<div class="work_bottom login_area">
<form action="" name="login" id="login" method="post">
<table width="100%" border="0" cellpadding="5" cellspacing="5" class="contact_user">
<tr>
<td align="right" valign="top"><label><?php echo $language['username']; ?> :</label></td>
<td><input name="username" type="text" class="required input_box" title="<?php echo $language['valid_username']; ?>" /></td>
</tr>
<tr>
<td align="right" valign="top"><label><?php echo $language['password']; ?> :</label></td>
<td><input name="password" type="password" class="required input_box" title="<?php echo $language['valid_password']; ?>" /></td>
</tr>
<tr><td>&nbsp;</td>
<td><input type="submit" value="<?php echo $language['login']; ?>" name="submit" /> &nbsp;
<input type="reset" class="ml10" value="<?php echo $language['reset']; ?>" name="submit" />
</td>
</tr>
<tr><td>&nbsp;</td><td><a href="<?php echo DOCROOT;?>forgot-password.html" title="<?php echo $language['forgot_password']; ?>"><?php echo $language['forgot_password']; ?></a></td></tr>
</table>
</form>

</div>
