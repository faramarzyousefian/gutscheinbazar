<?php ob_start(); ?>
<?php 
/******************************************** * @Created on June, 2011
 * @Updated on April, 2011
 * @Package: ndotdeals 3.0 (Opensource groupon clone) 
 * @Author: NDOT
 * @URL : http://www.ndot.in
 ********************************************/
?>
<?php

// for install start
$path=explode('.',$_SERVER['SCRIPT_NAME']);  
$url=explode('/',$path[0]);
$cnt=count($url)-1;
$ur='';
for($i=0;$i<$cnt;$i++)
{
	$ur.=$url[$i].'/';
}
$documentroot = "http://".$_SERVER['HTTP_HOST'].$ur;

if(file_exists('system/includes/dboperations.php'))  // if file exists search the db list.if all tables avaialble will redirect to the landing page or else to install 
{ 
        include_once 'system/includes/dboperations.php';
		$result = mysql_query("SHOW TABLES FROM $db");
        if (!$result) {
            echo 'Installation Error in your application: ' . mysql_error();
            exit;
        }
        if(mysql_num_rows($result) == 0) {
		$docroot2='install/process.php?docroot='.$documentroot; 
		
		?>
		<script type="text/javascript">
		window.location = "<?php echo $docroot2; ?>";
		</script>
		<?php	
	}
	else
	{
		if(file_exists('install/process.php')) {
			//header('location:../');
		?>
	<?php /*	<!--<p style=" background-color:red; font-color:whiter; font-size:15px; padding:10px; ">Please delete 'install' folder or back it up some where before you Go live!</p>-->
	*/	
		}

			session_start();
			error_reporting(E_ALL ^ E_NOTICE);

			
			
			#-------------------------------------------------------------------------------
			require_once ($_SERVER['DOCUMENT_ROOT'].'/system/includes/library.inc.php');
			#-------------------------------------------------------------------------------

			/** update session to change the theme **/
			
			$theme_url = explode('/',$_SERVER["REQUEST_URI"]);
			if(isset($theme_url[1]) && isset($theme_url[2]) && $theme_url[1]== 'theme')
			{
			       $_SESSION["my_theme"] = $theme_url[2] ;
			       url_redirect(DOCROOT);
			}

			/** end update session to change the theme **/
			
			//check email id exist in db
			if($_SESSION["userid"])
			{
		
				$refurl = substr($_SERVER['REQUEST_URI'],1);
				if($refurl!='edit.html' && empty($_SESSION["emailid"]) && $refurl!='logout.html') 
				{ 
						set_response_mes(-1, 'Enter your email address to proceed further');
						$url = DOCROOT."edit.html";
						header("Location:$url");
						die();
		
				}
		
				if($refurl=='edit.html')
				{		if(empty($_SESSION["emailid"])) {
						set_response_mes(-1, 'Enter your email address to proceed further'); }
				}
		
			}

			#-------------------------------------------------------------------------------
			$theme_name = CURRENT_THEME; //theme name
			#-------------------------------------------------------------------------------
			$default_city_id = $_SESSION['defaultcityId']; //get the default city id
			#-------------------------------------------------------------------------------
			$root_dir_path = 'themes/'.$theme_name.'/pages/'; //root directory
			$root_sidebar_path = 'themes/'.$theme_name.'/';
			#-------------------------------------------------------------------------------
			$file_name = $_REQUEST["file"]; //get the file name
			#-------------------------------------------------------------------------------
			# language file included here
			#-------------------------------------------------------------------------------
			$lang = $_SESSION["site_language"];
			if($lang)
			{
					include(DOCUMENT_ROOT."/system/language/".$lang.".php");
			}
			else
			{
					include(DOCUMENT_ROOT."/system/language/en.php");
			}
			$theme_q = new theme_sql($language); // creating theme query class
			
			#-------------------------------------------------------------------------------
			/*checking whether site in online or offline */
			#-------------------------------------------------------------------------------

			if(SITE_IN == 2)
			{
				$current_uid = $_SESSION["userid"];
				$current_urole = $_SESSION["userrole"];
				
				if($current_urole != 1)
				{
					include("themes/_base_theme/pages/offline.php");
					exit;
				}
			}
			
			#-------------------------------------------------------------------------------
			/* Get the city list here */
			#-------------------------------------------------------------------------------
			
			$cityString = "select * from coupons_cities where status='A' order by cityname asc";
			$resultCity = mysql_query($cityString);
			$resultCity1 = mysql_query($cityString);
			
			#-------------------------------------------------------------------------------
			/* Redirect home page if session for city is set */
			#-------------------------------------------------------------------------------
			
			$current_url = explode('/',$_SERVER['REQUEST_URI']);
			if(empty($current_url[0]) && empty($current_url[1]))
			{
					if(isset($_SESSION['defaultcityname']))
					{
							url_redirect(DOCROOT.$_SESSION['default_city_url'].'/');    
					}
			}
                        if($_SESSION['default_city_url'])
	                {
	                        while($city_result = mysql_fetch_array($resultCity))
	                        {
	                                if($_SESSION['default_city_url'] == html_entity_decode($city_result["city_url"], ENT_QUOTES))
	                                {
	                                        $_SESSION['defaultcityname'] = html_entity_decode($city_result["cityname"], ENT_QUOTES);
	                                        $_SESSION['defaultcityId'] = $city_result["cityid"];
	                                        $_SESSION['default_city_url'] = html_entity_decode($city_result["city_url"], ENT_QUOTES);
	                                        $month = 2592000 + time();
                                                setcookie("defaultcityId", "");	
                                                setcookie("defaultcityId",$_SESSION['defaultcityId'], $month);
	                                        $change = 1;
						//url_redirect(DOCROOT.$_SESSION['default_city_url'].'/');
	                                        break;
	                                }
	                        }
			
	                        if(!$change)
	                        {
	                                $page_title = 'Oops page not found';
	                                $left_file = "error.php";
	                                $right = $root_sidebar_path."side_bar_2.php";
	                                $view = "template_2.php";
	                               // break;      
	                        }
	                }
			//include the controller file
			#-------------------------------------------------------------------------------
			include('system/controller.php');
			#-------------------------------------------------------------------------------
			$site_bus = get_saved_money();
			while($ro = mysql_fetch_array($site_bus))
			{
				$purchased_deals = $ro["coupons_purchased_count"];
				$saved_amount = $ro["coupons_amtsaved"];
			}
			#-------------------------------------------------------------------------------

			//dynamic rss feed generation
			if($file_name=='rss')
			{ 
				include('system/plugins/rss.php'); 
				die();
			}
			
			if($_REQUEST["sub1"]=='email_all')
			{
				include('themes/_base_theme/email/email_all.html'); 
				die();
			}

			#-------------------------------------------------------------------------------
			include ('themes/'.$theme_name.'/template.php');
			#-------------------------------------------------------------------------------
			
			}
}
else
{ 
			$docroot3=$_SERVER['REQUEST_URI'].'install/preinstall.php?docroot='.$documentroot;
			?>
			<script type="text/javascript">
				window.location = "<?php echo $docroot3; ?>";    // redirect to install
			</script>
			<?php
} 
?>
<?php ob_flush(); ?>
