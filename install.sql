-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2021-10-20 01:29:36
-- 服务器版本： 5.7.34-log
-- PHP 版本： 7.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `mh_jufb_cn`
--

-- --------------------------------------------------------

--
-- 表的结构 `mh_config`
--

CREATE TABLE `mh_config` (
  `k` varchar(255) NOT NULL,
  `v` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mh_config`
--

INSERT INTO `mh_config` (`k`, `v`) VALUES
('chouqu_money', '1'),
('daili_lirun', '50'),
('daili_lirun2', '20'),
('daili_money', '9.99'),
('daili_zdy', '0'),
('description', '智能建站系统平台创建'),
('epay_id', ''),
('epay_key', ''),
('epay_url', '/'),
('first_toudi', '0'),
('img1', '../public/index/img/lunbo/img_fc8321dd2f117d23d6735ebf5ab7bba5.png'),
('img1_url', '#'),
('img2', '../public/index/img/lunbo/img_780625d0d442d0f46d89709f990905e7.png'),
('img2_url', '#'),
('img3', '../public/index/img/lunbo/img_25d228a0feb2e0e4aab309fe83c8270b.png'),
('img3_url', '#'),
('jifen', '10'),
('keywords', '智能建站系统平台创建'),
('kfqq', '123456'),
('kfwx', '123456'),
('manghe_shenhe', '0'),
('manghe_vip', '1'),
('name', 'admin'),
('pwd', '123456'),
('sitename', '交友盲盒系统 - 刀客源码网'),
('sitetime', '2022-01-01'),
('title', '智能建站系统平台创建'),
('tixian', '1'),
('toudi_money', '1'),
('user_gonggao', '网络交友需谨慎！禁止大批量参与\"抽一个\"活动，禁止微商、电销、引流等类似行业参与，一经发现永久封禁IP和号码，忘悉知！'),
('user_url', 'baidu.com'),
('var', '1.0');

-- --------------------------------------------------------

--
-- 表的结构 `mh_manghe`
--

CREATE TABLE `mh_manghe` (
  `id` int(10) UNSIGNED NOT NULL,
  `site` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `birthday` varchar(255) NOT NULL,
  `sex` int(11) NOT NULL,
  `weixin` int(11) NOT NULL,
  `jieshao` text,
  `city` varchar(255) NOT NULL,
  `touxiang` text,
  `status` int(11) DEFAULT '1',
  `from_user` varchar(255) DEFAULT NULL,
  `for_user` varchar(255) DEFAULT NULL,
  `addtime` varchar(255) NOT NULL,
  `endtime` varchar(255) DEFAULT NULL,
  `orderid` varchar(255) NOT NULL,
  `ok` int(11) DEFAULT '0',
  `ip` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `mh_pay`
--

CREATE TABLE `mh_pay` (
  `id` int(11) UNSIGNED NOT NULL,
  `orderno` varchar(255) NOT NULL,
  `name` text NOT NULL,
  `addtime` varchar(255) NOT NULL,
  `endtime` varchar(255) DEFAULT NULL,
  `money` decimal(12,2) NOT NULL,
  `status` int(11) NOT NULL,
  `bz` text NOT NULL,
  `chouqu` varchar(255) DEFAULT NULL,
  `user` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `mh_point`
--

CREATE TABLE `mh_point` (
  `id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `money` decimal(12,2) NOT NULL,
  `type` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `addtime` varchar(255) NOT NULL,
  `orderid` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `mh_site`
--

CREATE TABLE `mh_site` (
  `id` int(11) NOT NULL,
  `type` int(1) DEFAULT '1',
  `url` varchar(255) DEFAULT NULL,
  `sitename` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `kfwx` varchar(255) NOT NULL,
  `kfqq` varchar(255) NOT NULL,
  `toudi` decimal(12,2) DEFAULT '1.00',
  `chouqu` decimal(12,2) DEFAULT '1.00',
  `daili` decimal(12,2) DEFAULT '1.00',
  `addtime` varchar(255) NOT NULL,
  `tx_zh` varchar(255) DEFAULT NULL,
  `tx_xm` varchar(255) DEFAULT NULL,
  `tx_skt` varchar(255) DEFAULT NULL,
  `tx_type` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `user` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `mh_tixian`
--

CREATE TABLE `mh_tixian` (
  `id` int(10) UNSIGNED NOT NULL,
  `money` decimal(12,2) NOT NULL,
  `addtime` varchar(255) NOT NULL,
  `endtime` varchar(255) DEFAULT NULL,
  `user` int(11) NOT NULL,
  `status` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `mh_user`
--

CREATE TABLE `mh_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `site` varchar(255) DEFAULT NULL COMMENT '分站',
  `upsite` int(11) NOT NULL COMMENT '上级站点',
  `user` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `rmb` decimal(12,2) DEFAULT '0.00',
  `jifen` varchar(255) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `sex` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `birthday` varchar(255) DEFAULT NULL,
  `weixin` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `jieshao` text,
  `touxiang` text,
  `addtime` varchar(255) NOT NULL,
  `viptime` varchar(255) DEFAULT NULL,
  `dltime` varchar(255) DEFAULT NULL,
  `qdtime` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转储表的索引
--

--
-- 表的索引 `mh_config`
--
ALTER TABLE `mh_config`
  ADD PRIMARY KEY (`k`);

--
-- 表的索引 `mh_manghe`
--
ALTER TABLE `mh_manghe`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `mh_pay`
--
ALTER TABLE `mh_pay`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `mh_point`
--
ALTER TABLE `mh_point`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `mh_site`
--
ALTER TABLE `mh_site`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `mh_tixian`
--
ALTER TABLE `mh_tixian`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `mh_user`
--
ALTER TABLE `mh_user`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `mh_manghe`
--
ALTER TABLE `mh_manghe`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- 使用表AUTO_INCREMENT `mh_pay`
--
ALTER TABLE `mh_pay`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=258;

--
-- 使用表AUTO_INCREMENT `mh_point`
--
ALTER TABLE `mh_point`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=257;

--
-- 使用表AUTO_INCREMENT `mh_site`
--
ALTER TABLE `mh_site`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `mh_tixian`
--
ALTER TABLE `mh_tixian`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `mh_user`
--
ALTER TABLE `mh_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
