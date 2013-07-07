<?php
is_login(DOCROOT."admin/login/"); //checking whether admin logged in or not.
$xml_content = '<?xml version="1.0" encoding="UTF-8"?>
<rss version="0.92">
<channel>
	<title>'.APP_NAME.'</title>
	<link>'.DOCROOT.'</link>
	<description>'.APP_NAME.'</description>
	<copyright>'.DOCROOT.'. All Rights Reserved</copyright>
';

$queryString = "select coupon_id,coupon_name,coupon_description,coupon_startdate,coupon_enddate,coupon_image,coupon_realvalue,coupon_offer from coupons_coupons where coupon_status = 'A' AND coupon_enddate > now() order by coupon_id desc";

$result = mysql_query($queryString);
while($row = mysql_fetch_array($result))
{
	$xml_content .= "	
			<item>
			  <title>".html_entity_decode(strip_tags($row['coupon_name']))."</title>
			  <description>".html_entity_decode(strip_tags($row['coupon_name']))."</description>
			  <link>".DOCROOT."deals/".friendlyURL(html_entity_decode($row["coupon_name"]))."_".$row["coupon_id"].".html</link>
            </item>";
}

//add the category url
$queryString = "select * from coupons_category where status='A' order by category_name asc";

$result = mysql_query($queryString);
			while($row = mysql_fetch_array($result))
			{
					$xml_content .= "	
					<item>
					  <title>".html_entity_decode($row['category_name'])."</title>
					  <description>".html_entity_decode($row['category_name'])."</description>
					  <link>".DOCROOT."deals/category/".$row["category_url"].".html</link>
					</item>";
			}


             $xml_content .="</channel></rss>";
			 
           	 $filename = 'rss.xml';
		 	 $somecontent = $xml_content;

                // Let's make sure the file exists and is writable first.
                if (is_writable($filename)) {

                    // In our example we're opening $filename in append mode.
                    // The file pointer is at the bottom of the file hence 
                    // that's where $somecontent will go when we fwrite() it.
                    if (!$handle = fopen($filename, 'w')) {
                         echo "Cannot open file ($filename)";
                         exit;
                    }

                    // Write $somecontent to our opened file.
                    if (fwrite($handle, $somecontent) === FALSE) {
                        echo "Cannot write to file ($filename)";
                        exit;
                    }
                    
                  //  echo "Success, wrote ($somecontent) to file ($filename)";
                    
                    fclose($handle);

                }
                else 
                {
                    echo "The file $filename is not writable";
                    exit; 
                }
				 set_response_mes(1,$admin_language['rssfileupdated']);
                 url_redirect($_SERVER['HTTP_REFERER']);
                 //return $query;

			
?>
