<?php
/******************************************
* @Created on March, 2011 * @Package: Ndotdeals unlimited v2.2
* @Author: NDOT
* @URL : http://www.NDOT.in
********************************************/

$file_name_list = array("login","forgot-password","registration","profile","edit","change-password","referral-list","my-coupons","contactus","users","orderdetails","error");

if(in_array($file_name,$file_name_list)){

	include('template_4.php');

}else{
?>

<div class="content">
  <!-- content left start -->
  <div class="content_left">
    <div class="left1 fr">
      <?php 
                        if(file_exists($left)){
                            include($left);
                        }else{
                            include($left_default);
                        }
                         ?>
    </div>
    <!-- content left end -->
    <!-- content right start -->
  </div>
  <div class="content_right fl mt10">
    <?php include($right); ?>
  </div>
  <!-- content right end -->
</div>
<?php 
	}
?>
