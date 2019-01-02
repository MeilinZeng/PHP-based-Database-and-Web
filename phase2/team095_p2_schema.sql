ALTER USER root IDENTIFIED WITH mysql_native_password BY 'PASSWORD';

CREATE USER 'adxeed'@'localhost' IDENTIFIED WITH mysql_native_password BY 'adxeed';

create database 6400_phase2;

GRANT ALL PRIVILEGES ON * . * TO 'adxeed'@'localhost';


CREATE TABLE `User` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `email` varchar(50) NOT NULL,
 `name` varchar(50) NOT NULL,
 `pin` varchar(50) NOT NULL,
 PRIMARY KEY (`ID`),
 UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8



CREATE TABLE FollowUser (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `FolloweruserID` int(11) NOT NULL,
 `FolloweeuserID` int(11) NOT NULL,
 PRIMARY KEY (`ID`),
 KEY `FolloweeuserID` (`FolloweeuserID`),
 KEY `FolloweruserID` (`FolloweruserID`),
 CONSTRAINT `followuser_ibfk_1` FOREIGN KEY (`FolloweruserID`) REFERENCES `user` (`id`),
 CONSTRAINT `followuser_ibfk_2` FOREIGN KEY (`FolloweeuserID`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8



CREATE TABLE Category (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `category_type` varchar(50) NOT NULL,
 PRIMARY KEY (`ID`),
 UNIQUE KEY `category_type` (`category_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8



CREATE TABLE Corkboard (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `userID` int(11) NOT NULL,
 `categoryID` int(11) NOT NULL,
 `title` varchar(50) NOT NULL,
 `visibility` tinyint(1) NOT NULL,
 `create_date` date NOT NULL,
 `create_time` time NOT NULL,
 PRIMARY KEY (`ID`),
 UNIQUE KEY `title` (`title`),
 KEY `userID` (`userID`),
 KEY `categoryID` (`categoryID`),
 CONSTRAINT `corkboard_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`id`),
 CONSTRAINT `corkboard_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `category` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8



CREATE TABLE PublicCorkboard (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `corkboardID` int(11) NOT NULL,
 PRIMARY KEY (`ID`),
 KEY `corkboardID` (`corkboardID`),
 CONSTRAINT `publiccorkboard_ibfk_1` FOREIGN KEY (`corkboardID`) REFERENCES `corkboard` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8



CREATE TABLE PrivateCorkboard (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `password` varchar(50) NOT NULL,
 `corkboardID` int(11) NOT NULL,
 PRIMARY KEY (`ID`),
 KEY `corkboardID` (`corkboardID`),
 CONSTRAINT `privatecorkboard_ibfk_1` FOREIGN KEY (`corkboardID`) REFERENCES `corkboard` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8



CREATE TABLE WatchCorkboard (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `userID` int(11) NOT NULL,
 `corkboardID` int(11) NOT NULL,
 PRIMARY KEY (`ID`),
 KEY `userID` (`userID`),
 KEY `corkboardID` (`corkboardID`),
 CONSTRAINT `watchcorkboard_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`id`),
 CONSTRAINT `watchcorkboard_ibfk_2` FOREIGN KEY (`corkboardID`) REFERENCES `corkboard` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8



CREATE TABLE PushPin (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `url` varchar(255) NOT NULL,
 `updated_date` date NOT NULL,
 `updated_time` time NOT NULL,
 `description` varchar(50) NOT NULL,
 `corkboardID` int(11) NOT NULL,
 PRIMARY KEY (`ID`),
 UNIQUE KEY `url` (`url`),
 KEY `corkboardID` (`corkboardID`),
 CONSTRAINT `pushpin_ibfk_1` FOREIGN KEY (`corkboardID`) REFERENCES `corkboard` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8



CREATE TABLE Tags (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `pushpin_tags` varchar(50) DEFAULT NULL,
 `pushpinID` int(11) NOT NULL,
 PRIMARY KEY (`ID`),
 UNIQUE KEY `pushpin_tags` (`pushpin_tags`),
 KEY `pushpinID` (`pushpinID`),
 CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`pushpinID`) REFERENCES `pushpin` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8



CREATE TABLE `Comment` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `content` varchar(50) NOT NULL,
 `date_time` datetime NOT NULL,
 `userID` int(11) NOT NULL,
 `pushpinID` int(11) NOT NULL,
 PRIMARY KEY (`ID`),
 KEY `userID` (`userID`),
 KEY `pushpinID` (`pushpinID`),
 CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`id`),
 CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`pushpinID`) REFERENCES `pushpin` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8



CREATE TABLE Like_Unlike (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `userID` int(11) NOT NULL,
 `pushpinID` int(11) NOT NULL,
 PRIMARY KEY (`ID`),
 KEY `userID` (`userID`),
 KEY `pushpinID` (`pushpinID`),
 CONSTRAINT `like_unlike_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`id`),
 CONSTRAINT `like_unlike_ibfk_2` FOREIGN KEY (`pushpinID`) REFERENCES `pushpin` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8

