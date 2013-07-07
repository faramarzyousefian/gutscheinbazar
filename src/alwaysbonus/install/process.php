<?php 
	if($_POST)
	{
	
		$adminname = $_POST['name'];
		$adminpassword = md5($_POST['password']);
		$adminemail = $_POST['email'];
		
		/*Getting information for Db connectivity from dboperations.php file */

		include_once '../system/includes/dboperations.php';
		include_once '../system/includes/docroot.php';

		// Performing SQL query

		//****** Creation of user table Starts here*******//

		$sql1="CREATE TABLE IF NOT EXISTS `coupons_users` (
		  `userid` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Coupons user Id',
		  `username` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Coupons user Name',
		  `password` varchar(200) DEFAULT NULL COMMENT 'Coupons user Password',
		  `firstname` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
		  `lastname` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
		  `email` varchar(100) DEFAULT NULL COMMENT 'Coupons user email',
		  `mobile` varchar(20) DEFAULT NULL COMMENT 'Coupons user Mobile number',
		  `pay_account` varchar(100) NOT NULL,
		  `user_role` int(5) DEFAULT NULL COMMENT 'Coupons user Role - AD(Administrator),CM(CityManager),SA(Store Administrator),G(General user)',
		  `user_status` varchar(3) NOT NULL COMMENT 'Coupons user Status - A(Active),B(Blocked),D(Deleted)',
		  `user_shopid` int(3) DEFAULT NULL COMMENT 'Coupons Shop id - this field is only for the Store admin role users',
		  `address` varchar(2500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Coupons user Address',
		  `city` int(3) DEFAULT NULL COMMENT 'Coupons user city',
		  `country` int(3) DEFAULT NULL COMMENT 'Coupons user country',
		  `created_by` int(3) DEFAULT NULL COMMENT 'Coupons user Created By',
		  `created_date` datetime NOT NULL,
		  `referral_earned_amount` double NOT NULL,
		  `referral_id` varchar(20) NOT NULL,
		  `account_balance` double NOT NULL,
		  `login_type` int(11) NOT NULL COMMENT '(0-General,1-fb connect, 2-twitter connect)',
		  `status` varchar(3) NOT NULL DEFAULT 'A' COMMENT 'email_all(A-Active, D-Deactive)',
		  PRIMARY KEY (`userid`),
		  UNIQUE KEY `username` (`username`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
		$exe1=mysql_query($sql1) or die(mysql_error());	

		$sql1="INSERT INTO `coupons_users` (`userid`, `username`, `password`, `firstname`, `lastname`, `email`, `mobile`, `pay_account`, `user_role`, `user_status`, `user_shopid`, `address`, `city`, `country`, `created_by`, `created_date`, `referral_earned_amount`, `referral_id`, `account_balance`, `login_type`, `status`) VALUES
		(1, '$adminname', '$adminpassword', '$adminname', '$adminname', '$adminemail', '', '', 1, 'A', NULL, '', '1', '1', 1, '2011-04-18 13:45:34', 0, '', 0, 0, 'A')";
		$exe1=mysql_query($sql1) or die(mysql_error());
						
                //****** Creation of user table ends here*******//
		//****** Creation of other table Starts here*******//		

		$sql1="CREATE TABLE IF NOT EXISTS `coupons_category` (
		  `category_id` int(3) NOT NULL AUTO_INCREMENT,
		  `category_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `category_url` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `status` varchar(3) NOT NULL DEFAULT 'A',
		  PRIMARY KEY (`category_id`),
		  FULLTEXT KEY `subtypename` (`category_name`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="INSERT INTO `coupons_category` (`category_id`, `category_name`, `category_url`, `status`) VALUES
		(1, 'Restaurants', 'restaurants', 'A'),
		(2, 'Fitness centres', 'fitness-centres', 'A'),
		(3, 'Sports', 'sports', 'A'),
		(4, 'Electronics', 'electronics', 'A'),
		(6, 'Massages', 'massages', 'A'),
		(7, 'Facial', 'facial', 'A'),
		(8, 'Weightloss', 'weightloss', 'A'),
		(9, 'Hairloss', 'hairloss', 'A'),
		(10, 'Hotel', 'hotel', 'A'),
		(12, 'Tours', 'tours', 'A'),
		(13, 'Pubs', 'pubs', 'A'),
		(14, 'Royal Bars ', 'royal-bars', 'A')";
		$exe1=mysql_query($sql1) or die(mysql_error());	

		$sql1="CREATE TABLE IF NOT EXISTS `coupons_cities` (
		  `cityid` int(3) NOT NULL AUTO_INCREMENT,
		  `cityname` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `city_url` varchar(200) NOT NULL,
		  `countryid` int(3) NOT NULL,
		  `status` varchar(3) NOT NULL DEFAULT 'A',
		  PRIMARY KEY (`cityid`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="INSERT INTO `coupons_cities` (`cityid`, `cityname`, `city_url`, `countryid`, `status`) VALUES
			(1, 'Florida', 'florida', 1, 'A'),
			(3, 'Kansas', 'kansas', 1, 'A'),
			(4, 'New Jersey', 'new-jersey', 1, 'A')";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="CREATE TABLE IF NOT EXISTS `coupons_country` (
		  `countryid` int(3) NOT NULL AUTO_INCREMENT,
		  `countryname` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `status` varchar(2) NOT NULL DEFAULT 'A',
		  PRIMARY KEY (`countryid`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2";
		$exe1=mysql_query($sql1) or die(mysql_error());	

		$sql1="INSERT INTO `coupons_country` (`countryid`, `countryname`, `status`) VALUES
		(1, 'USA', 'A')";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="CREATE TABLE IF NOT EXISTS `coupons_coupons` (
		  `coupon_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Coupons Id',
		  `coupon_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Coupons Name',
		  `deal_url` varchar(1000) NOT NULL,
		  `coupon_description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT 'Coupons Description',
		  `coupon_startdate` datetime DEFAULT NULL COMMENT 'Coupons purchase start date',
		  `coupon_enddate` datetime DEFAULT NULL COMMENT 'Coupons purchase end date',
		  `coupon_image` varchar(200) DEFAULT NULL,
		  `coupon_createdby` int(3) DEFAULT NULL,
		  `coupon_createddate` datetime DEFAULT NULL,
		  `coupon_value` float NOT NULL,
		  `coupon_status` varchar(3) DEFAULT NULL,
		  `coupon_minuserlimit` int(11) DEFAULT NULL,
		  `coupon_maxuserlimit` int(11) DEFAULT NULL,
		  `coupon_realvalue` int(11) NOT NULL,
		  `coupon_category` int(2) NOT NULL,
		  `coupon_shop` int(11) NOT NULL,
		  `coupon_country` int(3) DEFAULT NULL,
		  `coupon_city` int(3) DEFAULT NULL,
		  `coupon_person` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
		  `coupon_phoneno` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
		  `coupon_address` varchar(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
		  `coupon_fineprints` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `coupon_highlights` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `terms_and_condition` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `pay_capture_status` int(11) NOT NULL COMMENT '(1-true, 0-false)',
		  `side_deal` int(11) NOT NULL COMMENT 'side deal option(1-true,0-false)',
		  `meta_keywords` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `meta_description` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `main_deal` int(11) NOT NULL COMMENT '0=>normal, 1=>main deal',
		  `coupon_expirydate` datetime NOT NULL,
		  `is_video` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=>no video,1=>video',
		  `embed_code` longtext NOT NULL,
		  PRIMARY KEY (`coupon_id`),
		  FULLTEXT KEY `couponname` (`coupon_name`),
		  FULLTEXT KEY `coupon_description` (`coupon_description`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="INSERT INTO `coupons_coupons` (`coupon_id`, `coupon_name`, `deal_url`, `coupon_description`, `coupon_startdate`, `coupon_enddate`, `coupon_image`, `coupon_createdby`, `coupon_createddate`, `coupon_value`, `coupon_status`, `coupon_minuserlimit`, `coupon_maxuserlimit`, `coupon_realvalue`, `coupon_category`, `coupon_shop`, `coupon_country`, `coupon_city`, `coupon_person`, `coupon_phoneno`, `coupon_address`, `coupon_fineprints`, `coupon_highlights`, `terms_and_condition`, `pay_capture_status`, `side_deal`, `meta_keywords`, `meta_description`, `main_deal`, `coupon_expirydate`, `is_video`, `embed_code`) VALUES
(1, 'The Best  Pizza Coupons', 'the-best-pizza-coupons', 'Pizza is a USA based buffet restaurant chain specializing in American style\r\npizzas. It serves over 20 different types of pizzas, makes home deliveries and also\r\nprovides catering services. It was given the award for best customer service by Corporate\r\nResearch International for three years running. You gotta go to CiCi&#039;s, the endless buffet\r\nof fresh salads, savory pasta, delicious desserts and fresh-from-the-oven pizzas.\r\nOur dough is made from scratch everyday, our sauce is a blend of vine-ripened tomatoes\r\nand spices, our signature salads are hand tossed with the freshest ingredients and our\r\ndesserts are simply delicious. Plus, guests under the age of 3 can enjoy the entire buffet\r\nfor free.\r\n', '2011-09-07 20:58:39', '2011-11-26 21:53:03', 'uploads/coupons/ZRDRA7MA.jpg', 1, '2011-09-07 20:59:43', 30, 'A', 2, 50, 300, 10, 1, 1, 4, 'admin', '8903158385', 'chennai', 'All of our pizzas begin with three great ingredients made fresh daily at every restaurant\r\n\r\n- Crispy crust, made from scratch, topped off with vine-ripened tomato sauce or zesty\r\nwhite sauce and then sprinkled with real whole-milk mozzarella cheese. Whether you&#039;re\r\na meat lover, veggie lover or both, you&#039;ll find that our pepperoni, sizzling beef, ham,\r\nchicken, Italian-style sausage, crisp green peppers, savory onions, hearty mushrooms,\r\nspicy jalape&Atilde;&plusmn;os, tangy black and juicy green olives are all mouth watering choices.\r\nSpecial order your own pizza or choose from a CiCi&#039;s Classic or Signature Creation\r\n', 'Sometimes you just don&#039;t have time to gather everyone together and head out to a\r\nrestaurant. Practice runs long, you get stuck at work or you just want to relax at home.\r\nThat&#039;s where CiCi&#039;s Carry-Out Stores save the day. Call ahead to customize your order\r\nfrom a wide variety of menu items including specialty pizzas, buffalo wings, dippin&#039;\r\nsticks, cinnamon rolls and soda. Or walk right in and choose from our CiCi&#039;s Now menu,\r\nfeaturing all of your favorite items - always ready, no waiting. You can build a meal a la\r\ncarte or choose a CiCi&#039;s Now Craveable Combo for a complete family meal.\r\nTerms &amp; Conditions', 'You understand that all material transmitted through, or linked from the Service are the\r\nsole responsibility of the person from whom such material originated. Furthermore, the\r\nSite and material available through the Site may contain links to other websites, which\r\nare completely independent of CiCi&#039;s. Those links do not imply any endorsement by\r\nCiCi&#039;s and CiCi&#039;s makes no representation or warranty as to the accuracy, completeness\r\nor authenticity of the information contained in any such websites. Your linking to any\r\nother websites is at your own risk.\r\nMeta Description', 0, 1, 'Sometimes you just don&#039;t have time to gather everyone together and head out to a\r\nrestaurant', 'Sometimes you just don&#039;t have time to gather everyone together and head out to a\r\nrestaurant', 1, '2011-11-30 20:53:03', 0, ''),
(2, 'Salon offers healthy alternative for nail care', 'salon-offers-healthy-alternative-for-nail-care', 'She said clients from the spa had been asking about a nail salon, which is what sparked the idea.\r\n\r\nBalin and her sister wanted to have their business separate from the spa to make it more convenient for clients who just need a quick manicure. Even still, they feel each business compliments each other.\r\n\r\nThe sisters said they take sanitation seriously. They have to be extremely careful when mixing products because everything they use is organic. For this reason, Chalanda Hook, a nurse, is the only one in the salon who is allowed to handle the materials.\r\n\r\nHook said even if she works as a nurse, starting a business like this with her sister has been her dream.\r\n\r\n&acirc;€œWe tried to make a place that we would like to go,&acirc;€ Hook said.\r\n\r\nWell Polished is an eco-friendly salon that solely uses natural elements in their services. There are no fumes, drill or jets in the building. The salon doesn&acirc;€™t offer acrylics. Instead it encourages people to remove enhancements to allow nails to strengthen in a natural and healthy way by incorporating fresh fruits, veggies and essential oils into its treatments', '2011-09-07 21:03:11', '2011-12-23 22:00:16', 'uploads/coupons/14SGMCWT.jpg', 1, '2011-09-07 21:04:37', 20, 'A', 2, 50, 200, 10, 1, 1, 4, 'admin', '8903158385', 'chennai', 'The sisters said they take sanitation seriously. They have to be extremely careful when mixing products because everything they use is organic. For this reason, Chalanda Hook, a nurse, is the only one in the salon who is allowed to handle the materials.\r\n\r\nHook said even if she works as a nurse, starting a business like this with her sister has been her dream.\r\n\r\n&acirc;€œWe tried to make a place that we would like to go,&acirc;€ Hook said.', 'The sisters said they take sanitation seriously. They have to be extremely careful when mixing products because everything they use is organic. For this reason, Chalanda Hook, a nurse, is the only one in the salon who is allowed to handle the materials.\r\n\r\nHook said even if she works as a nurse, starting a business like this with her sister has been her dream.\r\n\r\n&acirc;€œWe tried to make a place that we would like to go,&acirc;€ Hook said.', 'She said clients from the spa had been asking about a nail salon, which is what sparked the idea.\r\n\r\nBalin and her sister wanted to have their business separate from the spa to make it more convenient for clients who just need a quick manicure. Even still, they feel each business compliments each other.\r\n\r\nThe sisters said they take sanitation seriously. They have to be extremely careful when mixing products because everything they use is organic. For this reason, Chalanda Hook, a nurse, is the only one in the salon who is allowed to handle the materials.\r\n\r\nHook said even if she works as a nurse, starting a business like this with her sister has been her dream.\r\n\r\n&acirc;€œWe tried to make a place that we would like to go,&acirc;€ Hook said.\r\n\r\nWell Polished is an eco-friendly salon that solely uses natural elements in their services. There are no fumes, drill or jets in the building. The salon doesn&acirc;€™t offer acrylics. Instead it encourages people to remove enhancements to allow nails to strengthen in a natural and healthy way by incorporating fresh fruits, veggies and essential oils into its treatments', 0, 1, 'Hook said even if she works as a nurse, starting a business like this with her sister has been her dream', 'Hook said even if she works as a nurse, starting a business like this with her sister has been her dream', 0, '2011-12-30 21:00:16', 0, '')";
		$exe1=mysql_query($sql1) or die(mysql_error());	

		$sql1="CREATE TABLE IF NOT EXISTS `coupons_favorite` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) NOT NULL,
		  `favorite_id` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="CREATE TABLE IF NOT EXISTS `coupons_purchase` (
		  `coupon_purchaseid` int(4) NOT NULL AUTO_INCREMENT COMMENT 'Purchase ID',
		  `couponid` int(3) DEFAULT NULL COMMENT 'Purchased Coupon id',
		  `coupon_userid` int(3) DEFAULT NULL COMMENT 'Coupon Purchased user id',
		  `Coupon_amount_Status` varchar(3) NOT NULL DEFAULT 'T' COMMENT 'T-Transferd,N-Error in transfer',
		  `coupon_status` varchar(3) DEFAULT NULL COMMENT 'Coupons validitiy status',
		  `coupon_validityid` varchar(20) DEFAULT NULL COMMENT 'This Id Generated After the coupons purchase closed and this generated by admin for each user purchased coupon',
		  `coupon_validityid_date` datetime DEFAULT NULL COMMENT 'Coupon validity id generated date',
		  `coupon_validityid_createdby` int(3) DEFAULT NULL COMMENT 'Coupon validity id created By',
		  `coupon_purchaseddate` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Purchased date',
		  `coupons_userstatus` varchar(3) DEFAULT 'UN' COMMENT 'After generating the coupon validity id the user can use the coupon in the shop,That status U-Used,UN-Notused,B-Blocked',
		  `coupons_user_useddate` datetime DEFAULT NULL COMMENT 'coupon used date by the user',
		  `coupons_acceptedby` int(3) DEFAULT NULL COMMENT 'coupons acceted By',
		  `coupons_note` varchar(512) DEFAULT NULL,
		  `gift_recipient_id` int(11) NOT NULL,
		  `transaction_details_id` int(11) NOT NULL,
		  PRIMARY KEY (`coupon_purchaseid`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="CREATE TABLE IF NOT EXISTS `coupons_purchase_status` (
		  `id` int(11) NOT NULL,
		  `coupons_purchased_count` bigint(20) NOT NULL,
		  `coupons_amtsaved` bigint(20) NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$exe1=mysql_query($sql1) or die(mysql_error());	

		$sql1="INSERT INTO `coupons_purchase_status` (`id`, `coupons_purchased_count`, `coupons_amtsaved`) VALUES
		(1, 0, 0)";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="CREATE TABLE IF NOT EXISTS `coupons_rights` (
		  `rights_id` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Rights Id',
		  `rights_name` varchar(100) DEFAULT NULL COMMENT 'Rights Name',
		  `created_date` date DEFAULT NULL COMMENT 'Rights Created Date',
		  PRIMARY KEY (`rights_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="INSERT INTO `coupons_rights` (`rights_id`, `rights_name`, `created_date`) VALUES
		(1, 'City Manager Creation', '2010-02-04'),
		(2, 'Store Administrator Creation', '2010-02-04'),
		(3, 'New Coupon Upload', '2010-02-04'),
		(4, 'Coupon Code Generation', '2010-02-04')";
		$exe1=mysql_query($sql1) or die(mysql_error());	

		$sql1="CREATE TABLE IF NOT EXISTS `coupons_roles` (
		  `roleid` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Role Id',
		  `role_name` varchar(200) DEFAULT NULL COMMENT 'Role Names AD(Admin),CM(City Manager),SA(Store Administrator),G(General user)',
		  `role_rights` varchar(200) DEFAULT NULL COMMENT 'Rights Fetch from rights table which will we given by user while creating the role ex: 1,2,3',
		  `created_by` int(5) DEFAULT NULL COMMENT 'Role Created By',
		  `created_date` date DEFAULT NULL COMMENT 'Role Created Date',
		  PRIMARY KEY (`roleid`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="INSERT INTO `coupons_roles` (`roleid`, `role_name`, `role_rights`, `created_by`, `created_date`) VALUES
		(1, 'AD', '1,2,3,4', 1, '2010-02-04'),
		(2, 'CM', '2,3,4', 1, '2010-02-04'),
		(3, 'SA', '4', 1, '2010-02-04'),
		(4, 'G', NULL, 1, '2010-02-04')";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="CREATE TABLE IF NOT EXISTS `coupons_shops` (
		  `shopid` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Shop id ',
		  `shopname` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Shop name',
		  `shop_address` varchar(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Shop address Details',
		  `shop_city` int(11) DEFAULT NULL COMMENT 'Shop city',
		  `shop_country` int(11) DEFAULT NULL COMMENT 'Shop Country',
		  `shop_status` varchar(3) DEFAULT NULL,
		  `shop_latitude` varchar(25) DEFAULT NULL,
		  `shop_longitude` varchar(25) DEFAULT NULL,
		  `shop_createdby` int(3) DEFAULT NULL,
		  `shop_createddate` datetime DEFAULT NULL,
		  `shop_url` varchar(100) NOT NULL,
		  PRIMARY KEY (`shopid`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2";
		$exe1=mysql_query($sql1) or die(mysql_error());	

		$sql1="INSERT INTO `coupons_shops` (`shopid`, `shopname`, `shop_address`, `shop_city`, `shop_country`, `shop_status`, `shop_latitude`, `shop_longitude`, `shop_createdby`, `shop_createddate`, `shop_url`) VALUES
		(1, 'Best Buy Store', 'Tallahassee, Florida, USA', 1, 1, 'A', '', '', 1, '2011-06-04 17:25:45', '')";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="CREATE TABLE IF NOT EXISTS `general_settings` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `description` varchar(800) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `keywords` varchar(800) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `currency` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `currency_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		  `ref_amount` int(11) NOT NULL,
		  `email` varchar(150) NOT NULL,
		  `theme` varchar(150) NOT NULL,
		  `review_type` int(11) NOT NULL COMMENT '1=>social,2=>normal',
		  `admin_commission` int(11) NOT NULL,
		  `city_admin_commission` int(11) NOT NULL,
		  `paypal_account_type` int(11) NOT NULL COMMENT '0=>sandbox account 1=>live account ',
		  `paypal_account` varchar(500) NOT NULL,
		  `paypal_api_password` varchar(500) NOT NULL,
		  `paypal_api_signature` varchar(500) NOT NULL,
		  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  `min_fund` int(11) NOT NULL,
		  `max_fund` int(11) NOT NULL,
		  `site_in` int(11) NOT NULL COMMENT '1=>online,2=>offline',
		  `site_name` varchar(250) NOT NULL,
		  `default_language` varchar(50) NOT NULL,
		  `smtp_username` varchar(250) NOT NULL,
		  `smtp_password` varchar(250) NOT NULL,
		  `smtp_host` varchar(250) NOT NULL,
		  `facebook_share` varchar(250) NOT NULL,
		  `twitter_share` varchar(250) NOT NULL,
		  `linkedin_share` varchar(250) NOT NULL,
		  `fb_fanpage_url` varchar(250) NOT NULL,
		  `fb_apikey` varchar(250) NOT NULL,
		  `fb_secretkey` varchar(250) NOT NULL,
		  `fb_appid` varchar(100) NOT NULL,
		  `tw_apikey` varchar(250) NOT NULL,
		  `tw_secretkey` varchar(250) NOT NULL,
		  `gmap_apikey` varchar(500) NOT NULL,
		  `default_cityid` int(11) NOT NULL,
		  `site_license_key` varchar(500) NOT NULL,
		  `mobile_theme` varchar(200) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2";
		$exe1=mysql_query($sql1) or die(mysql_error());	

		$sql1="INSERT INTO `general_settings` (`id`, `name`, `description`, `keywords`, `currency`, `currency_code`, `ref_amount`, `email`, `theme`, `review_type`, `admin_commission`, `city_admin_commission`, `paypal_account_type`, `paypal_account`, `paypal_api_password`, `paypal_api_signature`, `update_date`, `min_fund`, `max_fund`, `site_in`, `site_name`, `default_language`, `smtp_username`, `smtp_password`, `smtp_host`, `facebook_share`, `twitter_share`, `linkedin_share`, `fb_fanpage_url`, `fb_apikey`, `fb_secretkey`, `fb_appid`, `tw_apikey`, `tw_secretkey`, `gmap_apikey`, `default_cityid`, `site_license_key`, `mobile_theme`) VALUES
(1, 'Ndotdeals - Opensource Groupon Clone Version 7.0 ', 'Ndotdeals - Opensource Groupon Clone Version 7.0', 'Ndotdeals - Opensource Groupon Clone Version 7.0,great deal offer across world,groupon clone,groupon,deals application,coupon application.', '$', 'USD', 20, 'demo@ndot.in', 'nightcity', 0, 0, 4, 0, 'platfo_1255077030_biz_api1.gmail.com', '1255077037', 'Abg0gYcQyxQvnf2HDJkKtA-p6pqhA1k-KTYE0Gcy1diujFio4io5Vqjf', '2011-09-07 23:55:57', 2, 150, 1, 'Ndotdeals - Opensource Groupon Clone Version 7.0', 'en', 'prakashbtechit@gmail.com', 'rrprakash1984', 'smtp.gmail.com', 'http://www.facebook.com/pages/NDOT/125148634186302', 'http://twitter.com/ndotindia', 'http://www.linkedin.com/companies/269461', 'http://www.facebook.com/pages/Ndotdeals-Free-Groupon-clone/114836728589994', 'bf17970d1555e2bf3d06fb25b780d8bb', '09585343ce7b30970ff30c22688f6bb8', '144107318996364', 'zvv1vdWI7XcIl8C9ryq5fQ', 'z8K7zNqn5vbHwF1qPtfaiqA6kur22Ql776sySwtiOU', 'ABQIAAAAjl9eclbKPSt-bio3fpZhuxQFLp0jpUD2DlktLj6yYVLY31ILmBRGf6e0NAlqit-BZYzBLOJ8Y3VNGw', 4, '123456', 'green')";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="CREATE TABLE IF NOT EXISTS `mobile_subscribers` (
		  `Id` int(11) NOT NULL AUTO_INCREMENT,
		  `mobileno` varchar(20) NOT NULL,
		  `city_id` int(11) NOT NULL,
		  `status` varchar(20) NOT NULL DEFAULT 'A',
		  PRIMARY KEY (`Id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="CREATE TABLE IF NOT EXISTS `modules` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `featured_deals` int(11) NOT NULL,
		  `newsletter` int(11) NOT NULL,
		  `category` int(11) NOT NULL,
		  `fanpage` int(11) NOT NULL,
		  `facebook_connect` int(11) NOT NULL,
		  `twitter_connect` int(11) NOT NULL,
		  `tweets_around_city` int(11) NOT NULL,
		  `mobile_subscribtion` int(11) NOT NULL,
		  `smtp` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2";
		$exe1=mysql_query($sql1) or die(mysql_error());	

		$sql1="INSERT INTO `modules` (`id`, `featured_deals`, `newsletter`, `category`, `fanpage`, `facebook_connect`, `twitter_connect`, `tweets_around_city`, `mobile_subscribtion`, `smtp`) VALUES
		(1, 1, 1, 1, 1, 1, 1, 0, 1, 1)";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `email` varchar(75) NOT NULL,
		  `city_id` int(11) NOT NULL,
		  `status` varchar(3) NOT NULL DEFAULT 'A',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="CREATE TABLE IF NOT EXISTS `slider_images` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `coupon_id` int(11) NOT NULL,
		  `imagename` varchar(25) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7";
		$exe1=mysql_query($sql1) or die(mysql_error());	

		$sql1="INSERT INTO `slider_images` (`id`, `coupon_id`, `imagename`) VALUES
		(1, 1, '1_1.jpg'),
		(2, 1, '1_2.jpg'),
		(3, 1, '1_3.jpg'),
		(4, 2, '2_1.jpg'),
		(5, 2, '2_2.jpg'),
		(6, 2, '2_3.jpg')";
		$exe1=mysql_query($sql1) or die(mysql_error());

		$sql1="CREATE TABLE IF NOT EXISTS `transaction_details` (
		  `PAYERID` varchar(25) NOT NULL,
		  `PAYERSTATUS` varchar(25) NOT NULL,
		  `COUNTRYCODE` char(2) NOT NULL,
		  `COUPONID` int(20) NOT NULL,
		  `TIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  `CORRELATIONID` varchar(50) NOT NULL,
		  `ACK` varchar(25) NOT NULL,
		  `FIRSTNAME` varchar(50) NOT NULL,
		  `LASTNAME` varchar(50) NOT NULL,
		  `TRANSACTIONID` varchar(50) NOT NULL,
		  `RECEIPTID` varchar(50) NOT NULL,
		  `TRANSACTIONTYPE` char(15) NOT NULL,
		  `PAYMENTTYPE` char(15) NOT NULL,
		  `ORDERTIME` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `AMT` float NOT NULL,
		  `CURRENCYCODE` char(3) NOT NULL,
		  `PAYMENTSTATUS` varchar(50) NOT NULL,
		  `PENDINGREASON` varchar(50) NOT NULL,
		  `REASONCODE` varchar(50) NOT NULL,
		  `L_QTY0` int(3) NOT NULL,
		  `USERID` int(11) NOT NULL,
		  `ID` int(11) NOT NULL AUTO_INCREMENT,
		  `EMAIL` varchar(255) NOT NULL,
		  `TYPE` int(1) NOT NULL COMMENT '(1-Paypal Payment)',
		  `CAPTURED` int(1) NOT NULL COMMENT '0-NO,1-YES',
		  `CAPTURED_TRANSACTION_ID` varchar(50) NOT NULL,
		  `CAPTURED_TIMESTAMP` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `CAPTURED_CORRELATIONID` varchar(50) NOT NULL,
		  `CAPTURED_ACK` varchar(50) NOT NULL,
		  `INVOICEID` int(20) NOT NULL,
		  `REFERRAL_AMOUNT` double NOT NULL,
		  PRIMARY KEY (`ID`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
		$exe1=mysql_query($sql1) or die(mysql_error());


		//****** Creating Table ends here*******//

		$docroot = DOCROOT;
		header("Location: $docroot");		
		exit;

		/*Installation Ends Here*/
	}//Second Request Process Ends here Download all kinds of movies, muscis, books and softwares @ http://wwww.pixerup.com and request for movies and books also
?>		

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>NDOT Installation</title>
<link rel="stylesheet" href="style.css" type="text/css">
        <script src="../public/js/jquery.js" type="text/javascript"></script>

    <script src="../public/themes/default/js/jquery.validate.js" type="text/javascript"></script>
    
    <script src="../public/js/jquery.validate.js" type="text/javascript"></script>
    
<SCRIPT language=JavaScript type=text/javascript>
function checkrequired(which) {
var pass=true;
if (document.images) {
for (i=0;i<which.length;i++) {
var tempobj=which.elements[i];
if (tempobj.name.substring(0,8)=="title" || tempobj.name.substring(0,8)=="email" || tempobj.name.substring(0,8)=="name" || tempobj.name.substring(0,8)=="password" || tempobj.name.substring(0,8)=="tbname") {
if (((tempobj.type=="text"||tempobj.type=="text"||tempobj.type=="text"||tempobj.type=="text")&&
tempobj.value=='')||(tempobj.type.toString().charAt(0)=="s"&&
tempobj.selectedIndex==0)) {
pass=false;
break;
}
}
}
}
if (!pass) {
shortFieldName=tempobj.name.substring(8,30).toUpperCase();
alert("Please enter all required fields.");
return false;
}
else
return true;
}

$(document).ready(
  function()
  {
	 // show
	   $("#yourtable").click(function()
	    {
		$("#tblshow").show("slow");
	    });
		 // hide
	   $("#ndottable").click(function()
	    {
		$("#tblshow").hide("slow");
	    }); 
  });
</script>
</head>
<body>	
    <div class="instal_outer">
<div class="instal_logo"></div>
<div class="instal_inner">
<h1>Ndot Package Install </h1>
<?php
if(isset($_GET['msg1']))
{
$msg1=$_GET['msg1'];
if($msg1==1)
echo '<font color=red>Please enter the your Users tables correctly or the package will not work properly!</font>';


if($msg1==2)
echo '<font color=red>Users table does not exist!</font>';
}
?>
<script type="text/javascript">

$(document).ready(function(){$("#install").validate();});

</script>
<form action="" method="post" name="install_form" id="install" >

<table border="0" align="center" cellpadding="5" cellspacing="5"  class="table2">
<tr><td width="516">
<table width="506" border="0" >
<tr><td width="212">Your Application Name
<td width="10">
<td width="8">:</td>
<td width="268"><input type="text" name="title" value="Ndotdeals Opensource Groupon Clone v7.0" class="required" title="Enter your Application Name"/></td></tr>
<tr><td>Admin Email id<td><td>:</td><td><input type="text" name="email" class="required" title="Enter an e-mail" /></td></tr>
<tr><td>Admin Name<td><td>:</td><td><input type="text" name="name" class="required" title="Enter the admin name" /></td></tr>
<tr><td>Admin Password<td><td>:</td><td><input type="password" name="password" class="required" title="Enter the password"/></td></tr>
<input type="hidden" name="table" value="ndottbl" id="ndottable" title="Create Ndot's User Table"/>
</table>
<table width="420" border="0">
<tr><td width="225" align="right"><input name="" type="reset" value="" class="reset"/><td width="13">
<td width="45"></td>
<td width="119"><input name="" type="submit" value="" class="next"/></td></tr>
</table>
</td>
  </table>
</td></tr></table>

</div>
    <div class="instal_footer">Copyright &copy;2011 ndot.in.</div>
    </div>
</body>
</html>
