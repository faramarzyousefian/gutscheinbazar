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

$(document).ready(function(){$("#contactus").validate();});

</script>



<ul>
<li><a href="/" title="Home"><?php echo $language["home"]; ?> </a></li>
<li><span class="right_arrow"></span></li>
<li><a href="javascript:;" title="Contact Us"><?php echo $language["contact_us"]; ?></a></li>    
</ul>

<h1><?php echo $page_title; ?></h1>

<?php
	if($_POST)
	{
		$to = SITE_EMAIL;
		$name = $_POST["name"];
		$email = $_POST["email"];
		$phone = $_POST["phone"];
		$location = $_POST["location"];
		$subject = $_POST["subject"];
		
		$mes = "<p> Name:".$name."</p><p>Phone:".$phone."</p><p>Location:".$location."</p>"."<p>".$_POST["description"]."</p>";
		send_email($email,$to,$subject,$mes); //call email function
		set_response_mes(1,"Thank you for your enquiry. We will contact you soon.");
		url_redirect(DOCROOT.'contactus.html');
	}
?>

<div class="work_bottom contactus">
<form action="" name="contactus" id="contactus" method="post">
<table width="100%" border="0" cellpadding="5" cellspacing="5" class="contact_user">
<tr><td align="right">
<label><?php echo $language["contact_name"]; ?> :</label>
</td><td>
<input name="name" type="text" class="required input_box" title="<?php echo $language['valid_name']; ?>" />
</td></tr>
<tr><td align="right">
<label><?php echo $language["e-mail"]; ?> :</label>
</td><td>
<input name="email" type="text" class="required email input_box" title="<?php echo $language['valid_email']; ?>" />
</td></tr>

<tr><td align="right">
<label><?php echo $language["phone"]; ?> :</label></td>
<td><input name="phone" type="text" id="phone" class="input_box" /> </td></tr>

<tr><td>&nbsp;</td>
<td><span><?php echo $language["optional_text"]; ?></span>
</td></tr>

<tr><td align="right">
<label><?php echo $language["location"]; ?> :</label></td><td><input name="location" type="text" id="location" class="required input_box" title="<?php echo $language['valid_location']; ?>" /></td></tr>

<tr><td align="right">
<label><?php echo $language["subject"]; ?> :</label></td>
<td>
<input name="subject" type="text" id="subject" class="required input_box" title="<?php echo $language['valid_subject']; ?>" /></td></tr>

<tr><td align="right" valign="top">
<label><?php echo $language["message"]; ?> :</label> </td>
<td >
<textarea name="description" cols="40" rows="5" class="borderccc required" title="<?php echo $language['valid_message']; ?>"></textarea>
</td></tr>

<tr>
<td>&nbsp; </td>
<td>
<input type="submit" name="submit" value="Post" />
</td>
</tr>
</table>
</form>
</div>
