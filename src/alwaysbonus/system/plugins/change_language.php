<?php
ob_start();
include($_SERVER['DOCUMENT_ROOT'].'/system/includes/library.inc.php');
if($_REQUEST['lang']!='')
{
	session_start();
	$_SESSION["site_language"] = $_REQUEST['lang'];
}
else
{
	session_start();
        $_SESSION["site_language"] = 'en';
}

set_response_mes(1, "Language has been Changed.");
url_redirect(DOCROOT);
ob_flush();
?>	
