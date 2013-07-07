<?php 
/********************************************
 * @Created on March, 2011 * @Package: Ndotdeals unlimited v2.2
 * @Author: NDOT
 * @URL : http://www.NDOT.in
 ********************************************/
 
?>
<script type="text/javascript">
function changelang(lang)
{
	window.location = '<?php echo DOCROOT; ?>system/plugins/change_language.php?lang='+lang;
}

var win2;
function fbconnect1(docroot){

  win2 = window.open(docroot+'facebook-connect.html',null,'width=650,location=0,status=0,height=400');
  checkChild();  
    
}

function checkChild1() {
	  if (win2.closed) {
		window.location.reload(true);
	  } else setTimeout("checkChild1()",1);
}


</script>
<script type="text/javascript"> 
function searchfield_focus(obj)
{
obj.style.color=""
obj.style.fontStyle=""
if (obj.value=="Search w3schools.com")
	{
	obj.value=""
	}
}
 
function show_header(n)
{
document.getElementById('headerdiv1').style.display='none';
document.getElementById('headerdiv2').style.display='none';
document.getElementById('headerdiv3').style.display='none';
document.getElementById('arrowraquo1').style.background='';
document.getElementById('arrowraquo2').style.background='';
document.getElementById('arrowraquo3').style.background='';
document.getElementById('arrowraquo1').style.color='#333333';
document.getElementById('arrowraquo2').style.color='#333333';
document.getElementById('arrowraquo3').style.color='#333333';
document.getElementById('arrowhr1').style.background='#d4d4d4';
document.getElementById('arrowhr2').style.background='#d4d4d4';
document.getElementById('arrowhr3').style.background='#d4d4d4';
document.getElementById('arrowraquo' + n).style.background='#ff0000';
document.getElementById('arrowraquo'+ n).style.color='#ffffff';
document.getElementById('arrowhr' + n).style.background='#ff4800';
document.getElementById('headerdiv' + n).style.display='block';
}

</script>
<?php

if($url_arr[1] == 'today-deals.html')
{
        $today_deals = 'active';
}
else if($url_arr[1] == 'hot-deals.html')
{
        $hot_deals = 'active';
}
else if($url_arr[1] == 'past-deals.html' || $url_arr[2] == 'past')
{
        $past_deals = 'active';
}
else if($url_arr[1] == 'how-it-works.html')
{
        $how_it_works = 'active';
}
else if($url_arr[1] == 'contactus.html')
{
        $contactus = 'active';
}
else
{
        $home = 'active';
}
?>

<div class="header_image">
  <div class="header_top">
    <div class="headtop_left">
      <div class="header_middle fr ">
        <div class="fl">
          <div class="city_left"></div>
          <div class="city_mid">
            <?php //view more cities
        require_once(DOCUMENT_ROOT.'/'.$root_sidebar_path.'city/citylist.php'); ?>
          </div>
          <div class="city_right"></div>
        </div>
      </div>
    </div>
    <div class="headtop_right">
      <div class="header_right fr">
        <div class="top_right fr" >
          <ul>
            <?php if($_SESSION["userrole"]==1 || $_SESSION["userrole"]==2 || $_SESSION["userrole"]==3) {?>
            <li>
              <div class="sign_left"></div>
              <div class="sign_mid"> <a href="<?php echo DOCROOT;?>admin/profile/" title="<?php echo ucfirst($_SESSION["username"]);?>"> <?php echo ucfirst($_SESSION["username"]);?></a> </div>
              <div class="sign_right"></div>
            </li>
            <li>
              <div class="sign_left"></div>
              <div class="sign_mid"> <a href="<?php echo DOCROOT;?>admin/logout/" title="Logout"><?php echo $language["logout"]; ?></a> </div>
              <div class="sign_right"></div>
            </li>
            <?php 
            }
            else {
            if($_SESSION["userid"])
            {
            ?>
            <li>
              <div class="sign_left"></div>
              <div class="sign_mid"> <a href="<?php echo DOCROOT;?>my-coupons.html" title="<?php echo ucfirst($_SESSION["username"]);?>"><?php echo $language["mysavings"]; ?> (<?php echo CURRENCY.$_SESSION["savedamt"]; ?>)</a> </div>
              <div class="sign_right"></div>
            </li>
            <li>
              <div class="sign_left"></div>
              <div class="sign_mid"> <a href="<?php echo DOCROOT;?>profile.html" title="<?php echo ucfirst($_SESSION["username"]);?>"> <?php echo ucfirst($_SESSION["username"]);?></a> </div>
              <div class="sign_right"></div>
            </li>
            <li>
              <div class="sign_left"></div>
              <div class="sign_mid"> <a href="<?php echo DOCROOT;?>logout.html" title="<?php echo $language["logout"]; ?>"><?php echo $language["logout"]; ?></a> </div>
              <div class="sign_right"></div>
            </li>
            <?php 
            }
            else
            {
            ?>
            <li>
              <div class="sign_left"></div>
              <div class="sign_mid"><a href="<?php echo DOCROOT;?>login.html" title="<?php echo $language["signin"]; ?>"><?php echo $language["signin"]; ?></a></div>
              <div class="sign_right"></div>
            </li>
            <li>
              <div class="sign_left"></div>
              <div class="sign_mid"> <a href="<?php echo DOCROOT;?>registration.html" title="<?php echo $language["signup"]; ?>"><?php echo $language["signup"]; ?></a> </div>
              <div class="sign_right"></div>
            </li>
            <?php } 
        } ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="middle_head">
    <div class="header_left"> <a href="<?php echo DOCROOT;?>" title="<?php echo SITE_NAME; ?>  "> <img class="fl" src="<?php echo DOCROOT;?>themes/<?php echo CURRENT_THEME; ?>/images/logo.png" /> </a> </div>
    <div class="header_mid">
      <!--<p><span class="span1">Our Daily Deal in : </span> <span class="span"><?php echo $_SESSION['defaultcityname'];?></span></p> -->
    </div>
    <?php 
//if($file_name!='edit'){ ?>
    <script type="text/javascript">
$('input[type="text"]').each(function(){
 
    this.value = $(this).attr('title');
    $(this).addClass('text-label');
 
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
  </div>
  <div class="header_menu fl clr">
    <ul>
      <li  class="<?php if($home) echo 'men_act';?> ">
        <div class="menu_left"></div>
        <div class="menu_middle"> <a href="<?php echo DOCROOT;?>" title="<?php echo strtoupper($language['home']); ?>"><?php echo strtoupper($language["today"]); ?></a> </div>
        <div class="menu_right"></div>
        <div class="<?php  if($home){ echo '' ; } else { echo 'bg_downarrow'; } ?>"> </div>
      </li>
      <li  class="<?php if($hot_deals) echo 'men_act';?> ">
        <div class="menu_left"></div>
        <div class="menu_middle"> <a href="<?php echo DOCROOT;?>hot-deals.html" title="<?php echo strtoupper($language['hot']); ?>" ><?php echo strtoupper($language["hot"]); ?></a> </div>
        <div class="menu_right"></div>
        <div class="<?php  if($hot_deals){ echo 'bottom_active' ; } else { echo 'bg_downarrow'; } ?>"> </div>
      </li>
      <li  class="<?php if($past_deals) echo 'men_act';?> ">
        <div class="menu_left"></div>
        <div class="menu_middle"> <a href="<?php echo DOCROOT;?>past-deals.html" title="<?php echo strtoupper($language['past_deals']); ?>" ><?php echo strtoupper($language["past_deals"]); ?></a> </div>
        <div class="menu_right"></div>
        <div class="<?php  if($past_deals){ echo 'bottom_active' ; } else { echo 'bg_downarrow'; } ?>"> </div>
      </li>
      <li  class="<?php if($how_it_works) echo 'men_act';?> ">
        <div class="menu_left"></div>
        <div class="menu_middle"> <a href="<?php echo DOCROOT;?>how-it-works.html" title="<?php echo strtoupper($language['how_it_works']); ?>"><?php echo strtoupper($language["how_it_works"]); ?></a> </div>
        <div class="menu_right"></div>
        <div class="<?php  if($how_it_works){ echo 'bottom_active' ; } else { echo 'bg_downarrow'; } ?>"> </div>
      </li>
      <li  class="<?php if($contactus) echo 'men_act';?> ">
        <div class="menu_left"></div>
        <div class="menu_middle"> <a href="<?php echo DOCROOT;?>contactus.html" title="<?php echo strtoupper($language['contact_us']); ?>" > <?php echo strtoupper($language["contact_us"]); ?></a> </div>
        <div class="menu_right"></div>
        <div class="<?php  if($contactus){ echo 'bottom_active' ; } else { echo 'bg_downarrow'; } ?>"> </div>
      </li>
    </ul>
  </div>
</div>
