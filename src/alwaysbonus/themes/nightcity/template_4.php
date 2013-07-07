<?php
/******************************************
* @Created on March, 2011 * @Package: Ndotdeals unlimited v2.2
* @Author: NDOT
* @URL : http://www.NDOT.in
********************************************/

$file_name_list = array("how-it-works","pages");

if(in_array($file_name,$file_name_list)){

	include('template_1.php');

}else{
?>

<div class="content">
  <div class="content_left mt10">
    <div class="left1 fr">
      <div class="inner_top"></div>
      <div class="inner_center">
        <?php  
                        if(file_exists($left)){
                            include($left);
                        }else{
                            include($left_default);
                        }
                         ?>
      </div>
      <div class="inner_bottom"></div>
    </div>
  </div>
  <div class="content_right fl mt10">
    <?php include($right); ?>
  </div>
</div>
<?php 
	}
?>
