-- phpMyAdmin SQL Dump
-- version 4.1.9
-- http://www.phpmyadmin.net
--
-- 主機: 127.0.0.1
-- 產生時間： 2015 年 02 月 24 日 08:04
-- 伺服器版本: 5.5.32
-- PHP 版本： 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 資料庫： `padb`
--

-- --------------------------------------------------------

--
-- 資料表結構 `c2t`
--

CREATE TABLE IF NOT EXISTS `c2t` (
  `c2t_id` int(20) NOT NULL AUTO_INCREMENT COMMENT '序號',
  `c_id` int(20) NOT NULL COMMENT '班級序號',
  `t_id` varchar(12) NOT NULL COMMENT '教師序號',
  PRIMARY KEY (`c2t_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 資料表的匯出資料 `c2t`
--

INSERT INTO `c2t` (`c2t_id`, `c_id`, `t_id`) VALUES
(1, 1, 'teacher');

-- --------------------------------------------------------

--
-- 資料表結構 `class`
--

CREATE TABLE IF NOT EXISTS `class` (
  `c_id` int(20) NOT NULL AUTO_INCREMENT COMMENT '序號',
  `c_class` varchar(12) NOT NULL COMMENT '班級',
  `syear` varchar(4) NOT NULL COMMENT '學年',
  PRIMARY KEY (`c_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 資料表的匯出資料 `class`
--

INSERT INTO `class` (`c_id`, `c_class`, `syear`) VALUES
(1, 't_teacher_c', '1032');

-- --------------------------------------------------------

--
-- 資料表結構 `delworks`
--

CREATE TABLE IF NOT EXISTS `delworks` (
  `del_id` int(20) NOT NULL AUTO_INCREMENT,
  `s_id` varchar(27) NOT NULL,
  `m_id` int(20) NOT NULL,
  `w_name` varchar(30) NOT NULL,
  `w_desc` varchar(255) NOT NULL,
  `w_date` datetime NOT NULL,
  `del_date` datetime NOT NULL,
  PRIMARY KEY (`del_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `help`
--

CREATE TABLE IF NOT EXISTS `help` (
  `h_id` int(20) NOT NULL AUTO_INCREMENT,
  `m_id` int(20) NOT NULL,
  `h_word` varchar(10) NOT NULL,
  PRIMARY KEY (`h_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- 資料表的匯出資料 `help`
--

INSERT INTO `help` (`h_id`, `m_id`, `h_word`) VALUES
(1, 0, '創意十足'),
(2, 0, '五彩繽紛'),
(3, 0, '認真用心'),
(8, 0, '想像力豐富'),
(6, 0, '按部就班'),
(7, 0, '精確無誤'),
(9, 0, '用色大膽'),
(10, 0, '非常震撼'),
(11, 0, '功能創新'),
(12, 0, '活潑可愛'),
(13, 0, '表現細膩'),
(14, 0, '容易操作');

-- --------------------------------------------------------

--
-- 資料表結構 `m2c`
--

CREATE TABLE IF NOT EXISTS `m2c` (
  `m2c_id` int(20) NOT NULL AUTO_INCREMENT COMMENT ' 序號',
  `m_id` int(20) NOT NULL COMMENT '任務序號',
  `c_id` int(20) NOT NULL COMMENT '班級序號',
  `m2c_status` varchar(1) NOT NULL DEFAULT '0' COMMENT '狀態(0指派任務, 1開放繳交, 2開放互評, 3截止互評, 4截止自評)',
  PRIMARY KEY (`m2c_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `ms_id` int(20) NOT NULL AUTO_INCREMENT COMMENT '序號',
  `receiver` varchar(27) NOT NULL COMMENT '收件者',
  `sender` varchar(27) NOT NULL COMMENT '寄件者',
  `contents` text NOT NULL COMMENT '訊息內容',
  `ms_date` datetime NOT NULL COMMENT '寄件時間',
  `ms_read` varchar(1) NOT NULL DEFAULT '0' COMMENT '已閱讀(0尚未,1已閱讀)',
  `category` varchar(2) NOT NULL COMMENT '"類別 ( s1教師完成評審,  s2作品受到退件,  s3互評受到退件,  s4自評受到退件,  s5互評受到好評,  t1學生完成作品,  t2學生完成互評,  t3學生完成自評,  t4學生提出申訴,  t5學生重交作品)"',
  `mresult` text NOT NULL COMMENT '處理結果',
  PRIMARY KEY (`ms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `mission`
--

CREATE TABLE IF NOT EXISTS `mission` (
  `m_id` int(20) NOT NULL AUTO_INCREMENT COMMENT '序號',
  `m_name` varchar(30) NOT NULL COMMENT '任務名稱',
  `m_desc` text NOT NULL COMMENT '任務描述',
  `m_date` datetime NOT NULL COMMENT '建立日期',
  `m_start` varchar(30) NOT NULL COMMENT '開始時間',
  `m_stop` varchar(30) NOT NULL COMMENT '結束時間',
  `m_status` varchar(1) NOT NULL COMMENT '任務狀態(0尚未開始,1開放,2結束)',
  `syear` varchar(4) NOT NULL COMMENT '學年學期',
  `m_grade` varchar(1) NOT NULL COMMENT '年級',
  `t_id` varchar(12) NOT NULL COMMENT '教師序號',
  `m_spath` varchar(255) NOT NULL COMMENT '範例檔位置',
  `m_order` int(3) NOT NULL COMMENT '任務編號',
  `m_panums` varchar(2) NOT NULL COMMENT '互評人數',
  `m_proportion` varchar(4) NOT NULL DEFAULT '1' COMMENT '計分比重',
  PRIMARY KEY (`m_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `pareport`
--

CREATE TABLE IF NOT EXISTS `pareport` (
  `par_id` int(20) NOT NULL AUTO_INCREMENT,
  `txt_rid` int(20) NOT NULL,
  `par_stat` varchar(1) NOT NULL,
  PRIMARY KEY (`par_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `pg`
--

CREATE TABLE IF NOT EXISTS `pg` (
  `pg_id` int(20) NOT NULL AUTO_INCREMENT COMMENT '序號',
  `s_id` varchar(27) NOT NULL COMMENT '學生學號',
  `c_id` int(20) NOT NULL COMMENT '班級序號',
  `m_id` int(20) NOT NULL COMMENT '任務序號',
  `pg_member` varchar(150) NOT NULL COMMENT '互評對象',
  `pg_pas` varchar(20) NOT NULL COMMENT '互評狀態(0尚未, 1已儲存, 2已完成)(對照上一欄位)',
  PRIMARY KEY (`pg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `popularity`
--

CREATE TABLE IF NOT EXISTS `popularity` (
  `pop_id` int(20) NOT NULL AUTO_INCREMENT COMMENT '序號',
  `w_id` int(20) NOT NULL COMMENT '作品序號',
  `s_id` varchar(27) NOT NULL COMMENT '學號',
  `pop_date` datetime NOT NULL COMMENT '點擊日期',
  `w_type` int(1) NOT NULL DEFAULT '0' COMMENT '類型(0原作品,1修正後作品)',
  PRIMARY KEY (`pop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `progress2stu`
--

CREATE TABLE IF NOT EXISTS `progress2stu` (
  `p_id` int(20) NOT NULL AUTO_INCREMENT COMMENT '序號',
  `s_id` varchar(27) NOT NULL COMMENT '學號',
  `m_id` int(20) NOT NULL COMMENT '任務序號',
  `p_uploaded` varchar(1) NOT NULL DEFAULT '0' COMMENT '繳交(0否,1是)',
  `p_pa` varchar(1) NOT NULL DEFAULT '0' COMMENT '互評(0否,1是)',
  `p_sa` varchar(1) NOT NULL DEFAULT '0' COMMENT '自評(0否,1是)',
  PRIMARY KEY (`p_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `rework`
--

CREATE TABLE IF NOT EXISTS `rework` (
  `rew_id` int(20) NOT NULL AUTO_INCREMENT COMMENT '序號',
  `s_id` varchar(27) NOT NULL COMMENT '學號',
  `m_id` int(20) NOT NULL COMMENT '任務序號',
  `rew_name` varchar(30) NOT NULL COMMENT '檔案名稱',
  `rew_desc` varchar(255) NOT NULL COMMENT '檔案位置',
  `rew_date` datetime NOT NULL COMMENT '上傳日期',
  `t_status` varchar(1) NOT NULL COMMENT '教師評審狀態',
  `w_status` varchar(1) NOT NULL DEFAULT '0' COMMENT '狀態(1製作中,2已完成,3被退件)',
  `pop_point` int(5) NOT NULL DEFAULT '0' COMMENT '點擊數',
  `up_point` int(5) NOT NULL DEFAULT '0' COMMENT '進步點數',
  PRIMARY KEY (`rew_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `s2c`
--

CREATE TABLE IF NOT EXISTS `s2c` (
  `s2c_id` int(20) NOT NULL AUTO_INCREMENT COMMENT '序號',
  `syear` varchar(4) NOT NULL COMMENT '學年學期',
  `s_id` varchar(27) NOT NULL COMMENT '學生學號',
  `c_id` varchar(3) NOT NULL COMMENT '班級序號',
  `s_classnums` varchar(4) NOT NULL COMMENT '座號',
  PRIMARY KEY (`s2c_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `scale`
--

CREATE TABLE IF NOT EXISTS `scale` (
  `sca_id` int(20) NOT NULL AUTO_INCREMENT,
  `m_id` int(20) NOT NULL,
  `sca_directions` text NOT NULL,
  `sca_n` varchar(1) NOT NULL,
  `sca_order` varchar(2) NOT NULL,
  `sca_word` varchar(100) NOT NULL,
  PRIMARY KEY (`sca_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `scaler`
--

CREATE TABLE IF NOT EXISTS `scaler` (
  `sca_rid` int(20) NOT NULL AUTO_INCREMENT,
  `s_id` varchar(27) NOT NULL,
  `pg_sid` varchar(27) NOT NULL,
  `m_id` int(20) NOT NULL,
  `sca_reply` varchar(1) NOT NULL,
  `sca_id` int(20) NOT NULL COMMENT '單選題序號',
  PRIMARY KEY (`sca_rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `scaleretr`
--

CREATE TABLE IF NOT EXISTS `scaleretr` (
  `sca_retrid` int(20) NOT NULL AUTO_INCREMENT COMMENT '序號',
  `t_id` varchar(12) NOT NULL COMMENT '教師序號',
  `pg_sid` varchar(10) NOT NULL COMMENT '被評者學號',
  `sca_reply` varchar(1) NOT NULL COMMENT '填答內容',
  `sca_id` int(20) NOT NULL COMMENT '單選題序號',
  PRIMARY KEY (`sca_retrid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `scaletr`
--

CREATE TABLE IF NOT EXISTS `scaletr` (
  `sca_trid` int(20) NOT NULL AUTO_INCREMENT COMMENT '序號',
  `t_id` varchar(12) NOT NULL COMMENT '教師序號',
  `pg_sid` varchar(10) NOT NULL COMMENT '被評者學號',
  `sca_reply` varchar(1) NOT NULL COMMENT '填答內容',
  `sca_id` int(20) NOT NULL COMMENT '單選題序號',
  PRIMARY KEY (`sca_trid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `stu`
--

CREATE TABLE IF NOT EXISTS `stu` (
  `s_id` varchar(27) NOT NULL,
  `s_pass` varchar(20) NOT NULL,
  `s_name` varchar(18) NOT NULL,
  `s_sex` varchar(1) NOT NULL,
  `c_id` int(20) NOT NULL COMMENT '班級序號',
  `s_classnums` varchar(4) NOT NULL,
  `work_point` int(5) NOT NULL DEFAULT '0' COMMENT '作品點數',
  `pa_point` int(5) NOT NULL DEFAULT '0' COMMENT '評審點數',
  `up_point` int(5) NOT NULL DEFAULT '0' COMMENT '進步點數'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `stu`
--

INSERT INTO `stu` (`s_id`, `s_pass`, `s_name`, `s_sex`, `c_id`, `s_classnums`, `work_point`, `pa_point`, `up_point`) VALUES
('t_teacher_01', '', '測試生01號', '1', 1, '1', 0, 0, 0),
('t_teacher_02', '', '測試生02號', '0', 1, '2', 0, 0, 0),
('t_teacher_03', '', '測試生03號', '1', 1, '3', 0, 0, 0),
('t_teacher_04', '', '測試生04號', '0', 1, '4', 0, 0, 0),
('t_teacher_05', '', '測試生05號', '1', 1, '5', 0, 0, 0),
('t_teacher_06', '', '測試生06號', '0', 1, '6', 0, 0, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `system`
--

CREATE TABLE IF NOT EXISTS `system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `syear` varchar(4) NOT NULL,
  `adminid` varchar(6) NOT NULL,
  `adminpass` varchar(8) NOT NULL,
  `adminname` varchar(18) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 資料表的匯出資料 `system`
--

INSERT INTO `system` (`id`, `syear`, `adminid`, `adminpass`, `adminname`) VALUES
(1, '1032', 'admin', '12345678', '管理員');

-- --------------------------------------------------------

--
-- 資料表結構 `taboo`
--

CREATE TABLE IF NOT EXISTS `taboo` (
  `taboo_id` int(20) NOT NULL AUTO_INCREMENT COMMENT '序號',
  `taboo_word` varchar(10) NOT NULL COMMENT '禁語',
  PRIMARY KEY (`taboo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- 資料表的匯出資料 `taboo`
--

INSERT INTO `taboo` (`taboo_id`, `taboo_word`) VALUES
(23, 'bad'),
(22, '爛'),
(16, '幹幹'),
(17, '糟'),
(18, '遜'),
(28, 'fuck'),
(20, '醜'),
(21, '難看'),
(24, '白癡'),
(25, '笨蛋'),
(26, 'wtf'),
(27, '大便'),
(29, '機掰'),
(30, '幹你娘'),
(31, '廢'),
(32, '評語參考詞');

-- --------------------------------------------------------

--
-- 資料表結構 `teacher`
--

CREATE TABLE IF NOT EXISTS `teacher` (
  `t_id` varchar(12) NOT NULL COMMENT '序號',
  `t_name` varchar(18) NOT NULL COMMENT '教師姓名',
  `t_account-X` varchar(6) NOT NULL COMMENT '班級序號',
  `t_pass` varchar(8) NOT NULL,
  PRIMARY KEY (`t_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `teacher`
--

INSERT INTO `teacher` (`t_id`, `t_name`, `t_account-X`, `t_pass`) VALUES
('teacher', '測試教師', '', '1234');

-- --------------------------------------------------------

--
-- 資料表結構 `text`
--

CREATE TABLE IF NOT EXISTS `text` (
  `txt_id` int(20) NOT NULL AUTO_INCREMENT,
  `m_id` int(20) NOT NULL,
  `txt_directions` text NOT NULL,
  `txt_order` varchar(2) NOT NULL,
  PRIMARY KEY (`txt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `textr`
--

CREATE TABLE IF NOT EXISTS `textr` (
  `txt_rid` int(20) NOT NULL AUTO_INCREMENT,
  `s_id` varchar(27) NOT NULL,
  `pg_sid` varchar(27) NOT NULL,
  `m_id` int(20) NOT NULL,
  `txt_reply` text NOT NULL,
  `txt_id` int(20) NOT NULL COMMENT '文字題序號',
  PRIMARY KEY (`txt_rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `textretr`
--

CREATE TABLE IF NOT EXISTS `textretr` (
  `txt_retrid` int(20) NOT NULL AUTO_INCREMENT COMMENT '序號',
  `t_id` varchar(12) NOT NULL COMMENT '教師序號',
  `pg_sid` varchar(10) NOT NULL COMMENT '被評者學號',
  `txt_reply` text NOT NULL COMMENT '填答內容',
  `txt_id` int(20) NOT NULL COMMENT '文字題序號',
  PRIMARY KEY (`txt_retrid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `textrs`
--

CREATE TABLE IF NOT EXISTS `textrs` (
  `txt_rsid` int(20) NOT NULL AUTO_INCREMENT,
  `m_id` varchar(20) NOT NULL,
  `txt_sample` text NOT NULL,
  `owner` varchar(20) NOT NULL,
  PRIMARY KEY (`txt_rsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `texttr`
--

CREATE TABLE IF NOT EXISTS `texttr` (
  `txt_trid` int(20) NOT NULL AUTO_INCREMENT COMMENT '序號',
  `t_id` varchar(12) NOT NULL COMMENT '教師序號',
  `pg_sid` varchar(10) NOT NULL COMMENT '被評者學號',
  `txt_reply` text NOT NULL COMMENT '填答內容',
  `txt_id` int(20) NOT NULL COMMENT '文字題序號',
  PRIMARY KEY (`txt_trid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `works`
--

CREATE TABLE IF NOT EXISTS `works` (
  `w_id` int(20) NOT NULL AUTO_INCREMENT COMMENT '序號',
  `w_name` varchar(30) NOT NULL COMMENT '檔案名稱',
  `w_desc` varchar(255) NOT NULL COMMENT '檔案位置',
  `w_date` datetime NOT NULL COMMENT '上傳日期',
  `s_id` varchar(27) NOT NULL COMMENT '上傳者(學生帳號)',
  `m_id` int(20) NOT NULL COMMENT '任務序號',
  `w_status` varchar(1) NOT NULL DEFAULT '0' COMMENT '狀態(1已繳交,2開放)',
  `sa_status` varchar(1) NOT NULL DEFAULT '0' COMMENT '自評狀態(1儲存,2送出)',
  `t_status` varchar(1) NOT NULL DEFAULT '0' COMMENT '教師評審狀態(1儲存,2送出)',
  `pop_point` int(5) NOT NULL DEFAULT '0' COMMENT '點擊數',
  PRIMARY KEY (`w_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
