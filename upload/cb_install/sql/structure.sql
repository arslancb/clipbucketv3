
--
-- Database: `ClipBucket v3.0`
-- Release by Arslan Hassan
-- Its an Early Beta (Intehai Beta)
-- For Surprise reasons only
--

-- --------------------------------------------------------

--
-- Table structure for table `cb_action_log`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}action_log` (
  `action_id` int(255) NOT NULL AUTO_INCREMENT,
  `action_type` varchar(60) NOT NULL,
  `action_username` varchar(60) NOT NULL,
  `action_userid` int(30) NOT NULL,
  `action_useremail` varchar(200) NOT NULL,
  `action_userlevel` int(11) NOT NULL,
  `action_ip` varchar(15) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `action_success` enum('yes','no') NOT NULL,
  `action_details` text NOT NULL,
  `action_link` text NOT NULL,
  `action_obj_id` int(255) NOT NULL,
  `action_done_id` int(255) NOT NULL,
  PRIMARY KEY (`action_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}admin_notes`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}admin_notes` (
  `note_id` int(225) NOT NULL AUTO_INCREMENT,
  `note` text CHARACTER SET ucs2 NOT NULL,
  `date_added` datetime NOT NULL,
  `userid` int(225) NOT NULL,
  PRIMARY KEY (`note_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}ads_data`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}ads_data` (
  `ad_id` int(50) NOT NULL AUTO_INCREMENT,
  `ad_name` mediumtext NOT NULL,
  `ad_code` mediumtext NOT NULL,
  `ad_placement` varchar(50) NOT NULL DEFAULT '',
  `ad_category` int(11) NOT NULL DEFAULT '0',
  `ad_status` enum('0','1') NOT NULL DEFAULT '0',
  `ad_impressions` bigint(255) NOT NULL DEFAULT '0',
  `last_viewed` datetime NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}ads_placements`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}ads_placements` (
  `placement_id` int(20) NOT NULL AUTO_INCREMENT,
  `placement` varchar(26) NOT NULL,
  `placement_name` varchar(50) NOT NULL,
  `disable` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`placement_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}collections`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}collections` (
  `collection_id` bigint(25) NOT NULL AUTO_INCREMENT,
  `collection_name` varchar(225) NOT NULL,
  `collection_description` text NOT NULL,
  `collection_tags` text NOT NULL,
  `category` varchar(200) NOT NULL,
  `userid` int(10) NOT NULL,
  `views` bigint(20) NOT NULL,
  `date_added` datetime NOT NULL,
  `featured` varchar(4) NOT NULL DEFAULT 'no',
  `broadcast` varchar(10) NOT NULL,
  `allow_comments` varchar(4) NOT NULL,
  `allow_rating` enum('yes','no') NOT NULL DEFAULT 'yes',
  `total_comments` bigint(20) NOT NULL,
  `last_commented` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `total_objects` bigint(20) NOT NULL,
  `cover_photo` bigint(20) NOT NULL,
  `rating` bigint(20) NOT NULL,
  `rated_by` bigint(20) NOT NULL,
  `voters` longtext NOT NULL,
  `active` varchar(4) NOT NULL,
  `public_upload` varchar(4) NOT NULL,
  `type` varchar(10) NOT NULL,
  `is_avatar_collection` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`collection_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}collection_categories`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}collection_categories` (
  `category_id` int(255) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL,
  `category_icon` varchar(100) NOT NULL,
  `category_order` int(5) NOT NULL,
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumint(9) NOT NULL,
  `isdefault` enum('yes','no') NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}collection_items`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}collection_items` (
  `ci_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `collection_id` bigint(20) NOT NULL,
  `object_id` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `type` varchar(10) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`ci_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}comments`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}comments` (
  `comment_id` int(60) NOT NULL AUTO_INCREMENT,
  `type` varchar(3) NOT NULL,
  `comment` text NOT NULL,
  `comment_attributes` text NOT NULL,
  `userid` int(60) NOT NULL,
  `anonym_name` varchar(255) NOT NULL,
  `anonym_email` varchar(255) NOT NULL,
  `parent_id` int(60) NOT NULL,
  `has_children` int(50) NOT NULL,
  `thread_id` int(255) NOT NULL,
  `type_id` int(225) NOT NULL,
  `type_owner_id` int(255) NOT NULL,
  `vote` varchar(225) NOT NULL,
  `voters` text NOT NULL,
  `spam_votes` bigint(20) NOT NULL,
  `spam_voters` text NOT NULL,
  `date_added` datetime NOT NULL,
  `comment_ip` text NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}config`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}config` (
  `configid` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`configid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}contacts`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}contacts` (
  `contact_id` int(225) NOT NULL AUTO_INCREMENT,
  `userid` int(225) NOT NULL,
  `contact_userid` int(225) NOT NULL,
  `confirmed` enum('yes','no') NOT NULL DEFAULT 'no',
  `contact_group_id` int(225) NOT NULL,
  `request_type` enum('in','out') NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}conversion_queue`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}conversion_queue` (
  `queue_id` int(11) NOT NULL AUTO_INCREMENT,
  `queue_name` varchar(32) CHARACTER SET latin1 NOT NULL,
  `queue_ext` varchar(5) CHARACTER SET latin1 NOT NULL,
  `queue_tmp_ext` varchar(3) CHARACTER SET latin1 NOT NULL,
  `file_directory` varchar(255) NOT NULL,
  `extras` text NOT NULL,
  `conversion` enum('yes','no','p') CHARACTER SET latin1 NOT NULL DEFAULT 'no',
  `conversion_counts` int(10) NOT NULL,
  `status` enum('u','s','f') NOT NULL DEFAULT 'u',
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `messages` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_started` varchar(32) NOT NULL,
  `time_completed` varchar(32) NOT NULL,
  PRIMARY KEY (`queue_id`),
  UNIQUE KEY `queue_name` (`queue_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}counters`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}counters` (
  `counter_id` int(100) NOT NULL AUTO_INCREMENT,
  `section` varchar(200) NOT NULL,
  `query` text NOT NULL,
  `query_md5` varchar(200) NOT NULL,
  `counts` bigint(200) NOT NULL,
  `date_added` varchar(200) NOT NULL,
  PRIMARY KEY (`counter_id`),
  UNIQUE KEY `query_md5` (`query_md5`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}countries`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}countries` (
  `country_id` int(80) NOT NULL AUTO_INCREMENT,
  `iso2` char(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `name_en` varchar(80) NOT NULL,
  `iso3` char(3) DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}editors_picks`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}editors_picks` (
  `pick_id` int(225) NOT NULL AUTO_INCREMENT,
  `videoid` int(225) NOT NULL,
  `sort` bigint(5) NOT NULL DEFAULT '1',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pick_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}email_templates`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}email_templates` (
  `email_template_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_template_name` varchar(225) NOT NULL,
  `email_template_code` varchar(225) NOT NULL,
  `email_template_subject` mediumtext NOT NULL,
  `email_template` text NOT NULL,
  `email_template_allowed_tags` mediumtext NOT NULL,
  PRIMARY KEY (`email_template_id`),
  UNIQUE KEY `email_template_code` (`email_template_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}favorites`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}favorites` (
  `favorite_id` int(225) NOT NULL AUTO_INCREMENT,
  `type` varchar(4) NOT NULL,
  `id` int(225) NOT NULL,
  `userid` int(225) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`favorite_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}feeds`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}feeds` (
  `feed_id` int(255) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `message_attributes` mediumtext NOT NULL,
  `userid` int(255) NOT NULL,
  `user` text NOT NULL,
  `content_id` int(255) NOT NULL,
  `content_cached_id` int(255) NOT NULL,
  `content_type` varchar(50) NOT NULL,
  `object_id` int(255) NOT NULL,
  `object_cached_id` int(255) NOT NULL,
  `object_type` varchar(50) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `action` varchar(200) NOT NULL,
  `action_group_id` int(11) NOT NULL,
  `is_activity` enum('yes','no') NOT NULL DEFAULT 'no',
  `privacy` varchar(200) NOT NULL,
  `comments_count` bigint(255) NOT NULL,
  `comments` text NOT NULL,
  `likes_count` bigint(255) NOT NULL,
  `likes` text NOT NULL,
  `date_added` datetime NOT NULL,
  `time_added` int(11) NOT NULL,
  `last_commented` datetime NOT NULL,
  `last_updated` varchar(200) NOT NULL,
  PRIMARY KEY (`feed_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}flags`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}flags` (
  `flag_id` int(225) NOT NULL AUTO_INCREMENT,
  `type` varchar(4) NOT NULL,
  `id` int(225) NOT NULL,
  `userid` int(225) NOT NULL,
  `flag_type` bigint(25) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`flag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}friend_requests`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}friend_requests` (
  `req_id` int(100) NOT NULL AUTO_INCREMENT,
  `userid` int(255) NOT NULL,
  `friend_id` int(255) NOT NULL,
  `message` varchar(200) NOT NULL,
  `seen` enum('yes','no') NOT NULL DEFAULT 'no',
  `ignored` enum('yes','no') NOT NULL DEFAULT 'no',
  `time_added` int(11) NOT NULL,
  PRIMARY KEY (`req_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}groups`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}groups` (
  `group_id` int(225) NOT NULL AUTO_INCREMENT,
  `group_name` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(255) NOT NULL,
  `group_admins` text NOT NULL,
  `group_description` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group_tags` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group_url` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group_privacy` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `video_type` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `post_type` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `active` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `date_added` datetime NOT NULL,
  `featured` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `total_views` bigint(225) NOT NULL,
  `total_videos` int(225) NOT NULL,
  `total_members` int(225) NOT NULL,
  `total_topics` int(225) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}group_categories`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_categories` (
  `category_id` int(225) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL DEFAULT '',
  `category_icon` varchar(100) NOT NULL,
  `category_order` int(5) NOT NULL DEFAULT '1',
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumtext NOT NULL,
  `isdefault` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}group_invitations`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_invitations` (
  `invitation_id` int(225) NOT NULL AUTO_INCREMENT,
  `group_id` int(225) NOT NULL,
  `userid` int(255) NOT NULL,
  `invited` int(225) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`invitation_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}group_members`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_members` (
  `group_mid` int(225) NOT NULL AUTO_INCREMENT,
  `group_id` int(225) NOT NULL,
  `userid` int(11) NOT NULL,
  `is_admin` enum('yes','no') NOT NULL DEFAULT 'no',
  `ban` enum('yes','no') NOT NULL DEFAULT 'no',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`group_mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}group_posts`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `post_content` text NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}group_topics`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_topics` (
  `topic_id` int(225) NOT NULL AUTO_INCREMENT,
  `topic_title` text NOT NULL,
  `userid` int(225) NOT NULL,
  `group_id` int(225) NOT NULL,
  `topic_post` text NOT NULL,
  `date_added` datetime NOT NULL,
  `last_poster` int(225) NOT NULL,
  `last_post_time` datetime NOT NULL,
  `total_views` bigint(225) NOT NULL,
  `total_replies` bigint(225) NOT NULL,
  `topic_icon` varchar(225) NOT NULL,
  `approved` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`topic_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}group_videos`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_videos` (
  `group_video_id` int(225) NOT NULL AUTO_INCREMENT,
  `videoid` int(255) NOT NULL,
  `group_id` int(225) NOT NULL,
  `userid` int(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`group_video_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}languages`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}languages` (
  `language_id` int(9) NOT NULL AUTO_INCREMENT,
  `language_code` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `language_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `language_regex` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `language_active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `language_default` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}mass_emails`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}mass_emails` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `email_subj` varchar(255) NOT NULL,
  `email_from` varchar(255) NOT NULL,
  `email_msg` text NOT NULL,
  `configs` text NOT NULL,
  `sent` bigint(255) NOT NULL,
  `total` bigint(20) NOT NULL,
  `users` text NOT NULL,
  `start_index` bigint(255) NOT NULL,
  `method` enum('browser','background') NOT NULL,
  `status` enum('completed','pending','sending') NOT NULL,
  `date_added` datetime NOT NULL,
  `last_update` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}messages`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}messages` (
  `message_id` int(225) NOT NULL AUTO_INCREMENT,
  `thread_id` bigint(255) NOT NULL,
  `userid` int(255) NOT NULL,
  `message` mediumtext NOT NULL,
  `subject` varchar(255) NOT NULL,
  `seen_by` mediumtext NOT NULL,
  `date_added` datetime NOT NULL,
  `time_added` int(11) NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `userid` (`userid`),
  KEY `thread_id` (`thread_id`),
  KEY `time_added` (`time_added`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}modules`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}modules` (
  `module_id` int(25) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(25) NOT NULL,
  `module_file` varchar(60) NOT NULL,
  `active` varchar(5) NOT NULL,
  `module_include_file` text NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}notifications`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `action` varchar(20) NOT NULL DEFAULT 'users',
  `actor` mediumtext NOT NULL,
  `actor_id` int(200) NOT NULL,
  `is_read` enum('yes','no') NOT NULL DEFAULT 'no',
  `time_added` int(11) NOT NULL,
  `elements` text NOT NULL,
  `date_added` datetime NOT NULL,
  `email_sent` enum('yes','no') NOT NULL DEFAULT 'no',
  `send_email` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`notification_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}objects_cache`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}objects_cache` (
  `object_id` int(255) NOT NULL AUTO_INCREMENT,
  `type_id` int(255) NOT NULL,
  `type` varchar(5) NOT NULL,
  `content` text NOT NULL,
  `last_updated` int(11) NOT NULL,
  `time_added` int(11) NOT NULL,
  PRIMARY KEY (`object_id`),
  KEY `object_type_id` (`type_id`),
  KEY `object_type_id_2` (`type_id`),
  KEY `object_type` (`type`),
  KEY `type_id` (`type_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}pages`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_order` bigint(100) NOT NULL,
  `display` enum('yes','no') NOT NULL DEFAULT 'yes',
  `page_name` varchar(225) NOT NULL,
  `page_title` varchar(225) NOT NULL,
  `page_content` text NOT NULL,
  `userid` int(225) NOT NULL,
  `active` enum('yes','no') NOT NULL,
  `delete_able` enum('yes','no') NOT NULL DEFAULT 'yes',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}photos`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}photos` (
  `photo_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `photo_key` mediumtext NOT NULL,
  `photo_title` mediumtext NOT NULL,
  `photo_description` mediumtext NOT NULL,
  `photo_tags` mediumtext NOT NULL,
  `photo_details` mediumtext NOT NULL,
  `userid` int(255) NOT NULL,
  `collection_id` int(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `last_viewed` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `views` bigint(255) NOT NULL,
  `allow_comments` enum('yes','no') NOT NULL DEFAULT 'yes',
  `allow_embedding` enum('yes','no') NOT NULL DEFAULT 'yes',
  `allow_tagging` enum('yes','no') NOT NULL DEFAULT 'yes',
  `featured` enum('yes','no') NOT NULL DEFAULT 'no',
  `reported` enum('yes','no') NOT NULL DEFAULT 'no',
  `allow_rating` enum('yes','no') NOT NULL DEFAULT 'yes',
  `broadcast` enum('public','private') NOT NULL DEFAULT 'public',
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `total_comments` int(255) NOT NULL,
  `ptags_count` int(255) NOT NULL,
  `has_exif` enum('yes','no') NOT NULL DEFAULT 'no',
  `last_commented` datetime NOT NULL,
  `total_favorites` int(255) NOT NULL,
  `rating` int(15) NOT NULL,
  `rated_by` int(25) NOT NULL,
  `voters` mediumtext NOT NULL,
  `filename` varchar(100) NOT NULL,
  `ext` char(5) NOT NULL,
  `downloaded` bigint(255) NOT NULL,
  `server_url` text NOT NULL,
  `owner_ip` varchar(20) NOT NULL,
  `is_avatar` enum('yes','no') NOT NULL DEFAULT 'no',
  `is_mature` enum('yes','no') NOT NULL DEFAULT 'no',
  `view_exif` enum('yes','no') NOT NULL DEFAULT 'yes',
  `file_directory` varchar(25) NOT NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}photosmeta`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}photosmeta` (
  `pmeta_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `photo_id` bigint(255) NOT NULL,
  `meta_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `meta_value` longtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`pmeta_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}photo_tags`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}photo_tags` (
  `ptag_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `ptag_key` varchar(32) CHARACTER SET utf8 NOT NULL,
  `ptag_width` int(10) NOT NULL,
  `ptag_height` int(10) NOT NULL,
  `ptag_top` int(10) NOT NULL,
  `ptag_left` int(10) NOT NULL,
  `ptag_userid` bigint(100) NOT NULL,
  `ptag_username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ptag_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ptag_isuser` int(4) NOT NULL,
  `ptag_isfriend` int(4) NOT NULL,
  `ptag_by_userid` bigint(100) NOT NULL,
  `ptag_by_username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ptag_by_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ptag_active` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'yes',
  `date_added` bigint(255) NOT NULL,
  `photo_id` bigint(255) NOT NULL,
  `photo_owner_userid` bigint(255) NOT NULL,
  `photo_owner_username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `photo_owner_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`ptag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}phrases`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}phrases` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang_iso` varchar(5) NOT NULL DEFAULT 'en',
  `varname` varchar(250) NOT NULL DEFAULT '',
  `text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}playlists`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}playlists` (
  `playlist_id` int(11) NOT NULL AUTO_INCREMENT,
  `playlist_name` varchar(225) CHARACTER SET latin1 NOT NULL,
  `userid` int(11) NOT NULL,
  `playlist_type` varchar(10) CHARACTER SET latin1 NOT NULL,
  `category` enum('normal','favorites','likes','history','quicklist','watch_later') NOT NULL DEFAULT 'normal',
  `description` mediumtext NOT NULL,
  `tags` mediumtext NOT NULL,
  `played` bigint(255) NOT NULL,
  `privacy` enum('public','private','unlisted') NOT NULL DEFAULT 'public',
  `allow_comments` enum('yes','no') NOT NULL DEFAULT 'yes',
  `allow_rating` enum('yes','no') NOT NULL DEFAULT 'yes',
  `total_comments` int(255) NOT NULL,
  `total_items` bigint(200) NOT NULL,
  `rating` bigint(3) NOT NULL,
  `rated_by` int(200) NOT NULL,
  `voters` text NOT NULL,
  `last_update` datetime NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`playlist_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}playlist_items`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}playlist_items` (
  `playlist_item_id` int(225) NOT NULL AUTO_INCREMENT,
  `object_id` int(225) NOT NULL,
  `playlist_id` int(225) NOT NULL,
  `item_order` bigint(10) NOT NULL,
  `item_note` mediumtext NOT NULL,
  `playlist_item_type` varchar(10) CHARACTER SET latin1 NOT NULL,
  `userid` int(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playlist_item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}plugins`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}plugins` (
  `plugin_id` int(255) NOT NULL AUTO_INCREMENT,
  `plugin_file` text NOT NULL,
  `plugin_folder` text NOT NULL,
  `plugin_version` float NOT NULL,
  `plugin_license_type` varchar(10) NOT NULL DEFAULT 'GPL',
  `plugin_license_key` varchar(5) NOT NULL,
  `plugin_license_code` text NOT NULL,
  `plugin_active` enum('yes','no') NOT NULL,
  PRIMARY KEY (`plugin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}plugin_config`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}plugin_config` (
  `plugin_config_id` int(223) NOT NULL AUTO_INCREMENT,
  `plugin_id_code` varchar(25) NOT NULL,
  `plugin_config_name` text NOT NULL,
  `plugin_config_value` text NOT NULL,
  `player_type` enum('built-in','plugin') NOT NULL DEFAULT 'built-in',
  `player_admin_file` text NOT NULL,
  `player_include_file` text NOT NULL,
  PRIMARY KEY (`plugin_config_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}recipients`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}recipients` (
  `recipient_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `userid` int(255) NOT NULL,
  `thread_id` bigint(20) NOT NULL,
  `unread_msgs` int(10) NOT NULL,
  `unseen_msgs` int(20) NOT NULL,
  `last_message_time` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `time_added` int(11) NOT NULL,
  PRIMARY KEY (`recipient_id`),
  KEY `thread_id` (`thread_id`),
  KEY `userid` (`userid`),
  KEY `userid_2` (`userid`),
  KEY `userid_3` (`userid`),
  KEY `thread_id_2` (`thread_id`),
  KEY `thread_id_3` (`thread_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}sessions`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `session` varchar(100) NOT NULL,
  `session_user` int(11) NOT NULL,
  `session_string` varchar(60) NOT NULL,
  `session_value` varchar(32) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `session_date` datetime NOT NULL,
  `current_page` text NOT NULL,
  `referer` text NOT NULL,
  `agent` text NOT NULL,
  `last_active` datetime NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}slugs`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}slugs` (
  `slug_id` int(255) NOT NULL AUTO_INCREMENT,
  `object_type` varchar(5) NOT NULL,
  `object_id` int(255) NOT NULL,
  `in_use` enum('yes','no') NOT NULL DEFAULT 'yes',
  `slug` mediumtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`slug_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}stats`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}stats` (
  `stat_id` int(255) NOT NULL AUTO_INCREMENT,
  `date_added` date NOT NULL,
  `video_stats` text NOT NULL,
  `user_stats` text NOT NULL,
  `group_stats` text NOT NULL,
  PRIMARY KEY (`stat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}subscriptions`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}subscriptions` (
  `subscription_id` int(225) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `subscribed_to` mediumtext NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'user',
  `time_added` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`subscription_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}subscription_emails_queue`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}subscription_emails_queue` (
  `queue_id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `object_type` varchar(10) NOT NULL,
  `status` enum('pending','sending','completed','cancelled') NOT NULL,
  `current_batch` varchar(80) NOT NULL,
  `date_added` date NOT NULL,
  `time_started` int(10) NOT NULL,
  `time_completed` int(10) NOT NULL,
  `error_log` tinytext NOT NULL,
  PRIMARY KEY (`queue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}template`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}template` (
  `template_id` int(20) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(25) NOT NULL,
  `template_dir` varchar(30) NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}threads`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}threads` (
  `thread_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `thread_type` enum('private','public') NOT NULL DEFAULT 'private',
  `userid` int(11) NOT NULL,
  `recipient_md5` varchar(32) NOT NULL,
  `total_recipients` bigint(100) NOT NULL,
  `main_recipients` mediumtext NOT NULL,
  `total_messages` bigint(100) NOT NULL,
  `last_message_id` int(255) NOT NULL,
  `last_message` tinytext NOT NULL,
  `subject` tinytext NOT NULL,
  `last_userid` int(255) NOT NULL,
  `last_message_date` datetime NOT NULL,
  `is_archived` enum('yes','no') NOT NULL DEFAULT 'no',
  `date_added` datetime NOT NULL,
  `time_added` int(11) NOT NULL,
  PRIMARY KEY (`thread_id`),
  UNIQUE KEY `recipient_md5` (`recipient_md5`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}users`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}users` (
  `userid` bigint(20) NOT NULL AUTO_INCREMENT,
  `category` int(20) NOT NULL,
  `featured_video` mediumtext NOT NULL,
  `username` text NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `user_session_key` varchar(32) NOT NULL,
  `user_session_code` int(5) NOT NULL,
  `password` varchar(40) NOT NULL DEFAULT '',
  `email` varchar(80) NOT NULL DEFAULT '',
  `status` enum('verified','unverified') NOT NULL DEFAULT 'unverified',
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `msg_notify` enum('yes','no') NOT NULL DEFAULT 'yes',
  `avatar` varchar(225) NOT NULL DEFAULT '',
  `avatar_url` text NOT NULL,
  `avatar_collection` bigint(255) NOT NULL,
  `sex` enum('male','female') NOT NULL DEFAULT 'male',
  `dob` date NOT NULL DEFAULT '0000-00-00',
  `country` varchar(20) NOT NULL DEFAULT 'PK',
  `level` int(6) NOT NULL DEFAULT '2',
  `avcode` mediumtext NOT NULL,
  `doj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_logged` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `num_visits` bigint(20) NOT NULL DEFAULT '0',
  `session` varchar(32) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `signup_ip` varchar(15) NOT NULL DEFAULT '',
  `time_zone` tinyint(4) NOT NULL DEFAULT '0',
  `featured` enum('no','yes') NOT NULL DEFAULT 'no',
  `featured_date` datetime NOT NULL,
  `profile_hits` bigint(20) DEFAULT '0',
  `total_watched` bigint(20) NOT NULL DEFAULT '0',
  `total_videos` bigint(20) NOT NULL,
  `total_comments` bigint(20) NOT NULL,
  `total_photos` bigint(255) NOT NULL,
  `total_collections` bigint(255) NOT NULL,
  `comments_count` bigint(20) NOT NULL,
  `last_commented` datetime NOT NULL,
  `voted` text NOT NULL,
  `ban_status` enum('yes','no') NOT NULL DEFAULT 'no',
  `upload` varchar(20) NOT NULL DEFAULT '1',
  `subscribers` bigint(225) NOT NULL DEFAULT '0',
  `total_subscriptions` bigint(255) NOT NULL,
  `background` mediumtext NOT NULL,
  `background_color` varchar(25) NOT NULL,
  `background_url` text NOT NULL,
  `background_repeat` enum('no-repeat','repeat','repeat-x','repeat-y') NOT NULL DEFAULT 'repeat',
  `background_attachement` enum('yes','no') NOT NULL DEFAULT 'no',
  `total_groups` bigint(20) NOT NULL,
  `last_active` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `banned_users` text NOT NULL,
  `welcome_email_sent` enum('yes','no') NOT NULL DEFAULT 'no',
  `total_downloads` bigint(255) NOT NULL,
  `album_privacy` enum('public','private','friends') NOT NULL DEFAULT 'private',
  PRIMARY KEY (`userid`),
  KEY `ind_status_doj` (`doj`),
  KEY `ind_status_id` (`userid`),
  KEY `ind_hits_doj` (`profile_hits`,`doj`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}user_categories`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_categories` (
  `category_id` int(225) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL DEFAULT '',
  `category_icon` varchar(100) NOT NULL,
  `category_order` int(5) NOT NULL DEFAULT '1',
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumtext NOT NULL,
  `isdefault` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}user_levels`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_levels` (
  `user_level_id` int(20) NOT NULL AUTO_INCREMENT,
  `user_level_active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `user_level_name` varchar(100) NOT NULL,
  `user_level_is_default` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`user_level_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}user_levels_permissions`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_levels_permissions` (
  `user_level_permission_id` int(22) NOT NULL AUTO_INCREMENT,
  `user_level_id` int(22) NOT NULL,
  `admin_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `allow_video_upload` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_video` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_channel` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_group` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_videos` enum('yes','no') NOT NULL DEFAULT 'yes',
  `avatar_upload` enum('yes','no') NOT NULL DEFAULT 'yes',
  `video_moderation` enum('yes','no') NOT NULL DEFAULT 'no',
  `member_moderation` enum('yes','no') NOT NULL DEFAULT 'no',
  `ad_manager_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `manage_template_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `group_moderation` enum('yes','no') NOT NULL DEFAULT 'no',
  `web_config_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `view_channels` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_groups` enum('yes','no') NOT NULL DEFAULT 'yes',
  `playlist_access` enum('yes','no') NOT NULL DEFAULT 'yes',
  `allow_channel_bg` enum('yes','no') NOT NULL DEFAULT 'yes',
  `private_msg_access` enum('yes','no') NOT NULL DEFAULT 'yes',
  `edit_video` enum('yes','no') NOT NULL DEFAULT 'yes',
  `download_video` enum('yes','no') NOT NULL DEFAULT 'yes',
  `admin_del_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `photos_moderation` enum('yes','no') NOT NULL DEFAULT 'no',
  `collection_moderation` enum('yes','no') NOT NULL DEFAULT 'no',
  `plugins_moderation` enum('yes','no') NOT NULL DEFAULT 'no',
  `tool_box` enum('yes','no') NOT NULL DEFAULT 'no',
  `plugins_perms` text NOT NULL,
  PRIMARY KEY (`user_level_permission_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}user_mentions`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_mentions` (
  `mention_id` int(255) NOT NULL AUTO_INCREMENT,
  `userid` int(255) NOT NULL,
  `who_id` int(255) NOT NULL,
  `who_type` varchar(10) NOT NULL,
  `feed_id` int(255) NOT NULL,
  `comment_id` int(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`mention_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}user_notifications`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_notifications` (
  `notification_id` int(255) NOT NULL AUTO_INCREMENT,
  `userid` int(255) NOT NULL,
  `new_msgs` int(20) NOT NULL,
  `new_notifications` int(20) NOT NULL,
  `new_friend_requests` int(20) NOT NULL,
  `date_updated` datetime NOT NULL,
  `time_updated` int(11) NOT NULL,
  PRIMARY KEY (`notification_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}user_permissions`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_permissions` (
  `permission_id` int(225) NOT NULL AUTO_INCREMENT,
  `permission_type` int(225) NOT NULL,
  `permission_name` varchar(225) NOT NULL,
  `permission_code` varchar(225) NOT NULL,
  `permission_desc` mediumtext NOT NULL,
  `permission_default` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `permission_code` (`permission_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}user_permission_types`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_permission_types` (
  `user_permission_type_id` int(225) NOT NULL AUTO_INCREMENT,
  `user_permission_type_name` varchar(225) NOT NULL,
  `user_permission_type_desc` mediumtext NOT NULL,
  PRIMARY KEY (`user_permission_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}user_profile`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_profile` (
  `user_profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `show_my_collections` enum('yes','no') NOT NULL DEFAULT 'yes',
  `userid` bigint(20) NOT NULL,
  `profile_title` mediumtext NOT NULL,
  `profile_desc` mediumtext NOT NULL,
  `featured_video` mediumtext NOT NULL,
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `avatar` varchar(225) NOT NULL DEFAULT 'no_avatar.jpg',
  `show_dob` enum('no','yes') DEFAULT 'no',
  `postal_code` varchar(20) NOT NULL DEFAULT '',
  `time_zone` tinyint(4) NOT NULL DEFAULT '0',
  `profile_tags` mediumtext,
  `web_url` varchar(200) NOT NULL DEFAULT '',
  `hometown` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `online_status` enum('online','offline','custom') NOT NULL DEFAULT 'online',
  `show_profile` enum('all','members','friends') NOT NULL DEFAULT 'all',
  `allow_comments` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `allow_ratings` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `allow_subscription` enum('yes','no') NOT NULL DEFAULT 'yes',
  `content_filter` enum('Nothing','On','Off') NOT NULL DEFAULT 'Nothing',
  `icon_id` bigint(20) NOT NULL DEFAULT '0',
  `browse_criteria` mediumtext,
  `about_me` mediumtext NOT NULL,
  `education` varchar(3) DEFAULT NULL,
  `schools` mediumtext NOT NULL,
  `occupation` mediumtext NOT NULL,
  `companies` mediumtext NOT NULL,
  `relation_status` varchar(15) DEFAULT NULL,
  `hobbies` mediumtext NOT NULL,
  `fav_movies` mediumtext NOT NULL,
  `fav_music` mediumtext NOT NULL,
  `fav_books` mediumtext NOT NULL,
  `background` mediumtext NOT NULL,
  `profile_video` int(255) NOT NULL,
  `profile_item` varchar(25) NOT NULL,
  `rating` tinyint(2) NOT NULL,
  `voters` text NOT NULL,
  `rated_by` int(150) NOT NULL,
  `show_my_videos` enum('yes','no') NOT NULL DEFAULT 'yes',
  `show_my_photos` enum('yes','no') NOT NULL DEFAULT 'yes',
  `show_my_subscriptions` enum('yes','no') NOT NULL DEFAULT 'yes',
  `show_my_subscribers` enum('yes','no') NOT NULL DEFAULT 'yes',
  `show_my_friends` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`user_profile_id`),
  KEY `ind_status_id` (`userid`),
  FULLTEXT KEY `profile_tags` (`profile_tags`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}validation_re`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}validation_re` (
  `re_id` int(25) NOT NULL AUTO_INCREMENT,
  `re_name` varchar(60) NOT NULL,
  `re_code` varchar(60) NOT NULL,
  `re_syntax` text NOT NULL,
  PRIMARY KEY (`re_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}video`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}video` (
  `videoid` bigint(20) NOT NULL AUTO_INCREMENT,
  `videokey` mediumtext NOT NULL,
  `slug_id` int(255) NOT NULL,
  `slug` mediumtext NOT NULL,
  `video_password` varchar(255) NOT NULL,
  `video_users` text NOT NULL,
  `username` text NOT NULL,
  `userid` int(11) NOT NULL,
  `title` text,
  `flv` mediumtext NOT NULL,
  `file_name` varchar(32) NOT NULL,
  `description` text,
  `tags` mediumtext NOT NULL,
  `category` varchar(200) NOT NULL DEFAULT '0',
  `category_parents` text NOT NULL,
  `broadcast` varchar(10) NOT NULL DEFAULT '',
  `location` mediumtext,
  `datecreated` date DEFAULT NULL,
  `country` mediumtext,
  `allow_embedding` char(3) NOT NULL DEFAULT '',
  `rating` int(15) NOT NULL DEFAULT '0',
  `rated_by` varchar(20) NOT NULL DEFAULT '0',
  `voter_ids` mediumtext NOT NULL,
  `allow_comments` char(3) NOT NULL DEFAULT '',
  `comment_voting` char(3) NOT NULL DEFAULT '',
  `comments_count` int(15) NOT NULL DEFAULT '0',
  `last_commented` datetime NOT NULL,
  `featured` char(3) NOT NULL DEFAULT 'no',
  `featured_date` datetime NOT NULL,
  `featured_description` mediumtext NOT NULL,
  `allow_rating` char(3) NOT NULL DEFAULT '',
  `active` char(3) NOT NULL DEFAULT '0',
  `favourite_count` varchar(15) NOT NULL DEFAULT '0',
  `playlist_count` varchar(15) NOT NULL DEFAULT '0',
  `views` bigint(22) NOT NULL DEFAULT '0',
  `last_viewed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `flagged` varchar(3) NOT NULL DEFAULT 'no',
  `duration` varchar(20) NOT NULL DEFAULT '00',
  `status` enum('Successful','Processing','Failed') NOT NULL DEFAULT 'Processing',
  `failed_reason` enum('none','max_duration','max_file','invalid_format','invalid_upload') NOT NULL DEFAULT 'none',
  `flv_file_url` text,
  `default_thumb` int(3) NOT NULL DEFAULT '1',
  `aspect_ratio` varchar(10) NOT NULL,
  `embed_code` text NOT NULL,
  `refer_url` text NOT NULL,
  `downloads` bigint(255) NOT NULL,
  `uploader_ip` varchar(20) NOT NULL,
  `mass_embed_status` enum('no','pending','approved') NOT NULL DEFAULT 'no',
  `is_hd` enum('yes','no') NOT NULL DEFAULT 'no',
  `ebay_epn_keywords` varchar(255) NOT NULL,
  `ebay_pre_desc` text NOT NULL,
  `unique_embed_code` varchar(50) NOT NULL,
  `mature_content` enum('yes','no') NOT NULL DEFAULT 'no',
  `remote_play_url` text NOT NULL,
  `server_ip` varchar(20) NOT NULL,
  `file_server_path` text NOT NULL,
  `files_thumbs_path` text NOT NULL,
  `file_thumbs_count` varchar(30) NOT NULL,
  `has_hq` enum('yes','no') NOT NULL DEFAULT 'no',
  `has_mobile` enum('yes','no') NOT NULL DEFAULT 'no',
  `filegrp_size` varchar(30) NOT NULL,
  `process_status` bigint(30) NOT NULL DEFAULT '0',
  `has_hd` enum('yes','no') NOT NULL DEFAULT 'no',
  `file_directory` varchar(10) NOT NULL,
  `version` float NOT NULL DEFAULT '2.6',
  `extras` text NOT NULL,
  PRIMARY KEY (`videoid`),
  FULLTEXT KEY `description` (`description`,`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}video_categories`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}video_categories` (
  `category_id` int(225) NOT NULL AUTO_INCREMENT,
  `parent_id` int(255) NOT NULL DEFAULT '0',
  `category_name` varchar(30) NOT NULL DEFAULT '',
  `category_icon` varchar(100) NOT NULL,
  `category_order` int(5) NOT NULL DEFAULT '1',
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumtext NOT NULL,
  `isdefault` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}video_favourites`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}video_favourites` (
  `fav_id` int(11) NOT NULL AUTO_INCREMENT,
  `videoid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`fav_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}video_files`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}video_files` (
  `file_id` int(255) NOT NULL AUTO_INCREMENT,
  `queue_id` int(255) NOT NULL,
  `file_name` varchar(32) NOT NULL,
  `raw_name` varchar(200) NOT NULL,
  `file_directory` varchar(200) NOT NULL,
  `extras` text NOT NULL,
  `original_source` varchar(255) NOT NULL,
  `is_original` enum('yes','no') NOT NULL DEFAULT 'no',
  `file_ext` varchar(5) NOT NULL,
  `output_results` text NOT NULL,
  `status` enum('p','s','f') NOT NULL,
  `profile_id` int(255) NOT NULL,
  `log_file` varchar(255) NOT NULL,
  `log` text NOT NULL,
  `date_completed` int(12) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}video_meta`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}video_meta` (
  `meta_id` int(255) NOT NULL AUTO_INCREMENT,
  `meta_name` varchar(255) NOT NULL,
  `videoid` int(255) NOT NULL,
  `meta_value` text NOT NULL,
  `extras` text NOT NULL,
  PRIMARY KEY (`meta_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}video_profiles`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}video_profiles` (
  `profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `format` varchar(100) NOT NULL,
  `ext` varchar(10) NOT NULL,
  `suffix` varchar(100) NOT NULL,
  `mobile` enum('yes','no') NOT NULL DEFAULT 'no',
  `height` smallint(5) NOT NULL,
  `width` smallint(5) NOT NULL,
  `profile_order` int(10) NOT NULL,
  `verify_dimension` enum('yes','no') NOT NULL DEFAULT 'yes',
  `video_codec` varchar(50) NOT NULL,
  `audio_codec` varchar(50) NOT NULL,
  `audio_bitrate` mediumint(50) NOT NULL,
  `video_bitrate` mediumint(50) NOT NULL,
  `audio_rate` mediumint(50) NOT NULL,
  `video_rate` mediumint(50) NOT NULL,
  `resize` enum('none','max','fit','wxh') NOT NULL DEFAULT 'max',
  `preset` enum('none','low','normal','hq','max') NOT NULL DEFAULT 'normal',
  `2pass` enum('yes','no') NOT NULL DEFAULT 'no',
  `apply_watermark` enum('yes','no') NOT NULL,
  `ffmpeg_cmd` mediumtext NOT NULL,
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`profile_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
