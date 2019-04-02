-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2019 at 12:36 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mini_imdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(50) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `type`, `value`) VALUES
(1, 'Language', 'English'),
(2, 'Language', 'Hindi'),
(4, 'Genre', 'Drama'),
(5, 'Genre', 'Comedy'),
(6, 'Genre', 'Horror'),
(7, 'Genre', 'Sci-Fi'),
(8, 'Genre', 'Fantasy'),
(9, 'Genre', 'Animation'),
(10, 'Genre', 'Crime'),
(11, 'Genre', 'Action'),
(12, 'Genre', 'Biography'),
(13, 'Genre', 'History'),
(14, 'Genre', 'Adventure'),
(15, 'Genre', 'Family '),
(16, 'Genre', 'Western '),
(17, 'Genre', 'Thriller '),
(18, 'Language', 'Marathi'),
(19, 'Genre', 'Time Pass'),
(20, 'Genre', 'Suspense');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(50) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `featured_image` varchar(100) DEFAULT NULL,
  `movie_length` varchar(50) DEFAULT NULL,
  `movie_rel_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `description`, `featured_image`, `movie_length`, `movie_rel_date`) VALUES
(1, 'The Godfather', 'The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.', 'img/movie_the_godfather01_04_201906_49_43godfather.jpg', '175', '1972-08-27'),
(2, 'The Shawshank Redemption', 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.', 'img/movie_the_shawshank_redemption01_04_201906_51_22the_shawshank_redemption.jpg', '142', '1995-02-17'),
(3, 'The Dark Knight', 'When the menace known as the Joker emerges from his mysterious past, he wreaks havoc and chaos on the people of Gotham.', 'img/movie_the_dark_knight01_04_201906_59_03the_dark_knight.jpg', '152', '2008-07-24'),
(4, '12 Angry Men', 'A jury holdout attempts to prevent a miscarriage of justice by forcing his colleagues to reconsider the evidence.', 'img/movie_12_angry_men01_04_201907_01_1112angrymen.jpg', '96', '1957-04-10'),
(5, 'Schindler s List', 'In German-occupied Poland during World War II, industrialist Oskar Schindler gradually becomes concerned for his Jewish workforce after witnessing their persecution by the Nazis.', 'img/movie_schindler_s_list01_04_201907_03_28schindler_s_list.jpg', '195', '1994-02-18'),
(6, 'The Lord of the Rings: The Return of the King ', 'Gandalf and Aragorn lead the World of Men against Sauron s army to draw his gaze from Frodo and Sam as they approach Mount Doom with the One Ring.', 'img/movie_the_lord_of_the_rings__the_return_of_the_king_01_04_201907_05_56the_lord_of_the_rings.jpg', '201', '2003-12-17'),
(7, 'Inception', 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a CEO.', 'img/movie_inception01_04_201907_09_03inception.jpg', '148', '2010-07-16'),
(8, 'Interstellar', 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity s survival.', 'img/movie_interstellar01_04_201907_10_44interstellar.jpg', '169', '2017-09-07'),
(9, 'WALL-E', 'In the distant future, a small waste-collecting robot inadvertently embarks on a space journey that will ultimately decide the fate of mankind.', 'img/movie_wall_e01_04_201907_13_26walle.jpg', '98', '2008-07-18'),
(10, 'Dangal', 'Former wrestler Mahavir Singh Phogat and his two wrestler daughters struggle towards glory at the Commonwealth Games in the face of societal oppression.', 'img/movie_dangal01_04_201907_15_22dangal.jpg', '161', '2016-12-22'),
(11, '3 Idiots', 'Two friends are searching for their long lost companion. They revisit their college days and recall the memories of their friend who inspired them to think differently, even as the rest of the world called them \"idiots\".', 'img/movie_3_idiots01_04_201907_16_223idiots.jpg', '170', '2009-12-24'),
(12, 'Toy Story 3', 'The toys are mistakenly delivered to a day-care center instead of the attic right before Andy leaves for college, and it s up to Woody to convince the other toys that they weren t abandoned and to return home.', 'img/movie_toy_story_301_04_201907_17_34toystory3.jpg', '103', '2010-07-19'),
(13, 'Inside Out', 'After young Riley is uprooted from her Midwest life and moved to San Francisco, her emotions - Joy, Fear, Anger, Disgust and Sadness - conflict on how best to navigate a new city, house, and school.', 'img/movie_inside_out01_04_201907_18_59insideout.jpg', '95', '2015-07-24'),
(14, 'The Terminator', 'A seemingly indestructible android is sent from 2029 to 1984 to assassinate a waitress, whose unborn son will lead humanity in a war against the machines, while a soldier from that war is sent to protect her at all costs.', 'img/movie_the_terminator01_04_201907_20_23terminator.jpg', '107', '1985-01-11');

-- --------------------------------------------------------

--
-- Table structure for table `relationship`
--

CREATE TABLE `relationship` (
  `id` int(50) NOT NULL,
  `category_id` int(50) DEFAULT NULL,
  `movie_id` int(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `relationship`
--

INSERT INTO `relationship` (`id`, `category_id`, `movie_id`) VALUES
(1, 1, 1),
(2, 10, 1),
(3, 4, 1),
(4, 1, 2),
(5, 4, 2),
(6, 1, 3),
(7, 11, 3),
(8, 10, 3),
(9, 4, 3),
(10, 1, 4),
(11, 4, 4),
(12, 1, 5),
(13, 12, 5),
(14, 4, 5),
(15, 13, 5),
(16, 1, 6),
(17, 14, 6),
(18, 4, 6),
(19, 8, 6),
(20, 1, 7),
(21, 11, 7),
(22, 14, 7),
(23, 7, 7),
(24, 1, 8),
(25, 14, 8),
(26, 4, 8),
(27, 7, 8),
(28, 1, 9),
(29, 9, 9),
(30, 14, 9),
(31, 4, 9),
(32, 2, 10),
(33, 11, 10),
(34, 12, 10),
(35, 4, 10),
(36, 2, 11),
(37, 5, 11),
(38, 4, 11),
(39, 1, 12),
(40, 9, 12),
(41, 14, 12),
(42, 5, 12),
(43, 1, 13),
(44, 9, 13),
(45, 14, 13),
(46, 5, 13),
(47, 1, 14),
(48, 11, 14),
(49, 7, 14);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `relationship`
--
ALTER TABLE `relationship`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `relationship`
--
ALTER TABLE `relationship`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
