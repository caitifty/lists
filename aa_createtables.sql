/*   
    Copyright (C) 2023 Peter J. Davidson

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published
    by the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program, in the file LICENSE.txt.  If not, see <https://www.gnu.org/licenses/>.
*/


/*

To generate password hashes, do:

php -r 'echo password_hash("[yourpassword]", PASSWORD_DEFAULT)."\n";'

*/

use `mysql`;
CREATE USER 'lists'@'[sql server IP or localhost]' IDENTIFIED BY '[password hash]' REQUIRE SSL;
GRANT ALL PRIVILEGES ON lists . * TO 'lists'@'[sql server IP or localhost]';
FLUSH PRIVILEGES;


drop database if exists `lists`;
create database `lists`;
use `lists`;

DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
    `uniqueid` integer NOT NULL AUTO_INCREMENT COMMENT 'Table-specific unique auto-incrementing row identifier',
    `itemuid` integer NOT NULL COMMENT 'Outcome item unique id',
    `outcome` text NOT NULL COMMENT 'Actual outcome being sought',
    `duedate` date DEFAULT NULL COMMENT 'Due date if exists',
    `impact_family` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Likert scale for impact on family if not done by date',
    `impact_social` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Likert scale for impact on friends/work relationships if not done by date',
    `impact_career` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Likert scale for impact on career if not done by date',
    `impact_personal` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Likert scale for impact on own well being/health if not done by date',
    `impact_fiveyears` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Likert scale for importance of task in 5 years if not done',
    `impact_urgency` float NOT NULL DEFAULT 0 COMMENT 'Weighted daily updated urgency score based on days remaining',
    `impact_total` float NOT NULL DEFAULT 0 COMMENT 'Weighted sum of impacts for ranking',
    `urgency` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Likert scale for overall urgency (alternate scale attempt)',
    `importance` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Likert scale for overall importance (alternate scale attempt)',
    `urgentsortscore` float NOT NULL DEFAULT 50 COMMENT 'Rank order more/less urgent than other tasks (alternate scale attempt 2)',
    `importantsortscore` float NOT NULL DEFAULT 50 COMMENT 'Rank order more/less important than other tasks (alternate scale attempt 2)',
    `universalsortscore` float NOT NULL DEFAULT 0 COMMENT 'August 2024 universal sortscore',
    `timeout` date NOT NULL COMMENT 'If set to a date after 1970-01-01 will not display until the set date in most display screens',
    `periodic` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 if regular item, 1 if item only displays on timeout date',
    `dotoday` date DEFAULT NULL COMMENT 'Set to today\'s date to create a day-specific todo list',
    `suspended` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Set to 1 if on suspended list else 0',
    `suspenddate` date DEFAULT NULL COMMENT 'Date suspend list status ends',
    `suspendreason` text DEFAULT NULL COMMENT 'Waiting for..?',
    `completed` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 if outcome not reached, 1 if reached or abandoned',
    `completeddate` datetime DEFAULT NULL COMMENT 'Timestamp for when item marked as completed',
  PRIMARY KEY (`uniqueid`)
);


DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
    `uniqueid` integer NOT NULL AUTO_INCREMENT COMMENT 'Table-specific unique auto-incrementing row identifier',
    `itemuniqueid` integer NOT NULL COMMENT 'Outcome item this task belongs to',
    `task` text NOT NULL COMMENT 'Description of the task',
    `taskorder` integer NOT NULL DEFAULT 1 COMMENT 'If multiple tasks, which number is this one in the task order',
    `completed` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 if outcome not reached, 1 if reached or abandoned',
    `completeddate` datetime DEFAULT NULL COMMENT 'Timestamp for when task marked as completed',
  PRIMARY KEY (`uniqueid`)
);


DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
    `uniqueid` integer NOT NULL AUTO_INCREMENT COMMENT 'Table-specific unique auto-incrementing row identifier',
    `itemuniqueid` integer NOT NULL COMMENT 'Outcome item this task belongs to',
    `tag` text NOT NULL COMMENT 'Description of the tag',
  PRIMARY KEY (`uniqueid`)
);



DROP TABLE IF EXISTS users;
CREATE TABLE users (
  `uniqueid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Table-specific unique auto-incrementing row identifier',
  `username` varchar(36) NOT NULL COMMENT 'Username, one word, all lower case',
  `fullname` varchar(45) DEFAULT NULL COMMENT 'User\'s full name with correct capitalization',
  `pass` char(255) NOT NULL COMMENT 'User\'s hashed and salted password',
  `emailaddress` varchar(36) NOT NULL COMMENT 'User\'s email address',
  `disabled` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Flag showing whether the user\'s access to the database has been disabled. 1=yes.',
   PRIMARY KEY (`uniqueid`)
);

insert into users (username, fullname, pass, emailaddress, disabled) values ('[admin user username]', '[admin user full name]', '[admin user hashed password]','[admin user email address]', 0);


