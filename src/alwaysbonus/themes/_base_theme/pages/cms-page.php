<?php 
/******************************************** * @Created on June, 2011
* @Package: ndotdeals 3.0 (Opensource groupon clone) 
* @Author: NDOT
 * @URL : http://www.NDOT.in
 ********************************************/
?>
<ul>
<li><a href="/" title="<?php echo $language["home"]; ?>"><?php echo $language["home"]; ?> </a></li>
<li><span class="right_arrow"></span></li>
<li><a href="javascript:;" title="<?php echo ucfirst($page_title); ?>">
<?php echo ucfirst($page_title); ?>
</a></li>    
</ul>

<h1><?php echo ucfirst($page_title); ?></h1>
                        
<div class="work_bottom">
<p>
<?php echo nl2br(html_entity_decode($page_desc));?>
</p>

</div>
