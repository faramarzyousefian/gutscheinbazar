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

$(document).ready(function(){$("#suggest").validate();});

</script>



<ul>
<li><a href="/" title="Home"><?php echo $language["home"]; ?> </a></li>
<li><span class="right_arrow"></span></li>
<li><a href="javascript:;" title="<?php echo $language["suggest"]; ?>"><?php echo $language["suggest"]; ?></a></li>    
</ul>

<h1><?php echo $page_title; ?></h1>

<?php
//get the cist
	$business_city = mysql_query("select * from coupons_cities order by cityname asc");
	$business_type = mysql_query("select * from coupons_category order by category_name asc");

	if($_POST)
	{
		$to = SITE_EMAIL;
		$business_name = $_POST["business_name"];
		$business_website = $_POST["business_website"];
		$email = $_POST["email"];
		$business_city = $_POST["business_city"];
		$business_type = $_POST["business_type"];
		$description = $_POST["description"];
		$subject = $language["suggest"];
		
		$mes = "<p> Business Name:".$business_name."</p><p>Business website:".$business_website."</p><p>Business city:".$business_city."</p><p>Business type:".$business_type."</p>"."<p>".$description."</p>";
		send_email($email,$to,$subject,$mes); //call email function
		
		set_response_mes(1,"Thank you for your interest with us. We will contact you soon...");
		url_redirect(DOCROOT.'business.html');
	}
?>

<div class="work_bottom contactus">
<form action="" name="suggest" id="suggest" method="post">
<table width="100%" border="0" cellpadding="5" cellspacing="5" class="contact_user ml10">

<tr><td><label><?php echo $language['business_name']; ?> :</label></td></tr>
<tr>
<td>
<input name="business_name" type="text" class="required input_box" title="<?php echo $language['name_error']; ?>" />
</td></tr>

<tr><td><label><?php echo $language['business_website']; ?> :</label></td></tr>

<tr>
<td>
<input name="business_website" type="text" class="input_box" title="<?php echo $language['website_error']; ?>" />
</td></tr>

<tr><td><label><?php echo $language["e-mail"]; ?> :</label></td></tr>
<tr>
<td><input name="email" type="text" class="required email input_box" title="<?php echo $language['valid_email']; ?>" />
</td></tr>

<tr><td><label><?php echo $language['city']; ?> :</label></td></tr>
<tr>
<td>
<select name="business_city" id="business_city" class="input_box p3 required borderccc" title="<?php echo $language['city_error']; ?>">
<?php 
if(mysql_num_rows($business_city)>0)
{
	while($row = mysql_fetch_array($business_city))
	{
		?>
		<option value="<?php echo $row["cityname"];?>"><?php echo $row["cityname"];?></option>		
		<?php 
	}
}
?>
</select>
</td></tr>

<tr><td><label><?php echo $language['business_type']; ?> :</label></td></tr>

<tr>
<td>
<select name="business_type" id="business_type" class="input_box p3 required borderccc" title="<?php echo $language['type_error']; ?>">
<?php 
if(mysql_num_rows($business_type)>0)
{
	while($row = mysql_fetch_array($business_type))
	{
		?>
		<option value="<?php echo $row["category_name"];?>"><?php echo $row["category_name"];?></option>		
		<?php 
	}
}
?>
</select>
</td></tr>

<tr><td valign="top"><label><?php echo $language['business_message']; ?> :</label> </td></tr>
<tr>
<td >
<textarea name="description" cols="60" rows="7" class="borderccc required" title="<?php echo $language['valid_message']; ?>"></textarea>
</td></tr>

<tr>
<td>
<input type="submit" name="submit" value="<?php echo $language['post']; ?>" />
</td>
</tr>
</table>
</form>
</div>
