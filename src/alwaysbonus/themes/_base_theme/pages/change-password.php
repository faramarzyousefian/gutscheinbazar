<?php 
/******************************************** * @Created on June, 2011
* @Package: ndotdeals 3.0 (Opensource groupon clone) 
* @Author: NDOT
 * @URL : http://www.NDOT.in
 ********************************************/
?>

<script type = "text/javascript">
/* validation */
$(document).ready(function(){$("#change_password").validate();});

</script>

<?php 

is_login(DOCROOT."login.html"); //checking whether user logged in or not. 

$userid = $_SESSION['userid'];

if($_POST)
{
        $password_old = md5($_POST["old_password"]);
	$password_1 = $_POST["password"];
	$password_2 = $_POST["password_2"];
	$queryString = "select * from coupons_users where password ='$password_old' and  userid='$userid' "; 
	
	$resultSet = mysql_query($queryString); 
	
	if(mysql_num_rows($resultSet) > 0)
	{
	        if($password_1 == $password_2)
	        {
		        $password = md5($password_1);
		        $query = "update coupons_users set password='$password' where userid='$userid' ";
		        mysql_query($query);
		
		        set_response_mes(1,$language["password_changed"]);
		        url_redirect(DOCROOT."change-password.html");
	        }
	        else
	        {
			        set_response_mes(-1,$language["not_matched"]);
			        url_redirect(DOCROOT."change-password.html");
	        }
        }
        else
        {
                set_response_mes(-1,$language["not_matched"]);
		url_redirect(DOCROOT."change-password.html");
                
        }
}
?>	 

<?php include("profile_submenu.php"); ?>
<h1><?php echo $page_title; ?></h1>


<div class="work_bottom1 ">
<form action="" name="login" id="change_password" method="post">
<table width="600px;" border="0" cellpadding="5" cellspacing="5" class="contact_user">

<?php if($_SESSION["logintype"] == 'connect') { 

	$userid = $_SESSION['userid'];

	$queryString = "SELECT * FROM coupons_users where userid='$userid'";
	$resultSet = mysql_query($queryString);

		while($row = mysql_fetch_array($resultSet))
		{
			$password = $row['password'];
		}

		if($password==md5('allowme'))
		{
		?>

			<tr>
			<td align="right" valign="top"><label>Dummy Password:</label></td>
			<td>allowme</td>
			</tr>

		<?php
		}

}?>

<tr>
<td align="right" valign="top"><label><?php echo $language["password_old"]; ?> :</label></td>
<td><input name="old_password" id="old_password" type="text" class="required input_box" title="<?php echo $language['valid_password']; ?>" /></td>
</tr>
<tr>
<td align="right" valign="top"><label><?php echo $language["password_new"]; ?> :</label></td>
<td><input name="password" id="password" type="text" class="required input_box" title="<?php echo $language['valid_password']; ?>" /></td>
</tr>

<tr>
<td align="right" valign="top"><label><?php echo $language["reenter_password"]; ?> :</label></td>
<td><input name="password_2" id="password_2" equalto="#password" type="text" class="required input_box" title="<?php echo $language['valid_password']; ?>" /></td>
</tr>

<tr><td>&nbsp;</td>
<td><input type="submit" value="<?php echo $language['update']; ?>" name="submit" />
</td>
</tr>
</table>
</form> 
</div>
