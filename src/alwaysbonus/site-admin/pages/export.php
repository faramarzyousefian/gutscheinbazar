<?php ob_start(); ?>
<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/system/includes/library.inc.php');
$user_role = $_SESSION['userrole'];
$admin_lang = $_SESSION["site_admin_language"];

if($admin_lang)
{
        include(DOCUMENT_ROOT."/system/language/admin_".$admin_lang.".php");
}
else
{
        include(DOCUMENT_ROOT."/system/language/admin_en.php");
}
	//get the type
	$type = $_GET["type"];
	$i = 0;
	
	if($type == "transaction")
	{

		$division = $_GET["format"];
		$searchkey = $_GET["searchkey"];

		if($division=='all')
		{

			$query = "select ID,DATE_FORMAT(TIMESTAMP, '%b %d %Y %H:%i:%s') as TIMESTAMP,coupons_users.userid,COUPONID,AMT,username,CAPTURED_ACK,coupon_name,coupons_coupons.coupon_id as cid, CAPTURED from transaction_details left join coupons_coupons on transaction_details.COUPONID = coupons_coupons.coupon_id left join coupons_users on transaction_details.USERID = coupons_users.userid ";

					if(!empty($searchkey)) {
						$query .= " where coupons_users.username like '%".$searchkey."%' or coupons_coupons.coupon_name like '%".$searchkey."%'"; }

					$query .= " order by TIMESTAMP desc";

		}
			$result = mysql_query($query);
			$field = array('Id','Username','Deal name','User Id','Date','Deal Id','Amount','Status');
			$output = '<table>';
			
			if(count($field) > 0)
			{
					$output .= '<tr>';
					foreach($field as $row)
					{
							$output .= "<th>".$row."</th>";
							$i++;
					}
					$output .= "</tr><tr></tr>";
			}
			
			if (mysql_num_rows($result) > 0)
			{
	
					while($row = mysql_fetch_array($result)) 
					{
							$output .= '<tr>';
							$output .= "<td>".$row["ID"]."</td>";
							$output .= "<td>".$row["username"]."</td>";
							$output .= "<td>".$row["coupon_name"]."</td>";
							$output .= "<td>".$row["userid"]."</td>";
							$output .= "<td>".$row["TIMESTAMP"]."</td>";
							$output .= "<td>".$row["COUPONID"]."</td>";
							$output .= "<td>".$row["AMT"]."</td>";	
							if($row["CAPTURED"] == 1)
			                                {
				                                $output .= "<td>Success</td>";
			                                }
			                                else if(($row['CAPTURED_ACK'] == '') && ($row["CAPTURED"] == 0))
			                                {
				                                $output .= "<td>Hold</td>";
			                                }
			                                else
			                                {
				                                $output .= "<td>Failed</td>";	 				
				                        }
							$output .= "</tr>";           
					}
			}
	
			
			$output .= "</table>";
	}

	else if($type == "deals")
	{

	    $division = $_GET["format"];
	    $user_role = $_SESSION["userrole"];
	    $searchkey = $_GET["searchkey"];

		if($division == "all")
		{
		
			$queryString = "SELECT c.coupon_enddate,(
		
		SELECT count( p.coupon_purchaseid )
		FROM coupons_purchase p
		WHERE p.couponid = c.coupon_id  and p.Coupon_amount_Status='T'
		) AS pcounts, u.firstname, u.lastname, c.coupon_id, c.coupon_name, DATE_FORMAT( c.coupon_startdate, '%d %M %Y' ) AS startdate, DATE_FORMAT( c.coupon_enddate, '%d %M %Y' ) AS enddate, c.coupon_createdby,(
		if( LENGTH( concat( u.firstname, u.lastname ) ) =0, u.username, concat( u.firstname, u.lastname ) )
		) AS name,c.coupon_status,c.coupon_minuserlimit as minuserlimit,c.coupon_maxuserlimit as maxuserlimit
		,c.coupon_realvalue,c.coupon_value,c.coupon_image FROM coupons_coupons c left join coupons_users u on u.userid=c.coupon_createdby";
		
		//checking whether user is not admin
		if($user_role !=1)
		{
			$queryString .= " where c.coupon_createdby =".$_SESSION['userid'];
		}

		if(!empty($searchkey))
		{
		        if($user_role == 1)
		        {
		                $queryString .= " where c.coupon_name like '%".$searchkey."%'";
		        }
		        else
		        {
		                $queryString .= " and c.coupon_name like '%".$searchkey."%'";
		        }
		}

		$queryString .= ' order by c.coupon_id desc';
		}
		else if($division == "active")
		{

			$queryString = "SELECT c.coupon_enddate,(
		
		SELECT count( p.coupon_purchaseid )
		FROM coupons_purchase p
		WHERE p.couponid = c.coupon_id  and p.Coupon_amount_Status='T'
		) AS pcounts, u.firstname, u.lastname, c.coupon_id, c.coupon_name, DATE_FORMAT( c.coupon_startdate, '%d %M %Y' ) AS startdate, DATE_FORMAT( c.coupon_enddate, '%d %M %Y' ) AS enddate, c.coupon_createdby,(
		if( LENGTH( concat( u.firstname, u.lastname ) ) =0, u.username, concat( u.firstname, u.lastname ) )
		) AS name,c.coupon_status,c.coupon_minuserlimit as minuserlimit,c.coupon_maxuserlimit as maxuserlimit
		,c.coupon_realvalue,c.coupon_value,c.coupon_image FROM coupons_coupons c left join coupons_users u on u.userid=c.coupon_createdby where c.coupon_status='A' and c.coupon_enddate >= now()";
		
		//checking whether user is not admin
		if($user_role !=1)
		{
			$queryString .= " and c.coupon_createdby =".$_SESSION['userid'];
		}

		if(!empty($searchkey)) {
		$queryString .= " and c.coupon_name like '%".$searchkey."%'";
		}

		$queryString .= ' order by c.coupon_id desc';

	    
		}
		else if($division == "rejected")
		{
			$queryString = "SELECT (
		
			SELECT count( p.coupon_purchaseid )
			FROM coupons_purchase p
			WHERE p.couponid = c.coupon_id  and p.Coupon_amount_Status='T'
			) AS pcounts, u.firstname, u.lastname, c.coupon_id, c.coupon_name, DATE_FORMAT( c.coupon_startdate, '%d %M %Y' ) AS startdate, DATE_FORMAT( c.coupon_enddate, '%d %M %Y' ) AS enddate, c.coupon_createdby,(
			if( LENGTH( concat( u.firstname, u.lastname ) ) =0, u.username, concat( u.firstname, u.lastname ) )
			) AS name,c.coupon_status,c.coupon_minuserlimit as minuserlimit,c.coupon_maxuserlimit as maxuserlimit
			,c.coupon_realvalue,c.coupon_offer,c.coupon_image FROM coupons_coupons c, coupons_users u  where u.userid=c.coupon_createdby and c.coupon_status='R'";
		
			//checking whether user is not admin
			if($user_role !=1)
			{
				$queryString .= " and c.coupon_createdby =".$_SESSION['userid'];
			}

			if(!empty($searchkey)) {
			$queryString .= " and c.coupon_name like '%".$searchkey."%'";
			}

			$queryString .= ' order by c.coupon_id desc';

	    
		}
		else if($division == "pending")
		{
			$queryString = "SELECT (
		
			SELECT count( p.coupon_purchaseid )
			FROM coupons_purchase p
			WHERE p.couponid = c.coupon_id  and p.Coupon_amount_Status='T'
			) AS pcounts, u.firstname, u.lastname, c.coupon_id, c.coupon_name, DATE_FORMAT( c.coupon_startdate, '%d %M %Y' ) AS startdate, DATE_FORMAT( c.coupon_enddate, '%d %M %Y' ) AS enddate, c.coupon_createdby,(
			if( LENGTH( concat( u.firstname, u.lastname ) ) =0, u.username, concat( u.firstname, u.lastname ) )
			) AS name,c.coupon_status,c.coupon_minuserlimit as minuserlimit,c.coupon_maxuserlimit as maxuserlimit
			,c.coupon_realvalue,c.coupon_offer,c.coupon_image FROM coupons_coupons c, coupons_users u  where u.userid=c.coupon_createdby and c.coupon_status='D' and c.coupon_enddate > now()";
		
			//checking whether user is not admin
			if($user_role !=1)
			{
				$queryString .= " and c.coupon_createdby =".$_SESSION['userid'];
			}

			if(!empty($searchkey)) {
			$queryString .= " and c.coupon_name like '%".$searchkey."%'";
			}

			$queryString .= ' order by c.coupon_id desc';

	    
		}
		else if($division == "closed")
		{
			$queryString = "SELECT c.coupon_enddate,(
		
		SELECT count( p.coupon_purchaseid )
		FROM coupons_purchase p
		WHERE p.couponid = c.coupon_id  and p.Coupon_amount_Status='T'
		) AS pcounts, u.firstname, u.lastname, c.coupon_id, c.coupon_name, DATE_FORMAT( c.coupon_startdate, '%d %M %Y' ) AS startdate, DATE_FORMAT( c.coupon_enddate, '%d %M %Y' ) AS enddate, c.coupon_createdby,(
		if( LENGTH( concat( u.firstname, u.lastname ) ) =0, u.username, concat( u.firstname, u.lastname ) )
		) AS name,c.coupon_status,c.coupon_minuserlimit as minuserlimit,c.coupon_maxuserlimit as maxuserlimit
		,c.coupon_realvalue,c.coupon_value,c.coupon_image FROM coupons_coupons c left join coupons_users u on u.userid=c.coupon_createdby where (c.coupon_status='C' or c.coupon_enddate < now())";
		
		//checking whether user is not admin
		if($user_role !=1)
		{
			$queryString .= " and c.coupon_createdby =".$_SESSION['userid'];
		}

		if(!empty($searchkey)) {
		$queryString .= " and c.coupon_name like '%".$searchkey."%'";
		}

		$queryString .= ' order by c.coupon_id desc';

	    
		}
		else if($division == "shopadmin")
		{
			$userid = $_SESSION['userid'];
			$queryString = "SELECT (
		
			SELECT count( p.coupon_purchaseid )
			FROM coupons_purchase p
			WHERE p.couponid = c.coupon_id  and p.Coupon_amount_Status='T'
			) AS pcounts, u.firstname, u.lastname, c.coupon_id, c.coupon_name, DATE_FORMAT( c.coupon_startdate, '%d %M %Y' ) AS startdate, DATE_FORMAT( c.coupon_enddate, '%d %M %Y' ) AS enddate, c.coupon_createdby,(
			if( LENGTH( concat( u.firstname, u.lastname ) ) =0, u.username, concat( u.firstname, u.lastname ) )
			) AS name,c.coupon_status,c.coupon_minuserlimit as minuserlimit,c.coupon_maxuserlimit as maxuserlimit
			,c.coupon_realvalue,c.coupon_offer,c.coupon_image FROM coupons_coupons c left join coupons_users u  on u.userid=c.coupon_createdby where c.coupon_createdby =(select userid from coupons_users where created_by = '$userid' and user_role = 3)  order by c.coupon_id desc";
	    
		}
			
			//end of query
			$result = mysql_query($queryString);
			$field = array('Deal Id','Deal Name','End Date','Min User','Max User','Amount','Status','Offer(%)','Sold','First Name','Last Name');
			$output = '<table>';
			
			if(count($field) > 0)
			{
					$output .= '<tr>';
					foreach($field as $row)
					{
							$output .= "<th>".$row."</th>";
							$i++;
					}
					$output .= "</tr><tr></tr>";
			}
			
			if (mysql_num_rows($result) > 0)
			{
	
					while($row = mysql_fetch_array($result)) 
					{
							$output .= '<tr>';
							$output .= "<td>".$row["coupon_id"]."</td>";
							$output .= "<td>".$row["coupon_name"]."</td>";
							$output .= "<td>".$row["enddate"]."</td>";
							$output .= "<td>".$row["minuserlimit"]."</td>";
							$output .= "<td>".$row["maxuserlimit"]."</td>";
							$output .= "<td>".$row["coupon_realvalue"]."</td>";
							if($division == 'closed')
							{
							        $output .= "<td>C</td>";
							}
							else
							{ 
							        $output .= "<td>".$row["coupon_status"]."</td>";
							}
							$output .= "<td>".get_discount_value($row["coupon_realvalue"],$row["coupon_value"])."</td>";		
							$output .= "<td>".$row["pcounts"]."</td>";		
							$output .= "<td>".$row["firstname"]."</td>";
							$output .= "<td>".$row["lastname"]."</td>";		 				
							$output .= "</tr>";           
					}
			}
	
			
			$output .= "</table>";

	}
	else if($type == 'deals_transaction')
	{
	        $dealid = $_GET['deal_id'];
	        
	        $select = $_GET['status'];
	        
	        $queryString = "select ID,TIMESTAMP,CAPTURED_ACK,coupons_userstatus, coupon_validityid, coupons_userstatus, coupons_users.userid, transaction_details.COUPONID,AMT,username,coupon_name,CAPTURED,L_QTY0 from transaction_details left join coupons_coupons on transaction_details.COUPONID = coupons_coupons.coupon_id left join coupons_users on transaction_details.USERID = coupons_users.userid left join coupons_purchase on coupons_purchase.transaction_details_id=transaction_details.ID where transaction_details.COUPONID = '$dealid' ";
	        
	        if($select == 'success')
	        {
	                $queryString .= "and CAPTURED=1 ";
	        }
	        else if($select == 'hold')
	        {
	                $queryString .= "and CAPTURED=0 and CAPTURED_ACK='' ";
	        }
	        else if($select == 'failed')
	        {
	                $queryString .= "and CAPTURED=0 and CAPTURED_ACK='Failed' ";
	        }
	        
	        
	        $queryString .= "order by TIMESTAMP desc";
	        $resultSet  =mysql_query($queryString);
	        if(mysql_num_rows($resultSet)>0)
	        {
			         $output = '<table cellpadding="10" cellspacing="0"  style="border:1px; margin:5px;">
			         <tr>
			         <th>'.$admin_language["date"].'</th>
			         <th>'.$admin_language["user"].'</th>
			         <th>'.$admin_language["description"].'</th>
			         <th>'.$admin_language["quantity"].'</th>
			         <th>'.$admin_language["amount"].'('.CURRENCY.')</th> 
			         <th>'.$admin_language["status"].'</th>
			         <th>Coupon code</th>
			         <th>Coupon used status</th>
			         </tr>';
	
		        while($row = mysql_fetch_array($resultSet))
		        { 
			        if($row["CAPTURED"] == 1)
			        {
				        $status =  "Success";
			        }
			        else
			        {
				        if($row["CAPTURED_ACK"] == 'Failed')
				                $status =  "Failed";
				        else
                                                $status =  'Hold';
			        }	
				        
			        $output .='<tr>
			        <td>'.$row["TIMESTAMP"].'</td>
			        <td>'.$row["username"].'</td>
			        <td>'.$row["username"].' '.$admin_language['bought'].' '.$row["coupon_name"].'</td>
			        <td>'.$row["L_QTY0"].'</td>
			        <td>'.round($row["AMT"], 2).'</td>
			        <td>'.$status.'</td>
			        <td>';
		                if(!empty($row["coupon_validityid"]))
		                {
		                        $output .= $row["coupon_validityid"];
		                }
		                else
		                {
		                       $output .= '-';
		                }
		                $output .='</td>
		                <td>'.$row["coupons_userstatus"].'</td>
			        </tr>';
		        }		
		        $sum = mysql_query("select SUM(AMT) as total_amount from transaction_details left join coupons_coupons on transaction_details.COUPONID = coupons_coupons.coupon_id left join coupons_users on transaction_details.USERID = coupons_users.userid where COUPONID = '$dealid'");
		        
		        /* GET TOTAL AMOUNT OF SUCCESS TRANSACTION */
	                $success_query = "SELECT SUM(AMT) FROM transaction_details where transaction_details.COUPONID = '$dealid' and CAPTURED=1";
	                $success_result = mysql_query($success_query);
	                $success_amount = CURRENCY.round(mysql_result($success_result,0,0), 2);
	
	                /* GET TOTAL AMOUNT OF HOLD TRANSACTION */
	                $hold_query = "SELECT SUM(AMT) FROM transaction_details where transaction_details.COUPONID = '$dealid' and CAPTURED=0 and CAPTURED_ACK=''";
	                $hold_result = mysql_query($hold_query);
	                $hold_amount = CURRENCY.round(mysql_result($hold_result,0,0), 2);
	
	                /* GET TOTAL AMOUNT OF FAILED TRANSACTION */
	                $failed_query = "SELECT SUM(AMT) FROM transaction_details where transaction_details.COUPONID = '$dealid' and CAPTURED=0 and CAPTURED_ACK='Failed'";
	                $failed_result = mysql_query($failed_query);
	                $failed_amount = CURRENCY.round(mysql_result($failed_result,0,0), 2);
		        $output .= '<tr>
		        <td colspan="4" align="right">'.$admin_language['totaltransactionamount'].'</td>
		        <td align="left">';
		        while($row = mysql_fetch_array($sum)) { 
			        $output .= CURRENCY.round($row["total_amount"], 2);
		        } 
		        $output .= '</td></tr>';
		        
		        if(empty($select) || $select == 'success')
		        {
		                $output .= '
		                <tr>
		                <td colspan="4" align="right">'.$admin_language['successtransactionamount'].'</td>
		                <td align="left">
		                '.$success_amount.'
		                </td>
		                </tr>';
		        }
		        if(empty($select) || $select == 'hold')
		        {
		                $output .= '
		                <tr>
		                <td colspan="4" align="right">'.$admin_language['holdtransactionamount'].'</td>
		                <td align="left">
		                '.$hold_amount.'
		                </td>
		                </tr>';
		        }
		        if(empty($select) || $select == 'failed')
		        {
		                $output .= '
		                <tr>
		                <td colspan="4" align="right">'.$admin_language['failedtransactionamount'].'</td>
		                <td align="left">
		                '.$failed_amount.'
		                </td>
		                </tr>';
		        }
		        if(empty($select))
		        {
		                $sum = mysql_query("select SUM(AMT) as total_amount from transaction_details left join coupons_coupons on transaction_details.COUPONID = coupons_coupons.coupon_id left join coupons_users on transaction_details.USERID = coupons_users.userid where COUPONID = '$dealid'");
		                ?>
		                <tr>
		                <td colspan="4" align="right"><?php echo $admin_language['totaltransactionamount']; ?></td>
		                <td align="left"><?php 
		                while($row = mysql_fetch_array($sum)) { 
			                echo CURRENCY.round($row["total_amount"], 2);
		                } ?>
		                </td>
		                </tr>
		                <?php
		        }
		        
		        $output .= '</table>';
		        
	        }
	}
	else if($type == "users")
	{
		$division = $_GET["format"];
		$searchkey = $_GET["searchkey"];

		//export all users to csv
		if($division == "all")
		{
		    $queryString = "select u.login_type,u.userid,u.username,u.firstname,u.lastname,u.email,u.mobile,u.user_role,r.role_name,u.created_by,u.user_status,(select sum(AMT) from transaction_details where transaction_details.USERID = u.userid) as purchased_amt
		      from coupons_users u,coupons_roles r where r.roleid=u.user_role and u.user_status in ('A','D') and user_role != 1 ";

			if(!empty($searchkey)) {
				$queryString .=	"and (u.email like '%".$searchkey."%' or u.username like '%".$searchkey."%')"; }

		}
		else if($division == "admin")
		{
		    $queryString = "select u.login_type,u.userid,u.username,u.firstname,u.lastname,u.email,u.mobile,u.user_role,r.role_name,u.created_by,u.user_status,(select sum(AMT) from transaction_details where transaction_details.USERID = u.userid) as purchased_amt
		      from coupons_users u,coupons_roles r where r.roleid=u.user_role and u.user_status in ('A','D') and user_role=1 ";

			if(!empty($searchkey)) {
				$queryString .=	"and (u.email like '%".$searchkey."%' or u.username like '%".$searchkey."%')"; }

		}
		else if($division == "general")
		{
		    $queryString = "select u.login_type,u.userid,u.username,u.firstname,u.lastname,u.email,u.mobile,u.user_role,r.role_name,u.created_by,u.user_status,(select sum(AMT) from transaction_details where transaction_details.USERID = u.userid) as purchased_amt
		      from coupons_users u,coupons_roles r where r.roleid=u.user_role and u.user_status in ('A','D') and u.user_role = '4' ";

			if(!empty($searchkey)) {
				$queryString .=	"and (u.email like '%".$searchkey."%' or u.username like '%".$searchkey."%')"; }

		}
		else if($division == "fb_users")
		{
		    $queryString = "select u.login_type,u.userid,u.username,u.firstname,u.lastname,u.email,u.mobile,u.user_role,r.role_name,u.created_by,u.user_status,(select sum(AMT) from transaction_details where transaction_details.USERID = u.userid) as purchased_amt
		      from coupons_users u,coupons_roles r where r.roleid=u.user_role and u.user_status in ('A','D') and login_type=1 ";

			if(!empty($searchkey)) {
				$queryString .=	"and (u.email like '%".$searchkey."%' or u.username like '%".$searchkey."%')"; }

		}
		else if($division == "tw_users")
		{
		    $queryString = "select u.login_type,u.userid,u.username,u.firstname,u.lastname,u.email,u.mobile,u.user_role,r.role_name,u.created_by,u.user_status,(select sum(AMT) from transaction_details where transaction_details.USERID = u.userid) as purchased_amt
		      from coupons_users u,coupons_roles r where r.roleid=u.user_role and u.user_status in ('A','D') and login_type=2 ";

			if(!empty($searchkey)) {
				$queryString .=	"and (u.email like '%".$searchkey."%' or u.username like '%".$searchkey."%')"; }

		}
		else if($division == "shopadmin")
		{
		    if($_SESSION['userrole'] == '1')
		    {

			$queryString = "select s.shopname,s.shop_address,u.login_type,u.userid,u.username,u.firstname,u.lastname,u.email,u.mobile,u.user_role,r.role_name,u.created_by,u.user_status
			from coupons_users u,coupons_roles r,coupons_shops s where r.roleid=u.user_role and u.user_status in ('A','D') and user_role=3 and s.shopid=u.user_shopid";

		    }
		    else if($_SESSION['userrole'] == '2')
		    {
			$userid = $_SESSION['userid'];
			$queryString = "select s.shopname,s.shop_address,u.login_type,u.userid,u.username,u.firstname,u.lastname,u.email,u.mobile,u.user_role,r.role_name,u.created_by,u.user_status
			from coupons_users u,coupons_roles r,coupons_shops s where r.roleid=u.user_role and u.user_status in ('A','D') and user_role=3 and s.shopid=u.user_shopid and u.created_by='$userid'";

		    }

			if(!empty($searchkey)) {
				$queryString .=	" and (u.email like '%".$searchkey."%' or u.username like '%".$searchkey."%' or s.shopname like '%".$searchkey."%' or s.shop_address like '%".$searchkey."%')"; }

		}
		else if($division == "citymgr")
		{
		    $queryString = "select u.login_type,u.userid,u.username,u.firstname,u.lastname,u.email,u.mobile,u.user_role,r.role_name,u.created_by,u.user_status
		      from coupons_users u,coupons_roles r where r.roleid=u.user_role and u.user_status in ('A','D') and user_role=2";

			if(!empty($searchkey)) {
				$queryString .=	" and (u.email like '%".$searchkey."%' or u.username like '%".$searchkey."%')"; }

		}
		//end of query


			
			$result = mysql_query($queryString);
			$field = array('User Id','User name','First name','Last name','Email','Mobile','Role name','Created On','Status');
			$output = '<table>';
			
			if(count($field) > 0)
			{
					$output .= '<tr>';
					foreach($field as $row)
					{
							$output .= "<th>".$row."</th>";
							$i++;
					}
					$output .= "</tr><tr></tr>";
			}

			if (mysql_num_rows($result) > 0)
			{
	
					while($row = mysql_fetch_array($result)) 
					{
							$output .= '<tr>';
							$output .= "<td>".$row["userid"]."</td>";
							$output .= "<td>".$row["username"]."</td>";
							$output .= "<td>".$row["firstname"]."</td>";
							$output .= "<td>".$row["lastname"]."</td>";
							$output .= "<td>".$row["email"]."</td>";
							$output .= "<td>".$row["mobile"]."</td>";
							$output .= "<td>".$row["role_name"]."</td>";
							$output .= "<td>".$row["created_date"]."</td>";		
							$output .= "<td>".$row["user_status"]."</td>";		
							$output .= "</tr>";           
					}
			}
	
			
			$output .= "</table>";
		}

			$filename = 'report.xls';
			header("Content-Description: File Transfer");
			header('Content-disposition: attachment; filename='.basename($filename));
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Transfer-Encoding: binary");
			header("Pragma: public");
			header('Cache-Control: max-age=0');
			print $output;
			exit;
	
	
?>
<?php ob_flush(); ?>
