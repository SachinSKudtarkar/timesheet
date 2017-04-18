<?php

// this contains the application parameters that can be maintained via GUI
return array(
    'list_intTestStatus' => array(
        1 => 'Completed',
        2 => 'Rejected',
        3 => 'Partially completed',
        4 => 'Schedule another date',
        5 => 'Other',
        6 => 'In Progress',
        7 => 'Rejected - Juniper Site',
    ),
    'list_intTestStatusReasons' => array(
        1 => 'Access not available',
        2 => 'Fiber cut',
        3 => 'Microwave down',
        4 => 'Power issue',
        5 => 'Duplicate IP',
        6 => 'Other NDD NIP issues',
    ),
    'site_survey_status_list' => array(
        1 => 'Completed',
        2 => 'Pending',
        3 => 'Other',
    ),
    'site_survey_accepted_rejected_list' => array(
        1 => 'Accepted',
        2 => 'Rejected',
    ),
    'final_site_dep_status_list' => array(
        1 => 'Ready For Integration',
        2 => 'Pending',
        3 => 'Other',
    ),
    'duoParams' => array(
        'AKEY' => '78f8dd2501540f0bbd3ee42e8ca8bcff0bbd3ee4',
        'IKEY' => 'DIT3P0J8VRJF1H462TZP',
        'SKEY' => 'Q5WFXoqFocK7aA5yVZMM5oU6oisaoPeBe2qaZVZZ',
        'HOST' => 'api-34166df4.duosecurity.com',
    ),
    'pmo_yes_no_list' => array(
        1 => 'Yes',
        0 => 'No',
    ),
    'SMSGateway' => array(
        'SMSCountry' => array(
            'url' => 'http://www.smscountry.com/SMSCwebservice_Bulk.aspx',
            'user' => 'rjilauto',
            'password' => 'P@ssw0rd',
            'senderid' => 'JIAUTO',
        )
    ),
    'SMSText' => array(
        'ndd_delivered' => "Hi <username>, Your NDD NIP request is resolved & NIP is generated for <hostname> & <sapid>. Regards, www.rjilauto.com",
        '1B_completed' => "Hi <username>, Your ATP-1B test report for <hostname> & <sapid> is completed. Regards, www.rjilauto.com",
    ),
    'connectivity_type' => array(
        1 => 'Microwave',
        2 => 'Fiber',
    ),
    'filePaths' => array(
        'UPLOADS' => 'uploads' . DIRECTORY_SEPARATOR,
        'FTP_SHOWRUN_UPLOADS' => 'uploads' . DIRECTORY_SEPARATOR . 'ftp_showrun_uploads' . DIRECTORY_SEPARATOR,
        'SHOWRUN_UPLOADS' => 'uploads' . DIRECTORY_SEPARATOR . 'showrun' . DIRECTORY_SEPARATOR,
        'SHOWRUN_LOG_UPLOADS' => 'uploads' . DIRECTORY_SEPARATOR . 'showrun_logs' . DIRECTORY_SEPARATOR,
        'CONFIGURED_PARSED' => 'uploads' . DIRECTORY_SEPARATOR . 'configured_parsed' . DIRECTORY_SEPARATOR,
        'UPLOADS_NDD' => 'uploads' . DIRECTORY_SEPARATOR . 'ndd' . DIRECTORY_SEPARATOR,
        'UPLOADS_NIP' => 'uploads' . DIRECTORY_SEPARATOR . 'nip' . DIRECTORY_SEPARATOR,
        'UPLOADS_FIBER_REPORT' => 'uploads' . DIRECTORY_SEPARATOR . 'site_connectivity_reports' . DIRECTORY_SEPARATOR,
        'OLD_CONFIGURED_PARSED' => 'uploads' . DIRECTORY_SEPARATOR . 'old_configured_parsed' . DIRECTORY_SEPARATOR,
        'MNT_SHARED_DRIVE' => DIRECTORY_SEPARATOR . 'mnt' . DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR,
    ),
    'skip_parse' => array(
        '.',
        '..',
        '.htaccess',
        'empty',
    ),
    'auto_int_status' => array(
        0 => 'Not Tested due to manual NDD and Nip',
        1 => 'Test InComplete',
        2 => 'Test Completed',
        3 => 'Integration InComplete',
        4 => 'Integration Completed',
        5 => 'Request Processing',
    ),
    'device_type' => array(
        '' => 'Select',
        1 => 'AG1',
        2 => 'CSS',
    ),
    'ler_updation_categories' => array(
        1 => 'Category 1 (SAP,FAC,NEID Change)',
        2 => 'Category 2 ( NN, X2 value change )',
        3 => 'Category 3 (Link Engg Change)',
    ),
    'ler_updation_levels' => array(
        1 => 'Level 1',
        2 => 'Level 2',
        3 => 'Level 3',
    ),
    'ndd_regeneration_levels' => array(
        1 => 'Level 1',
        2 => 'Level 2',
    ),
    'ndd_regeneration_categories' => array(
        1 => 'Category 1 (NLD CSS Approvals)',
        2 => 'Category 2 (Metro AG1 Approvals)',
        3 => 'Category 3 (NLD AG1 Approvals)',
    ),
    'boolean_list' => array(
        1 => 'Yes',
        0 => 'No',
    ),
    'eb_powe_gen_set' => array(
        1 => 'EB Power Backup',
        2 => 'Gen Set',
    ),
    'approval_levels' => array(
        2 => 'Reject',
        3 => 'Approve',
    ),
    'ip_over_allocation_email' => array(
        'ran_lb' => array(
            'enable' => false,
            'to' => array(
                array('name' => '<rjil-ip-team@external.cisco.com>', 'email' => 'rjil-ip-team@external.cisco.com')
            ),
            'cc' => array(),
        ),
        'core_lb' => array(
            'enable' => false,
            'to' => array(
                array('name' => '<rjil-ip-team@external.cisco.com>', 'email' => 'rjil-ip-team@external.cisco.com')
            ),
            'cc' => array(),
        ),
        'ran_wan' => array(
            'enable' => false,
            'to' => array(
                array('name' => '<rjil-ip-team@external.cisco.com>', 'email' => 'rjil-ip-team@external.cisco.com')
            ),
            'cc' => array(),
        ),
        'core_wan' => array(
            'enable' => false,
            'to' => array(
                array('name' => '<rjil-ip-team@external.cisco.com>', 'email' => 'rjil-ip-team@external.cisco.com')
            ),
            'cc' => array(),
        )
    ),
    'disk_monitoring_alert' => array(
        'enable' => false,
        'to' => array(
            array('name' => 'Pratik', 'email' => 'pratik.g@infinitylabsindia.com'),
            array('name' => 'Krishnaji', 'email' => 'kpanse@cisco.com'),
            array('name' => 'Shikhar', 'email' => 'shikhar.t@infinitylabsindia.com'),
            array('name' => 'Umang', 'email' => 'umang.s@infinitylabs.in'),
            array('name' => 'Vaibhav', 'email' => 'vaibhav.h@infinitylabs.in'),
            array('name' => 'Sagar', 'email' => 'sagar.g@infinitylabsindia.com'),
        ),
        'cc' => array(),
    ),
    'chng_request_process_affected' => array(
        'Architecture' => 'Architecture',
        'High Level Design' => 'High Level Design',
        'Low Level Design' => 'Low Level Design',
        'NIP & NDD' => 'NIP & NDD',
        'Integration' => 'Integration',
        'ATP-1A' => 'ATP-1A',
        'ATP-1B' => 'ATP-1B',
        'ATP-1C' => 'ATP-1C',
        'New Design' => 'New Design',
        'Design Change' => 'Design Change',
        'New Hardware' => 'New Hardware',
        'New Service' => 'New Service',
        'New Circle' => 'New Circle',
        'Problem Encoutered' => 'Problem Encoutered',
        'DCN' => 'DCN',
        'SAR' => 'SAR',
        'Nexus' => 'Nexus',
        'GCT Updated' => 'GCT Updated?',
        'Device Config Changes/Updated' => 'Device Config Changes/Updated',
        'Labaratory Testing' => 'Labaratory Testing',
        'Service Affecting' => 'Service Affecting',
        'Hoto' => 'Hoto',
        'Others' => 'Others'
    ),
    'version' => array(
        'NIPTemplate' => '20.6',
    ),
    'file_type' => array(
        'MTRO24GIG' => 'METRO 24GIG AG2',
        'COLO24GIG' => 'COLO 24GIG AG2',
        'NLD24GIG' => 'NLD 24GIG AG2'
    ),
    'collection_engine' => array(
        'command_set' => array(
            '1B' => array(
                'CSS' => array(
                    'commands' => __DIR__ . DIRECTORY_SEPARATOR . 'CollectionEngine' . DIRECTORY_SEPARATOR . 'CommandSet' . DIRECTORY_SEPARATOR . '1B' . DIRECTORY_SEPARATOR . 'CSS' . DIRECTORY_SEPARATOR . 'CollectionList.json'
                )
            ),
            'AsBuilt' => array(
                'CSS' => array(
                    'commands' => __DIR__ . DIRECTORY_SEPARATOR . 'CollectionEngine' . DIRECTORY_SEPARATOR . 'CommandSet' . DIRECTORY_SEPARATOR . 'AsBuilt' . DIRECTORY_SEPARATOR . 'CSS' . DIRECTORY_SEPARATOR . 'CollectionList.json'
                ),
                'AG1' => array(
                    'commands' => __DIR__ . DIRECTORY_SEPARATOR . 'CollectionEngine' . DIRECTORY_SEPARATOR . 'CommandSet' . DIRECTORY_SEPARATOR . 'AsBuilt' . DIRECTORY_SEPARATOR . 'AG1' . DIRECTORY_SEPARATOR . 'CollectionList.json'
                ),
                'Other' => array(
                    'commands' => __DIR__ . DIRECTORY_SEPARATOR . 'CollectionEngine' . DIRECTORY_SEPARATOR . 'CommandSet' . DIRECTORY_SEPARATOR . 'AsBuilt' . DIRECTORY_SEPARATOR . 'Other' . DIRECTORY_SEPARATOR . 'CollectionList.json'
                )
            )
        )
    ),
    'nexus_type' => array(
        '2232' => '2232',
        '2248' => '2248',
    ),
//    'gearman' => require(dirname(__FILE__) . '/gearman.php'),
    'deviceCredentials' => require (dirname(__FILE__) . '/device_credentials.php'),
    'hoto_readiness_batch_status' => array(
        '1' => 'Approved',
        '2' => 'Conditional Approved',
        '3' => 'Rejected',
        '4' => 'Hold'
    ),
    'rule_master_type' => array(
        'All' => 'All',
        'Plan vs Built' => 'Plan vs Built',
        'IPSLA' => 'IPSLA',
        'Config Audit' => 'Config Audit',
        'SNMP Performance Management' => 'SNMP Performance Management',
        'Syslog' => 'Syslog',
    ),
    'rule_device_type' => array(
        'All' => 'All',
        'CSS' => 'CSS',
        'NLD AG1' => 'NLD AG1',
        'Metro AG1' => 'Metro AG1',
        'AG2' => 'AG2',
        'SAR' => 'SAR',
    ),
    'dc_ssh_connect' => array(
        'username' => 'dc.dcnm',
        'password' => 'Dct00!nm',
    ),
    'dc_ssh_dtor_connect' => array(
        'username' => 'rjilauto',
        'password' => 'rjilauto@123',
    ),
    'dc_parenting_validation_alert' => array(
        'cc' => array(
         array('name' => 'Vijay Tak', 'email' => 'vijaykumar.tak@infinitylabs.in'),
        ),
    ),
    'mongodb_server' => 'mongodb://10.137.40.131:27017',
    'nexus_models' => array(
        'NEXUS_2K' => 'NEXUS 2K',
        'NEXUS_3K' => 'NEXUS 3K',
        'NEXUS_5K' => 'NEXUS 5K',
        'NEXUS_6K' => 'NEXUS 6K',
        'NEXUS_7K' => 'NEXUS 7K',
        'NEXUS_9K' => 'NEXUS 9K',
    ),
    'nexus_2k_sub_models' => array(
        'NEXUS_2232' => 'NEXUS 2232',
        'NEXUS_2248' => 'NEXUS 2248',
    ),
    'nexus_3k_sub_models' => array(
        'NEXUS_3172' => 'NEXUS 3172',
        'NEXUS_3048' => 'NEXUS 3048',
    ),
    'nexus_5k_sub_models' => array(
        'NEXUS_5672' => 'NEXUS 5672',
    ),
    'nexus_6k_sub_models' => array(
        'NEXUS_6001' => 'NEXUS 6001',
    ),
    'nexus_7k_sub_models' => array(
        'NEXUS_7710' => 'NEXUS 7710',
        'NEXUS_7010' => 'NEXUS 7010',
    ),
    'nexus_9k_sub_models' => array(
        'NEXUS_9396' => 'NEXUS 9396',
    ),
    'nexus_data_center' => array_combine(array_map(function($n) {
                return sprintf('IDC-%d', $n);
            }, range(1, 12)), array_map(function($n) {
                return sprintf('IDC-%d', $n);
            }, range(1, 12))),
    'nexus_server_hall' => array_combine(array_map(function($n) {
                return sprintf('SH-%d', $n);
            }, range(1, 12)), array_map(function($n) {
                return sprintf('SH-%d', $n);
            }, range(1, 12))),
    'nexus_rack_range' => array_combine(range(1, 10), range(1, 10)),
    'nexus_row_range' => array_combine(range(1, 10), range(1, 10)),
    'nexus_u_range' => array_combine(range(1, 42), range(1, 42)),
    'email_IP_Head' => array(
        'to' => array(
            array('name' => 'Yogesh Barge', 'email' => 'yogeshbarge@benchmarkitsolutions.com'),
	    array('name' => 'Aditya Tripathi', 'email' => 'aditya.tripathi@infinitylabs.in'),
            array('name' => 'Sridhar Kotamreddy', 'email' => 'srkotamr@cisco.com'),
            array('name' => 'Deepali', 'email' => 'deepalip.in@sraoss.com'),
            array('name' => 'Uday', 'email' => 'uharikan@cisco.com'),
        ),
    ),
     'nexus_port_range' => array_combine(range(1, 48), range(1, 48)),
);
