--
-- Table structure for table `email_verify`
--

CREATE TABLE IF NOT EXISTS `email_verify` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `old_email` varchar(255) NOT NULL,
  `new_email` varchar(255) NOT NULL,
  `code` varchar(200) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE IF NOT EXISTS `history` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_key` varchar(255) NOT NULL,
  `action_value` text NOT NULL,
  `action_date` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `mokejimai`
--

CREATE TABLE IF NOT EXISTS `mokejimai` (
  `orderid` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `amount` float NOT NULL,
  `currency` varchar(50) NOT NULL,
  `points` float NOT NULL,
  `ip` varchar(50) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `paygol`
--

CREATE TABLE IF NOT EXISTS `paygol` (
  `orderid` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `amount` float NOT NULL,
  `currency` varchar(50) NOT NULL,
  `points` float NOT NULL,
  `ip` varchar(50) NOT NULL,
  `sender_number` varchar(50) NOT NULL,
  `operator` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `pay_method` varchar(50) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `paypal`
--

CREATE TABLE IF NOT EXISTS `paypal` (
  `item_number` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `amount` float NOT NULL,
  `currency` varchar(50) NOT NULL,
  `txn_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `points` float NOT NULL,
  `ip` varchar(50) NOT NULL,
  `buyer_info` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `recovery`
--

CREATE TABLE IF NOT EXISTS `recovery` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `server` int(11) NOT NULL,
  `active_until` varchar(100) NOT NULL,
  `add_date` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(64) NOT NULL,
  `access` int(10) unsigned NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sms`
--

CREATE TABLE IF NOT EXISTS `sms` (
`id` int(11) NOT NULL,
  `sms_unique_id` varchar(255) NOT NULL,
  `sms_keyword` varchar(255) NOT NULL,
  `sms_price` varchar(50) NOT NULL,
  `sms_currency` varchar(50) NOT NULL,
  `sms_response` text NOT NULL,
  `sms_date` datetime NOT NULL,
  `sms_type` varchar(20) NOT NULL,
  `sms_from` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `code` varchar(30) NOT NULL,
  `username` varchar(255) NOT NULL,
  `balance` float NOT NULL,
  `server` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_status` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `email_verify`
--
ALTER TABLE `email_verify`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mokejimai`
--
ALTER TABLE `mokejimai`
 ADD PRIMARY KEY (`orderid`);

--
-- Indexes for table `paygol`
--
ALTER TABLE `paygol`
 ADD PRIMARY KEY (`orderid`);

--
-- Indexes for table `paypal`
--
ALTER TABLE `paypal`
 ADD PRIMARY KEY (`item_number`);

--
-- Indexes for table `recovery`
--
ALTER TABLE `recovery`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms`
--
ALTER TABLE `sms`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `email_verify`
--
ALTER TABLE `email_verify`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `recovery`
--
ALTER TABLE `recovery`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms`
--
ALTER TABLE `sms`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;