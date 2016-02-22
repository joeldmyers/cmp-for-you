CREATE TABLE IF NOT EXISTS `data` (
  `id` int(11) NOT NULL auto_increment,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `full_name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

INSERT INTO `data` (`id`, `from_date`, `to_date`, `full_name`, `email`, `city`) VALUES
(1, '2011-11-22', '2011-11-26', 'Aaron P. Holt', 'AaronPHolt@teleworm.com', 'London'),
(2, '2011-11-23', '2011-11-26', 'Calvin N. Harwood', 'CalvinNHarwood@teleworm.com', 'New York'),
(3, '2011-11-20', '2011-12-14', 'Richard J. Mayo', 'RichardJMayo@teleworm.com', 'London'),
(4, '2011-11-11', '2011-11-30', 'Melissa L. Haffey', 'MelissaLHaffey@teleworm.com', 'London'),
(5, '2011-12-06', '2011-12-15', 'Orlando P. Lucas', 'OrlandoPLucas@teleworm.com', 'New York'),
(6, '2012-11-07', '2011-12-16', 'Tara R. Hale', 'TaraRHale@teleworm.com', 'Paris'),
(7, '2011-12-19', '2011-12-23', 'Stuart D. Jordan', 'StuartDJordan@teleworm.com', 'Paris'),
(8, '2011-12-25', '2011-12-30', 'John R. Howell', 'JohnRHowell@teleworm.com', 'Paris'),
(9, '2011-12-13', '2011-12-30', 'Nick P. Bueche', 'NickPBueche@teleworm.com', 'New York'),
(10, '2011-12-29', '2012-01-04', 'Solomon S. Moreno', 'SolomonSMoreno@teleworm.com', 'Paris');