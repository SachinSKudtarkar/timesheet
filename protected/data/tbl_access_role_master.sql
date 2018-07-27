CREATE TABLE IF NOT EXISTS `tbl_access_role_master` (
`id` int(11) AUTO_INCREMENT PRIMARY KEY  ,
  `parent_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL ,
  `access_type` tinyint(3) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT '0',
  `created_by` int(10) NOT NULL ,
  `created_date` datetime NOT NULL ,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
