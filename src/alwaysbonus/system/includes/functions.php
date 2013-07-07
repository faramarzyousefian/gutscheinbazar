<?php ob_start();
/****************************************** 
* @Created on June, 2011
* @Package: ndotdeals 3.0 (Opensource groupon clone) 
* @Author: NDOT
* @URL : http://www.ndot.in
********************************************/
?>
<?php 
    include "pagination.class.php";
    $url_arr = explode("/",$_SERVER["REQUEST_URI"]);

    function getCouponEndDate()
    {
    
	$queryString = "SELECT DATE_ADD(now(), INTERVAL 60 MINUTE) as date"; //add 1 hour with the coupon end date
	$resultset = mysql_query($queryString);
	          if($row = mysql_fetch_array($resultset)){
	              return $row['date'];
	          }
    }
    
    function getCouponExpDate()
    {
    
	$queryString = "SELECT DATE_ADD(now(), INTERVAL 1 DAY) as date"; //add 1 hour with the coupon end date
	$resultset = mysql_query($queryString);
	          if($row = mysql_fetch_array($resultset)){
	              return $row['date'];
	          }
    }

    function payment_list()
    {
	$queryString = "select c.coupon_name,c.coupon_realvalue,c.coupon_offer,u.firstname,u.lastname,p.coupon_purchaseddate,p.Coupon_amount_Status from coupons_purchase p left join coupons_coupons c on p.couponid=c.coupon_id left join coupons_users u on p.coupon_userid=u.userid";

		//pagination
			
		$pagination = new pagination();
		$pagination->createPaging($queryString,25);

		$resultSet = $pagination->resultpage;
		
	if(mysql_num_rows($resultSet) > 0)
	{
		echo '<p style="color:#666;width:100%;" class="fwb txtcenter">* Note: T - Amount Transfered, C - Payment Failed.</p>';
			
	?>
                        
	<table cellpadding="0" cellspacing="0" class="">
	<tr class="fwb">
	<td>User Name</td>
	<td>Description</td>
	<td>Amount</td>
	<td>Status</td>	
	<td>Purchased Date</td>
	</tr>
		<?php
		while($result = mysql_fetch_array($resultSet))
		{
		?>

		<tr>
		<td><?php echo ucfirst(html_entity_decode($result["firstname"], ENT_QUOTES)); ?></td>
		<td><?php 
		if(strlen($result['coupon_name'])>50)
			echo 'Pay of '.ucfirst(html_entity_decode(substr($result['coupon_name'],0,50), ENT_QUOTES)).'...'; 
		else
			echo 'Pay of '.ucfirst(html_entity_decode($result['coupon_name'], ENT_QUOTES)); 		
		
		?></td>
		<td>
		<?php
			$coupons_realvalue = $result["coupon_realvalue"];
			$coupon_offer = $result["coupon_offer"];
			$offer_amount = (($coupons_realvalue * $coupon_offer)/100); 
			$paid_amount = $coupons_realvalue - $offer_amount;
			echo CURRENCY.$paid_amount;
		?>
		</td>
		<td><?php if($result['Coupon_amount_Status']) { echo $result['Coupon_amount_Status']; } else { echo 'C'; } ?></td>		
		<td><?php echo $result['coupon_purchaseddate']; ?></td>
		</tr>

		<?php
		}
		?>

	</table>
	<?php

			echo '<table border="0" width="400" align="center">';
			echo '<tr><td align="center">';
			$pagination->displayPaging();
			echo '</td></tr>';
			echo '</table>';	
	}else{
	
			   echo '<div class="nodata">No Data Available.</div>';	
	
	}


    }

    function get_saved_money()
    {
	$result = mysql_query("select * from coupons_purchase_status");
	return $result; 
    }
	
    function get_random_coopon($default_city_id = '',$limit = '')
    {
        $query = "select * from coupons_coupons c left join coupons_cities cty on cty.cityid = c.coupon_city where ";
			//add the city condition
			if($default_city_id)
			{
				$query .= " coupon_city = '$default_city_id' AND ";
			}
			  if($limit)
			  {
			  	$query .= " c.coupon_status='A' and c.side_deal='1' and c.coupon_startdate <= now() and c.coupon_enddate > now() order by c.coupon_enddate asc limit 0,$limit";
			  }
			  else
			  {
			  	$query .= " c.coupon_status='A' and c.side_deal='1' and c.coupon_startdate <= now() and c.coupon_enddate > now() order by c.coupon_enddate asc limit 0,3";
			  }
              $result = mysql_query($query);
              return $result;
    }
    
    function get_cityname()
    {
        $query = "select * from coupons_cities where cityid='".$_SESSION['defaultcityId']."'";
              $result = mysql_query($query);
              return $result;
    }
  
  
    function get_subcategory()
    {
        $query = "select * from coupons_category where status='A' order by category_name";
              $result = mysql_query($query);
              return $result;
    }
    
    function add_subscriber($email='',$city='')
    {
	      $check_exist = mysql_query("select * from newsletter_subscribers where email='$email' AND city_id='$city' ");
		  
		  if(mysql_num_rows($check_exist) == 0)
		  {
			  $query = "insert into newsletter_subscribers(email,city_id)values('$email','$city')";
			  $result = mysql_query($query);
			  return $result;
		  }else{
		  	return '';
		  }
    }

    
    function add_mobilesubscriber($mobileno='',$city='')
    {
    
	      $check_exist = mysql_query("select * from mobile_subscribers where mobileno='$mobileno' AND city_id='$city' ");
		  
		  if(mysql_num_rows($check_exist) == 0)
		  {
			  $query = "insert into mobile_subscribers(mobileno,city_id)values('$mobileno','$city')";
			  $result = mysql_query($query);
			  return $result;
		  }else{
		  	return '';
		  }
    }
    
    //get the coupen information
    function get_coupon_code($c_id)
    {
            $query = "select * from coupons_coupons where coupon_id='$c_id'";
            $resultset = mysql_query($query);
            return $resultset;
    }

    function getRoleId($name)
    {
    
    $queryString = "select roleid from coupons_roles where role_name='".$name."'";
    $resultset = mysql_query($queryString);
                    if($role = mysql_fetch_array($resultset)){
                        return $role['roleid'];
                    }
    }
    
    
    // Function to insert coupon menbers
    
    function insertMember($user_id,$coupon_id,$date)
    {
        $member_count = getMenbersCount($coupon_id);
        if($member_count>50)
        $queryString = "insert into coupon_members(user_id,coupon_id,status,applied_time) values ('$user_id','$coupon_id','0','$date')";
        $result = mysql_query($querystring);
    }

    // Function to manage coupon members and their payment status
    
    function manageMembers($coupon_id)
    {
        $queryString = "select count(*) as count from coupon_members where coupon_id='$coupon_id'";
        $member_count = mysql_query($querystring);
    }
    
    // Function to reterive menbers count for each coupon
    
    function getMembersCount($coupon_id)
    {
           $queryString = "select count(*) as count from coupons_purchase where couponid='$coupon_id' and  Coupon_amount_Status='T' ";
	//$queryString = "select count(*) as count from coupons_purchase where couponid='$coupon_id'";        
        $result = mysql_query($queryString);
        $row = mysql_fetch_object($result);
        return $row->count;
    }	
    
    
    // Function to reterive coupon maxusers and minusers
    
    function getCouponUsersLimit($coupon_id)
    {
        $queryString = "select coupon_minuserlimit as minuser,coupon_maxuserlimit as maxuser  from coupons_coupons where coupon_id='$coupon_id'";
        $resultSet = mysql_query($queryString);
        while ($row = mysql_fetch_object($resultSet)) 
        {
            $limit[min] = $row->minuser;
            $limit[max] = $row->maxuser;
        }
        return $limit;
    }	
    
    function maxUserId()
    {
    
    $queryString = "select max(userid) as id from coupons_users";
    $resultset = mysql_query($queryString);
                    if($userid = mysql_fetch_array($resultset)){
                        return $userid['id'];
                    }
    }
    

    function loginCheck($uname,$password,$admin_language = '')
    {
			session_start();
			$uname = htmlentities($uname, ENT_QUOTES);
			$queryString = "SELECT * FROM coupons_users where username ='".$uname."' and password='".$password."'";
            $resultSet = mysql_query($queryString);
            if(mysql_num_rows($resultSet)>0)
            {
                          if($values=mysql_fetch_array($resultSet))
                           {
                            if($values['user_status']!="A")
                             { 
                                 return "Blocked";
                             } 
                             else
                		  {
                                
					if($values['login_type']==1 || $values['login_type']==2) //fb and twitter users
					{
						    $_SESSION["username"] = html_entity_decode($values['firstname'], ENT_QUOTES);
						    $_SESSION["logintype"] = 'connect';
					}
					else
					{
						    $_SESSION["username"] = html_entity_decode($values['username'], ENT_QUOTES);	
						    $_SESSION["logintype"] = 'normal';
					}					                          	
                    
				       $_SESSION["userid"] = $values['userid'];
				       $_SESSION["userrole"] = $values['user_role'];
				       $_SESSION["userstatus"] = $values['user_status'];
				       $_SESSION["cityid"] = $values['city'];
		       		       $_SESSION["countryid"] = $values['country'];
				       $_SESSION["user_shopid"] = $values['user_shopid'];		       
				       $_SESSION["savedamt"] = '0';
				       $_SESSION["emailid"] = $values['email'];
				       $_SESSION["site_admin_language"] = $admin_language;
						 
					 // to get total amt saved during user purchase
					 getsavingamt();
					 return "Success";
					 
                }
             }
                  }
            else
            {
                         return "Failed"; 
            }
    
    }
	
    function checknull($input,$alt){
    if(is_null($input) || strlen($input)<1 || $input ==""){
        $input = $alt;
    }
    return $input;
    }
    
    function couponUpload()
    {
	    include "docroot.php";
	    $cname = htmlentities($_POST['couponname'], ENT_QUOTES);
            $deal_permalink = htmlentities($_POST['deal_permalink'], ENT_QUOTES);
	    $cdesc = htmlentities($_POST['cdesc'], ENT_QUOTES);
	    $cfineprints = htmlentities($_POST['cfineprints'], ENT_QUOTES);
	    $chighlights = htmlentities($_POST['chighlights'], ENT_QUOTES);
	    $cenddate = htmlentities($_POST['cenddate']);
	    $cstartdate = htmlentities($_POST['cstartdate']);
	    $cexpdate = htmlentities($_POST['cexpdate']);
	    $climit = htmlentities($_POST['climit']);
	    $cterms = htmlentities($_POST['cterms']);
	    $cdiscountvalue = htmlentities($_POST['cdiscountvalue']);
	    $cminuser = htmlentities($_POST['minlimit']);
	    $cmaxuser = htmlentities($_POST['maxlimit']);
	    $crealvalue = htmlentities($_POST['crealvalue']);
	    $cperson = htmlentities($_POST['cperson'], ENT_QUOTES);
	    $phonenum = htmlentities($_POST['phonenum']);
	    $address = htmlentities($_POST['address'], ENT_QUOTES);
		$meta_keywords = htmlentities($_POST['meta_keywords'], ENT_QUOTES);
		$meta_description = htmlentities($_POST['meta_description'], ENT_QUOTES);
		$termscondition = htmlentities($_POST['termscondition'], ENT_QUOTES);

		if($_POST['couponname']=='' || $_POST['cdesc']=='' || $_POST['crealvalue']=='' || $_POST['crealvalue']==0)
		{
			$redirect_url = DOCROOT."admin/couponsupload/";
			set_response_mes(1,'All fields are mandatory.'); 	
			url_redirect($redirect_url);
		}
     
	    $uid=$_SESSION["userid"];
	    $shopid=$_POST['shop'];
	    $csubtype=$_POST['csubtype'];
	    $ctype=$_POST['ctype'];
	    $country = $_POST['country'];
	    $city=$_POST['city'];

	    if($_POST['sidedeal'])
		    $sidedeal=1;
	    else
  		    $sidedeal=0;
            
            if($_POST['maindeal'])
		    $maindeal=1;
	    else
  		    $maindeal=0;
            $is_video = $_POST['is_video'];
            $embed_code = htmlentities($_REQUEST['embed_code'], ENT_QUOTES);
            
            
	    $randomvalue=ranval();
    
    if ($_FILES['cpicture']['type'] == "image/jpeg"||$_FILES['cpicture']['type'] == "image/jpg"||$_FILES['cpicture']['type'] == "image/gif"||$_FILES['cpicture']['type'] == "image/png"||$_FILES['cpicture']['type'] == "image/pjpeg")
    {
    
        if(isset($_FILES['cpicture'])) 
        {
            try 
            {         
                $imgData =addslashes (file_get_contents($_FILES['cpicture']['tmp_name']));
                $size = getimagesize($_FILES['cpicture']['tmp_name']);
                $userid = $_SESSION["userid"];
                $imtype = $_FILES['cpicture']['type'];

	
                switch ($imtype)
                {
                    case 'image/gif':
                    $im = imagecreatefromgif($_FILES['cpicture']['tmp_name']);
                                
                    break;
                    case "image/pjpeg":
                    case "image/jpg":
                    case 'image/jpeg':
                    $im = imagecreatefromjpeg($_FILES['cpicture']['tmp_name']);
                    break;
                    case 'image/png':
                    $im = imagecreatefrompng($_FILES['cpicture']['tmp_name']);
                    break;
                };
                    $width = imagesx($im);
                    $height = imagesy($im);

		 
		        		$newwidthX=420;
                        $newheightX=($height/$width)*$newwidthX;
                        if($newheightX >285)
                        {
							$newheight=285;
							$newwidth=($newwidthX/$newheightX)*$newheight;
                        } 
                        else 
                        {
							$newheight = $newheightX;
							$newwidth = $newwidthX; 
                        } 
		      

                    $thumb=imagecreatetruecolor($newwidth,$newheight);
        
                    ImageCopyResampled($thumb,$im,0,0,0,0,$newwidth,$newheight,ImageSX($im),ImageSY($im));
                                    
                    ImagejpeG($thumb,DOCUMENT_ROOT."/uploads/coupons/".$randomvalue.".jpg");
                                    $imgurl="uploads/coupons/".$randomvalue.".jpg";
                                    
    
    
            }
			catch(Exception $e)
			{
			}
            
        }
    }
    
		$status="A";

        if($_SESSION['userrole']=='3')
        {
            $status="D";
        }
    
	    $queryString = "insert into coupons_coupons
	    (coupon_name,deal_url,coupon_description,coupon_enddate,coupon_image,coupon_createdby,coupon_createddate,coupon_value,coupon_status,coupon_minuserlimit,coupon_maxuserlimit,coupon_realvalue,coupon_category,coupon_country,coupon_city,coupon_person,coupon_phoneno,coupon_address,  	coupon_shop,coupon_fineprints,coupon_highlights,side_deal,meta_keywords,meta_description,terms_and_condition,main_deal,coupon_expirydate,coupon_startdate,is_video, embed_code) values ('$cname','$deal_permalink','$cdesc',STR_TO_DATE('$cenddate','%Y-%m-%d %H:%i:%s'),'$imgurl','$uid',now(),'$cdiscountvalue','$status','$cminuser','$cmaxuser','$crealvalue','$ctype','$country','$city', '$cperson', '$phonenum', '$address','$shopid','$cfineprints','$chighlights','$sidedeal','$meta_keywords','$meta_description','$termscondition','$maindeal','$cexpdate','$cstartdate','$is_video', '$embed_code')";
	    
	    $resultset = mysql_query($queryString) or die(mysql_error());
	    $last_insert_id=mysql_insert_id();
	    if($maindeal == 1)
	    {
	        $maindealQuery = "update coupons_coupons set main_deal=0 where coupon_city='$city' and coupon_id!='$last_insert_id'";
	        $maindealResult = mysql_query($maindealQuery);
	    }

if ($_FILES['slide1']['type'] == "image/jpeg"||$_FILES['slide1']['type'] == "image/jpg"||$_FILES['slide1']['type'] == "image/gif"||$_FILES['slide1']['type'] == "image/png"||$_FILES['slide1']['type'] == "image/pjpeg")
    {
    
        if(isset($_FILES['slide1'])) 
        {
            try 
            {         
                $imgData =addslashes (file_get_contents($_FILES['slide1']['tmp_name']));
                $size = getimagesize($_FILES['slide1']['tmp_name']);
                $userid = $_SESSION["userid"];
                $imtype = $_FILES['slide1']['type'];
                switch ($imtype)
                {
                    case 'image/gif':
                    $im = imagecreatefromgif($_FILES['slide1']['tmp_name']);
                                
                    break;
                    case "image/pjpeg":
                    case "image/jpg":
                    case 'image/jpeg':
                    $im = imagecreatefromjpeg($_FILES['slide1']['tmp_name']);
                    break;
                    case 'image/png':
                    $im = imagecreatefrompng($_FILES['slide1']['tmp_name']);
                    break;
                };
                    $width = imagesx($im);
                    $height = imagesy($im);
		     		$newwidthX=420;
					$newheightX=($height/$width)*$newwidthX;
					if($newheightX >285)
					{
						$newheight=285;
						$newwidth=($newwidthX/$newheightX)*$newheight;
					} 
					else 
					{
						$newheight = $newheightX;
						$newwidth = $newwidthX; 
					} 

                    $thumb=imagecreatetruecolor($newwidth,$newheight);
        
                    ImageCopyResampled($thumb,$im,0,0,0,0,$newwidth,$newheight,ImageSX($im),ImageSY($im));

                                    
                    ImagejpeG($thumb,DOCUMENT_ROOT."/uploads/slider_images/".$last_insert_id."_1.jpg");
                                    $imgurl="uploads/slider_images/".$last_insert_id."_1.jpg";
                                    $slide1_image_name=$last_insert_id."_1".".jpg";
            
			//slide show images                
		    $query = "insert into slider_images(coupon_id,imagename) values('$last_insert_id','$slide1_image_name')";
		    $result=mysql_query($query) or die(mysql_error());
    
            }
                catch(Exception $e)
                {
                }
            
        }
    }	
    
    if ($_FILES['slide2']['type'] == "image/jpeg"||$_FILES['slide2']['type'] == "image/jpg"||$_FILES['slide2']['type'] == "image/gif"||$_FILES['slide2']['type'] == "image/png"||$_FILES['slide2']['type'] == "image/pjpeg")
    {
    
        if(isset($_FILES['slide2'])) 
        {
            try 
            {         
                $imgData =addslashes (file_get_contents($_FILES['slide2']['tmp_name']));
                $size = getimagesize($_FILES['slide2']['tmp_name']);
                $userid = $_SESSION["userid"];
                $imtype = $_FILES['slide2']['type'];
                switch ($imtype)
                {
                    case 'image/gif':
                    $im = imagecreatefromgif($_FILES['slide2']['tmp_name']);
                                
                    break;
                    case "image/pjpeg":
                    case "image/jpg":
                    case 'image/jpeg':
                    $im = imagecreatefromjpeg($_FILES['slide2']['tmp_name']);
                    break;
                    case 'image/png':
                    $im = imagecreatefrompng($_FILES['slide2']['tmp_name']);
                    break;
                };
                    $width = imagesx($im);
                    $height = imagesy($im);
		      $newwidthX=420;
                        $newheightX=($height/$width)*$newwidthX;
                        if($newheightX >285)
                        {
                        $newheight=285;
                        $newwidth=($newwidthX/$newheightX)*$newheight;
                        } 
                        else 
                        {
                        $newheight = $newheightX;
                        $newwidth = $newwidthX; 
                        } 

                    $thumb=imagecreatetruecolor($newwidth,$newheight);
        
                    ImageCopyResampled($thumb,$im,0,0,0,0,$newwidth,$newheight,ImageSX($im),ImageSY($im));

                                    
                    ImagejpeG($thumb,DOCUMENT_ROOT."/uploads/slider_images/".$last_insert_id."_2.jpg");
                                    $imgurl="uploads/slider_images/".$last_insert_id."_2.jpg";
                                    $slide2_image_name=$last_insert_id."_2".".jpg";
                                    
		    $query="insert into slider_images(coupon_id,imagename) values('$last_insert_id','$slide2_image_name')";
		    $result=mysql_query($query) or die(mysql_error());
    
            }
                catch(Exception $e)
                {
                }
            
        }
    }	
    
    if ($_FILES['slide3']['type'] == "image/jpeg"||$_FILES['slide3']['type'] == "image/jpg"||$_FILES['slide3']['type'] == "image/gif"||$_FILES['slide3']['type'] == "image/png"||$_FILES['slide3']['type'] == "image/pjpeg")
    {
    
        if(isset($_FILES['slide3'])) 
        {
            try 
            {         
                $imgData =addslashes (file_get_contents($_FILES['slide3']['tmp_name']));
                $size = getimagesize($_FILES['slide3']['tmp_name']);
                $userid = $_SESSION["userid"];
                $imtype = $_FILES['slide3']['type'];
                switch ($imtype)
                {
                    case 'image/gif':
                    $im = imagecreatefromgif($_FILES['slide3']['tmp_name']);
                                
                    break;
                    case "image/pjpeg":
                    case "image/jpg":
                    case 'image/jpeg':
                    $im = imagecreatefromjpeg($_FILES['slide3']['tmp_name']);
                    break;
                    case 'image/png':
                    $im = imagecreatefrompng($_FILES['slide3']['tmp_name']);
                    break;
                };
                    $width = imagesx($im);
                    $height = imagesy($im);
		      $newwidthX=420;
                        $newheightX=($height/$width)*$newwidthX;
                        if($newheightX >285)
                        {
                        $newheight=285;
                        $newwidth=($newwidthX/$newheightX)*$newheight;
                        } 
                        else 
                        {
                        $newheight = $newheightX;
                        $newwidth = $newwidthX; 
                        } 

                    $thumb=imagecreatetruecolor($newwidth,$newheight);
        
                    ImageCopyResampled($thumb,$im,0,0,0,0,$newwidth,$newheight,ImageSX($im),ImageSY($im));

                                    
                    ImagejpeG($thumb,DOCUMENT_ROOT."/uploads/slider_images/".$last_insert_id."_3.jpg");
                                    $imgurl="uploads/slider_images/".$last_insert_id."_3.jpg";
                                    $slide3_image_name=$last_insert_id."_3".".jpg";
                                    
		    $query="insert into slider_images(coupon_id,imagename) values('$last_insert_id','$slide3_image_name')";
		    $result=mysql_query($query) or die(mysql_error());
    
            }
                catch(Exception $e)
                {
                }
            
        }
    }	
	
		$redirect_url = DOCROOT."admin/couponsupload/";
		set_response_mes(1,'The Coupon has been Created Successfully.'); 	
		url_redirect($redirect_url);

    
    }
    
    
    
  
    function ranval($length = 8)
    { 
            /* srand(time()); 
            $random_letter_lcase = chr(rand(ord("a"), ord("z"))); 
            $random_letter_ucase = chr(rand(ord("A"), ord("Z")));
            $random_letter_number = chr(rand(ord("0"), ord("9")));
            $random_letter_lcase1 = chr(rand(ord("a"), ord("z")));  
            $randomvalue= $random_letter_lcase.$random_letter_ucase.$random_letter_number.$random_letter_lcase1;
            return $randomvalue; */

			for($j=1; $j<=3; $j++){
				$alpha = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
				$alpha = str_split($alpha, 1);
				$max = count($alpha) - 1;
				$str = '';
				for ($i = 0; $i < $length; $i++)
				{
					$str .= $alpha[mt_rand(0, $max)];
				}
				 $record = mysql_query("select coupon_validityid from coupons_purchase where coupon_validityid='".$str."'");
				 if(count($record) == 0){
				 	return $str;
				 }
			}
			return $str;            
            
    }
    

    function referral_ranval()
    { 
            srand(time());     
	  $random_letter_lcase = chr(rand(ord("a"), ord("z"))); 
	  $random_letter_ucase = chr(rand(ord("A"), ord("Z")));
	  $random_letter_number = chr(rand(ord("0"), ord("9")));
	  $random_letter_lcase1 = chr(rand(ord("a"), ord("z")));  
	  $random_letter_lcase2 = chr(rand(ord("a"), ord("z"))); 
	  $random_letter_ucase2 = chr(rand(ord("A"), ord("Z")));
	  $random_letter_number2 = chr(rand(ord("0"), ord("9")));
	  $random_letter_lcase2 = chr(rand(ord("a"), ord("z")));  
	  
	  $randomvalue= $random_letter_lcase.$random_letter_ucase.$random_letter_number.$random_letter_lcase1.$random_letter_lcase2.$random_letter_ucase2.$random_letter_number2.$random_letter_lcase2;
	   return $randomvalue;    
    }
    
    
    function getcoupons($type = '',$val = '',$default_city_id = '')
    {
		
		if($type=="H")
		{
			//select hot deals
			$queryString = "select TIMEDIFF(coupons_coupons.coupon_enddate,now())as timeleft, coupon_id,coupon_name,deal_url,coupon_description,coupon_startdate,coupon_enddate,coupon_image,coupon_realvalue,coupon_value from coupons_coupons where ";
			
			//add the city condition
			if($default_city_id)
			{
				$queryString .= " coupon_city = '$default_city_id' AND ";
			}
			
			$queryString .= " coupon_status = 'A' and coupon_startdate <= now() and coupon_enddate > now() order by coupon_id desc";
		

		}
		else if($type=="P")
		{ 
			//select hot deals
			$queryString = "select TIMEDIFF(coupons_coupons.coupon_enddate,now())as timeleft, coupon_id,coupon_name,deal_url,coupon_description,coupon_startdate,coupon_enddate,coupon_image,coupon_realvalue,coupon_value from coupons_coupons where ";
			
			//add the city condition
			if($default_city_id)
			{
				$queryString .= " coupon_city = '$default_city_id' AND ";
			}
			
			$queryString .= "(coupon_enddate between DATE_SUB(now(), INTERVAL 10 DAY) and now() and coupon_status in ('A','D')) or coupon_status='C' order by coupon_id desc";
		

		}
		else if($type=="T")
		{
			//select today deals
			$queryString = "select TIMEDIFF(coupons_coupons.coupon_enddate,now())as timeleft, coupon_id,coupon_name,deal_url,coupon_description,coupon_startdate,coupon_enddate,coupon_image,coupon_realvalue,coupon_value from coupons_coupons where ";
			//add the city condition
			if($default_city_id)
			{
				$queryString .= " coupon_city = '$default_city_id' AND ";
			}
			
			$current_startdate = date("Y-m-d").' 00:00:00';
			$current_enddate = date("Y-m-d").' 23:59:59';
						
			$queryString .= " coupon_enddate between '$current_startdate' and '$current_enddate' AND coupon_status = 'A'  order by coupon_id desc";

		}
		else if($type=="C")
		{
			//select hot deals
			$queryString = "select TIMEDIFF(coupons_coupons.coupon_enddate,now())as timeleft, coupon_id,coupon_name,deal_url,coupon_description,coupon_startdate,coupon_enddate,coupon_image,coupon_realvalue,coupon_value from coupons_coupons where ";
			
			//add the city condition
			if($default_city_id)
			{
				$queryString .= " coupon_city = '$default_city_id' AND ";
			}
			
			$queryString .= " coupon_category = '$val' AND coupon_status = 'A'  and coupon_startdate <= now() and coupon_enddate > now() order by coupon_id desc ";

		}
		else if($type=="S")
		{
			//select hot deals
			$queryString = "select TIMEDIFF(coupons_coupons.coupon_enddate,now())as timeleft, coupon_id,coupon_name,deal_url,coupon_description,coupon_startdate,coupon_enddate,coupon_image,coupon_realvalue,coupon_value from coupons_coupons where ";
			
			//add the city condition
			if($default_city_id)
			{
				$queryString .= " (coupon_city = '$default_city_id') AND ";
			}
			
			$queryString .= " (coupon_name like '%$val%' || coupon_description like '%$val%')  AND (coupon_status = 'A')   and coupon_enddate > now()  order by coupon_id desc";
			
		}
		else
		{
			//select deals
			$queryString = "select TIMEDIFF(coupons_coupons.coupon_enddate,now())as timeleft, coupon_id,coupon_name,deal_url,coupon_description,coupon_startdate,coupon_enddate,coupon_image,coupon_realvalue,coupon_value from coupons_coupons where coupon_startdate <= now() and coupon_enddate > now() order by coupon_id desc";


		}

	include(DOCUMENT_ROOT."/themes/".CURRENT_THEME."/pages/getcoupons.php"); //include the remaining content		
		
   }
    
 
    //get the random products
    function random_coopens($coupons_subtype)
    {
     include "docroot.php";
    $turl_arr = explode("/",$_SERVER["REQUEST_URI"]);
    // to show the list of subtypes in the selected type 
    if($coupons_subtype!='')
    {
            $query = "select * ,TIMEDIFF(coupon_enddate,now())as timeleft from coupons_coupons where coupon_status='A' and coupon_enddate > now() and coupons_subtype='$coupons_subtype' order by RAND() limit 0,25";
    }
    else
    {
            $query = "select * ,TIMEDIFF(coupon_enddate,now())as timeleft from coupons_coupons where coupon_status='A' and coupon_enddate > now() order by RAND() limit 0,25";
    }       
            $result = mysql_query($query);
            $coupons_subtype='';
    
    
    if($turl_arr[1]=='index.php' || $turl_arr[1]=='')
    {
    
            if(mysql_num_rows($result)>0)
            {
            ?>
    <div class="h_bbox fl mt20"  id="makeMeScrollable"> 
    <div class="h_slide fl">
            <div class="scrollingHotSpotLeft "></div>
            <div class="scrollingHotSpotRight "></div> 
            <div class="random_coopens3"><h3>Featured Coopons:</h3></div>
            <div class="scrollWrapper fl ">
            <div class="random_coopens scrollableArea" onMouseOver="javascript: stopscroll();" onMouseOut="javascript: startscroll();">
    
            <?php 
                    while($data = mysql_fetch_array($result) )
                    {
            if($data['timeleft'] > 0) 
            {           
                            ?>
    
                            <div class="random_items mt20 fl">
    
                            <a href=' <?php echo $docroot; ?>user/icoopon/<?php echo $data['coupon_outsideurl'];?>' title="<?php echo html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES);?>">
                            <?php if($data['coupon_image']){ ?>
                            <img src="<?php echo $docroot; ?>imaging.php/image-name.jpg?width=100&height=100&cropratio=1:1&noimg=100&image=<?php echo $docroot.$data['coupon_image']; ?>" title="<?php echo html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES);?>" alt="<?php echo html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES);?>" >
                            <?php }else{ ?>
                            <img src="<?php echo $docroot; ?>imaging.php/image-name.jpg?width=100&height=100&cropratio=1:1&image=<?php echo $docroot; ?>images/nothing.jpg" title="<?php echo html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES);?>" alt="<?php echo html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES);?>" >
                            <?php } ?>
                            </a> <br>
                            
                            <a href=' <?php echo $docroot; ?>user/icoopon/<?php echo $data['coupon_outsideurl'];?>' title="<?php echo html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES);?>">
                            <?php if(strlen(html_entity_decode($data['couponname'], ENT_QUOTES))>12) { echo substr(html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES),0,12)."..."; } else { echo html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES); }?>
                            </a>
                            </div>
                            
                            <?php 
                    } 
                    }?>
            </div>
            </div>
      
    </div>
    </div>
     
            
                    <?php 
       
       }
       }
       
    else
    {
    
            if(mysql_num_rows($result)>0)
            {
            ?>
    <div class="h_bbox2 fl mt20 ml5"  id="makeMeScrollable"> 
    <div class="h_slide2 fl">
            
            <div class="scrollingHotSpotLeft "></div>
            <div class="scrollingHotSpotRight "></div> 
            <div class="random_coopens3"><h3>Featured Coopons:</h3></div>
            <div class="scrollWrapper fl ">
    
            <div class="random_coopens scrollableArea" onMouseOver="javascript: stopscroll();" onMouseOut="javascript: startscroll();">
                    
            <?php 
                    while($data = mysql_fetch_array($result) )
                    {
            if($data['timeleft'] > 0) 
            {           
                            ?>
    
                            <div class="random_items2">
    
                            <a href=' <?php echo $docroot; ?>user/icoopon/<?php echo $data['coupon_outsideurl'];?>' title="<?php echo html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES);?>">
                            <?php if($data['coupon_image']){ ?>
                            <img src="<?php echo $docroot; ?>imaging.php/image-name.jpg?width=100&height=100&cropratio=1:1&noimg=100&image=<?php echo $docroot.$data['coupon_image']; ?>" title="<?php echo html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES);?>" alt="<?php echo html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES);?>" >
                            <?php }else{ ?>
                            <img src="<?php echo $docroot; ?>imaging.php/image-name.jpg?width=100&height=100&cropratio=1:1&image=<?php echo $docroot; ?>images/nothing.jpg" title="<?php echo html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES);?>" alt="<?php echo html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES);?>" >
                            <?php } ?>
                            </a> <br>
                            <a href=' <?php echo $docroot; ?>user/icoopon/<?php echo $data['coupon_outsideurl'];?>' title="<?php echo html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES);?>">
                            <?php if(strlen(html_entity_decode($data['couponname'], ENT_QUOTES))>12) { echo substr(html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES),0,12)."..."; } else { echo html_entity_decode(ucfirst($data['couponname']), ENT_QUOTES); }?>
                            </a>
                            </div>
                            
                            <?php 
                    } 
                    }?>
            </div>
            </div>
      
    </div>
    </div>
     
            
                    <?php 
       
       }
       }
     
    }

    function easyRegister($userid,$firstname,$lastname,$email,$image,$login_type)
    {
    
        
            $queryString = "select username,password from coupons_users where username='".$userid."'";
            $resultSet = mysql_query($queryString);
            if(mysql_num_rows($resultSet)>0)
            {   
                         $noticia = mysql_fetch_array($resultSet);
                         loginCheck($noticia['username'],$noticia['password']);
                       
            }
            else
            {
                $roleid=4;
                $uid=maxUserId()+1;
                $ranval = referral_ranval();
		$firstname = htmlentities($firstname, ENT_QUOTES);
		$lastname = htmlentities($lastname, ENT_QUOTES);

                $queryString = "insert into coupons_users
                         (username,password,email,user_role,created_by,created_date,user_status,firstname,lastname,referral_id,login_type) values
                         ('$userid','798449d5cc26268f9a3aaa356b639ca6','$email',$roleid,$uid,now(),'A','$firstname','$lastname','$ranval','$login_type')";
                $resultset = mysql_query($queryString) or die(mysql_error());
                $insert_id = mysql_insert_id();
                $img = DOCUMENT_ROOT.'/uploads/profile_images/'.$insert_id.'.jpg';
                $user_img = file_get_contents($image);
                file_put_contents($img, $user_img);
                loginCheck($userid,'798449d5cc26268f9a3aaa356b639ca6');
           }
    }
    
    
    function report($type)
    {

    include "docroot.php";
    $lang = $_SESSION["site_language"];
    if($lang)
    {
        include(DOCUMENT_ROOT."/system/language/".$lang.".php");
    }
    else
    {
        include(DOCUMENT_ROOT."/system/language/en.php");
    }
	
    if($type=='coupons')
    {
    
     $queryString = "SELECT (
    
    SELECT count( p.coupon_purchaseid )
    FROM coupons_purchase p
    WHERE p.couponid = c.coupon_id and p.Coupon_amount_Status='T'
    ) AS pcounts, c.coupon_id, c.coupon_name, DATE_FORMAT( c.coupon_startdate, '%d %M %Y' ) AS startdate, DATE_FORMAT( c.coupon_enddate, '%d %M %Y' ) AS enddate, c.coupon_createdby,u.firstname,u.lastname,c.coupon_status,c.coupon_minuserlimit as minuserlimit,c.coupon_maxuserlimit as maxuserlimit
    FROM coupons_coupons c, coupons_users u  where u.userid=c.coupon_createdby and c.coupon_status in ('A','D') and c.coupon_enddate > now()";

		$pagination = new pagination();
		$pagination->createPaging($queryString,20);
		$resultSet = $pagination->resultpage;
            
            if(mysql_num_rows($resultSet)>0)
            {
    
                         echo '<table cellpadding="0" cellspacing="0">';
    		     echo '<tr class="fwb"><td>Coupon Name</td><td>Start Date</td> <td>End Date</td><td>Created By</td><td>Min User</td><td>Max User</td><td>Purchase Count</td>';
    
			  if($_SESSION['userrole']=='1') {
			  echo '<td width="15%">&nbsp;</td>';
			  }
    
	    	     echo '</tr>';
                while($noticia=mysql_fetch_array($resultSet))
                { 
                    echo '<tr><td>'.ucfirst(html_entity_decode($noticia['coupon_name'], ENT_QUOTES)).'</td><td>'.html_entity_decode($noticia['startdate'], ENT_QUOTES).'</td><td>'.html_entity_decode($noticia['enddate'], ENT_QUOTES).'</td><td>'.ucfirst(html_entity_decode($noticia['firstname'], ENT_QUOTES)).'</td><td>'.html_entity_decode($noticia['minuserlimit'], ENT_QUOTES).'</td><td>'.html_entity_decode($noticia['maxuserlimit'], ENT_QUOTES).'</td><td>'.$noticia['pcounts'].'</td>';
                    
                    if($_SESSION['userrole']=='1') {
                    echo '<td><a href="'.$docroot.'edit/coupon/'.$noticia['coupon_id'].'/" class="edit_but" title="Edit"></a>';
                    
                    if($noticia['coupon_status']=="D")
                    {
			echo '<a href="javascript:;" onclick="updatecoupon(\'A\',\''.$noticia["coupon_id"] .'\');" class="unblock" title="Unblock"></a>';
                    }
                    else{
	                    echo '<a href="javascript:;" onclick="updatecoupon(\'D\',\''.$noticia["coupon_id"] .'\');" class="block" title="Block"></a>';
		}                    

                    echo '<a href="javascript:;" onclick="javascript:deletecoupon('.$noticia["coupon_id"].');" class="delete"></a></td>';
                   
                    }
                    
                    if($noticia['pcounts'] > 0)
                    {
                    echo '<td><a href="'.$docroot.'coupon/code/'.$noticia['coupon_id'].'">Start Offer</a></td>';
                    }
                                        
                    echo '</tr>';
    
                }
                        echo '</table>';

                    echo '<table border="0" width="400" align="center">';
		echo '<tr><td align="center">';
		$pagination->displayPaging();
		echo '</td></tr>';
		echo '</table>';

               }
    
            else
            {
                          echo '<p class="nodata">No Data Available</p>';
            }

            
    }
    
    else if($type=="shops")
    {
        $cityid = $_SESSION['city'];
         $queryString = "SELECT *, DATE_FORMAT('shop_createddate', '%D %b %y' ) AS cdate ,s.shop_status as status
                    FROM coupons_shops s
                    LEFT JOIN coupons_users ON shop_createdby = userid
                    LEFT JOIN coupons_country ON countryid = shop_country
                    LEFT JOIN coupons_cities ON cityid = shop_city
                    WHERE shop_status in ('A','D')";
    
            if($_SESSION['userrole']==2)
                 $queryString .=	" AND shop_city = '$cityid'";
                 
		$pagination = new pagination();
		$pagination->createPaging($queryString,20);
		$resultSet = $pagination->resultpage;
		            
            if(mysql_num_rows($resultSet)>0)
            {
		echo '<table cellpadding="0" cellspacing="0" class="coupon_report">';
		echo '<tr class="fwb"><td>Shop Name</td><td>Created Date</td>';
		if( $_SESSION['userrole']=='1') {
		echo '<td>City</td><td>Country</td>';
		}
		
		if($_SESSION['userrole']=='2' || $_SESSION['userrole']=='1') {
		echo '<td>&nbsp;</td>';
		}
		echo '</tr>';
                while($noticia=mysql_fetch_array($resultSet))
                { 
                    echo '<tr><td>'.ucfirst(html_entity_decode($noticia['shopname'], ENT_QUOTES)).'</td><td>'.$noticia['shop_createddate'].'</td>';
                    if( $_SESSION['userrole']=='1') {
                    echo '<td>'.ucfirst(html_entity_decode($noticia['cityname'], ENT_QUOTES)).'</td><td>'.ucfirst(html_entity_decode($noticia['countryname'], ENT_QUOTES)).'</td>';
                    }

                    if($_SESSION['userrole']=='2' || $_SESSION['userrole']=='1') {
                    echo '<td><a href="'.$docroot.'edit/shop/'.$noticia['shopid'].'/" class="edit_but" title="Edit"></a>';
                    }
                    echo '</tr>';
    
                }
                        echo '</table>';
    
    
                    echo '<table border="0" width="400" align="center">';
		echo '<tr><td align="center">';
		$pagination->displayPaging();
		echo '</td></tr>';
		echo '</table>';
		
               }
               
            else
            {
                          echo '<p class="nodata">No Data Available</p>';
            }
    
    }
        
    else if($type=='users')
    {
    $queryString = "select u.userid,u.username,u.firstname,u.lastname,u.email,u.mobile,u.user_role,r.role_name,u.created_by,u.user_status
      from coupons_users u,coupons_roles r where r.roleid=u.user_role and u.user_status in ('A','D') ";
            
		$pagination = new pagination();
		$pagination->createPaging($queryString,20);
		$resultSet = $pagination->resultpage;
            
            if(mysql_num_rows($resultSet)>0)
            {
		echo '<p style="color:#666;width:100%;" class="fwb txtcenter">* Note: AD - Admin, DM - Demo Admin.<br/>CM - City Manager, SA - Store Administrator, G - General User.</p>';
                        echo '<table cellpadding="0" cellspacing="0" class="">';
		    echo '<tr class="fwb"><td>User Name</td><td>Role</td><td>Mobile</td><td>Created By</td><td width="15%" >&nbsp;</td>';  
    echo '</tr>';
    
                while($noticia=mysql_fetch_array($resultSet))
                { 
    
                    echo '<tr><td>'.ucfirst(html_entity_decode($noticia['firstname'], ENT_QUOTES)).'</td><td>'.$noticia['role_name'].'</td><td>'.html_entity_decode($noticia['mobile'], ENT_QUOTES).'</td>
					<td>'.ucfirst(html_entity_decode($noticia['firstname'], ENT_QUOTES)).'</td>
					<td><a href="'.$docroot.'admin/edit/'.$noticia['role_name'].'/'.$noticia['userid'].'/" class="edit_but" title="Edit"></a>';
                    

                  
		          if($noticia['user_status']=="D")
		          {
		          echo '<a href="javascript:;" onclick="updateuser(\'A\',\''.$noticia["userid"] .'\');" class="block" title="Block"></a>';
		          }
		          else
		          {
		          echo '<a href="javascript:;" onclick="updateuser(\'D\',\''.$noticia["userid"] .'\');" class="unblock" title="Unblock"></a>';
		          }

		          echo '<a href="javascript:;" onclick="javascript:deleteuser('.$noticia["userid"].');" class="delete" title="Delete"></a>';
                    
		
                    echo '</td></tr>';
                            }
                        echo '</table>';

                    echo '<table border="0" width="400" align="center">';
		echo '<tr><td align="center">';
		$pagination->displayPaging();
		echo '</td></tr>';
		echo '</table>';
		    
               }
               
            else
            {
                          echo '<p class="nodata">No Data Available</p>';
            }
    }
    
    else if($type=='closecoupon')
    {
       $queryString =" SELECT c.coupon_id, (select username from coupons_users where userid = p.coupons_acceptedby) as coupons_acceptedby ,u.firstname, u.lastname, u.mobile, p.coupon_purchaseid,p.couponid,c.coupon_name,p.coupon_userid,p.coupon_validityid,p.coupon_validityid_date,p.coupon_validityid_createdby,p.coupons_userstatus  FROM coupons_purchase p,coupons_users u,coupons_coupons c where p.coupon_status='C' and u.userid=p.coupon_userid and c.coupon_id=p.couponid";
    
		$pagination = new pagination();
		$pagination->createPaging($queryString,20);
		$resultSet = $pagination->resultpage;
		    
    
            if(mysql_num_rows($resultSet)>0)
            {

                        echo '<p style="color:#666;width:100%;" class="fwb txtcenter">* Note: UN-User Yet to Use this Offer in the Shop. <br/> U-User Used this Offer in the Shop. </p>';
                        echo '<table cellpadding="0" cellspacing="0">';
                        echo '<tr class="fwb"><td>User Name</td><td>Coupon Name</td> <td>Validated By</td><td>Mobile</td><td>Validated Date</td><td>Status</td><td width="5%">&nbsp;</td><td width="10%">&nbsp;</td></tr>';
                while($noticia=mysql_fetch_array($resultSet))
                { 
                    echo '<tr><td>'.ucfirst(html_entity_decode($noticia['firstname'], ENT_QUOTES)).'</td><td>'.ucfirst(html_entity_decode($noticia['coupon_name'], ENT_QUOTES)).'</td><td>';
                    
                    if($noticia['coupons_acceptedby']!="")
	                    echo ucfirst($noticia['coupons_acceptedby']);
                    else
	                    echo '-';
                    
                    echo '</td> <td>'.html_entity_decode($noticia['mobile'], ENT_QUOTES).'</td><td>'.$noticia['coupon_validityid_date'].'</td>
					<td>'.$noticia['coupons_userstatus'].'</td>
    <td><a href="'.$docroot.'admin/viewclosedcoupon/'.$noticia['coupon_id'].'/" class="edit_but" title="View"></a></td>';
    
    if($noticia['coupons_userstatus']=="UN")
    echo '<td><a href="javascript:;" onclick=" getPid(\''.$noticia['coupon_validityid'].'\')" title="Close Offer">Close Offer</a></td>';
    
    echo '</tr>';
                            }
    
                        echo '</table>';


                    echo '<table border="0" width="400" align="center">';
		echo '<tr><td align="center">';
		$pagination->displayPaging();
		echo '</td></tr>';
		echo '</table>';

               }
               
            else
            {
                          echo '<p class="nodata">No Data Available</p>';
            }
    
    }
    
    else if($type=="mycoupon")
    {
        
          $queryString ="SELECT (
if( LENGTH( concat( u.firstname, u.lastname ) ) =0, u.username, concat( u.firstname, u.lastname ) )
) AS name, u.mobile, p.coupon_purchaseid, p.coupon_purchaseddate, p.couponid,transaction_details.CAPTURED, c.coupon_status, c.coupon_name, c.coupon_enddate, c.coupon_expirydate, c.coupon_image, p.coupon_userid, p.coupon_validityid, p.coupon_validityid_date, p.coupon_validityid_createdby, p.coupons_userstatus
FROM coupons_purchase p LEFT JOIN coupons_users u ON u.userid = p.coupon_userid LEFT JOIN coupons_coupons c ON c.coupon_id = p.couponid LEFT JOIN transaction_details on p.transaction_details_id=transaction_details.ID WHERE p.Coupon_amount_Status = 'T' AND p.coupon_userid = '".$_SESSION['userid']."' ORDER BY p.coupon_purchaseid DESC";
    
		$pagination = new pagination();
		$pagination->createPaging($queryString,20);
		$resultSet = $pagination->resultpage;    
		
            if(mysql_num_rows($resultSet)>0)
            {
                if($_SESSION["userrole"] == 4)
                {

			include(DOCUMENT_ROOT."/themes/".CURRENT_THEME."/pages/my-coupons-details.php"); //include the remaining content		

                }
                else
                {
				?>
				<div style="color:#666;width:100%;" class="fwb txtcenter">
				<?php echo $language['note']; ?>: UN - <?php echo $language['user_yet_to_use']; ?> <br/> U - <?php echo $language['user_used']; ?><br/> <?php echo $language['validityid']; ?> - <?php echo $language['validityid_generated']; ?> <br/><?php echo $language['validated_date']; ?> - <?php echo $language['coupon_validated_date']; ?></div>
				
				<table cellpadding="5" cellspacing="5" class="fl clr">
				<tr class="fwb"><td><?php echo $language['coupon_name']; ?></td> <td><?php echo $language['validityid']; ?></td><td><?php echo $language['validated_date']; ?></td><td><?php echo $language['status']; ?></td></tr>
                
				<?php         
                while($noticia=mysql_fetch_array($resultSet))
                { 
				?>
                    <tr><td><?php echo ucfirst(html_entity_decode($noticia['coupon_name'], ENT_QUOTES)); ?></td><td>
					
					<?php 
                    if($noticia['coupon_validityid']!='')
	                    echo $noticia['coupon_validityid'];
                    else
	                    echo '-';
                    ?>
                    </td> 
					
					<td>
                    <?php 
                    if($noticia['coupon_validityid_date']!='')
	                    echo $noticia['coupon_validityid_date'];
                    else
	                    echo '-';
	                 ?>   
                    </td><td><?php $noticia['coupons_userstatus'];?></td>
					</tr>
                    
                      <?php 
					        }
    ?>
			</table>


		<table border="0" width="400" align="center">
		<tr><td align="center">
				<?php $pagination->displayPaging(); ?>
		</td></tr>
		</table>
		
		<?php 
                }
               }
                   else
                    {
                        echo '<p class="nodata">'.$language['no_coupons_purchased'].'</p>';
                    }
    
    }
    
    elseif($type=='cmcitycoupon')
    {
    
    $queryString = "SELECT (
    
    SELECT count( p.coupon_purchaseid )
    FROM coupons_purchase p
    WHERE p.couponid = c.coupon_id and p.Coupon_amount_Status='T'
    ) AS pcounts, c.coupon_id, c.coupon_name, DATE_FORMAT( c.coupon_startdate, '%d %M %Y' ) AS startdate, DATE_FORMAT( c.coupon_enddate, '%d %M %Y' ) AS enddate, c.coupon_createdby, u.firstname, u.lastname,c.coupon_status,c.coupon_minuserlimit as minuserlimit,c.coupon_maxuserlimit as maxuserlimit
    FROM coupons_coupons c, coupons_users u  where u.userid=c.coupon_createdby and c.coupon_status in ('A','D') and c.coupon_city = ".$_SESSION['city'];
    
		$pagination = new pagination();
		$pagination->createPaging($queryString,20);
		$resultSet = $pagination->resultpage;

            
            if(mysql_num_rows($resultSet)>0)
            {
                         echo '<table cellpadding="0" cellspacing="0"   class="coupon_report">';

		    echo '<tr class="fwb"><td>Coupon Name</td><td>Start Date</td> <td>End Date</td><td>Created By</td><td>Min User</td><td>Max User</td><td>Purchase Count</td><td  width="15%">&nbsp;</td><td  width="10%">&nbsp;</td></tr>';

    
                while($noticia=mysql_fetch_array($resultSet))
                { 
                    echo '<tr><td>'.ucfirst(html_entity_decode($noticia['coupon_name'], ENT_QUOTES)).'</td><td>'.html_entity_decode($noticia['startdate'], ENT_QUOTES).'</td> <td>'.html_entity_decode($noticia['enddate'], ENT_QUOTES).'</td><td>'.ucfirst(html_entity_decode($noticia['firstname'], ENT_QUOTES)).' '.ucfirst(html_entity_decode($noticia['firstname'], ENT_QUOTES)).'</td><td>'.html_entity_decode($noticia['minuserlimit'], ENT_QUOTES).'</td><td>'.html_entity_decode($noticia['maxuserlimit'], ENT_QUOTES).'</td><td>'.$noticia['pcounts'].'</td><td><a class="edit_but" title="Edit" href="'.$docroot.'edit/coupon/'.$noticia['coupon_id'].'/"></a>';
                    
                    if($noticia['coupon_status']=="D")
                    {
			echo '<a href="javascript:;" title="Unblock" class="unblock" onclick="updatecoupon(\'A\',\''.$noticia["coupon_id"] .'\');"></a>';
                    }
                    else{
	                    echo '<a href="javascript:;" class="block" title="Block" onclick="updatecoupon(\'D\',\''.$noticia["coupon_id"] .'\');"></a>';
		}                    

                    echo '<a href="javascript:;" class="delete" title="Delete" onclick="javascript:deletecoupon('.$noticia["coupon_id"].');"></a></td>';
                    
                    
                    if($noticia['pcounts'] > 0)
                    {
                    echo '<td><a href="'.$docroot.'coupon/code/'.$noticia['coupon_id'].'">Start Offer</a></td>';
                    }
                    echo '</tr>';
    
                }
                        echo '</table>';

                    echo '<table border="0" width="400" align="center">';
		echo '<tr><td align="center">';
		$pagination->displayPaging();
		echo '</td></tr>';
		echo '</table>';              
                        
               }
               
            else
            {
                          echo '<p class="nodata">No Data Available</p>';
            }
    }
    
    elseif($type=='cmcityclosecoupon')
    {
       $queryString =" SELECT  (select username from coupons_users where userid = p.coupons_acceptedby) as coupons_acceptedby ,u.firstname, u.lastname, u.mobile,c.coupon_id,  p.coupon_purchaseid,p.couponid,c.coupon_name,p.coupon_userid,p.coupon_validityid,p.coupon_validityid_date,p.coupon_validityid_createdby,p.coupons_userstatus FROM coupons_purchase p,coupons_users u,coupons_coupons c where p.coupon_status='C' and u.userid=p.coupon_userid and c.coupon_id=p.couponid and c.coupon_city = " .$_SESSION['city'];
    
		$pagination = new pagination();
		$pagination->createPaging($queryString,20);
		$resultSet = $pagination->resultpage;
		    
            if(mysql_num_rows($resultSet)>0)
            {

                        echo '<p style="color:#666;width:100%;" class="fwb txtcenter">* Note: UN - User Yet to Use this Offer in the Shop. <br />U - User Used this Offer in the Shop. </p>';
                        echo '<table cellpadding="0" cellspacing="0"   class="coupon_report" >';
                        
		    echo '<tr class="fwb"><td>User Name</td><td>Coupon Name</td> <td>Validated By</td><td>Mobile</td><td>Validated Date</td><td>Status</td><td width="5%">View</td><td width="10%">Close</td></tr>';

    
                while($noticia=mysql_fetch_array($resultSet))
                { 
                    echo '<tr><td>'.ucfirst(html_entity_decode($noticia['firstname'], ENT_QUOTES)).'</td><td>'.ucfirst(html_entity_decode($noticia['coupon_name'], ENT_QUOTES)).'</td><td>'.ucfirst($noticia['coupons_acceptedby']).'</td> <td>'.html_entity_decode($noticia['mobile'], ENT_QUOTES).'</td><td>'.$noticia['coupon_validityid_date'].'</td><td>'.$noticia['coupons_userstatus'].'</td><td><a href="'.$docroot.'admin/viewclosedcoupon/'.$noticia['coupon_id'].'/">View</a></td>';
    
    if($noticia['coupons_userstatus']=="UN")
    echo '<td><a href="javascript:;" onclick=" getPid(\''.$noticia['coupon_validityid'].'\')">Close Offer</a></td>';
    
    echo '</tr>';
                            }
                        echo '</table>';

                    echo '<table border="0" width="400" align="center">';
		echo '<tr><td align="center">';
		$pagination->displayPaging();
		echo '</td></tr>';
		echo '</table>';

    
               }
               
            else
            {
                          echo '<p class="nodata">No Coupons Available</p>';
            }
    
    }
    
    elseif($type=='all')
    {}
    
    
    }
    
	//valid url format 
    function valid_url($message)
    {
        return ( ! preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $message)) ? FALSE : TRUE;
    }
    
    function email($to,$subject,$message)
    {

	    $from = "demo@ndot.in";
	    $header = "Content-Type: text/html"."\r\n"."From: $from";
	    $result = valid_url($message);
		    if($result == '1')
		    {
		    $message = file_get_contents($message);
		    }
	    $result = mail($to,$subject,$message,$header);

    }
    
    function getTypes($val)
    {
    $queryString = "select s.subtypename,t.typename,s.subtypeid from coupons_subtypes s,coupons_types t where t.typeid=s.typeid and t.typename='".str_replace("_"," ",$val)."'";
    
            $resultSet = mysql_query($queryString);
            if(mysql_num_rows($resultSet)>0)
            {
                
                while($noticia=mysql_fetch_array($resultSet))
                { 
                               $types1=$noticia['typename'];
                               $typesvalues=$typesvalues."+".$noticia['subtypename'];
                               
                }
                               $types1=$types1."+".$typesvalues;
            }
          return $types1;
    }
    
    function getCurDate()
    {
    
    $queryString = "select now() as date";
    $resultset = mysql_query($queryString);
                    if($row = mysql_fetch_array($resultset)){
                        return $row['date'];
                    }
    }
    
    //get the feedbacks
    function get_feedbacks($cid)
    {
            $query = "select *, feedbacks.id as fid,date_format(feedbacks.cdate,'%b-%d-%y') as cdate from feedbacks left join coupons_users on feedbacks.uid=coupons_users.userid where  feedbacks.cid= '$cid' order by feedbacks.id desc";
            $result = mysql_query($query);
            return $result;
    }
    
    //post the feedbacks
    function post_feedbacks($cid='',$message='',$userid='')
    {
            $message = htmlspecialchars($message);
            $query = "insert into feedbacks (uid,message,cid,cdate) values ('$userid','$message','$cid',now())";
            $resultset = mysql_query($query);
         
            $_SESSION["response"] = "Recommendation has been posted.";
          
    }
    
    function get_coopen_feedbacks($coupon_outsideurl)
    {
    
            $query = "select coupons_coupons.coupon_status,feedbacks.id as fid,message,couponname,couponid,coupon_outsideurl,username from feedbacks, coupons_coupons, coupons_users 
    where feedbacks.cid=coupons_coupons.couponid and feedbacks.uid = coupons_users.userid and coupons_coupons.coupon_status ='A' and coupons_coupons.coupon_enddate > now() AND 
    coupons_coupons.coupon_outsideurl = '$coupon_outsideurl' order by rand() limit 0,1";
            $result = mysql_query($query);
            return $result;
    }
    
    function get_coopen_feedbacks_usrrecommend($coupon_outsideurl,$fid)
    {
        
            $query = "select coupons_coupons.coupon_status,feedbacks.id as fid,message,couponname,couponid,coupon_outsideurl,username from feedbacks, coupons_coupons, coupons_users 
    where feedbacks.cid=coupons_coupons.couponid and feedbacks.uid = coupons_users.userid and coupons_coupons.coupon_status ='A' and coupons_coupons.coupon_enddate > now() AND 
    coupons_coupons.coupon_outsideurl = '$coupon_outsideurl' and feedbacks.id = '$fid'";
            $result = mysql_query($query);
            return $result;
    }
    
    // to get overall savings amount by the user thru purchase
    function getsavingamt()
    {
                $userid = $_SESSION['userid'];
                $value = '0';
                $queryString = "SELECT c.coupon_value,c.coupon_realvalue FROM coupons_purchase p , coupons_coupons c  where p.couponid = c.coupon_id  and p.coupon_userid = '$userid'";
                $resultSet = mysql_query($queryString) or die(mysql_error());		
				while($row = mysql_fetch_array($resultSet))
				{
					$realvalue = html_entity_decode($row['coupon_realvalue'], ENT_QUOTES);				
					$coupon_value = html_entity_decode($row['coupon_value'], ENT_QUOTES);
					$value = $value + ($realvalue-$coupon_value);
				}
            
            $_SESSION["savedamt"] = $value;
    
    }
    
    function getlastaddedcoopen()
    {
    $queryString2 = "select * from coupons_coupons order by couponid desc limit 0,1";
    $resultset2 = mysql_query($queryString2);
    if($row = mysql_fetch_array($resultset2)) 
    {
    $coupon_outsideurl = $row['coupon_outsideurl'];
    }
    return $coupon_outsideurl;
    }
    
    function getusrpurchaseddetails($cid)
    {
    $userid = $_SESSION['userid'];
    $queryString = "select * from coupons_purchase where couponid='$cid' and coupon_userid='$userid' and coupons_userstatus ='UN'";
    $resultSet = mysql_query($queryString);
    return $rs = mysql_num_rows($resultSet);
    }
    
    // to check weather user voted for the coopon or not
    function checkvotestatus($fid,$uid)
    {
    $queryString = "select * from vote where uid='$uid' and fid='$fid'";
    $resultSet = mysql_query($queryString);
    return $resultSet2 = mysql_num_rows($resultSet);
    }
    
    function getclosedcoopondetails()
    {
    include "docroot.php";
    $turl_arr = explode("/",$_SERVER["REQUEST_URI"]);
    
    $queryString = "SELECT (select category_name from coupons_category where category_id = c.coupon_category) as couponstype,coupon_minuserlimit,coupon_maxuserlimit,coupon_realvalue,coupon_description,coupon_id, coupon_name, DATE_FORMAT( coupon_startdate, '%d %M %Y' ) AS startdate, DATE_FORMAT( coupon_enddate, '%d %M %Y' ) AS enddate, coupon_image, coupon_createdby,(
    if( LENGTH( concat( u.firstname, u.lastname ) ) =0, u.username, concat( u.firstname, u.lastname ) )
    ) AS name, DATE_FORMAT( coupon_createddate, '%d %M %Y' ) AS createddate, coupon_offer, coupon_status,TIMEDIFF(coupon_enddate,now())as timeleft,DATEDIFF(date_format(coupon_enddate,'%Y-%m-%d'),date_format(now(),'%Y-%m-%d'))as dayleft,(
    
    SELECT count( p.coupon_purchaseid )
    FROM coupons_purchase p
    WHERE p.couponid = c.coupon_id and p.Coupon_amount_Status='T' 
    ) AS pcounts
    ,c.coupon_startdate as coupon_startdate,c.coupon_enddate as coupon_enddate
    FROM coupons_coupons c, coupons_users u
    WHERE coupon_createdby = u.userid and coupon_id='".$turl_arr[3]."' ";
    
            $resultSet = mysql_query($queryString)or die(mysql_error());
    
            if(mysql_num_rows($resultSet)>0)
            {     
              $i=0;
              
                if($cdetails=mysql_fetch_array($resultSet))
                { 
    
    echo '<h1 class="inner_title fwb" style="padding:5px;">'.html_entity_decode($cdetails["coupon_name"], ENT_QUOTES).'!</h1>'; 
    echo '<div  class=" full_page_content">';
    echo '<div  class="vclosed_coopen mt10">';
    
    echo '<div class="fontbold mb10" style="margin-left:5px;" >';
    echo '<i class="fwb color666">';
    echo "Category --- ".$cdetails["couponstype"];
    echo '</i>';
    echo '</div>';  
    echo '<div class="mt10 fl" style="width:770px;">';   
	    if($cdetails["coupon_image"]!='')
	    {
		    echo '<img class="clr  borderE3E3E3" src="'.$docroot.$cdetails["coupon_image"].'"/>';
	    }
	    else
	    {
		    echo '<img src="'.$docroot.'themes/'.CURRENT_THEME.'/images/no_image.jpg" style="margin-top:20px;" />';
	    }
	    

    echo '<p class="fl p5 w300" style="width:500px;">'.html_entity_decode($cdetails["coupon_description"], ENT_QUOTES).'<br /></p>';
        echo '<p class="fl p5 w300 color344F86 font18" style="width:500px;text-align:center;">Coupon Purchased Count: '.$cdetails["pcounts"].'<br /></p>';
    echo '</div>';  	        
    ?>
                                  <div class="discount_value" style="margin-left:5px;">
                                          <div class="timetop">
                                              <div class="value">
                                              <span class="color333 font12">Value</span><br /><span class="color344F86 font18"><?php echo CURRENCY;?><?php echo html_entity_decode($cdetails["coupon_realvalue"], ENT_QUOTES); ?></span>
                                              </div>
                                              
                                              <div class="Discount">
                                              <span class="color333 font12">Discount</span><br /><span class="color344F86 font18"><?php echo html_entity_decode($cdetails["coupon_offer"], ENT_QUOTES); ?>%</span>
                                              </div>
                                              
                                              <div class="Save">
                                              <span class="color333 font12">You Save</span><br /><span class="color344F86 font18"><?php echo CURRENCY;?><?php echo ((html_entity_decode($cdetails["coupon_realvalue"], ENT_QUOTES) * html_entity_decode($cdetails["coupon_offer"], ENT_QUOTES)) / 100); ?></span>
                                              </div>
                                          </div>
                                   </div>
                                       
    <?php
                             
    echo'</div >';echo'</div >';	
                }
                }
                else
                {
                                  echo '<p class="nodata">No Data Available</p>';
                    }
    }
	
	//email
	function send_email($from ='',$to = '', $subject = '',$msg = '',$name = '')
	{
	 
	        include "docroot.php";
             
	        $logo = DOCROOT."site-admin/images/logo.png";
	        //$docroot = DOCROOT;
	   
	        if(empty($name))
	   	        $name='Customer';

                $message = '<div style="border:1px solid #A32F7A; width:660px;float:left;">
			<div style="background:#A32F7A;width:650px;height:100px;padding:5px;">
			<a href="'.$docroot.'" target="_blank" style="color:#fff;text-decoration:none;margin-left:20px;padding-top:40px;">
			<img src="'.$logo.'" border="0" />
			</a>
			</div>
			
			<div style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:12px; margin:10px;">
			<p style="font-size:16px; margin-left:20px;"><strong>Dear '.ucfirst($name).',</strong></p>
			<p style="margin-left:20px; line-height:20px; margin-bottom:10px;">'.ucfirst($msg).'</p>
			</div>
			</div>';


     		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		
		// Additional headers
		$headers .= 'From: '.$from.'' . "\r\n";

        mail($to, $subject,$message, $headers);

               
	}
	
	//set the response message
	function set_response_mes($type = '', $mes = '')
	{
		if($type == 1)
		{
			$_SESSION["mes"] = $mes;
		}
		else
		{
			$_SESSION["emes"] = $mes;
		}
		
	}
	
	//is login function => checks whether user logged or not
	
	function is_login($url='')
	{
	
		if(empty($_SESSION['userid']))
		{ 
			$_SESSION['ref'] = $_SERVER['HTTP_REFERER'];
			$_SESSION['msg'] = 'Please login to proceed further...';
			header("Location:$url");
			die();
		}
		
	}
	
	//url redirect
	function url_redirect($url)
	{
		if($url)
		{
			header("Location:$url");
			die();
		}
	}
	
	function success_mes()
	{
		if($_SESSION["mes"])
		{
	 ?>
	 	<script type="text/javascript">
	    $(document).ready(function () {
			if($('#messagedisplay')){
			  $('#messagedisplay').animate({opacity: 1.0}, 10000)
			  $('#messagedisplay').fadeOut('slow');
			}

			});
	 	</script>
					 
		  <div id="messagedisplay" class="success">
		  <div class="inner_left2"></div>
			<div class="inner_mid2">	
			  <p class="tick1"><?php echo $_SESSION["mes"]; ?></p>
		  </div>
		  <div class="inner_right2"></div>
		  </div>
		
		 <?php 
		 $_SESSION["mes"] = "";
		}
		 
	}
	
	//failed response message
	function failed_mes()
	{
		if($_SESSION["emes"])
		{
	 ?>
	 	<script type="text/javascript">
	    $(document).ready(function () {
			if($('#error_messagedisplay')){
			  $('#error_messagedisplay').animate({opacity: 1.0}, 10000)
			  $('#error_messagedisplay').fadeOut('slow');
			}

			});
	 	</script>
		  <div id="error_messagedisplay" class="failed">
		  <div class="inner_top2"></div>
		  	<div class="inner_center2">	
			  <p class="into"></p><p><?php echo $_SESSION["emes"]; ?></p>
		  </div>
		  <div class="inner_bottom2"></div>
		  </div>
		 <?php 
		 $_SESSION["emes"] = "";
		}
		 
	}


	function manage($type='')
	{
	include 'docroot.php';
	if($type=="country")
	{
			$queryString = "select * from coupons_country order by countryname";

			$pagination = new pagination();
			$pagination->createPaging($queryString,20);
			$resultSet = $pagination->resultpage;
				  
		  if(mysql_num_rows($resultSet)>0)
		  {

		                echo '<table cellpadding="0" cellspacing="0" class="coupon_report">';
			      echo '<tr class="fwb"><td>Country Name</td><td>&nbsp;</td>';
			      echo '</tr>';
			      while($noticia=mysql_fetch_array($resultSet))
			      { 
				echo '<tr><td>'.ucfirst(html_entity_decode($noticia['countryname'], ENT_QUOTES)).'</td><td>';
		          
		          echo '<a href="'.$docroot.'edit/country/'.$noticia['countryid'].'/" class="edit_but" title="Edit"></a>';
		          
		          
		          if($noticia["status"]=='D'){
		          
			          echo '<a href="javascript:;" onclick="updatecountry(\'A\',\''.$noticia["countryid"] .'\');" class="unblock" title="Unblock"></a>';
		          }else{

		          echo '<a href="javascript:;" onclick="updatecountry(\'D\',\''.$noticia["countryid"] .'\');" class="block" title="Block"></a>';		          
		          }

		          echo '<a href="javascript:;" onclick="javascript:deletecountry('.$noticia["countryid"].');" class="delete" title="Delete"></a>';		          
		          
		          echo '</td></tr>';
		          	    
			      }
		                echo '</table>';
	    
	    
	   		//pagination
			if($pagination->totalresult>20)
			{		 
				echo '<table border="0" width="400" align="center">';
				echo '<tr><td align="center">';
				$pagination->displayPaging();
				echo '</td></tr>';
				echo '</table>';
			}
		
		  }
		     
		  else
		  {
		                echo '<p class="nodata">No Data Available</p>';
		  }
	}
	else if($type=="city")
	{
		$queryString = "select coupons_cities.cityid, coupons_cities.cityname, coupons_cities.countryid, coupons_cities.status, coupons_country.countryname  from coupons_cities left join coupons_country on coupons_cities.countryid = coupons_country.countryid order by coupons_cities.cityname";

			$pagination = new pagination();
			$pagination->createPaging($queryString,20);
			$resultSet = $pagination->resultpage;
				  
		  if(mysql_num_rows($resultSet)>0)
		  {

		          echo '<table cellpadding="5" cellspacing="5" class="coupon_report">';
			      echo '<tr class="fwb"><td>City Name</td><td>Country Name</td><td>&nbsp;</td>';
			      echo '</tr>';
			      while($noticia=mysql_fetch_array($resultSet))
			      { 
					echo '<tr><td>'.ucfirst(html_entity_decode($noticia['cityname'], ENT_QUOTES)).'</td><td>'.ucfirst(html_entity_decode($noticia['countryname'], ENT_QUOTES)).'</td>';
		          
		          	echo '<td><a href="'.$docroot.'edit/city/'.$noticia['cityid'].'/" class="edit_but" title="Edit"></a>';
		          
		          if($noticia["status"]=='D'){
		          
			          echo '<a href="javascript:;" onclick="updatecity(\'A\',\''.$noticia["cityid"] .'\');" class="unblock" title="Unblock"></a>';
		          }else{
			          echo '<a href="javascript:;" onclick="updatecity(\'D\',\''.$noticia["cityid"] .'\');" class="block" title="Block"></a>';		          
		          }


		          echo '<a href="javascript:;" onclick="javascript:deletecity('.$noticia["cityid"].');" class="delete" title="Delete"></a>';
		          
		          echo '</td></tr>';
		          		          	    
			      }
		                echo '</table>';
	    
	    
	   		//pagination
			if($pagination->totalresult>20)
			{		   
				echo '<table border="0" width="400" align="center">';
				echo '<tr><td align="center">';
				$pagination->displayPaging();
				echo '</td></tr>';
				echo '</table>';
			}
		
		  }
		     
		  else
		  {
		                echo '<p class="nodata">No Data Available</p>';
		  }
	}
	else if($type=="category")
	{
		$queryString = "select * from coupons_category order by category_name";

			$pagination = new pagination();
			$pagination->createPaging($queryString,20);
			$resultSet = $pagination->resultpage;
				  
		  if(mysql_num_rows($resultSet)>0)
		  {

		                echo '<table cellpadding="5" cellspacing="5" class="coupon_report">';
			      echo '<tr class="fwb"><td>Category Name</td><td>&nbsp;</td>';
			      echo '</tr>';
			      while($noticia=mysql_fetch_array($resultSet))
			      { 
				echo '<tr><td>'.ucfirst(html_entity_decode($noticia['category_name'], ENT_QUOTES)).'</td>';
		          
		          echo '<td><a href="'.$docroot.'edit/category/'.$noticia['category_id'].'/" class="edit_but" title="Edit"></a>';
		          

		          
		          if($noticia["status"]=='D'){
		          
			          echo '<a href="javascript:;" onclick="updatecategory(\'A\',\''.$noticia["category_id"] .'\');" class="unblock" title="Unblock"></a>';
		          }else
		          {
			          echo '<a href="javascript:;" onclick="updatecategory(\'D\',\''.$noticia["category_id"] .'\');" class="block" title="Block"></a>';		          
		          }

		          echo '<a href="javascript:;" onclick="javascript:deletecategory('.$noticia["category_id"].');" class="delete" title="Delete"></a>';
		          		          	
		          echo '</td></tr>';		          	    
			      }
		                echo '</table>';
	    
	   		//pagination
			if($pagination->totalresult>20)
			{
				echo '<table border="0" width="400" align="center">';
				echo '<tr><td align="center">';
				$pagination->displayPaging();
				echo '</td></tr>';
				echo '</table>';
			}
		
		  }
		     
		  else
		  {
		                echo '<p class="nodata">No Data Available</p>';
		  }
	}
  
  }
	
    //data format
    function change_time($time) 
    {
        $time = strtotime($time);
        $c_time = time() - $time;
        if ($c_time < 60) {
            return '0 minute ago';
        } else if ($c_time < 120) {
            return '1 minute ago';
        } else if ($c_time < (45 * 60)) {
            return floor($c_time / 60) . ' minutes ago';
        } else if ($c_time < (90 * 60)) {
            return '1 hour ago.';
        } else if ($c_time < (24 * 60 * 60)) {
            return floor($c_time / 3600) . ' hours ago';
        } else if ($c_time < (48 * 60 * 60)) {
            return '1 day ago.';
        } else {
            return floor($c_time / 86400) . ' days ago';
        }
     }


?>		


<?php

  // Set timezone
  // date_default_timezone_set("UTC");
 
  // Time format is UNIX timestamp or
  // PHP strtotime compatible strings
  function dateDiff($time1, $time2, $precision = 6) {
    // If not numeric then convert texts to unix timestamps
    if (!is_int($time1)) {
      $time1 = strtotime($time1);
    }
    if (!is_int($time2)) {
      $time2 = strtotime($time2);
    }
 
    // If time1 is bigger than time2
    // Then swap time1 and time2
    if ($time1 > $time2) {
      $ttime = $time1;
      $time1 = $time2;
      $time2 = $ttime;
    }
 
    // Set up intervals and diffs arrays
    $intervals = array('year','month','day','hour','minute','second');
    $diffs = array();
 
    // Loop thru all intervals
    foreach ($intervals as $interval) {
      // Set default diff to 0
      $diffs[$interval] = 0;
      // Create temp time from time1 and interval
      $ttime = strtotime("+1 " . $interval, $time1);
      // Loop until temp time is smaller than time2
      while ($time2 >= $ttime) {
	$time1 = $ttime;
	$diffs[$interval]++;
	// Create new temp time from time1 and interval
	$ttime = strtotime("+1 " . $interval, $time1);
      }
    }
 
    $count = 0;
    $times = array();
    // Loop thru all diffs
    foreach ($diffs as $interval => $value) {
      // Break if we have needed precission
      if ($count >= $precision) {
	break;
      }
      // Add value and interval 
      // if value is bigger than 0
      if ($value > 0) {
	// Add s if value is not 1
	if ($value != 1) {
	  $interval .= "s";
	}
	// Add value and interval to times array
	$times[$interval] = $value;
	$count++;
      }
    }
 
    // print_r($times);
    // Return string with times
    // return implode(", ", $times);
	return $times;
  }

	function get_discount_value($realvalue='',$dealvalue='')
	{

		$value = $realvalue - $dealvalue;
		$value = ($value/$realvalue)*100;
		return $value;

	}
  
  ?>
<?php ob_flush(); ?>
