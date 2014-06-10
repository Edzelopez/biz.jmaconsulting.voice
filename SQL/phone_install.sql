/**
* Voice Broadcasy module for CIVICRM
*
* @author: Eftakhairul Islam <eftakhairul@gmail.com>
*/
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


CREATE TABLE IF NOT EXISTS `civicrm_voice_response` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `to_number` varchar(12) NOT NULL,
  `response_type` varchar(10) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `to_number` (`to_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `civicrm_voice_broadcast` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `to_number` varchar(12) NOT NULL,
  `from_number` varchar(12) NOT NULL,
  `message_file_name` varchar(100) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

SET FOREIGN_KEY_CHECKS=1;