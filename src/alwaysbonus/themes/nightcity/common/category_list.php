<div class="category mb15 ">
  <div class="great_top">
    <h1><?php echo $language['categories']; ?></h1>
  </div>
  <div class="cate_middle">
    <ul>
      <?php
        $sub_category  = get_subcategory(); 
         if(mysql_num_rows($sub_category)>0)
         {
                while($row = mysql_fetch_array($sub_category))
                { ?>
      <li><a class="cate_menu" href="<?php echo DOCROOT;?>deals/category/<?php echo html_entity_decode($row['category_url']); ?>_<?php echo $row['category_id']; ?>.html" title="<?php echo ucfirst(html_entity_decode($row['category_name'], ENT_QUOTES)); ?>" > <span style="margin-left:40px;"><?php echo ucfirst(html_entity_decode($row['category_name'], ENT_QUOTES)); ?> </span> </a> </li>
      <?php 	
                }
        }	
        ?>
    </ul>
  </div>
</div>
