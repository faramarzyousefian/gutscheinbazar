<?php
/****************************************** 
* @Created on June, 2011
* @Package: ndotdeals 3.0 (Opensource groupon clone) 
* @Author: NDOT
* @URL : http://www.NDOT.in
********************************************/
?>

<?php
if(is_numeric($deal_id))
{
	//select the deal
		$result = $theme_q->deals_info_q($default_city_id, $deal_id ); // Changed on April 21 -system/includes/functions_theme.php
        $deal_type = $language['main_hot'];
	
}

include($root_dir_path."deals_info_content.php"); //include the remaining content
?>

