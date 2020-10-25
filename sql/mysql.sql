# phpMyAdmin SQL Dump
# version 2.5.3
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Nov 05, 2004 at 09:32 AM
# Server version: 4.0.15
# PHP Version: 4.3.3
# 
# Database : `dev2`
# 

# --------------------------------------------------------

#
# Table structure for table `xent_prj_project`
#

CREATE TABLE `xent_prj_project` (
    `ID_PROJECT`   INT(11)      NOT NULL AUTO_INCREMENT,
    `title`        VARCHAR(255) NOT NULL DEFAULT '',
    `introduction` TEXT         NOT NULL,
    `description`  TEXT         NOT NULL,
    `status`       INT(5)       NOT NULL DEFAULT '0',
    `priority`     INT(5)       NOT NULL DEFAULT '0',
    `pict`         VARCHAR(255)          DEFAULT NULL,
    `imagewhereto` INT(5)       NOT NULL DEFAULT '0',
    KEY `ID_PROJECT` (`ID_PROJECT`)
)
    ENGINE = ISAM
    AUTO_INCREMENT = 1;




