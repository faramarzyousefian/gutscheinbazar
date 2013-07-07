<?php session_start();
is_login(DOCROOT."admin-login/"); //checking whether admin logged in or not.
?>

<?php
$type = $url_arr[3];
$type = explode('?',$type);
$url_arr[3] = $type = $type[0];

?>

<script type="text/javascript">
$(document).ready(function(){
$(".toggleul_1005").slideToggle();
document.getElementById("left_menubutton_1005").src = "<?php echo DOCROOT; ?>site-admin/images/minus_but.png";
});
</script>

<script type="text/javascript">
/* validation */
$(document).ready(function(){ $("#fund_request").validate();});
</script>

<script type="text/javascript">
function docapture(id,refid)
{
		window.location='<?php echo DOCROOT; ?>site-admin/pages/docapture.php?id='+id+'&refid='+refid;
}
</script>

<?php
	if($_GET)
	{
		$url = $_SERVER['REQUEST_URI'];
		$arr = explode('=',$url); //print_r($arr);
		$arr2 = explode('?',$url); //print_r($arr2);
		$value = substr($arr2[1],0,5);
			if(!empty($arr[1]) && $value!='page=') {
				$val = explode('&page',$arr[1]); 
				$searchkey_txt = $val[0] = trim(str_replace('+',' ',$val[0]));
				$searchkey = htmlentities($val[0], ENT_QUOTES);
			}
	}
?>

<script type="text/javascript">
/* validation */
$(document).ready(function(){ $("#usrsearch").validate();});
</script>

<div  class="fl ml10">
	<form method="get" name="usrsearch" id="usrsearch" action="" class="fl clr">
		<table>
		<tr>
		<td style="padding-top:8px;" valign="top"><?php echo $admin_language['search']; ?></td>
		<td>
		<input type="text" name="searchkey" class="required" title="<?php echo $admin_language['enteryoursearchkey']; ?>" value="<?php if(!empty($searchkey_txt)) { echo urldecode($searchkey_txt); } ?>" />
		</td>
		</tr>
		<tr>
		<td></td>
		<td><input type="submit" value="<?php echo $admin_language['submit']; ?>"/></td>
		</tr>
		</table>
	</form>
</div>

<div class="fl trans clr">
<!-- list of fund -->
<?php
if($url_arr[3]=='all')
{
	$query = "select TRANSACTIONTYPE,ID,DATE_FORMAT(TIMESTAMP, '%b %d %Y %H:%i:%s') as TIMESTAMP,coupons_users.userid,COUPONID,AMT,username,coupon_name,coupons_users.firstname,coupons_coupons.coupon_id as cid, CAPTURED,TRANSACTIONID,TYPE,PAYMENTTYPE,CAPTURED_TRANSACTION_ID,CAPTURED_ACK from transaction_details left join coupons_coupons on transaction_details.COUPONID = coupons_coupons.coupon_id left join coupons_users on transaction_details.USERID = coupons_users.userid  ";

			if(!empty($searchkey)) {
				$query .= " where coupons_users.username like '%".$searchkey."%' or coupons_coupons.coupon_name like '%".$searchkey."%' or TRANSACTIONID like '%".$searchkey."%' or CAPTURED_TRANSACTION_ID like '%".$searchkey."%' "; }

			$query .= " order by TIMESTAMP desc";

}

	$pagination = new pagination();
	$pagination->createPaging($query,20);
	$resultSet = $pagination->resultpage;

	if(mysql_num_rows($resultSet)>0)
	{
	 ?>
			 <table cellpadding="7" cellspacing="0"  >
			 <tr><td colspan="8" align="right">
			 <a href="<?php echo DOCROOT;?>site-admin/pages/export.php?type=transaction&format=<?php echo $url_arr[3]; ?>&searchkey=<?php echo $searchkey; ?>" title="<?php echo $admin_language['exportall']; ?>"><?php echo $admin_language['exportall']; ?></a>
			 </td></tr>
			 <tr>
			 <th><?php echo $admin_language['date']; ?></th>
			 <th><?php echo $admin_language['user']; ?></th>
			 <th><?php echo $admin_language['description']; ?></th>
			 <th><?php echo $admin_language['amount']; ?>(<?php echo CURRENCY;?>)</th> 			
			 <th>Transaction Id</th>
			 <th>Invoice No</th>
			 <th>Transaction Type</th>
			 <th><?php echo $admin_language['status']; ?></th>
			 </tr>
	
	<?php 
		while($row = mysql_fetch_array($resultSet))
		{ 
			?>
			<tr>
			<td><?php echo $row["TIMESTAMP"];?></td>
			<td>
			<?php
			$user_name = explode('_',$row["username"]);
			if($user_name[0] == 'FB' || $user_name[0] == 'TW')
			{
			        echo $row["firstname"];
			}
			else
			{
			        echo $row["username"];
			}
			?>
			</td>
			<td><a href="<?php echo DOCROOT; ?>admin/deal_report_view/<?php echo $row['cid']; ?>"><?php echo ucfirst(html_entity_decode($row["coupon_name"], ENT_QUOTES));?></a></td>
			<td><?php echo round($row["AMT"], 2);?></td>

			<?php if($row["CAPTURED"] == 1) {
			if($row["TYPE"] == '2')
			{
			     ?>
			<td><?php echo $row["TRANSACTIONID"];?></td>
			<?php   
			}
			else
			{
			 ?>
			<td><?php echo $row["CAPTURED_TRANSACTION_ID"];?></td>
			<?php
			}
			}
			else
			{
			?>
			<td><?php echo $row["TRANSACTIONID"];?></td>
			<?php
			}
			?>

			<td><?php echo $row["ID"];?></td>
			<td><?php 
			if($row["TYPE"] == '1')
			{
				echo "Paypal"; 
			}
			?></td>
			<td><?php 
			if($row["CAPTURED"] == 1)
			{
				echo "Success";
			}
			else if(($row['CAPTURED_ACK'] == '') && ($row["CAPTURED"] == 0))
			{
				echo "Hold";
			}
			else
			{
				echo "Failed";
				?>
				<br />
				<a href="javascript:;" onclick="docapture('<?php echo $row["ID"];?>','<?php echo DOCROOT.substr($_SERVER["REQUEST_URI"],1); ?>');" title="Request Transaction">[ Request Transaction ]</a>
				<?php 
			}	
				?></td>
			</tr>
			<?php 
		} ?>
		
		<?php 
$query1 = "select (select SUM(AMT) as total_amount from transaction_details left join coupons_coupons on transaction_details.COUPONID = coupons_coupons.coupon_id left join coupons_users on transaction_details.USERID = coupons_users.userid where transaction_details.CAPTURED_ACK='' and transaction_details.CAPTURED='0') as hold_account, (select SUM(AMT) from transaction_details where transaction_details.CAPTURED='0') as failed_amount, SUM(AMT) as total_amount from transaction_details left join coupons_coupons on transaction_details.COUPONID = coupons_coupons.coupon_id left join coupons_users on transaction_details.USERID = coupons_users.userid ";


		if(!empty($searchkey)) {
			$query1 .= "where (coupons_users.username like '%".$searchkey."%' or coupons_coupons.coupon_name like '%".$searchkey."%')"; }
$result1 = mysql_query($query1);

if($url_arr[3]=='all')
{
		$result = mysql_query($query1);
}
	?>
		
		<?php if($url_arr[3]=='all') { ?>
			<tr>
			<td colspan="3" align="right"><?php echo $admin_language['totaltransactionamount']; ?></td>
			<td align="left"><?php 	$total = CURRENCY.round(mysql_result($result1,0,2), 2);
				echo $total;
			 ?></td></tr>
		<?php } ?>
			</table>
		
		<table border="0" width="400" align="center">
			<tr>
			<td align="center">
			<?php echo $pagination->displayPaging(); ?>
			</td>
			</tr>
		</table>
		
		<?php 
	}
	else
	{
		?>
			<div class="no_data">
			<?php echo $admin_language['notransactionavailable']; ?>
			</div>
		<?php 
	}
	?>
	
	</div>
