<?php 
#-------------------------------------------------------------------------------
/* Get the request files here */
#-------------------------------------------------------------------------------

switch($file_name)
{
	case "home":
	//FB CODE
	$cFB = explode("?code=",$_SERVER['REQUEST_URI']);
	
	if(isset($cFB[0])){
	
		if($cFB[0] == "/facebook-connect.html/"){
		
			header("Location:/facebook-connect.html?code=".$cFB[1]);
			die();
		
		}
	
	}

	$sub1 = $_REQUEST['sub1'];
	
	if($sub1 != $_SESSION['default_city_url'])
	{
	        while($city_result = mysql_fetch_array($resultCity1))
	        {
	                if($sub1 == html_entity_decode($city_result["city_url"], ENT_QUOTES))
	                {
	                        $_SESSION['defaultcityname'] = html_entity_decode($city_result["cityname"], ENT_QUOTES);
	                        $_SESSION['defaultcityId'] = $city_result["cityid"];
	                        $_SESSION['default_city_url'] = html_entity_decode($city_result["city_url"], ENT_QUOTES);
	                        $month = 2592000 + time();
                                setcookie("defaultcityId", "");	
                                setcookie("defaultcityId",$_SESSION['defaultcityId'], $month);
	                        $change = 1;
							url_redirect(DOCROOT.$_SESSION['default_city_url'].'/');
	                        break;
	                }
	        }
			
	        if(!$change)
	        {
	                $page_title = 'Oops page not found';
	                $left_file = "error.php";
	                $right = $root_sidebar_path."side_bar_2.php";
	                $view = "template_2.php";
	                break;      
	        }
	}
	
	$page_title = $language["home"];
	$left_file = "home.php";
	$right = $root_sidebar_path."side_bar.php";
	$view = "template_3.php";
	break;
	
	case "business":
	$page_title = $language["suggest"];
	$left_file = "business.php";
	$view = "template_1.php";
	break;

	case "gettimestamp":
	$page_title = $language["contact_us"];
	$left_file = "gettimestamp.php";
	$view = "template_1.php";
	break;


	case "login":
	$page_title = $language["user_login"];
	$left_file = "login.php";
	$right = $root_sidebar_path."side_bar_2.php";
	$view = "template_2.php";
	break;
	
	case "offline_payement":
	$page_title = $language["user_login"];
	$left_file = "offline_payement.php";
	$right = $root_sidebar_path."side_bar_2.php";
	$view = "template_2.php";
	break;

	case "forgot-password":
	$page_title = $language["forgot_password"];
	$left_file = "forgot-password.php";
	$right = $root_sidebar_path."side_bar_2.php";
	$view = "template_2.php";
	break;

	case "logout":
	$page_title = $language["logout"];
	$left_file = "logout.php";
	$right = $root_sidebar_path."side_bar_2.php";
	$view = "template_2.php";
	break;

	case "registration":
	$page_title = $language["user_reg"];
	$left_file = "registration.php";
	$right = $root_sidebar_path."side_bar_2.php";
	$view = "template_2.php";
	break;

	case "ref":
	$page_title = $language["user_reg"];
	$left_file = "registration.php";
	$right = $root_sidebar_path."side_bar_2.php";
	$view = "template_2.php";
	break;
		
	case "profile":
	$page_title = $language["profile"];
	$left_file = "profile.php";
	$right = $root_sidebar_path."side_bar_2.php";
	$view = "template_2.php";
	break;

	case "edit":
	$page_title = $language["edit"];
	$left_file = "edit.php";
	$right = $root_sidebar_path."side_bar_2.php";
	$view = "template_2.php";
	break;

	case "change-password":
	$page_title = $language["change_password"];
	$left_file = "change-password.php";
	$right = $root_sidebar_path."side_bar_2.php";
	$view = "template_2.php";
	break;

	case "fund-request":
	$page_title = $language["withdraw_fund_request"];
	$left_file = "fund-request.php";
	$right = $root_sidebar_path."side_bar_2.php";
	$view = "template_2.php";
	break;
	
	case "referral-list":
	$page_title = $language["referral_list"];
	$left_file = "referral-list.php";
	$right = $root_sidebar_path."side_bar_2.php";
	$view = "template_2.php";
	break;
	
	case "my-coupons":
	$page_title = $language["mycoupons"];
	$left_file = "my-coupons.php";
	$right = $root_sidebar_path."side_bar_2.php";
	$view = "template_2.php";
	break;

	case "how-it-works":
	$page_title = $language["how_grouponclone_works"];
	$left_file = "how-it-works.php";
	$view = "template_1.php";
	break;

	case "privacy-policy":
	$page_title = $language["privacy"];
	$left_file = "privacy.php";
	$view = "template_1.php";
	break;

	case "pages":
	$page_name = $_REQUEST["sub1"];

	if($page_name)
	{
		$page_result = mysql_query("select * from pages where title_url='$page_name'");
		if(mysql_num_rows($page_result)>0)
		{
			while($row = mysql_fetch_array($page_result))
			{
				$page_title = html_entity_decode($row["title"], ENT_QUOTES);
				$page_desc = html_entity_decode($row["description"], ENT_QUOTES);
				$meta_keywords = html_entity_decode($row["meta_keywords"], ENT_QUOTES);
				$meta_description = html_entity_decode($row["meta_description"], ENT_QUOTES);
			}
		}
		else
		{
				$page_title = "None";
				$page_desc = "Page not found";
				$meta_keywords = "Page not found";
				$meta_description = "Page not found";		
		}

	}

	$left_file = "cms-page.php";
	$view = "template_1.php";
	break;
	
	case "referral":
	$page_title = $language["refer_friends_and_get"].CURRENCY.REF_AMOUNT;
	$left_file = "referral.php";
	$view = "template_1.php";
	break;
		
	case "faq":
	$page_title = $language["faq"];
	$left_file = "faq.php";
	$view = "template_1.php";
	break;

	case "about-us":
	$page_title = $language["about_us"];
	$left_file = "aboutus.php";
	$view = "template_1.php";
	break;
	
	case "hot-deals":
	$page_title = $language["hot"];
	$left_file = "hot-deals.php";
	$right = $root_sidebar_path."side_bar.php";
	$view = "template_2.php"; 
	break;

	case "past-deals":
	$page_title = $language["past_deals"];
	$left_file = "past-deals.php";
	$right = $root_sidebar_path."side_bar.php";
	$view = "template_2.php";
	break;

	case "contactus":
	$page_title = $language["contact_us"];
	$left_file = "contactus.php";
	$view = "template_1.php";
	break;

	case "city":
	$page_title = $language["city"];
	$left_file = "city.php";
	$right = $root_sidebar_path."side_bar.php";
	$view = "template_2.php";
	break;

	case "today-deals":
	$page_title = $language["today_deals"];
	$left_file = "today-deals.php";
	$right = $root_sidebar_path."side_bar.php";
	$view = "template_2.php";
	break;

	case "search":
	$left_file = "search.php";
	$right = $root_sidebar_path."side_bar.php";
	$view = "template_2.php";
	break;
	
	case "purchase":
	$page_title = $language["your_purchase"];
	$left_file = "purchase.php";
	$right = $root_sidebar_path."side_bar.php";
	$view = "template_1.php";
	break;
	
	case "subscribe":
	$page_title = $language["subscribe"];
	$left_file = "subscribe_email.php";
	$right = $root_sidebar_path."side_bar.php";
	$view = "template_2.php";
	break;

	case "unsubscribe":
	$page_title = $language["unsubscribe"];
	$left_file = "unsubscribe.php";
	$view = "template_1.php";
	break;

	case "unsubscribeconfirmation":
	$page_title = $language["unsubscribe_confirmation"];
	$left_file = "unsubscribeconfirmation.php";
	$view = "template_1.php";
	break;
		
	case "print":
	$sub1 = $_REQUEST["sub1"];
	$page_title = $language["print_coupon"];
	include("themes/_base_theme/pages/print-coupon.php");
	exit;
	break;
	
	case "export":
	$sub1 = $_REQUEST["sub1"];
	$sub2 = $_REQUEST["sub2"];
	$page_title = "Export as pdf";
	$left_file = "export_pdf.php";
	$right = $root_sidebar_path."side_bar.php";
	$view = "template_1.php";
	break;
	
        case "users":
	$sub1 = $_REQUEST["sub1"];
	$page_title = $language["api"];
	$left_file = "user-api.php";
	$right = $root_sidebar_path."side_bar.php";
	$view = "template_2.php";
	break;

	case "deals":
	$sub1 = $_REQUEST["sub1"];
	if($sub1 == "category")
	{
		$url = $_SERVER['REQUEST_URI'];
		$last_uri = end(explode('/',$url));
		$category_name = current(explode('_',$last_uri));
		
		$category_url = urldecode($category_name);
		$query = "select * from coupons_category where category_url = '$category_url'";
		$result = mysql_query($query);
                if(mysql_num_rows($result) > 0)
                {
                        while($cat = mysql_fetch_array($result))
                        {
                                $category_name = $cat["category_name"];
                        }
                }
		$left_file = "category.php";
		
		$page_title = ucfirst("Deals In ".ucfirst($category_name));
		$view = "template_2.php";
	}
	else if($sub1 == "past")
	{
	        $url = $_SERVER['REQUEST_URI'];
		
		if(!empty($aff_url_aff[1]))
		{
			   $_SESSION["affId"] = $aff_url_aff[1];
		}  
		
		$last_uri = end(explode('_',$url));
		$deal_id = current(explode('.',$last_uri));
		//get the deal tile
		$title_res = mysql_query("select coupon_name from coupons_coupons where coupon_id='$deal_id'");
		
		while($r = mysql_fetch_array($title_res))
		{
			$page_title = ucfirst($r["coupon_name"]);
		}
		
		$left_file = "past_deals_info.php";
		$view = "template_3.php";
	}
	else
	{ 
		$url = $_SERVER['REQUEST_URI'];
		
		if(!empty($aff_url_aff[1]))
		{
			   $_SESSION["affId"] = $aff_url_aff[1];
		}  
		
		$last_uri = end(explode('_',$url));
		$deal_id = current(explode('.',$last_uri));
		//get the deal tile
		$title_res = mysql_query("select coupon_name,meta_keywords,meta_description from coupons_coupons where coupon_id='$deal_id'");
		
		while($r = mysql_fetch_array($title_res))
		{
			$page_title = ucfirst($r["coupon_name"]);
			$meta_keywords = html_entity_decode($r["meta_keywords"]);
			$meta_description = html_entity_decode($r["meta_description"]);
		}
		
		$left_file = "deals_info.php";
		$view = "template_3.php";
	}
	$right = $root_sidebar_path."side_bar.php";
	break;
	
     	case "orderdetails":
	$page_title = $language["your_order_status"];
	$left_file = "orderdetails.php";
	$right = $root_sidebar_path."side_bar.php";
	$view = "template_2.php";
	break;
	
	case "error":
	$sub1 = $_REQUEST['sub1'];
	$page_title = "Oops! That page doesn't exist.";
	$left_file = "error.php";
	$right = $root_sidebar_path."side_bar.php";
	$view = "template_2.php";
	break;

	default:
	$page_title = $language["home"];
	$left_file = "home.php";
	$right = $root_sidebar_path."side_bar.php";
	$view = "template_3.php";
	break;
	
}
	$left = $root_dir_path.$left_file; // appending the file & path

	$left_default = 'themes/_base_theme/pages/'.$left_file;
	//echo $root_dir_path;exit;
?>
