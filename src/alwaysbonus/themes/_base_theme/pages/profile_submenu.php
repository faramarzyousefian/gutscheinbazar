<?php 
if($_SESSION["userrole"]!=4){
	url_redirect(DOCROOT);
}

$uri = end(explode("/",$_SERVER['REQUEST_URI']));
$my_coupons = $profile = $edit = $password = $referral_list = $api_client = $fund_request ='';
if($uri == "my-coupons.html")
{
	$my_coupons = "profile_vscurr";
}
else if($uri == "edit.html")
{
	$edit = "profile_vscurr";
}
else if($uri == "change-password.html")
{
	$password = "profile_vscurr";
}
else if($uri == "fund-request.html")
{
	$fund_request = "profile_vscurr";
}
else
{
	$profile = "profile_vscurr";
}
?>

<div class="profile_submenu">
	<ul class="profile_vsubmenu">
		<li><a href="<?php echo DOCROOT;?>my-coupons.html" title="<?php echo $language['mycoupons']; ?>" class="<?php echo $my_coupons; ?>"><?php echo $language['mycoupons']; ?></a></li>
		<li><a href="<?php echo DOCROOT;?>profile.html" title="<?php echo $language['profile']; ?>" class="<?php echo $profile; ?>"><?php echo $language['profile']; ?></a></li>
		<li><a href="<?php echo DOCROOT;?>edit.html" title="<?php echo $language['edit']; ?>" class="<?php echo $edit; ?>"><?php echo $language['edit']; ?></a></li>
		<li><a href="<?php echo DOCROOT;?>change-password.html" title="<?php echo $language['change_password']; ?>" class="<?php echo $password; ?>" ><?php echo $language['change_password']; ?></a></li>
	</ul>
</div>
