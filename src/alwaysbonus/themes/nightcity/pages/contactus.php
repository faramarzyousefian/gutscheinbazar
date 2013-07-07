<?php 
/******************************************
* @Created on March, 2011 * @Package: Ndotdeals unlimited v2.2
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

		if($_POST['agreeopt']!=1){

			set_response_mes(-1,"You have to agree the terms and conditions to proceed.");
			url_redirect(DOCROOT.'contactus.html');

		}

		$to = SITE_EMAIL;
		$name = $_POST["name"];
		$from = $_POST["email"];
		$phone = $_POST["phone"];
		$location = $_POST["location"];
		$subject = $_POST["subject"];
		
		$logo = DOCROOT."site-admin/images/logo.png";
		
		$mes = "<p> Name:".$name."</p><p>Phone:".$phone."</p><p>Location:".$location."</p>"."<p>".$_POST["description"]."</p>";
		/* GET THE EMAIL TEMPLATE FROM THE FILE AND REPLACE THE VALUES */	
                $str = implode("",file(DOCUMENT_ROOT.'/themes/_base_theme/email/email_all.html'));
                
                $str = str_replace("SITEURL",$docroot,$str);
                $str = str_replace("SITELOGO",$logo,$str);
                $str = str_replace("RECEIVERNAME",'Admin',$str);
                $str = str_replace("MESSAGE",ucfirst($mes),$str);
                $str = str_replace("SITENAME",SITE_NAME,$str);        
                
                $message = $str;
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
			$headers .= 'Bcc: '.$to.'' . "\r\n";
			mail($to,$subject,$message,$headers);	
		}
		
		//send_email($email,$to,$subject,$mes); //call email function
		set_response_mes(1,"Thank you for your enquiry. We will contact you soon.");
		url_redirect(DOCROOT.'contactus.html');
	}
?>
<div class="work_bottom contactus">
  <h4>The easiest way to contact us is to simply fill out the form below. Or for any specific questions/requests give us a call.</h4>
  <div class="contact_left">
    <form action="" name="contactus" id="contactus" method="post">
      <table width="100%" border="0" cellpadding="5" cellspacing="5" class="contact_user">
        <tr>
          <td width="100" align="right" valign="top"><label><?php echo $language["contact_name"]; ?> :</label>
          </td>
          <td><input name="name" type="text" class="required nospecialchars" title="<?php echo $language['valid_name']; ?>" />
          </td>
        </tr>
        <tr>
          <td width="100" align="right" valign="top"><label><?php echo $language["e-mail"]; ?> :</label>
          </td>
          <td><input name="email" type="text" class="required email input_box" title="<?php echo $language['valid_email']; ?>" />
          </td>
        </tr>
        <tr>
          <td width="100" align="right" valign="top"><label><?php echo $language["phone"]; ?> :</label></td>
          <td><input name="phone" type="text" id="phone" minlength="5" maxlength="20" />
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><span><?php echo $language["optional_text"]; ?></span> </td>
        </tr>
        <tr>
          <td width="100" align="right" valign="top"><label><?php echo $language["location"]; ?> :</label></td>
          <td><input name="location" type="text" id="location" class="required input_box" title="<?php echo $language['valid_location']; ?>" /></td>
        </tr>
        <tr>
          <td width="100" align="right" valign="top"><label><?php echo $language["subject"]; ?> :</label></td>
          <td><input name="subject" type="text" id="subject" class="required input_box" title="<?php echo $language['valid_subject']; ?>" /></td>
        </tr>
        <tr>
          <td width="100" align="right" valign="top"><label><?php echo $language["message"]; ?> :</label>
          </td>
          <td ><textarea name="description" cols="40" rows="5" class="borderccc required" title="<?php echo $language['valid_message']; ?>"></textarea>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" value="1" name="agreeopt" class="fl"/>
            <h3 style="width:235px;float:left;margin-top:3px;"><span  style="float:left;font:normal 12px arial;color:#000;">I agree to the </span><br />
              <a style="float:left;font:normal 12px arial;color:#FA7A0A;" href="javascript:;">Terms of Use and Privacy Policy</a></h3></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><div class="fl"> <span class="submit">
              <input type="submit" name="submit" value="<?php echo $language['post']; ?>" class="bnone" />
              </span> </div></td>
        </tr>
      </table>
    </form>
  </div>
  <div class="contact_us_right">
    <div class="fl clr mb20 ">
      <h5>Question about today's deal?</h5>
      <p>We can try to help, but you should probably try the business first - you can find a link to their website on the main deal page.
        Or just check out today's discussion board for the deal</p>
    </div>
    <div class="fl clr  mb20">
      <h5>Trouble accessing your Ndot?</h5>
      <p>Click on "Sign in" on the right side of the page near the top. Sign in using your email address and password, or if you originally used Facebook connect when you made the purchase, sign in that way. Once you are signed in, you will see your name at the top right of the page. Click on your name and your account menu will drop down. Click on the "My Ndot deals" link to access your Ndot deals. Any Ndot deals that you've purchased will always show up here, so come back as often as you'd like!</p>
    </div>
    <div class="fl clr mb20">
      <h5>Having trouble using NDOT DELAS?</h5>
      <p>Email NDOT at enquiriesndot.in or call +91(422) 422-1160 (Monday through Saturday, 24X7- or try chatting to us live. You'll find the link on the side bar to your right!)</p>
    </div>
    <div class="fl clr mb20">
      <h5>Press</h5>
      <p>Email us with specific media requests <br />
        contact@ndot.in</p>
    </div>
    <div class="fl clr mb20">
      <h5>Postal Address</h5>
      <p>India Corporate HQ<br />
        No 21,Selvam Nagar,<br />
        Vadavalli, Coimbatore - 641 041,<br />
        India, </p>
    </div>
  </div>
</div>
<script type="text/javascript">
jQuery.validator.addMethod('mobile', function(value) {
var numbers = value.split(/\d/).length - 1;
return (5 <= numbers && numbers <= 20 && value.match(/^(\+){0,1}(\d|\s|\(|\)|\-){5,20}$/)); }, 'Please enter a valid mobile number');
</script>
