<?php 
/********************************************
 * @Created on March, 2011 * @Package: Ndotdeals unlimited v2.2
 * @Author: NDOT
 * @URL : http://www.NDOT.in
 ********************************************/
?>
<link href="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/css/tabs.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/scripts/tabs.js" type="text/javascript"></script>
<link href="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/css/subscribe.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/css/invite.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/scripts/jquery-ui.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/css/slider.css" type="text/css" media="screen" />
<script src="<?php echo $docroot; ?>themes/<?php echo CURRENT_THEME; ?>/scripts/loopedslider.js" type="text/javascript"></script>
<?php 

$url = $_SERVER['REQUEST_URI'];
$last_uri = end(explode('/',$url)); 
$last_uri = current(explode('.',$last_uri)); 
$last_uri = explode('_',$last_uri);

//select the deal
$query = "select * from coupons_category where category_id = '$last_uri[1]' ";
$result = mysql_query($query);

if(mysql_num_rows($result) == 1)
{
	while($row = mysql_fetch_array($result))
	{

	?>
<div class="bread_com mt10">
  <div class="bread_top"></div>
  <div class="bread_mid">
    <ul>
      <li><a href="/" title="Home"><?php echo $language["home"]; ?> </a></li>
      <li><span class="right_arrow"></span></li>
      <li><a href="javascript:;" title="<?php echo ucfirst($row["category_name"]);?>"> <?php echo ucfirst($row["category_name"]);?></a></li>
    </ul>
  </div>
  <div class="bread_bottom"></div>
</div>
<?php /*?>        <h1><?php echo $page_title;?></h1>
<?php */?>
<?php 
	//get the hot deals by category
	getcoupons('C',$row["category_id"],$default_city_id);
	}
}
?>
