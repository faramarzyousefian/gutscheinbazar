<?php 
/******************************************** * @Created on June, 2011
* @Package: ndotdeals 3.0 (Opensource groupon clone) 
* @Author: NDOT
 * @URL : http://www.NDOT.in
 ********************************************/
?>
<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/system/includes/library.inc.php');
if(isset($_SESSION['defaultcityId']))
{
	$month = 2592000 + time();
	setcookie("defaultcityId", "");	
	setcookie("defaultcityId", $_SESSION['defaultcityId'], $month);	

}else
{
	if(isset($_COOKIE['defaultcityId']))
	{
	$_SESSION['defaultcityId'] = $_COOKIE['defaultcityId'];
	}
}
?>
<?php 
$reqUrl = $_SERVER["REQUEST_URI"];
//remove first slash from url
$docrootUrl = substr($reqUrl,1);
?>


<script type="text/javascript">
function citySelect(id,city_name,city_url)
{
	window.location='<?php echo $docroot.$root_sidebar_path; ?>city/setcity.php?cityid='+id+'&cityname='+city_name+'&city_url='+city_url;
}
</script>

<?php
$queryString = "select * from coupons_cities where status='A' order by cityname asc";
$resultSet = mysql_query($queryString);
?>
<div class="fl ml20 mt5">
<a href="javascript:;" onclick="$('#citylist').toggle('slow');" class="city_li">

<?php
if(!empty($_SESSION['defaultcityId']))
{
?>

        
                <?php $result = get_cityname();
                        if(mysql_num_rows($result) > 0)
                        {
                                while($row = mysql_fetch_array($result))
                                {
					$_SESSION['cityname'] = ucfirst(html_entity_decode($row['cityname'], ENT_QUOTES));
					?>
					<span title="<?php echo $language['visit_more']; ?>" class="city_link">
	                                        <?php echo ucfirst(html_entity_decode($row['cityname'], ENT_QUOTES)); ?>
				        </span>
			<?php
                                }
                        }else{
			        ?>
				<span title="<?php echo $language['cities']; ?>" class="fontbold color999"><?php echo $language['cities']; ?></span>
			<?php
			}
}
else
{
?>
        <span title="<?php echo $language['cities']; ?>" class="fontbold color999"><?php echo $language['cities']; ?></span>
<?php
}?>

<img title="<?php echo $language['visit_more']; ?>" alt="<?php echo $language['visit_more']; ?>" src="<?php echo DOCROOT.'themes/'.$theme_name; ?>/images/dnarrow.png" style="border:0px; vertical-align:middle;" />
</a>
</div>
<div class="fl mt15 ml10">
						
                               <select name="language" onchange="javascript:changelang(this.value);" class="mr5">
									<?php foreach($lang_list as $key => $value) { ?>
                                    <option value="<?php echo $key; ?>" <?php if($_SESSION["site_language"] == $key) { echo "selected"; }?>><?php echo $value['lang']; ?></option>
                                        <?php } ?>
                                </select>
                        </div>
<div class="mt10 pb10 citylist_inner" style="display:none;" id="citylist">

	<div class="inner_top1"></div>
    <div class="inner_center1">
	
		<ul class="country_list">

		<?php 
		 if(mysql_num_rows($resultSet)>0)
		 {

			    while( $row = mysql_fetch_array($resultSet))
			    {
			     ?>
				    <li class="fl"><a href="javascript:;" title="<?php echo ucfirst(html_entity_decode($row['cityname'], ENT_QUOTES)); ?>" onclick="javascript:citySelect('<?php echo $row['cityid']; ?>','<?php echo html_entity_decode($row['cityname'], ENT_QUOTES); ?>','<?php echo html_entity_decode($row['city_url'], ENT_QUOTES); ?>');" ><?php echo ucfirst(html_entity_decode($row['cityname'], ENT_QUOTES)); ?></a></li>
	
			   <?php 
	
			    }
			    
		}	    
		?>

		</ul>
	
	</div>
	<div class="inner_bottom1"></div>
						
</div>


