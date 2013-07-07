<?php
/****************************************** 
* @Created on June, 2011
* @Package: ndotdeals 3.0 (Opensource groupon clone) 
* @Author: NDOT
* @URL : http://www.NDOT.in
********************************************/
?>

<ul>
<li><a href="/" title="Home"><?php echo $language['home']; ?> </a></li>
<li><span class="right_arrow"></span></li>
<li><a href="javascript:;" title="Hot Deals"><?php echo $language['hot']; ?></a></li>    
</ul>

<h1><?php echo $page_title; ?></h1>

<?php
//get the hot deals
getcoupons('H','',$default_city_id);

?>
