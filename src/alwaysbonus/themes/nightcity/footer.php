<?php 
/********************************************
 * @Created on March, 2011 * @Package: Ndotdeals unlimited v2.2
 * @Author: NDOT
 * @URL : http://www.NDOT.in
 ********************************************/
 $lang = $_SESSION["site_language"];
if($lang)
{
        include(DOCUMENT_ROOT."/system/language/".$lang.".php");
}
else
{
        include(DOCUMENT_ROOT."/system/language/en.php");
}
?>

<div class="footer_outer fl clr">
  <div class="footer_top_outer fl clr">
    <div class="footer_container">
      <div class="footer_top fl clr">
        <div class="footer_menu  fl pl10 ">
          <h5><?php echo $language["help"]; ?></h5>
          <ul>
            <li><a href="<?php echo DOCROOT;?>how-it-works.html" title="<?php echo strtoupper($language['how_it_works']); ?>" ><?php echo strtoupper($language["how_it_works"]); ?></a></li>
            <li><a href="<?php echo DOCROOT;?>about-us.html" title="<?php echo $language["about_us"]; ?>" ><?php echo $language["about_us"]; ?></a></li>
            <li><a href="<?php echo DOCROOT;?>faq.html" title="<?php echo $language["faq"]; ?>" ><?php echo $language["faq"]; ?></a></li>
            <li><a href="<?php echo DOCROOT;?>contactus.html" title="<?php echo $language["contact_us"]; ?>" ><?php echo $language["contact_us"]; ?></a></li>
          </ul>
        </div>
        <div class="footer_menu  fl">
          <h5><?php echo $language["company"]; ?> </h5>
          <ul>
            <li><a href="<?php echo DOCROOT;?>" title="<?php echo $language["About_Ndot"]; ?>" ><?php echo $language["About_Ndot"]; ?></a></li>
            <li><a href="<?php echo DOCROOT;?>" title="<?php echo $language["Terms_service"]; ?>" ><?php echo $language["Terms_service"]; ?></a></li>
            <li><a href="<?php echo DOCROOT;?>privacy-policy.html" title="<?php echo $language["Privacy"]; ?>" ><?php echo $language["Privacy"]; ?></a></li>
          </ul>
        </div>
        <div class="footer_menu  fl bnone">
          <h5><?php echo $language["follow"]; ?> </h5>
          <ul>
            <li> <a href="<?php echo FACEBOOK_FOLLOW; ?>" title="facebook" target="_blank" > <span class="valign_top">Facebook</span> </a> </li>
            <li> <a href="<?php echo TWITTER_FOLLOW; ?>" title="twitter" target="_blank" > <span class="valign_top">Twitter</span> </a> </li>
            <!--<li><a href="<?php echo DOCROOT;?>" title="<?php echo $language["Subscibe_to"]; ?>" ><?php echo $language["Subscibe_to"]; ?></a></li>-->
          </ul>
        </div>
        <div class="footer_menu4 fl pl10 mt10">
          <div class="follow">
            <p>Follow Us:</p>
            <ul>
              <li> <a href="<?php echo TWITTER_FOLLOW; ?>" title="twitter" target="_blank"> <img src="<?php echo DOCROOT; ?>themes/<?php echo CURRENT_THEME;?>/images/twitter.png" /> </a> </li>
              <li> <a href="<?php echo LINKEDIN_FOLLOW; ?>" title="linked_in" target="_blank"> <img src="<?php echo DOCROOT; ?>themes/<?php echo CURRENT_THEME;?>/images/linked_in.png" /> </a> </li>
              <li> <a href="<?php echo DOCROOT;?>rss.php" title="social" target="_blank"> <img src="<?php echo DOCROOT; ?>themes/<?php echo CURRENT_THEME;?>/images/social.png" /> </a> </li>
              <li> <a href="<?php echo FACEBOOK_FOLLOW; ?>" title="facebook" target="_blank"> <img src="<?php echo DOCROOT; ?>themes/<?php echo CURRENT_THEME;?>/images/facebook.png" /> </a> </li>
            </ul>
          </div>
          <div class="ndot">
            <div class="address fl">
              <h5>NDOT DEALS <span style="font:normal 12px arial;">Network</span></h5>
              <p>Total dollars saved <span style="font:bold 16px arial;color:#019FE2;"><?php echo CURRENCY;?><?php echo $saved_amount;?></span></p>
              <p>Total items sold <span style="font:bold 16px arial;color:#019FE2;"><?php echo $purchased_deals; ?></span></p>
            </div>
          </div>
        </div>
        <div class="footer_menu_bottom">
          <div class="footer_menu_bottom_left">
            <p> &copy; 2011. <?php echo $language['all_rights_reserved']; ?> </p>
          </div>
          <div class="footer_menu_bottom_right"> <a href="<?php echo DOCROOT;?>">
            <!-- <img src="/themes/<?php echo CURRENT_THEME;?>/images/footer_logo.png" class="fr"/>-->
            </a> </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer_bottom_outer fl clr"> </div>
</div>
<?php //if($file_name!='contactus'){ ?>
<script type="text/javascript">
$('input[type="text"]').each(function(){
   /*  var c =this.attr("class");
     alert(c);*/
    var v =this.id;
    var form = this.form;
   // var form_name=$(form).attr('name');
     var form_name=form.id;
    if((v!='share_link') && (form_name !='edit_profile' && form_name !='edit_register')){ 
    this.value = $(this).attr('title');
    $(this).addClass('text-label');
     }
    $(this).focus(function(){
        if(this.value == $(this).attr('title')) {
            this.value = '';
            $(this).removeClass('text-label');
        }
    });
 
    $(this).blur(function(){
        if(this.value == '') {
            this.value = $(this).attr('title');
            $(this).addClass('text-label');
        }
    });
});
</script>
<?php //} ?>
