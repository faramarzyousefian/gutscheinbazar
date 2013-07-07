<?php session_start();
is_login(DOCROOT."admin/login/"); //checking whether admin logged in or not.
?>

<div class="deals_desc1">
<?php 
$type = 'shops';
	echo report($type); 
?>
</div>
