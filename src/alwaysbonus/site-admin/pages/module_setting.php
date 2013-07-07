<?php
	is_login(DOCROOT."admin/login/"); //checking whether admin logged in or not.
?>

<script type="text/javascript">
$(document).ready(function(){
$("#morepaymodule").hide();
$(".toggleul_4").slideToggle();
document.getElementById("left_menubutton_4").src = "<?php echo DOCROOT; ?>site-admin/images/minus_but.png";
});

function showmore(){
	$("#morepaymodule").show();
}

function del(id){
	var conf = confirm("Are you sure want to remove the module permanently and never retrive if already done payment with this gateway!!!");
	if(conf){
		window.location = "<?php echo DOCROOT;?>admin/module/"+id;
	}
}
</script>

<?php
	if($_POST['submit'])
	{ 
		$id = $_POST["id"];
		$featured_deals = $_POST['featured_deals'];
		$newsletter = $_POST['newsletter'];
		$category = $_POST['category'];
		$fanpage = $_POST['fanpage'];
		$smtp = $_POST['smtp'];
		$mobile_subscribtion = $_POST['mobile_subscribtion'];

		$query = "update modules set featured_deals = '$featured_deals',newsletter = '$newsletter',category = '$category',fanpage = '$fanpage',mobile_subscribtion = '$mobile_subscribtion',smtp = '$smtp' where id='$id' ";
		mysql_query($query);
	
		set_response_mes(1,$admin_language['changesmodified']);
		url_redirect(DOCROOT."admin/module/");
	}
	
	//get the general site information
	$query1 = "SELECT * FROM modules LIMIT 0,1";
	$result_set = mysql_query($query1);

	if(mysql_num_rows($result_set))
	{
		$row = mysql_fetch_array($result_set);
	}
?>
  
        <form name="module_settings" id="module_settings" action="" enctype="multipart/form-data" method="post" class="ml10">

                  <input type="hidden" name="id" value="<?php echo $row["id"];?>" />
		 
		 
		  <fieldset class="field">         
        <legend class="legend"><?php echo $admin_language['general_module']; ?></legend>
		 <p>
              <label for="dummy0"><?php echo $admin_language['featureddeal']; ?></label><br>
			  <input type="radio" name="featured_deals" value="1" <?php if($row["featured_deals"] == 1) { ?> checked="checked" <?php } ?> /> <?php echo $admin_language['yes']; ?>
			  &nbsp;
			  <input type="radio" name="featured_deals" value="0" <?php if($row["featured_deals"] == 0) { ?> checked="checked" <?php } ?> /> <?php echo $admin_language['no']; ?>
			  


         </p>

		
		 <p>
              <label for="dummy0"><?php echo $admin_language['newsletter']; ?></label><br>
			  <input type="radio" name="newsletter" value="1" <?php if($row["newsletter"] == 1) { ?> checked="checked" <?php } ?> /> <?php echo $admin_language['yes']; ?>
			  &nbsp;
			  <input type="radio" name="newsletter" value="0" <?php if($row["newsletter"] == 0) { ?> checked="checked" <?php } ?> /><?php echo $admin_language['no']; ?>
			  


         </p>

			
		 <p>
              <label for="dummy0"><?php echo $admin_language['category']; ?></label><br>
			  <input type="radio" name="category" value="1" <?php if($row["category"] == 1) { ?> checked="checked" <?php } ?> /> <?php echo $admin_language['yes']; ?>
			  &nbsp;
			  <input type="radio" name="category" value="0" <?php if($row["category"] == 0) { ?> checked="checked" <?php } ?> /> <?php echo $admin_language['no']; ?>
			  


         </p>

			
		 <p>
              <label for="dummy0"><?php echo $admin_language['fanpage']; ?></label><br>
			  <input type="radio" name="fanpage" value="1" <?php if($row["fanpage"] == 1) { ?> checked="checked" <?php } ?> /> <?php echo $admin_language['yes']; ?>
			  &nbsp;
			  <input type="radio" name="fanpage" value="0" <?php if($row["fanpage"] == 0) { ?> checked="checked" <?php } ?> /> <?php echo $admin_language['no']; ?>
			  


         </p>



			
		 <p>
              <label for="dummy0"><?php echo $admin_language['smtp']; ?></label><br>
			  <input type="radio" name="smtp" value="1" <?php if($row["smtp"] == 1) { ?> checked="checked" <?php } ?> /> <?php echo $admin_language['yes']; ?>
			  &nbsp;
			  <input type="radio" name="smtp" value="0" <?php if($row["smtp"] == 0) { ?> checked="checked" <?php } ?> /> <?php echo $admin_language['no']; ?>
			  


         </p>
		
		 <p>
              <label for="dummy0"><?php echo $admin_language['mobilesubscribtion']; ?></label><br>
			  <input type="radio" name="mobile_subscribtion" value="1" <?php if($row["mobile_subscribtion"] == 1) { ?> checked="checked" <?php } ?> /> <?php echo $admin_language['yes']; ?>
			  &nbsp;
			  <input type="radio" name="mobile_subscribtion" value="0" <?php if($row["mobile_subscribtion"] == 0) { ?> checked="checked" <?php } ?> /> <?php echo $admin_language['no']; ?>
			  


         </p>
  </fieldset>
 	 		
	   		<div class="fl clr">
              <input style="margin-left:13px;" type="submit" name="submit" value="<?php echo $admin_language['submit']; ?>" class="button_c">
            </div>

        
        </form>
		


