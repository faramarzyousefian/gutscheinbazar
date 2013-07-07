<?php
/****************************************** 
* @Created on June, 2011
* @Package: ndotdeals 3.0 (Opensource groupon clone) 
* @Author: NDOT
* @URL : http://www.ndot.in
********************************************/
?>
<?php 
	//declare the default variable 
	$page_title = "";
	$bread_crump = ""; 
	$left = "";
	$right = "";
	$view = "";
	$meta_description = "";
	$meta_keywords = "";
	//get the general site information
	$app_query = "select * from general_settings limit 1";
	$result_set = mysql_query($app_query);
	if(mysql_num_rows($result_set))
	{
		$app_row = mysql_fetch_array($result_set);
	}				    	     

	#-------------------------------------------------------------------------------
	//application Information
	define("DOCUMENT_ROOT",$_SERVER['DOCUMENT_ROOT']);
	define("APP_NAME",html_entity_decode($app_row["name"], ENT_QUOTES)); //app title
	define("SITE_NAME",html_entity_decode($app_row["site_name"], ENT_QUOTES)); //app name
	define("APP_DESC",html_entity_decode($app_row["description"], ENT_QUOTES));
	define("APP_KEYWORDS",html_entity_decode($app_row["keywords"], ENT_QUOTES));
	define("SITE_LICENSE_KEY",html_entity_decode($app_row["site_license_key"], ENT_QUOTES));
	
	define("REF_AMOUNT",$app_row['ref_amount']);
	define("ADMIN_COMMISSION",$app_row['admin_commission']);
	define("CA_COMMISSION",$app_row['city_admin_commission']);

	define("ADMIN_PAYPAL_ACCOUNT",html_entity_decode($app_row['paypal_account'], ENT_QUOTES));
	define("ADMIN_PAYPAL_API_PASSWORD",html_entity_decode($app_row['paypal_api_password'], ENT_QUOTES));
	define("ADMIN_PAYPAL_API_SIGNATURE",html_entity_decode($app_row['paypal_api_signature'], ENT_QUOTES));

	define("MIN_FUND",$app_row['min_fund']);
	define("MAX_FUND",$app_row['max_fund']);
	#---------------------------------------------------------------------------------------
	define("PAYPAL_CURRENCY_CODE",html_entity_decode($app_row["currency_code"], ENT_QUOTES));
	define("PAYPAL_ACCOUNT_TYPE",$app_row["paypal_account_type"]);
			
	//define the currency format
	define("CURRENCY",html_entity_decode($app_row["currency"], ENT_QUOTES));
	define("CURRENCY_CODE",html_entity_decode($app_row["currency_code"], ENT_QUOTES));
	
	define("SITE_EMAIL",$app_row["email"]); //configure the site email id
	define("SITE_DEFAULT_CITYID",$app_row["default_cityid"]); 
	//set the language
	if(empty($_SESSION["site_language"]))
	{
		$_SESSION["site_language"] = $app_row["default_language"];
	}
	
	define("MOBILE_THEME",$app_row["mobile_theme"]); 
	
	if($_SESSION["mobile_access"] == "mobile")	
	{
		define("CURRENT_THEME",MOBILE_THEME); 
	}
	else
	{
	        if(!empty($_SESSION["my_theme"]))
	        {
	              define("CURRENT_THEME",$_SESSION["my_theme"]);   
	        }
	        else
	        {
	    		define("CURRENT_THEME",$app_row["theme"]); 
	    	}
	}
	
	#---------------------------------------------------------------------------------------	
	define("REVIEW_TYPE",$app_row["review_type"]); //Review type
	define("SITE_IN",$app_row["site_in"]); 
	#---------------------------------------------------------------------------------------	
	define("FACEBOOK_API",html_entity_decode($app_row["fb_apikey"], ENT_QUOTES)); //facebook api key
	define("FACEBOOK_SECRET",html_entity_decode($app_row["fb_secretkey"], ENT_QUOTES)); //facebook secret key
	define("FACEBOOK_APP_ID",html_entity_decode($app_row["fb_appid"], ENT_QUOTES));  //facebook application id
	define("FACEBOOK_REDIRECT_URL","system/modules/facebook/facebook.php/"); // facebook redirection url
	#---------------------------------------------------------------------------------------
	define("TWITTER_API",html_entity_decode($app_row["tw_apikey"], ENT_QUOTES)); //twitter api key
	define("TWITTER_SECRET",html_entity_decode($app_row["tw_secretkey"], ENT_QUOTES)); //twitter secret key
	#---------------------------------------------------------------------------------------	
	//google map api key
	define("GMAP_API",html_entity_decode($app_row["gmap_apikey"], ENT_QUOTES));
	#---------------------------------------------------------------------------------------
	//language file list

	$lang_list = array( "en" => array("lang"=>"English", "content_type"=>"utf-8") ); 

	#---------------------------------------------------------------------------------------	
	//language file list
	$admin_lang_list = array( "en" => array("lang"=>"English", "content_type"=>"utf-8") ); 
	#---------------------------------------------------------------------------------------	

	//facebook fan page url. 
	define("FANPAGE_URL",html_entity_decode($app_row["fb_fanpage_url"], ENT_QUOTES));
	#---------------------------------------------------------------------------------------
	//currency formats
	$currency_symbol = array( "$" => "$" ); 
	$paypal_currency_code = array( "USD" => "USD" ); 
	#---------------------------------------------------------------------------------------
	//follow us links
	define("FACEBOOK_FOLLOW",html_entity_decode($app_row["facebook_share"], ENT_QUOTES)); //facebook share
	define("TWITTER_FOLLOW",html_entity_decode($app_row["twitter_share"], ENT_QUOTES)); //twitter share
	define("LINKEDIN_FOLLOW",html_entity_decode($app_row["linkedin_share"], ENT_QUOTES)); //linkedin share
	#---------------------------------------------------------------------------------------	
	//get module status
	$mod_query = "select * from modules limit 1";
	$result_set = mysql_query($mod_query);
	if(mysql_num_rows($result_set))
	{
		$mod_row = mysql_fetch_array($result_set);
	}
	#---------------------------------------------------------------------------------------	
	define("FEATURED_DEAL",$mod_row["featured_deals"]);
	define("CATEGORY",$mod_row["category"]); 
	define("NEWSLETTER",$mod_row["newsletter"]); 
	define("FANPAGE",$mod_row["fanpage"]);
	define("FACEBOOK_CONNECT",$mod_row["facebook_connect"]); 
	define("TWITTER_CONNECT",$mod_row["twitter_connect"]); 
	define("TWEETS_AROUND_CITY",$mod_row["tweets_around_city"]); 
	define("MOBILE_SUBSCRIPTION",$mod_row["mobile_subscribtion"]); 
	define("SMTP_STATUS",$mod_row["smtp"]); 
	#---------------------------------------------------------------------------------------	
	//SMTP Configuration variables
	define("SMTP_USERNAME",html_entity_decode($app_row["smtp_username"], ENT_QUOTES));
	define("SMTP_PASSWORD",html_entity_decode($app_row["smtp_password"], ENT_QUOTES));
	define("SMTP_HOST",html_entity_decode($app_row["smtp_host"], ENT_QUOTES)); 
	#---------------------------------------------------------------------------------------	
	//contact information
	define("FROM_EMAIL",SITE_EMAIL);
	define("FROM_NAME",SITE_NAME);
	define("REPLY_TO_EMAIL",SITE_EMAIL);
	define("REPLY_TO_NAME",SITE_NAME);
	#---------------------------------------------------------------------------------------	
	//fund release information
	define("SUB","your fund released in ".SITE_NAME);
	define("NOTE","Check your fund information");
	#---------------------------------------------------------------------------------------	
	#---------------------------------------------------------------------------------------	
	//set your timezone
	//date_default_timezone_set('Asia/Calcutta'); //SPECIFY YOUR TIME ZONE
	//mysql_query("SET `time_zone` = '".date('P')."'");
	
	#---------------------------------------------------------------------------------------
	#set the header
	#---------------------------------------------------------------------------------------
	//getting the language content type
	foreach($lang_list as $key => $value) { 
		if($_SESSION["site_language"] == $key) { 

								$php_content_type = $html_content_type = "text/html; charset=".$value['content_type'];
								break;
						
							}
	}
?>
