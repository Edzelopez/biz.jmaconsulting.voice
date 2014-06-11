SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `civicrm_voice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `from_caller_name` varchar(200) DEFAULT NULL,
  `from_phone_id` int(11) NOT NULL,
  `from_number` varchar(20) DEFAULT NULL,
  `from_contact_id` int(11) NOT NULL,
  `is_primary` tinyint(1) NOT NULL,
  `phone_location` int(11) NOT NULL,
  `phone_type` int(11) NOT NULL,
  `is_track_call_disposition` tinyint(1) NOT NULL,
  `is_track_call_duration` tinyint(1) NOT NULL,
  `is_track_call_cost` tinyint(1) NOT NULL,
  `voice_message_file` varchar(200) NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `civicrm_voice_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voice_id` int(11) NOT NULL,
  `group_type` enum('Include','Exclude','Base') NOT NULL,
  `group_id` int(11) NOT NULL,
  `create_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `voice_id` (`voice_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `civicrm_voice_job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voice_id` int(11) NOT NULL,
  `schedule_datetime` datetime NOT NULL,
  `status` enum('Scheduled','Running','Complete','Paused','Canceled') NOT NULL,
  `create_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `civicrm_voice_recipients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voice_id` int(11) NOT NULL,
  `phone_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `voice_id` (`voice_id`,`phone_id`,`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `civicrm_voice_response` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voice_id` int(11) NOT NULL,
  `phone_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `disposition` varchar(10) DEFAULT NULL,
  `duration` varchar(10) DEFAULT NULL,
  `cost` varchar(10) DEFAULT NULL,
  `create_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `voice_id` (`voice_id`,`phone_id`,`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `civicrm_voice_spool` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `voice_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `recipient_number` varchar(20) NOT NULL,
  `added_at` datetime NOT NULL,
  `removed_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `voice_id` (`voice_id`,`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

SET FOREIGN_KEY_CHECKS=1;
