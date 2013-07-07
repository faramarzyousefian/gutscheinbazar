<?php
               echo '<p style="width:640px;color:#666;text-align:center;padding-bottom:10px;" class="mb20">'.$language['note'].': UN -'.$language['user_yet_to_use'].' <br/> U - '.$language['user_used'].'<br/> '.$language['validityid'].' - '.$language['validityid_generated'].' <br/>'.$language['validated_date'].' - '.$language['coupon_validated_date'].'</p>';

                echo '<table cellpadding="5" cellspacing="5" class="fl clr borderr" style="width:640px; border:1px solid #C7C7C7;">';
                echo '<tr class="fwb bb" ><th class="bb">'.$language['coupon_name'].'</th><th class="bb">'.$language['purchase_date'].'</th><th class="bb">'.$language['expiration_date'].'</th><th class="bb">'.$language['status'].'</th><th class="bb">&nbsp;</th><th class="bb">&nbsp;</th></tr>';
                        
                while($noticia=mysql_fetch_array($resultSet))
                { 
                    echo '<tr><td style="border-bottom:1px solid #C7C7C7;padding:10px 0px 10px 3px;"><div style="width:250px;float:left;"><div class="fl" style="border:1px solid #DDDDDD;padding:2px;width:70px;">';
                    
                    if(file_exists($noticia["coupon_image"]) && $noticia['CAPTURED'] == 1)
                    {
                    ?>

<img src="<?php echo DOCROOT; ?>system/plugins/imaging.php?width=70&height=70&cropratio=1:1&noimg=100&image=<?php echo DOCROOT.$noticia["coupon_image"]; ?>" alt="<?php echo $noticia["coupon_name"];?>" title="<?php echo ucfirst(html_entity_decode($noticia['coupon_name'], ENT_QUOTES));?>" />
<?php
                    }
                    else
                    {
                    ?>
<img src="<?php echo DOCROOT; ?>system/plugins/imaging.php?width=70&height=70&cropratio=1:1&noimg=100&image=<?php echo DOCROOT; ?>themes/<?php echo CURRENT_THEME; ?>/images/no_image.jpg" alt="<?php echo $noticia["coupon_name"];?>" title="<?php echo ucfirst(html_entity_decode($noticia['coupon_name'], ENT_QUOTES));?>" />
<?php
                    }
                    echo '</div><div class="fl ml5" style="width:165px;">'.ucfirst(html_entity_decode($noticia['coupon_name'], ENT_QUOTES)).'</div></td><td valign="top" style="border-bottom:1px solid #C7C7C7;padding:10px 0px 10px 0px;">';
                    echo date('d/m/Y', strtotime($noticia['coupon_purchaseddate'])).'</td><td valign="top" style="border-bottom:1px solid #C7C7C7;padding:10px 0px 10px 0px;">';
                    echo date('d/m/Y', strtotime($noticia['coupon_expirydate'])).'</td><td valign="top" style="border-bottom:1px solid #C7C7C7;padding:10px 0px 10px 0px;">';
                    echo $noticia['coupons_userstatus'].'</td>';
                  if(!empty($noticia['coupon_validityid']))
                    {
                        echo '<td valign="top">'; 
                        ?>
<input type="submit" value="" onclick="window.location='<?php echo DOCROOT.'print/'.friendlyurl($noticia['coupon_name']).'_'.$noticia['couponid'].'_'.$noticia['coupon_purchaseid'].'.html'; ?>'" class="print_but" title="<?php echo $language['print_coupon']; ?>"/>
</td>
<td valign="top"><a href="/export/pdf/<?php echo friendlyurl(html_entity_decode($noticia['coupon_name'], ENT_QUOTES));?>_<?php echo $noticia['couponid'].'_'.$noticia['coupon_purchaseid']; ?>.html" title="pdf" class="fl mr5" style="color:#000;width:120px;">Download as PDF</a></td>
<?php
                    }
                    echo '</tr>';
                            }
    
                        echo '</table>';

                 
                echo '<table border="0" width="650" class="fl clr" align="center" cellpadding="10">';
		echo '<tr><td align="center"><div class="pagenation">';
		$pagination->displayPaging();
		echo '</div></td></tr>';
		echo '</table>';
                
?>
