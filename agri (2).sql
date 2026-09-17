-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2026 at 11:10 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agri`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogdata`
--

CREATE TABLE `blogdata` (
  `blogId` int(10) NOT NULL,
  `blogUser` varchar(256) NOT NULL,
  `fid` int(10) DEFAULT NULL,
  `blogTitle` varchar(256) NOT NULL,
  `blogContent` longtext NOT NULL,
  `blogTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `likes` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `blogdata`
--

INSERT INTO `blogdata` (`blogId`, `blogUser`, `fid`, `blogTitle`, `blogContent`, `blogTime`, `likes`) VALUES
(20, 'aadya', NULL, 'Modern Agriculture and Sustainable Farming', '<p>Agriculture is the backbone of our society and plays an important role in providing food and supporting the economy.</p>\r\n\r\n<p>Modern agriculture uses technology and innovative farming methods to improve crop production while reducing unnecessary use of resources.</p>\r\n\r\n<p>Sustainable farming focuses on protecting soil, saving water and maintaining a healthy environment. Farmers can use methods such as crop rotation, organic farming, drip irrigation and proper use of fertilizers.</p>\r\n\r\n<p>Technology is also transforming agriculture through smart farming, weather monitoring, modern agricultural equipment and digital marketplaces. These technologies help farmers make better decisions and connect directly with customers.</p>\r\n\r\n<p>By combining traditional farming knowledge with modern technology, we can create a productive, sustainable and profitable future for agriculture.</p>\r\n', '2026-08-12 10:00:50', 1),
(23, 'kavya', 6, '? Modern Agriculture: The Future of Smart Farming', '<h1>Modern Agriculture: The Future of Smart Farming</h1>\r\n\r\n<p>Agriculture is rapidly changing with the introduction of modern technologies. Traditional farming methods are increasingly being supported by <strong>Artificial Intelligence (AI), Internet of Things (IoT), drones, sensors, robotics, GPS, and data analytics</strong>. This technology-driven approach is commonly known as <strong>smart farming or precision agriculture</strong>.</p>\r\n\r\n<h2>What is Smart Farming?</h2>\r\n\r\n<p>Smart farming is the use of digital technologies to monitor crops, soil, weather, water, and farming activities. Sensors can collect real-time information about soil moisture and environmental conditions, while AI and data analytics can help farmers make better decisions about irrigation, fertilization, pest control, and harvesting.</p>\r\n\r\n<h2>Technologies Used in Smart Farming</h2>\r\n\r\n<p>Some important technologies include:</p>\r\n\r\n<ul>\r\n	<li>\r\n	<p><strong>IoT Sensors:</strong> Monitor soil moisture, temperature, humidity, and crop conditions.</p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Artificial Intelligence:</strong> Helps identify diseases, predict crop yields, and support farming decisions.</p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Drones:</strong> Capture aerial images to monitor crop health and identify problem areas.</p>\r\n	</li>\r\n	<li>\r\n	<p><strong>GPS and Satellite Technology:</strong> Helps farmers manage fields more precisely.</p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Robotics:</strong> Can assist with planting, monitoring, weeding, and harvesting.</p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Data Analytics:</strong> Converts farm data into useful information for better decision-making.</p>\r\n	</li>\r\n</ul>\r\n\r\n<h2>Benefits of Smart Farming</h2>\r\n\r\n<p>Smart farming can improve agricultural productivity while making better use of limited resources. Precision technologies can help farmers apply the right amount of water, fertilizer, pesticides, and seeds where they are needed. This can reduce waste, lower operating costs, and support environmental sustainability.</p>\r\n\r\n<p>Smart technology can also help farmers detect crop diseases and water stress earlier, allowing them to take preventive action and reduce potential crop losses.</p>\r\n\r\n<h2>The Future of Agriculture</h2>\r\n\r\n<p>The future of agriculture is likely to become increasingly <strong>data-driven, automated, and sustainable</strong>. AI, IoT, drones, robotics, satellite imagery, and advanced analytics are expected to play an important role in helping farmers respond to changing weather conditions, resource limitations, and growing food demands.</p>\r\n\r\n<p>However, challenges such as high initial investment, limited rural connectivity, technical complexity, and the need for farmer training must also be addressed.</p>\r\n\r\n<h2>Conclusion</h2>\r\n\r\n<p>Modern agriculture is not about replacing farmers with technology. It is about <strong>giving farmers better tools and information to make smarter decisions</strong>. With the right combination of traditional farming knowledge and modern technology, smart farming can contribute to higher productivity, efficient resource management, and a more sustainable agricultural future.</p>\r\n\r\n<p><strong>The future of farming is smart, connected, and sustainable.</strong></p>\r\n', '2026-08-12 14:01:38', 0),
(24, 'aadya', 4, 'Modern Agriculture: The Future of Sustainable Farming', '<p>Agriculture is one of the most important sectors in the world because it provides food, raw materials, and employment to millions of people. As the population continues to grow and climate conditions become more unpredictable, traditional farming methods alone may not be sufficient to meet future food demands. Modern agriculture combines traditional knowledge with technology and scientific methods to make farming more productive, sustainable, and efficient.</p>\r\n\r\n<h2>The Importance of Agriculture</h2>\r\n\r\n<p>Agriculture plays a major role in the economic and social development of a country. Farmers produce essential crops such as rice, wheat, vegetables, fruits, pulses, and oilseeds. Agriculture also supports industries that depend on agricultural products as raw materials.</p>\r\n\r\n<h2>Role of Technology in Agriculture</h2>\r\n\r\n<p>Technology is transforming the way farmers grow and manage crops. Modern technologies such as drones, GPS, sensors, automated irrigation systems, and satellite monitoring help farmers make better decisions. Artificial intelligence and machine learning can also be used to identify crop diseases, predict weather conditions, and analyze soil and crop data.</p>\r\n\r\n<h2>Sustainable Farming</h2>\r\n\r\n<p>Sustainable agriculture focuses on producing food while protecting natural resources for future generations. Techniques such as organic farming, crop rotation, rainwater harvesting, drip irrigation, composting, and integrated pest management can reduce environmental damage and improve soil health.</p>\r\n\r\n<h2>Smart Irrigation</h2>\r\n\r\n<p>Water is one of the most valuable resources in agriculture. Smart irrigation systems provide water according to the actual needs of crops. Drip irrigation and soil-moisture sensors can reduce water wastage while ensuring that plants receive sufficient moisture.</p>\r\n\r\n<h2>Challenges in Agriculture</h2>\r\n\r\n<p>Farmers face several challenges, including climate change, water scarcity, soil degradation, pests and diseases, fluctuating market prices, and limited access to modern technology. Providing farmers with better training, affordable technology, financial support, and reliable market information can help overcome these challenges.</p>\r\n\r\n<h2>Future of Agriculture</h2>\r\n\r\n<p>The future of agriculture is closely connected with digital technology and sustainable practices. Smart farms, precision agriculture, automated machinery, artificial intelligence, and data-driven decision-making can help farmers increase productivity while reducing costs and environmental impact.</p>\r\n\r\n<h2>Conclusion</h2>\r\n\r\n<p>Agriculture is not only the foundation of our food system but also an important part of economic development. By combining traditional farming knowledge with modern technology and sustainable practices, we can build a stronger and more efficient agricultural sector. Supporting farmers and adopting innovative farming methods will be essential for achieving food security and a sustainable future.</p>\r\n', '2026-08-13 12:28:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `blogfeedback`
--

CREATE TABLE `blogfeedback` (
  `blogId` int(10) NOT NULL,
  `comment` varchar(256) NOT NULL,
  `commentUser` varchar(256) NOT NULL,
  `commentPic` varchar(256) NOT NULL DEFAULT 'profile0.png',
  `commentTime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `blogfeedback`
--

INSERT INTO `blogfeedback` (`blogId`, `comment`, `commentUser`, `commentPic`, `commentTime`) VALUES
(19, 'Mast yarr', 'ThePhenom', 'profile0.png', '2018-02-25 13:09:54'),
(20, '“Great information. This is really useful for farmers and agriculture students.”', 'aadya', 'profile4.jpg', '2026-08-12 10:04:15'),
(22, 'helpfull.....?', 'aadya', 'profile4.jpg', '2026-08-12 13:41:56'),
(24, 'informative......', 'Ammu', 'profile0.png', '2026-08-13 14:17:32');

-- --------------------------------------------------------

--
-- Table structure for table `buyer`
--

CREATE TABLE `buyer` (
  `bid` int(100) NOT NULL,
  `bname` varchar(100) NOT NULL,
  `busername` varchar(100) NOT NULL,
  `bpassword` varchar(100) NOT NULL,
  `bhash` varchar(100) NOT NULL,
  `bemail` varchar(100) NOT NULL,
  `bmobile` varchar(100) NOT NULL,
  `baddress` text NOT NULL,
  `bactive` int(100) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `buyer`
--

INSERT INTO `buyer` (`bid`, `bname`, `busername`, `bpassword`, `bhash`, `bemail`, `bmobile`, `baddress`, `bactive`) VALUES
(4, 'Ammu', 'Ammu', '$2y$10$kwQqjkmbCE6WfqKjW/IeG.BzAlTSASvVq/mXXacviYJrhWGUdSy4C', 'c8c41c4a18675a74e01c8a20e8a0f662', 'ammu123@gmail.com', '7259426476', '725, Bhogadi', 0),
(5, 'abc', 'abc', '$2y$10$qdj/wOMGUvRZ2r/xMwerkOZpvo0LYgdEBFWI4iwUgB37e5uF5RbnW', '37bc2f75bf1bcfe8450a1a41c200364c', 'abc@gmail.com', '7259426476', 'mysuru', 0),
(6, 'test2', 'aadya', '$2y$10$FhR5VllYKfx6u50VifW5vuelwF2TXsEc9wh/d8vyQ3RufIiTkImDG', 'f5deaeeae1538fb6c45901d524ee2f98', 'aadya191@gmail.com', '9876543210', 'kavyapr12@gmail.com', 0);

-- --------------------------------------------------------

--
-- Table structure for table `farmer`
--

CREATE TABLE `farmer` (
  `fid` int(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `fusername` varchar(255) NOT NULL,
  `fpassword` varchar(255) NOT NULL,
  `fhash` varchar(255) NOT NULL,
  `femail` varchar(255) NOT NULL,
  `fmobile` varchar(255) NOT NULL,
  `faddress` text NOT NULL,
  `factive` int(255) NOT NULL DEFAULT 0,
  `frating` int(11) NOT NULL DEFAULT 0,
  `picExt` varchar(255) NOT NULL DEFAULT 'png',
  `picStatus` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `farmer`
--

INSERT INTO `farmer` (`fid`, `fname`, `fusername`, `fpassword`, `fhash`, `femail`, `fmobile`, `faddress`, `factive`, `frating`, `picExt`, `picStatus`) VALUES
(4, 'aadya', 'aadya', '$2y$10$3usyt9wOadG60DhXw1FmPu88m3LPgrJNy26p3jlvYIujkb3kUC4nG', '7eacb532570ff6858afd2723755ff790', 'aadya191@gmail.com', '9008805156', 'channapatna', 0, 0, 'jpg', 1),
(5, 'abc', 'abc', '$2y$10$3Vf6GyV.RCU9pLffUfPH3O7S8/IMEKSbE5EbHwBsHiVvHlgs6y9U.', '0584ce565c824b7b7f50282d9a19945b', 'abc@gmail.com', '7259426476', 'banglore', 0, 0, 'jpg', 1),
(6, 'kavya', 'kavya', '$2y$10$2li8WvwOhAuCGna4GQnCt.HJv4IXBu2eZckfULN8jCmtR.y/1bk56', '66808e327dc79d135ba18e051673d906', 'kavyapr75@gmail.com', '7259426476', 'mysuru', 0, 0, 'jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fproduct`
--

CREATE TABLE `fproduct` (
  `fid` int(255) NOT NULL,
  `pid` int(255) NOT NULL,
  `product` varchar(255) NOT NULL,
  `pcat` varchar(255) NOT NULL,
  `pinfo` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `pimage` varchar(255) NOT NULL DEFAULT 'blank.png',
  `picStatus` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `fproduct`
--

INSERT INTO `fproduct` (`fid`, `pid`, `product`, `pcat`, `pinfo`, `price`, `pimage`, `picStatus`) VALUES
(4, 1, 'Onion', 'Vegetable', 'Fresh red onions directly supplied by the farmer. These onions are fresh, healthy and suitable for daily cooking.', 50, 'Onion4.jpeg', 1),
(4, 2, 'Dragan fruit', 'Fruit', 'Fresh and high-quality onions directly from the farm.\r\nSuitable for cooking and daily household use.\r\nFreshly harvested and carefully packed.', 200, 'Dragan fruit4.jpeg', 1),
(4, 3, 'Wheat', 'Grains', 'Fresh, high-quality wheat grains directly supplied by the farmer. Clean, nutritious and suitable for making flour, bread and other food products.', 400, 'Wheat4.jpeg', 1),
(6, 7, 'pappaya', 'Fruit', 'fruit\r\n', 80, 'pappaya6.jpeg', 1),
(6, 8, 'sweet corn', 'Vegetable', 'Sweet Corn is a fresh and nutritious vegetable known for its naturally sweet taste and tender kernels. It is rich in carbohydrates, fiber, vitamins, and minerals. Sweet corn can be boiled, roasted, grilled, or added to soups, salads, and other dishes.', 60, 'sweet corn6.jpeg', 1),
(4, 9, 'Rice', 'Grains', 'rice', 460, 'Rice4.jpeg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `likedata`
--

CREATE TABLE `likedata` (
  `blogId` int(10) NOT NULL,
  `blogUserId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `likedata`
--

INSERT INTO `likedata` (`blogId`, `blogUserId`) VALUES
(20, 4),
(24, 4);

-- --------------------------------------------------------

--
-- Table structure for table `mycart`
--

CREATE TABLE `mycart` (
  `bid` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `mycart`
--

INSERT INTO `mycart` (`bid`, `pid`, `quantity`) VALUES
(5, 7, 1),
(5, 8, 1),
(4, 8, 1),
(4, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `bid`, `total_amount`, `name`, `mobile`, `email`, `city`, `pincode`, `address`, `order_date`, `status`) VALUES
(18, 4, 400.00, 'aadya', '9008805156', 'kavyapr12@gmail.com', 'Mysuru', '570009', '725, Bhogadi\r\nMysuru', '2026-08-13 09:14:07', 'Delivered'),
(19, 4, 600.00, 'aadya', '9008805156', 'kavyapr12@gmail.com', 'Mysuru', '570009', '725, Bhogadi\r\nMysuru', '2026-08-13 14:16:57', 'Delivered'),
(20, 4, 280.00, 'jaaanu', '7259426476', 'kavyapr12@gmail.com', 'Mysuru', '570009', '725, Bhogadi\r\nMysuru', '2026-08-13 15:04:03', 'Delivered'),
(21, 4, 140.00, 'jaaanu', '7259426476', 'kavyapr12@gmail.com', 'Mysuru', '570009', '725, Bhogadi\r\nMysuru', '2026-08-14 05:25:13', 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `pid`, `quantity`, `price`) VALUES
(30, 18, 3, 1, 400.00),
(31, 19, 7, 5, 80.00),
(32, 19, 5, 1, 200.00),
(33, 20, 2, 1, 200.00),
(34, 20, 7, 1, 80.00),
(35, 21, 8, 1, 60.00),
(36, 21, 7, 1, 80.00);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `pid` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `rating` int(10) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `tid` int(10) NOT NULL,
  `bid` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pincode` varchar(255) NOT NULL,
  `addr` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`tid`, `bid`, `pid`, `name`, `city`, `mobile`, `email`, `pincode`, `addr`) VALUES
(1, 3, 28, 'sa,j,cns', 'sajc', 'sajch', 'kmendki98@gmail.com', 'sacu', 'ckaskjc');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogdata`
--
ALTER TABLE `blogdata`
  ADD PRIMARY KEY (`blogId`);

--
-- Indexes for table `buyer`
--
ALTER TABLE `buyer`
  ADD PRIMARY KEY (`bid`),
  ADD UNIQUE KEY `bid` (`bid`);

--
-- Indexes for table `farmer`
--
ALTER TABLE `farmer`
  ADD PRIMARY KEY (`fid`),
  ADD UNIQUE KEY `fid` (`fid`);

--
-- Indexes for table `fproduct`
--
ALTER TABLE `fproduct`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `likedata`
--
ALTER TABLE `likedata`
  ADD KEY `blogId` (`blogId`),
  ADD KEY `blogUserId` (`blogUserId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`tid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogdata`
--
ALTER TABLE `blogdata`
  MODIFY `blogId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `buyer`
--
ALTER TABLE `buyer`
  MODIFY `bid` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `farmer`
--
ALTER TABLE `farmer`
  MODIFY `fid` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `fproduct`
--
ALTER TABLE `fproduct`
  MODIFY `pid` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `tid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `likedata`
--
ALTER TABLE `likedata`
  ADD CONSTRAINT `likedata_ibfk_1` FOREIGN KEY (`blogId`) REFERENCES `blogdata` (`blogId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
