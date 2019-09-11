CREATE TABLE `#__modern_tours_assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `small_description` text NOT NULL,
  `category` text NOT NULL,
  `location` text NOT NULL,
  `max_people` varchar(255) NOT NULL,
  `params` longtext NOT NULL,
  `cover` longtext NOT NULL,
  `imageFiles` longtext NOT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `extra_services` varchar(255) DEFAULT NULL,
  `related` varchar(255) DEFAULT NULL,
  `times` mediumtext,
  `bandates` mediumtext,
  `travellersData` mediumtext,
  `language` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__modern_tours_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` mediumtext,
  `language` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__modern_tours_coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `code` varchar(12) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `couponsnumber` int(11) NOT NULL,
  `pricepercent` int(11) NOT NULL,
  `pricetype` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__modern_tours_emails` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `params` mediumtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `#__modern_tours_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `fields` longtext NOT NULL,
  `formdata` longtext NOT NULL,
  `alias` varchar(255) NOT NULL,
  `user_fields` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__modern_tours_google_calendars` (
  `token` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__modern_tours_invoice` (
  `id` int(11) NOT NULL,
  `template` text,
  `currency` varchar(5) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__modern_tours_locations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` mediumtext,
  `language` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__modern_tours_reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `travellersData` mediumtext,
  `assets_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `paid_deposit` decimal(10,2) NOT NULL,
  `deposit` int(1) NOT NULL,
  `adults` int(3) NOT NULL,
  `children` int(3) NOT NULL,
  `people` int(3) NOT NULL,
  `registered` datetime NOT NULL,
  `coupon` varchar(255) DEFAULT NULL,
  `userData` mediumtext,
  `user_id` int(11) DEFAULT NULL,
  `fields_id` int(5) DEFAULT NULL,
  `unique_id` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__modern_tours_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `assets_id` int(11) NOT NULL,
  `rating` decimal(10,0) NOT NULL,
  `title` varchar(255) NOT NULL,
  `review` text NOT NULL,
  `date` text NOT NULL,
  `user_id` int(255) NOT NULL,
  `language` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__modern_tours_invoice` (`id`, `template`, `currency`) VALUES (9999, '<header class="clearfix"> <div class="block left" id="logo"> <img src="../media/com_modern_tours/img/logo.png" width="170px"> </div> <div class="block right" id="company"> <h2 class="name">Example Company</h2> <div>Joomla street 21 - 51, New York, USA</div> <div>(602) 519-0450</div> <div><a href="mailto:company@example.com">company@example.com</a></div> </div> </header> <main style=" position: relative; "> <div class="rel"><div id="details" class="clearfix"> <div class="block left client"> <div class="to">INVOICE TO: <br>{your-name} {your-surname}&nbsp; <br>{address}</div> <div class="address">{email}</div> </div> <div class="block right"> <h1>INVOICE {invoice_number}</h1> <div class="date">Date of Invoice: {pdf_date}</div> </div> </div></div> <table id="items" border="0" cellspacing="0" cellpadding="0"> <thead> <tr> <th class="no">#</th> <th class="desc">DESCRIPTION</th> <th class="unit">UNIT PRICE</th> <th class="qty">QUANTITY</th> <th class="total">TOTAL</th> </tr> </thead> <tbody> <tr> <td class="no">01</td> <td class="desc"><h3><i>{tour}</i> <br>{adults} adult(s) tickets </h3></td> <td class="unit">€{adultPrice}</td> <td class="qty">{adults}</td><td class="total">€{adultsPrice}<br></td> </tr><tr> <td class="no">02</td> <td class="desc"><h3><i><b>{tour}</b></i> <br>{children} children tickets </h3></td> <td class="unit">€{childPrice}</td> <td class="qty">{children}</td><td class="total">€{childrenPrice}<br></td> </tr> </tbody> </table> <div class="price-list"><div id="total"><span class="move">Total price</span> € {price}<br> <span class="move">VAT ( 19% )</span>&nbsp;<span style="font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;">€&nbsp;</span><span style="font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif;">{only_vat_price}</span></div></div></main><div style=" clear: both; "></div>', '€');
