
DROP TABLE IF EXISTS tbl_state_master;

CREATE TABLE `tbl_state_master` (
  `state_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `state_name_short` varchar(2) NOT NULL,
  `state_name` varchar(100) DEFAULT NULL,
  `is_disabled` tinyint(1) DEFAULT '0' COMMENT '1 = Disabled',
  `has_form` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`state_id`),
  KEY `IX_STATE_MASTER_IS_DISABLED` (`is_disabled`),
  KEY `IX_STATE_MASTER_HAS_FORM` (`has_form`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

INSERT INTO tbl_state_master VALUES("1","DL","Delhi","",""),
("2","MH","Maharashtra","",""),
("3","TN","Tamil Nadu","",""),
("4","KL","Kerala","",""),
("5","AP","Andhra Pradesh","",""),
("6","KA","Karnataka","",""),
("7","GA","Goa","",""),
("8","MP","Madhya Pradesh","",""),
("9","PY","Pondicherry","",""),
("10","GJ","Gujarat","",""),
("11","OR","Orrisa","",""),
("12","CA","Chhatisgarh","",""),
("13","JH","Jharkhand","",""),
("14","BR","Bihar","",""),
("15","WB","West Bengal","",""),
("16","UP","Uttar Pradesh","",""),
("17","RJ","Rajasthan","",""),
("18","PB","Punjab","",""),
("19","HR","Haryana","",""),
("20","CH","Chandigarh","",""),
("21","JK","Jammu & Kashmir","",""),
("22","HP","Himachal Pradesh","",""),
("23","UA","Uttaranchal","",""),
("24","LK","Lakshadweep","",""),
("25","AN","Andaman & Nicobar","",""),
("26","MG","Meghalaya","",""),
("27","AS","Assam","",""),
("28","DR","Dadra & Nagar Haveli","",""),
("29","DN","Daman & Diu","",""),
("30","SK","Sikkim","",""),
("31","TR","Tripura","",""),
("32","MZ","Mizoram","",""),
("33","MN","Manipur","",""),
("34","NL","Nagaland","",""),
("35","AR","Arunachal Pradesh","","");



