
<script type = "text/javascript">
/* validation */
$(document).ready(function(){$("#unsubscribe").validate();});

</script>

<ul>
<li><a href="/" title="<?php echo $language['home']; ?>"><?php echo $language['home']; ?> </a></li>
<li><span class="right_arrow"></span></li>
<li><a href="javascript:;" title="<?php echo $language['unsubscribe']; ?>"><?php echo $language['unsubscribe']; ?></a></li>    
</ul>

<h1><?php echo $page_title; ?></h1>

<?php 
if($_POST)
{

	$email = $_POST['email'];

	if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$email))
	{
		set_response_mes(-1,$language['invalid_email']);
		url_redirect($_SERVER['REQUEST_URI']);
	} 

	if($_POST['newsletter_unsubscribe']==1)
	{
		mysql_query("update newsletter_subscribers set status='D' where email='$email'");
		set_response_mes(1, $language['changes_updated']);  
	}

	if($_POST['email_unsubscribe']==1)
	{
		mysql_query("update coupons_users set status='D' where email='$email'");
		set_response_mes(1, $language['changes_updated']); 
	}

	url_redirect(DOCROOT.'unsubscribeconfirmation.html');

}
?>

                        
<div class="work_bottom">

<form action="" name="unsubscribe" id="unsubscribe" method="post">
<table width="100%" border="0" cellpadding="5" cellspacing="5" class="contact_user">

<tr>
<td align="right" valign="top"><input type="checkbox" name="newsletter_unsubscribe" value="1" class="" /></td>
		<td><label><?php echo $language['unsubscribe_txtmsg']; ?></label></td>
</tr>

<tr>
<td align="right" valign="top"><input type="checkbox" name="email_unsubscribe" value="1" class="" /></td>
		<td><label><?php echo $language['unsubscribe_txtmsg2']; ?></label></td>
</tr>

<tr>
<td align="right" valign="top"><label><?php echo $language['email']; ?> :</label></td>
		<td><input type="text" class="input_box required email" name="email" id="email" title="<?php echo $language['valid_email']; ?>" /></td>
</tr>

<tr><td>&nbsp;</td>
<td><input type="submit" value="<?php echo $language['submit']; ?>" name="submit" /> &nbsp;
<input type="reset" value="<?php echo $language['reset']; ?>" name="submit" />
</td>
</tr>

</table>
</form>

</div>
