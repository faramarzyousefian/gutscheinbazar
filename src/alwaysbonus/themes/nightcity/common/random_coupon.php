<script type="text/javascript" src="<?php echo DOCROOT;?>themes/<?php echo CURRENT_THEME;?>/scripts/easySlider1.7.js"></script>
<script type="text/javascript">
		$(document).ready(function(){	
			$("#slider").easySlider({
				/*auto: true, 
				continuous: true,
				numeric: true*/
			});
		});	
	</script>
<link href="<?php echo DOCROOT;?>themes/<?php echo CURRENT_THEME;?>/css/screen.css" rel="stylesheet" type="text/css" media="screen" />
<?php //random coopons
$randomCoopon = get_random_coopon($default_city_id); 
?>
<div class="great_deals mb20 ">
<div class="great_top">
  <h1><?php echo $language['featured']; ?></h1>
</div>
<div class="great_center">
  <?php
             	 if(mysql_num_rows($randomCoopon)>1)
		 {
			?>
  <div id="container" style="overflow:hidden;">
    <div id="content bg_none" style="overflow:hidden;">
      <div id="slider" style="overflow:hidden;">
        <ul style="overflow:hidden;">
          <?php
			while($row = mysql_fetch_array($randomCoopon))
			{?>
          <li>
            <div class="feature_deal_text">
              <?php 
				$cooponamount = $row["coupon_value"]; 
				?>
              <strong class="fontb18 color019FE2 ml15">
              <?php 
								if(ctype_digit($cooponamount)) { 
										echo '<strong>'.CURRENCY.$cooponamount.'.00</strong>';							} 
							
								else { 

									$cooponamount = number_format($cooponamount, 2,',','');
									$cooponamount = explode(',',$cooponamount);
									echo '<strong>'.CURRENCY.$cooponamount[0].'.'.$cooponamount[1].'</strong>';

								}							
								?>
              </strong> (<?php echo $language["value"];?> <span class="font20 color019FE2"> <?php echo CURRENCY.$row['coupon_realvalue'];?> </span>)
              <p> <a onclick="javascript:dealview('<?php echo DOCROOT;?>','<?php echo html_entity_decode($row['deal_url']);?>','<?php echo $row['coupon_id'];?>')">
                <?php 
					  if(strlen(html_entity_decode($row['coupon_name'], ENT_QUOTES))>30)
					  {
						  echo substr(ucfirst(html_entity_decode($row['coupon_name'], ENT_QUOTES)),0,30).'...'; 
					  }
					  else
					  {
					  						  echo ucfirst(html_entity_decode($row['coupon_name'], ENT_QUOTES)); 
					  } ?>
              </p>
              </a> </div>
            <?php if(file_exists($row["coupon_image"]))
                                   { 
                                   ?>
            <img src="<?php echo DOCROOT; ?>system/plugins/imaging.php?width=200&height=200&cropratio=1:1&noimg=100&image=<?php echo urlencode(DOCROOT.$row['coupon_image']); ?>" width="276" height="176" alt="#" class="fl"/>
            <?php }
				else
				{?>
            <img src="<?php echo DOCROOT; ?>themes/<?php echo CURRENT_THEME; ?>/images/no_image.jpg" width="276" height="176" alt="#" class="fl"/>
            <?php } ?>
            <div class="background">
              <div class="random_bottom">
                <div class="random_left"> <span class="coupon"><?php echo CURRENCY.$row['coupon_value'];?></span>
                  <div class="discount_price_desc"> <span class="fl"><?php echo $language['discount']; ?>:</span>
                    <p class="fl">
                      <?php $discount = get_discount_value($row["coupon_realvalue"],$row["coupon_value"]); echo round($discount); ?>
                      %</p>
                  </div>
                </div>
                <div class="random_right"> <a onclick="javascript:buydeal('<?php echo DOCROOT;?>','<?php echo $row["coupon_id"];?>')" class="buy_img mt8" title="Buy"></a> </div>
              </div>
            </div>
            </p>
          </li>
          <?php }?>
        </ul>
      </div>
      <div class="fl" id="slider_nav"> <span id="previous_slider"> <a href="javascript:void(0);" title="Previous"  class="pre_link fl"></a> </span> <span id="next_slider"> <a href="javascript:void(0);" title="Next" class="next_link fl"></a> </span> </div>
    </div>
  </div>
  <?php        
			
		}
     		else
		{ ?>
  <div class="no_data1" style="color:#000;"> <?php echo $language['no_deals_avail']; ?> </div>
  <?php 
		}
		?>
</div>
<div class="great_bottom fl clr"></div>
<script type='text/javascript'> 
/*$('#next_slider').click( function (){
if(options.continuous){
				$("ul", obj).prepend($("ul li:last-child", obj).clone().css("margin-left","-"+ w +"px"));
				$("ul", obj).append($("ul li:nth-child(2)", obj).clone());
				$("ul", obj).css('width',(s+1)*w);
				$("ul", obj).css('height','214px');
                          
			};
});*/
function buydeal(url,id)
{
 if(id!='')
  {
   window.location=url+'purchase.html?cid='+id;
  }

}
</script>
<script>
function dealview(url,dealurl,dealid)
{
 if(dealid!='')
  {
   window.location=url+'deals/'+dealurl+'_'+dealid+'.html';
  }

}
</script>
</script>
