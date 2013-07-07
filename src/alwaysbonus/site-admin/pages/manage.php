<?php session_start();
is_login(DOCROOT."admin/login/"); //checking whether admin logged in or not.
$url_value = explode('/',$_SERVER['REQUEST_URI']);
require_once(DOCUMENT_ROOT.'/site-admin/pages/delete.php');
require_once(DOCUMENT_ROOT.'/site-admin/pages/update.php');
?>

<?php 
if($url_value[2]=='country')
{?>
	<script type="text/javascript">
	$(document).ready(function(){
	$(".toggleul_4").slideToggle();
	document.getElementById("left_menubutton_4").src = "<?php echo DOCROOT; ?>site-admin/images/minus_but.png";
	});
	</script>
<?php
}
else if($url_value[2]=='city')
{
?>
	<script type="text/javascript">
	$(document).ready(function(){
	$(".toggleul_4").slideToggle();
	document.getElementById("left_menubutton_4").src = "<?php echo DOCROOT; ?>site-admin/images/minus_but.png";
	});
	</script>
<?php
}
else if($url_value[2]=='category')
{
?>
	<script type="text/javascript">
	$(document).ready(function(){
	$(".toggleul_4").slideToggle();
	document.getElementById("left_menubutton_4").src = "<?php echo DOCROOT; ?>site-admin/images/minus_but.png";
	});
	</script>
<?php
}
else if($url_value[2]=='pages')
{
	?>
	<script type="text/javascript">
	$(document).ready(function(){ 
	$(".toggleul_4").slideToggle(); 
	document.getElementById("left_menubutton_4").src = "<?php echo DOCROOT; ?>site-admin/images/minus_but.png"; 
	});
	</script>

	<?php 	
}

?>

<script type="text/javascript">
function deletecountry(id)
{	
	var sure=confirm("Are you sure want to delete this Country and its Cities?");
	if(sure)
	{
		window.location='<?php echo DOCROOT; ?>site-admin/pages/delete.php?countryid='+id;
	}

}
function deletecity(id)
{	
	var sure=confirm("Are you sure want to delete this City?");
	if(sure)
	{
		window.location='<?php echo DOCROOT; ?>site-admin/pages/delete.php?cityid='+id;
	}

}

function deletecategory(id)
{	
	var sure=confirm("Are you sure want to delete this Category?");
	if(sure)
	{
		window.location='<?php echo DOCROOT; ?>site-admin/pages/delete.php?categoryid='+id;
	}

}
//delete page
function deletepage(id)
{	
	var sure=confirm("Are you sure want to delete it?");
	if(sure)
	{
		window.location='<?php echo DOCROOT; ?>site-admin/pages/delete.php?page_id='+id;
	}

}

//delete subscriber
function delete_subscriber(id)
{
	var sure=confirm("Are you sure want to delete it?");
	if(sure)
	{
		window.location='<?php echo DOCROOT; ?>site-admin/pages/delete.php?subscriber_id='+id;
	}
}

//delete mobile subscriber
function delete_mobile_subscriber(id)
{
	var sure=confirm("Are you sure want to delete it?");
	if(sure)
	{
		window.location='<?php echo DOCROOT; ?>site-admin/pages/delete.php?mobile_subscriber_id='+id;
	}
}


function updatecountry(status,id)
{
		window.location='<?php echo DOCROOT; ?>site-admin/pages/update.php?countryid='+id+'&status='+status;
}
function updatecity(status,id)
{	
		window.location='<?php echo DOCROOT; ?>site-admin/pages/update.php?cityid='+id+'&status='+status;
}
function updatecategory(status,id)
{	
		window.location='<?php echo DOCROOT; ?>site-admin/pages/update.php?categoryid='+id+'&status='+status;
}
</script>


<span style="color:red" id="reportstatus"></span>

<div class="deals_desc1">
<?php 
	echo manage($url_arr[2]); 
?>
</div>
