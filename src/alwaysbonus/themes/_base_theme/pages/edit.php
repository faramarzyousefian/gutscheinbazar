
<?php
/****************************************** 
* @Created on June, 2011
* @Package: ndotdeals 3.0 (Opensource groupon clone) 
* @Author: NDOT
* @URL : http://www.NDOT.in
********************************************/
?>

<?php 
is_login(DOCROOT."login.html"); //checking whether user logged in or not. 

$userid = $_SESSION['userid'];
$queryString = "SELECT * FROM coupons_users where userid='$userid'";
$resultSet = mysql_query($queryString);

	while($row = mysql_fetch_array($resultSet))
	{
		$userid = $row['userid'];
		$username = html_entity_decode($row['username'], ENT_QUOTES);
		$firstname = html_entity_decode($row['firstname'], ENT_QUOTES);
		$lastname = html_entity_decode($row['lastname'], ENT_QUOTES);
		$email = html_entity_decode($row['email'], ENT_QUOTES);
		$mobile = html_entity_decode($row['mobile'], ENT_QUOTES);
		$address = html_entity_decode($row['address'], ENT_QUOTES);
		$city=$row['city'];
		$country=$row['country'];
	}
	
	
	$all_category_id = mysql_query("select * from coupons_category order by category_name asc");
	$init_array = array();
	while($init = mysql_fetch_array($all_category_id))
	{
		$init_array[] = $init["category_id"];
	}
	
	//update the changes in DB
	if($_POST)
	{
	        
		if($_SESSION["logintype"] == 'connect') {
		
				$username = htmlentities($_POST["username"], ENT_QUOTES);
				$result = mysql_query("select * from coupons_users where username='$username' and userid<>'$userid'");
				if(mysql_num_rows($result) > 0)
				{
					set_response_mes(-1,$language['username_exists']);
					url_redirect(DOCROOT."edit.html");				
				}
				$email = $_POST["email"];
				$result2 = mysql_query("select * from coupons_users where email='$email' and userid<>'$userid'");
				if(mysql_num_rows($result2) > 0)
				{
					set_response_mes(-1,$language['reg_email_exist']);
					url_redirect(DOCROOT."edit.html");				
				}

		}
	
		//first update user favorite list
		$category = $_POST["category"];

		$n=sizeof($category);
		
		if($n > 0)
		{
			$unchecked_array = array_diff($init_array,$category);		
			for($i=0;$i<$n;$i++)
			{
					$category_id = $category[$i];
					$check_exist = mysql_query("select * from coupons_favorite where user_id='$userid' AND favorite_id='$category_id'");
				
					if(mysql_num_rows($check_exist) == 0)
					{
							mysql_query("insert into coupons_favorite (user_id,favorite_id)values('$userid','$category_id')");
					}
			}
		
			foreach($unchecked_array as $key => $value)
			{
					$c_id = $unchecked_array[$key];
					mysql_query("delete from coupons_favorite where favorite_id = '$c_id' AND user_id='$userid' ");
			}
		
		}
		else
		{
			mysql_query("delete from coupons_favorite where user_id='$userid' ");		
		}		
		//other fields

		$firstname = htmlentities($_POST["firstname"], ENT_QUOTES);
		$lastname = htmlentities($_POST["lastname"], ENT_QUOTES);
		$email = $_POST["email"];

		if(!empty($email)){
		$_SESSION["emailid"] = $email;
		}
		$mobile = htmlentities($_POST["mobile"], ENT_QUOTES);
		$address = htmlentities($_POST["address"], ENT_QUOTES);

		if($_SESSION["logintype"] == 'connect') {

			$update = mysql_query("update coupons_users set username='$username',firstname='$firstname',lastname='$lastname',email='$email',mobile='$mobile',address='$address' where userid='$userid' ");

		}
		else
		{

			$update = mysql_query("update coupons_users set firstname='$firstname',lastname='$lastname',email='$email',mobile='$mobile',address='$address' where userid='$userid' ");

		}
		
		
		//uplaod the profile image
		$imgname=$userid.'.jpg';
		if($_FILES['profileimg']['type']== "image/jpg" || $_FILES['profileimg']['type']== "image/jpeg" || $_FILES['profileimg']['type']== "image/png" || $_FILES['profileimg']['type']== "image/gif")
		{
	   		
			
			if($_FILES["profileimg"]["error"] > 0)
			{

		                echo "Return Code: " . $_FILES["profileimg"]["error"] ;
	 
			}
			else if(is_uploaded_file($_FILES['profileimg']['tmp_name']))
			{
				$profileimgname = $_FILES['profileimg']['name'];
				$targetFile = 'uploads/profile_images/'.$imgname;
				move_uploaded_file($_FILES['profileimg']['tmp_name'],$targetFile); 
			}
	
			else
			{
			 
                            echo "Invalid file";

                        }
	
}
		if($update)
		{
			set_response_mes(1,$language['changes_updated']);
			url_redirect(DOCROOT."profile.html");
		}
		

  

}	
?>	 

<?php include("profile_submenu.php"); ?>
<h1><?php echo $page_title; ?></h1>

<script type="text/javascript">
/* validation */
$(document).ready(function(){ $("#edit_profile").validate();});
</script>


<div class="work_bottom1 ">
 <form name="edit_profile" id="edit_profile" action="" method="post" enctype="multipart/form-data"  >

  
		<table border="0" cellpadding="5" cellspacing="5" >

<?php if($_SESSION["logintype"] == 'connect') { ?>

		<tr>
		<td align="right"><label><?php echo $language['username']; ?></label></td>
		<td><input type="text" class="input_box required" name="username" id="username" title="<?php echo $language['valid_username']; ?>" value="<?php echo $username;?>" /></td>
		</tr>

<?php } ?>

		<tr>
		<td align="right"><label><?php echo $language['first_name']; ?></label></td>
		<td><input type="text" class="input_box required" name="firstname" id="firstname" title="<?php echo $language['valid_firstname']; ?>" value="<?php echo $firstname;?>" /></td>
		</tr>

		<tr>
		<td align="right"><label><?php echo $language['last_name']; ?></label></td>
		<td><input type="text" class="input_box required" name="lastname" id="lastname" title="<?php echo $language['valid_lastname']; ?>" value="<?php echo $lastname;?>" /></td>
		</tr>

		<tr>
		<td align="right"><label><?php echo $language['email']; ?></label></td>
		<td><input type="text" class="input_box required email" name="email" id="email" title="<?php echo $language['valid_email']; ?>" value="<?php echo $email;?>" /></td>
		</tr>
		<tr>
		<td align="right"><label><?php echo $language['mobile']; ?></label></td>
		<td><input type="text" class="input_box" name="mobile" id="mobile" title="<?php echo $language['valid_mobile']; ?>" value="<?php echo $mobile;?>" /></td>
		</tr>
		<td align="right" valign="top"><label><?php echo $language['address']; ?></label></td>
		<td>
		<textarea name="address" cols="40" rows="5" class="borderccc"><?php echo $address;?></textarea>
		</td>
		</tr>
		
		<tr>
		<td valign="top" align="right"><?php echo $language['my_fav']; ?></td>
		<td class="w550">
		<?php 
		//assign the user favorites to array
		$user_fav = array();
		$user_fav_res = mysql_query("select * from coupons_favorite where user_id='$userid'");
		while($val = mysql_fetch_array($user_fav_res))
		{
			$user_fav[] = $val['favorite_id'];
		}

	
	
		//get the coupon category
		$fav_res = mysql_query("select * from coupons_category where status='A' order by category_name asc");

		while($f_rows = mysql_fetch_array($fav_res))
		{
				if(in_array($f_rows['category_id'],$user_fav))
				{
					$checked = 'checked="checked"';
				}else {
					$checked = "";
				}
			
		?>
		
		
		<div class="favorite_list">
		<input type="checkbox" name="category[]" value="<?php echo $f_rows['category_id']; ?>" <?php echo $checked; ?>> <div class="check_label"><?php  echo html_entity_decode($f_rows['category_name'], ENT_QUOTES);?></div>
		</div>
		<?php } ?>
		
		</td>
		</tr>


		<tr>
		<td valign="top"><?php echo $language['profile_picture']; ?></td>
		
		<td valign="top">
		<input type="file" name="profileimg" value="" class="valign_top" />
		
		<?php
		
			       $filename='uploads/profile_images/'.$userid.'.jpg'; 
			       if (file_exists($filename)) 
			       {?>
					     <img src="<?php echo $filename;?>" alt="<?php echo $username;?>" title="<?php echo $username;?>" width="75" height="75" align="top"/> 
			       <?php
			       }
			       else{
			       ?>
					     <img  src="uploads/profile_images/photo_navailable.jpg" alt="" title="" width="75" height="75" />  
				<?php
				}?>
		</td>
		</tr>
		
		
		<tr>
		<td>&nbsp;</td>
		
		<td><input type="submit" name="signup" value="<?php echo $language['update']; ?>" />
		</td>
		</tr>
		</table>           

        </form></div>
