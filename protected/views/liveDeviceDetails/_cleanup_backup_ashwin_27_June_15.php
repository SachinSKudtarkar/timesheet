<?php $match = array(); ?>
<div id="asBuiltData" style="border:1px; float: left; width: 33%">
    <h3><span class="textblue">As Built Data</span></h3>
    <ul>
        <li id="enbLoopbackli"><span><strong>Enb Loopback Mapping</strong></span>
            <?php
            if (count($data['liveData'])) {
                foreach ($data['liveData'] as $row) {
                    $match['sapid'] = (!empty($row['enb'])) ? $row['enb'] : '';
                    $match['loopback'] = (!empty($row['loopback0_1'])) ? $row['loopback0_1'] : '';
                    $match['hostname'] = (!empty($row['host_name'])) ? $row['host_name'] : '';
                    ?>
                    <ul>
                        <li><span class="ctitle">Sap ID</span>    : <?php echo $row['enb']; ?></li>
                        <li><span class="ctitle">Hostname</span>  : <?php echo $row['host_name']; ?></li>
                        <li><span class="ctitle">Loopback0</span> : <?php echo $row['loopback0_1']; ?><hr /></li>
                    </ul>
                    <?php
                }
            }
            ?>
            <hr />
        </li>
        <li id="routerli"><span><strong>Router as Built Data</strong></span>
            <?php
            if (count($data['routerData'])) {
                foreach ($data['routerData'] as $row) {
                    $match['loopback'] = (!empty($row['Loopback0'])) ? $row['Loopback0'] : $match['loopback'];
                    $match['hostname'] = (!empty($row['host_name'])) ? $row['host_name'] : $match['hostname'];
                    $match['loopback0_ipv6'] = $row['Loopback0_ipv6'];
                    $match['Loopback200'] = $row['Loopback200'];
                    $type = substr(trim($row['host_name']), 8, 3);
                    $match['nnValue'] = $match['x2Value'] = '';
                    for ($no = 1; $no <= 6; $no++) {
                        if (preg_match('/64730/', $row['export_' . $no]) && $match['nnValue'] == '') {
                            $match['nnValue'] = substr(trim($row['export_' . $no]), -2);
                        } elseif (preg_match('/64740/', $row['export_' . $no]) && $match['x2Value'] == '') {
                            $match['x2Value'] = trim($row['export_' . $no]);
                        }
                        if (preg_match('/64730/', $row['import_' . $no]) && $match['nnValue'] == '') {
                            $match['nnValue'] = substr(trim($row['import_' . $no]), -4, 2);
                        } elseif (preg_match('/64740/', $row['import_' . $no]) && $match['x2Value'] == '') {
                            $match['x2Value'] = trim($row['import_' . $no]);
                        }
                    }
                    foreach ($row as $key => $val) {
                        if (preg_match('/^interface/', $key)) {
                            $temp = explode(" ", trim($val));
                            $row[$key] = $temp[0];
                            $match[str_replace('interface_', '', $key)] = $temp[0];
                        }
                    }
                    ?>
                    <ul>
                        <li><span class="ctitle">Hostname</span>        : <span class="<?php echo (!empty($match['hostname']) && $match['hostname'] != $row['host_name']) ? 'mismatch' : ''; ?>"><?php echo $row['host_name']; ?></span></li>
                        <li><span class="ctitle">Loopback0</span>       : <span class="<?php echo (!empty($match['loopback']) && $match['loopback'] != $row['Loopback0']) ? 'mismatch' : ''; ?>"><?php echo $row['Loopback0']; ?></span></li>
                        <li><span class="ctitle">Loopback0 ipv6</span>  : <?php echo $row['Loopback0_ipv6']; ?></li>
                        <li><span class="ctitle">Loopback200</span>  : <?php echo $row['Loopback200']; ?></li>
                        <li><span class="ctitle">Vlan351 ipv4</span>  : <?php echo $match['vlan351_ipv4'] = (empty($row['interface_vlan351_ipv4'])) ? $row['interface_bdi351_ipv4'] : $row['interface_vlan351_ipv4']; ?></li>
                        <li><span class="ctitle">Vlan351 ipv6</span>  : <?php echo $match['vlan351_ipv6'] = (empty($row['interface_vlan351_ipv6'])) ? $row['interface_bdi351_ipv6'] : $row['interface_vlan351_ipv6']; ?></li>
                        <li><span class="ctitle">Vlan352 ipv4</span>  : <?php echo $match['vlan352_ipv4'] = (empty($row['interface_vlan352_ipv4'])) ? $row['interface_bdi352_ipv4'] : $row['interface_vlan352_ipv4']; ?></li>
                        <li><span class="ctitle">Vlan352 ipv6</span>  : <?php echo $match['vlan352_ipv6'] = (empty($row['interface_vlan352_ipv6'])) ? $row['interface_bdi352_ipv6'] : $row['interface_vlan352_ipv6']; ?></li>
                        <li><span class="ctitle">Vlan353 ipv4</span>  : <?php echo $match['vlan353_ipv4'] = (empty($row['interface_vlan353_ipv4'])) ? $row['interface_bdi353_ipv4'] : $row['interface_vlan353_ipv4']; ?></li>
                        <li><span class="ctitle">Vlan353 ipv6</span>  : <?php echo $match['vlan353_ipv6'] = (empty($row['interface_vlan353_ipv6'])) ? $row['interface_bdi353_ipv6'] : $row['interface_vlan353_ipv6']; ?></li>
                        <li><span class="ctitle">East ngbr vlan354 ipv4</span>  : <?php echo $match['vlan354_ipv4'] = (empty($row['interface_vlan354_ipv4'])) ? $row['interface_bdi354_ipv4'] : $row['interface_vlan354_ipv4']; ?></li>
                        <li><span class="ctitle">East ngbr vlan354 remote hostname</span>  : <?php echo $match['vlan354_remote_hostname'] = (empty($row['interface_vlan354_remote_hostname'])) ? $row['interface_bdi354_remote_hostname'] : $row['interface_vlan354_remote_hostname']; ?></li>
                        <li><span class="ctitle">East ngbr vlan354 remote port</span>  : <?php echo $match['vlan354_remote_port'] = (empty($row['interface_vlan354_remote_port'])) ? $row['interface_bdi354_remote_port'] : $row['interface_vlan354_remote_port']; ?></li>
                        <li><span class="ctitle">Vlan354 ipv6</span>  : <?php echo $match['vlan354_ipv6'] = (empty($row['interface_vlan354_ipv6'])) ? $row['interface_bdi354_ipv6'] : $row['interface_vlan354_ipv6']; ?></li>
                        <li><span class="ctitle">West ngbr vlan355 ipv4</span>  : <?php echo $match['vlan355_ipv4'] = (empty($row['interface_vlan355_ipv4'])) ? $row['interface_bdi355_ipv4'] : $row['interface_vlan355_ipv4']; ?></li>
                        <li><span class="ctitle">West ngbr vlan355 remote hostname</span>  : <?php echo $match['vlan355_remote_hostname'] = (empty($row['interface_vlan355_remote_hostname'])) ? $row['interface_bdi355_remote_hostname'] : $row['interface_vlan355_remote_hostname']; ?></li>
                        <li><span class="ctitle">West ngbr vlan355 remote port</span>  : <?php echo $match['vlan355_remote_port'] = (empty($row['interface_vlan355_remote_port'])) ? $row['interface_bdi355_remote_port'] : $row['interface_vlan355_remote_port']; ?></li>
                        <li><span class="ctitle">Vlan355 ipv6</span>  : <?php echo $match['vlan355_ipv6'] = (empty($row['interface_vlan355_ipv6'])) ? $row['interface_bdi355_ipv6'] : $row['interface_vlan355_ipv6']; ?></li>
                        <?php if ($type == 'PAR') { ?>
                            <li><span class="ctitle">Vlan356 ipv4</span>  : <?php echo $match['vlan356_ipv4'] = (empty($row['interface_vlan356_ipv4'])) ? $row['interface_bdi356_ipv4'] : $row['interface_vlan356_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan356 ipv6</span>  : <?php echo $match['vlan356_ipv6'] = (empty($row['interface_vlan356_ipv6'])) ? $row['interface_bdi356_ipv6'] : $row['interface_vlan356_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan357 ipv4</span>  : <?php echo $match['vlan357_ipv4'] = (empty($row['interface_vlan357_ipv4'])) ? $row['interface_bdi357_ipv4'] : $row['interface_vlan357_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan357 ipv6</span>  : <?php echo $match['vlan357_ipv6'] = (empty($row['interface_vlan357_ipv6'])) ? $row['interface_bdi357_ipv6'] : $row['interface_vlan357_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan370 ipv4</span>  : <?php echo $match['vlan370_ipv4'] = (empty($row['interface_vlan370_ipv4'])) ? $row['interface_bdi370_ipv4'] : $row['interface_vlan370_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan370 ipv6</span>  : <?php echo $match['vlan370_ipv6'] = (empty($row['interface_vlan370_ipv6'])) ? $row['interface_bdi370_ipv6'] : $row['interface_vlan370_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan371 ipv4</span>  : <?php echo $match['vlan371_ipv4'] = (empty($row['interface_vlan371_ipv4'])) ? $row['interface_bdi371_ipv4'] : $row['interface_vlan371_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan371 ipv6</span>  : <?php echo $match['vlan371_ipv6'] = (empty($row['interface_vlan371_ipv6'])) ? $row['interface_bdi371_ipv6'] : $row['interface_vlan371_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan372 ipv4</span>  : <?php echo $match['vlan372_ipv4'] = (empty($row['interface_vlan372_ipv4'])) ? $row['interface_bdi372_ipv4'] : $row['interface_vlan372_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan372 ipv6</span>  : <?php echo $match['vlan372_ipv6'] = (empty($row['interface_vlan372_ipv6'])) ? $row['interface_bdi372_ipv6'] : $row['interface_vlan372_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan373 ipv4</span>  : <?php echo $match['vlan373_ipv4'] = (empty($row['interface_vlan373_ipv4'])) ? $row['interface_bdi373_ipv4'] : $row['interface_vlan373_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan373 ipv6</span>  : <?php echo $match['vlan373_ipv6'] = (empty($row['interface_vlan373_ipv6'])) ? $row['interface_bdi373_ipv6'] : $row['interface_vlan373_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan374 ipv4</span>  : <?php echo $match['vlan374_ipv4'] = (empty($row['interface_vlan374_ipv4'])) ? $row['interface_bdi374_ipv4'] : $row['interface_vlan374_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan374 ipv6</span>  : <?php echo $match['vlan374_ipv6'] = (empty($row['interface_vlan374_ipv6'])) ? $row['interface_bdi374_ipv6'] : $row['interface_vlan374_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan375 ipv4</span>  : <?php echo $match['vlan375_ipv4'] = (empty($row['interface_vlan375_ipv4'])) ? $row['interface_bdi375_ipv4'] : $row['interface_vlan375_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan375 ipv6</span>  : <?php echo $match['vlan375_ipv6'] = (empty($row['interface_vlan375_ipv6'])) ? $row['interface_bdi375_ipv6'] : $row['interface_vlan375_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan390 ipv4</span>  : <?php echo $match['vlan390_ipv4'] = (empty($row['interface_vlan390_ipv4'])) ? $row['interface_bdi390_ipv4'] : $row['interface_vlan390_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan390 ipv6</span>  : <?php echo $match['vlan390_ipv6'] = (empty($row['interface_vlan390_ipv6'])) ? $row['interface_bdi390_ipv6'] : $row['interface_vlan390_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan200 ipv4</span>  : <?php echo $match['vlan200_ipv4'] = (empty($row['interface_vlan200_ipv4'])) ? $row['interface_bdi200_ipv4'] : $row['interface_vlan200_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan200 ipv6</span>  : <?php echo $match['vlan200_ipv6'] = (empty($row['interface_vlan200_ipv6'])) ? $row['interface_bdi200_ipv6'] : $row['interface_vlan200_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan201 ipv4</span>  : <?php echo $match['vlan201_ipv4'] = (empty($row['interface_vlan201_ipv4'])) ? $row['interface_bdi201_ipv4'] : $row['interface_vlan201_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan201 ipv6</span>  : <?php echo $match['vlan201_ipv6'] = (empty($row['interface_vlan201_ipv6'])) ? $row['interface_bdi201_ipv6'] : $row['interface_vlan201_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan202 ipv4</span>  : <?php echo $match['vlan202_ipv4'] = (empty($row['interface_vlan202_ipv4'])) ? $row['interface_bdi202_ipv4'] : $row['interface_vlan202_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan202 ipv6</span>  : <?php echo $match['vlan202_ipv6'] = (empty($row['interface_vlan202_ipv6'])) ? $row['interface_bdi202_ipv6'] : $row['interface_vlan202_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan203 ipv4</span>  : <?php echo $match['vlan203_ipv4'] = (empty($row['interface_vlan203_ipv4'])) ? $row['interface_bdi203_ipv4'] : $row['interface_vlan203_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan203 ipv6</span>  : <?php echo $match['vlan203_ipv6'] = (empty($row['interface_vlan203_ipv6'])) ? $row['interface_bdi203_ipv6'] : $row['interface_vlan203_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan204 ipv4</span>  : <?php echo $match['vlan204_ipv4'] = (empty($row['interface_vlan204_ipv4'])) ? $row['interface_bdi204_ipv4'] : $row['interface_vlan204_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan204 ipv6</span>  : <?php echo $match['vlan204_ipv6'] = (empty($row['interface_vlan204_ipv6'])) ? $row['interface_bdi204_ipv6'] : $row['interface_vlan204_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan205 ipv4</span>  : <?php echo $match['vlan205_ipv4'] = (empty($row['interface_vlan205_ipv4'])) ? $row['interface_bdi205_ipv4'] : $row['interface_vlan205_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan205 ipv6</span>  : <?php echo $match['vlan205_ipv6'] = (empty($row['interface_vlan205_ipv6'])) ? $row['interface_bdi205_ipv6'] : $row['interface_vlan205_ipv6']; ?></li>
                            <li><span class="ctitle">Ten Gigabit Ethernet 0/3/0</span>  : <?php echo $row['TenGigabitEthernet0_3_0']; ?></li>
                        <?php } if ($type == 'ESR') { ?>
                            <li><span class="ctitle">Vlan551 ipv4</span>  : <?php echo $match['vlan551_ipv4'] = (empty($row['interface_vlan551_ipv4'])) ? $row['interface_bdi551_ipv4'] : $row['interface_vlan551_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan551 ipv6</span>  : <?php echo $match['vlan551_ipv6'] = (empty($row['interface_vlan551_ipv6'])) ? $row['interface_bdi551_ipv6'] : $row['interface_vlan551_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan552 ipv4</span>  : <?php echo $match['vlan552_ipv4'] = (empty($row['interface_vlan552_ipv4'])) ? $row['interface_bdi552_ipv4'] : $row['interface_vlan552_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan552 ipv6</span>  : <?php echo $match['vlan552_ipv6'] = (empty($row['interface_vlan552_ipv6'])) ? $row['interface_bdi552_ipv6'] : $row['interface_vlan552_ipv6']; ?></li>
                            <li><span class="ctitle">Vlan553 ipv4</span>  : <?php echo $match['vlan553_ipv4'] = (empty($row['interface_vlan553_ipv4'])) ? $row['interface_bdi553_ipv4'] : $row['interface_vlan553_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan553 ipv6</span>  : <?php echo $match['vlan553_ipv6'] = (empty($row['interface_vlan553_ipv6'])) ? $row['interface_bdi553_ipv6'] : $row['interface_vlan553_ipv6']; ?></li>
                            <li><span class="ctitle">East ngbr vlan554 ipv4</span>  : <?php echo $match['vlan554_ipv4'] = (empty($row['interface_vlan554_ipv4'])) ? $row['interface_bdi554_ipv4'] : $row['interface_vlan554_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan554 ipv6</span>  : <?php echo $match['vlan554_ipv6'] = (empty($row['interface_vlan554_ipv6'])) ? $row['interface_bdi554_ipv6'] : $row['interface_vlan554_ipv6']; ?></li>
                            <li><span class="ctitle">West ngbr vlan555 ipv4</span>  : <?php echo $match['vlan555_ipv4'] = (empty($row['interface_vlan555_ipv4'])) ? $row['interface_bdi555_ipv4'] : $row['interface_vlan555_ipv4']; ?></li>
                            <li><span class="ctitle">Vlan555 ipv6</span>  : <?php echo $match['vlan555_ipv6'] = (empty($row['interface_vlan555_ipv6'])) ? $row['interface_bdi555_ipv6'] : $row['interface_vlan555_ipv6']; ?></li>
                        <?php } ?>  
                        <li><span class="ctitle">NN value</span>  : <?php echo $match['nnValue']; ?></li>
                        <li><span class="ctitle">X2 value</span>  : <?php echo $match['x2Value']; ?></li>
                        <li><span class="ctitle">Utility</span>   : <?php echo $match['utility'] = (empty($row['interface_vlan951_ipv4'])) ? $row['interface_bdi951_ipv4'] : $row['interface_vlan951_ipv4']; ?></li>
                        <li><span class="ctitle">Bearer</span>    : <?php echo $match['bearer'] = (empty($row['interface_vlan101_ipv6'])) ? $row['interface_bdi101_ipv6'] : $row['interface_vlan101_ipv6']; ?></li>
                        <li><span class="ctitle">Signaling</span> : <?php echo $match['signaling'] = (empty($row['interface_vlan102_ipv6'])) ? $row['interface_bdi102_ipv6'] : $row['interface_vlan102_ipv6']; ?></li>
                        <li><span class="ctitle">OAM</span>       : <?php echo $match['oam'] = (empty($row['interface_vlan103_ipv6'])) ? $row['interface_bdi103_ipv6'] : $row['interface_vlan103_ipv6']; ?></li>
                    </ul>
                    <?php
                }
            }
            ?>
            <hr />
        </li>
        <li id="cdpli"><span><strong>Neighbour Details - CDP</strong></span>
            <?php
            if (count($data['cdpData'])) {
                foreach ($data['cdpData'] as $row) {
                    ?>
                    <ul>
                        <li><span class="ctitle">Device Hostname</span>  : <?php echo $row['device_host_name']; ?></li>
                        <li><span class="ctitle">Interface ipv4</span>    : <?php echo $row['interface_ipv4']; ?></li>
                        <li><span class="ctitle">Interface ipv6</span>    : <?php echo $row['interface_ipv6']; ?></li>
                        <li><span class="ctitle">Local interface</span>  : <?php echo $row['local_Interface']; ?></li>
                        <li><span class="ctitle">Remote interface</span>  : <?php echo $row['remote_Interface']; ?><hr /></li>
                    </ul>
                    <?php
                }
            }
            ?>
            <hr />
        </li>
        <li id="bgpli"><span><strong>Neighbour Details - BGP</strong></span>
            <?php
            if (count($data['bgpData'])) {
                foreach ($data['bgpData'] as $row) {
                    $match['bgpNgbr'][] = trim($row['loopback0']);
                    ?>
                    <ul>
                        <li><span class="ctitle">Loopback0</span>  : <?php echo $row['loopback0']; ?></li>
                    </ul>
                    <?php
                }
            }
            ?>
            <hr />
        </li>
        <li id="isisli"><span><strong>Neighbour Details - ISIS</strong></span>
            <?php
            $isisNeighInterface = array();
            if (count($data['isisData'])) {
                foreach ($data['isisData'] as $row) {
                    $isisNeighInterface[$row['neighbors_host_name']] = $row['neighors_interface_ip'];
                    ?>
                    <ul>
                        <li><span class="ctitle">Type</span>  : <?php echo $row['type']; ?></li>
                        <li><span class="ctitle">Neighbors hostname</span>  : <?php echo $row['neighbors_host_name']; ?></li>
                        <li><span class="ctitle">Interface</span>  : <?php echo $row['interface']; ?></li>
                        <li><span class="ctitle">Neighbors interface ip</span>  : <?php echo $row['neighors_interface_ip']; ?><hr /></li>
                    </ul>
                    <?php
                }
            }
            ?>
            <hr />
        </li>
        <li id="deviceli"><span><strong>Device Version</strong></span>
            <?php
            if (count($data['versionData'])) {
                foreach ($data['versionData'] as $row) {
                    ?>
                    <ul>
                        <li><span class="ctitle">Hardware</span>  : <?php echo $row['hardware']; ?></li>
                        <li><span class="ctitle">Show Version</span>  : <?php echo $row['show_version']; ?></li>
                        <li><span class="ctitle">Image name</span>  : <?php echo $row['image_name']; ?><hr /></li>
                    </ul>
                    <?php
                }
            }
            ?>
            <hr />
        </li>

    </ul>
</div>
<div id="outputMaster" style="border:1px; float: left; width: 33%">
    <h3><span class="textgreen">Output Master</span></h3>
    <ul>
        <li id="nddoutputli"><span><strong>NDD Output Master</strong></span>
            <?php
            if (count($data['nddOutputData'])) {
                foreach ($data['nddOutputData'] as $row) {
                    ?>
                    <ul>
                        <li><span class="ctitle">Sap ID</span>  : <span class="<?php echo (!empty($match['sapid']) && $match['sapid'] != $row['enode_b_sapid']) ? 'mismatch' : ''; ?>"><?php echo $row['enode_b_sapid']; ?></span></li>
                        <li><span class="ctitle">Hostname</span>  : <span class="<?php echo (!empty($match['hostname']) && $match['hostname'] != $row['host_name']) ? 'mismatch' : ''; ?>"><?php echo $row['host_name']; ?></span></li>
                        <li><span class="ctitle">Loopback0 ipv4</span>  : <span class="<?php echo (!empty($match['loopback']) && $match['loopback'] != $row['loopback0_ipv4']) ? 'mismatch' : ''; ?>"><?php echo $row['loopback0_ipv4']; ?></span></li>
                        <li><span class="ctitle">Loopback0 ipv6</span>  : <span class="<?php echo (!empty($match['loopback0_ipv6']) && $match['loopback0_ipv6'] != $row['loopback0_ipv6']) ? 'mismatch' : ''; ?>"><?php echo $row['loopback0_ipv6']; ?></span></li>
                        <li><span class="ctitle">Loopback200</span>  : <span class="<?php echo (!empty($match['Loopback200']) && $match['Loopback200'] != $row['loopback_200']) ? 'mismatch' : ''; ?>"><?php echo $row['loopback_200']; ?></span></li>
                        <li><span class="ctitle">Fiber/Microwave</span>  : <?php echo $row['fiber_microwave']; ?></li>
                        <li><span class="ctitle">East ag1 hostname</span>  : <?php echo $row['east_ag1_hostname']; ?></li>
                        <li><span class="ctitle">West ag1 hostname</span>  : <?php echo $row['west_ag1_hostname']; ?></li>
                        <li><span class="ctitle">East ag1 sapid</span>  : <?php echo $row['east_ag1_sapid']; ?></li>
                        <li><span class="ctitle">West ag1 sapid</span>  : <?php echo $row['west_ag1_sapid']; ?></li>
                        <li><span class="ctitle">East ngbr hostname</span>  : <span class="<?php echo (!empty($match['vlan354_remote_hostname']) && $match['vlan354_remote_hostname'] != $row['east_ag1_ngbr_hostname']) ? 'mismatch' : ''; ?>"><?php echo $row['east_ag1_ngbr_hostname']; ?></span></li>
                        <li><span class="ctitle">East ngbr remote port</span>  : <span class="<?php echo (!empty($match['vlan354_remote_port']) && $match['vlan354_remote_port'] != $row['e_ngbr_remport']) ? 'mismatch' : ''; ?>"><?php echo $row['e_ngbr_remport']; ?></span></li>
                        <li><span class="ctitle">West ngbr hostname</span>  : <span class="<?php echo (!empty($match['vlan355_remote_hostname']) && $match['vlan355_remote_hostname'] != $row['west_ag1_ngbr_hostname']) ? 'mismatch' : ''; ?>"><?php echo $row['west_ag1_ngbr_hostname']; ?></span></li>
                        <li><span class="ctitle">West ngbr remote port</span>  : <span class="<?php echo (!empty($match['vlan355_remote_port']) && $match['vlan355_remote_port'] != $row['w_ngbr_remport']) ? 'mismatch' : ''; ?>"><?php echo $row['w_ngbr_remport']; ?></span></li>
                        <li><span class="ctitle">East ngbr sapid</span>  : <?php echo $row['east_ag1_ngbr_sapid']; ?></li>
                        <li><span class="ctitle">West ngbr sapid</span>  : <?php echo $row['west_ag1_ngbr_sapid']; ?></li>
                        <li><span class="ctitle">East int ip</span>  : <span class="<?php echo (!empty($match['vlan354_ipv4']) && $match['vlan354_ipv4'] != $row['east_int_ip']) ? 'mismatch' : ''; ?>"><?php echo $row['east_int_ip']; ?></span></li>
                        <li><span class="ctitle">West int ip</span>  : <span class="<?php echo (!empty($match['vlan355_ipv4']) && $match['vlan355_ipv4'] != $row['west_int_ip']) ? 'mismatch' : ''; ?>"><?php echo $row['west_int_ip']; ?></span></li>
                        <li><span class="ctitle">Vlan351 ipv4</span>  : <span class="<?php echo (!empty($match['vlan351_ipv4']) && $match['vlan351_ipv4'] != $row['vlan351_ipv4']) ? 'mismatch' : ''; ?>"><?php echo $row['vlan351_ipv4']; ?></span></li>
                        <li><span class="ctitle">vlan351 ipv6</span>  : <span class="<?php echo (!empty($match['vlan351_ipv6']) && $match['vlan351_ipv6'] != $row['vlan351_ipv6']) ? 'mismatch' : ''; ?>"><?php echo $row['vlan351_ipv6']; ?></span></li>
                        <li><span class="ctitle">vlan351 ngbr HN</span>  : <?php echo $row['vlan351_neigh_hn']; ?></li>
                        <li><span class="ctitle">vlan351 remport</span>  : <?php echo $row['vlan351_remport']; ?></li>
                        <li><span class="ctitle">Vlan352 ipv4</span>  : <span class="<?php echo (!empty($match['vlan352_ipv4']) && $match['vlan352_ipv4'] != $row['vlan352_ipv4']) ? 'mismatch' : ''; ?>"><?php echo $row['vlan352_ipv4']; ?></span></li>
                        <li><span class="ctitle">vlan352 ipv6</span>  : <span class="<?php echo (!empty($match['vlan352_ipv6']) && $match['vlan352_ipv6'] != $row['vlan352_ipv6']) ? 'mismatch' : ''; ?>"><?php echo $row['vlan352_ipv6']; ?></span></li>
                        <li><span class="ctitle">vlan352 ngbr HN</span>  : <?php echo $row['vlan352_neigh_hn']; ?></li>
                        <li><span class="ctitle">vlan352 remport</span>  : <?php echo $row['vlan352_remport']; ?></li>
                        <li><span class="ctitle">Vlan353 ipv4</span>  : <span class="<?php echo (!empty($match['vlan353_ipv4']) && $match['vlan353_ipv4'] != $row['vlan353_ipv4']) ? 'mismatch' : ''; ?>"><?php echo $row['vlan353_ipv4']; ?></span></li>
                        <li><span class="ctitle">vlan353 ipv6</span>  : <span class="<?php echo (!empty($match['vlan353_ipv6']) && $match['vlan353_ipv6'] != $row['vlan353_ipv6']) ? 'mismatch' : ''; ?>"><?php echo $row['vlan353_ipv6']; ?></span></li>
                        <li><span class="ctitle">vlan353 ngbr HN</span>  : <?php echo $row['vlan353_neigh_hn']; ?></li>
                        <li><span class="ctitle">vlan353 remport</span>  : <?php echo $row['vlan353_remport']; ?></li>
                        <li><span class="ctitle">Vlan551 ipv4</span>  : <span class="<?php echo (!empty($match['vlan551_ipv4']) && $match['vlan551_ipv4'] != $row['vlan551_ipv4']) ? 'mismatch' : ''; ?>"><?php echo $row['vlan551_ipv4']; ?></span></li>
                        <li><span class="ctitle">Vlan551 ipv6</span>  : <span class="<?php echo (!empty($match['vlan551_ipv6']) && $match['vlan551_ipv6'] != $row['vlan551_ipv6']) ? 'mismatch' : ''; ?>"><?php echo $row['vlan551_ipv6']; ?></span></li>
                        <li><span class="ctitle">Vlan552 ipv4</span>  : <span class="<?php echo (!empty($match['vlan552_ipv4']) && $match['vlan552_ipv4'] != $row['vlan552_ipv4']) ? 'mismatch' : ''; ?>"><?php echo $row['vlan552_ipv4']; ?></span></li>
                        <li><span class="ctitle">Vlan552 ipv6</span>  : <span class="<?php echo (!empty($match['vlan552_ipv6']) && $match['vlan552_ipv6'] != $row['vlan552_ipv6']) ? 'mismatch' : ''; ?>"><?php echo $row['vlan552_ipv6']; ?></span></li>
                        <li><span class="ctitle">Vlan553 ipv4</span>  : <span class="<?php echo (!empty($match['vlan553_ipv4']) && $match['vlan553_ipv4'] != $row['vlan553_ipv4']) ? 'mismatch' : ''; ?>"><?php echo $row['vlan553_ipv4']; ?></span></li>
                        <li><span class="ctitle">Vlan553 ipv6</span>  : <span class="<?php echo (!empty($match['vlan553_ipv6']) && $match['vlan553_ipv6'] != $row['vlan553_ipv6']) ? 'mismatch' : ''; ?>"><?php echo $row['vlan553_ipv6']; ?></span></li>
                        <li><span class="ctitle">NN value</span>  : <span class="<?php echo (!empty($match['nnValue']) && $match['nnValue'] != $row['nn_value']) ? 'mismatch' : ''; ?>"><?php echo $row['nn_value']; ?></li>
                        <li><span class="ctitle">X2 value</span>  : <span class="<?php echo (!empty($match['x2Value']) && $match['x2Value'] != $row['x2_value']) ? 'mismatch' : ''; ?>"><?php echo $row['x2_value']; ?></li>
                        <li><span class="ctitle">Utility</span>  : <span class="<?php echo (!empty($match['utility']) && $match['utility'] != $row['utility_ip']) ? 'mismatch' : ''; ?>"><?php echo $row['utility_ip']; ?></span></li>
                        <li><span class="ctitle">Bearer</span>  : <span class="<?php echo (!empty($match['bearer']) && $match['bearer'] != $row['ip_bearer']) ? 'mismatch' : ''; ?>"><?php echo $row['ip_bearer']; ?></span></li>
                        <li><span class="ctitle">Signaling</span>  : <span class="<?php echo (!empty($match['signaling']) && $match['signaling'] != $row['ip_sig']) ? 'mismatch' : ''; ?>"><?php echo $row['ip_sig']; ?></span></li>
                        <li><span class="ctitle">OAM</span>  : <span class="<?php echo (!empty($match['oam']) && $match['oam'] != $row['ip_oam']) ? 'mismatch' : ''; ?>"><?php echo $row['ip_oam']; ?></span></li>
                        <li><span class="ctitle">Region</span>  : <?php echo $row['region']; ?></li>
                        <li><span class="ctitle">Circle</span>  : <?php echo $row['circle']; ?></li>                        
                        <li><span class="ctitle">PDF done</span>  : <?php echo ($row['pdf_done']) ? 'Yes' : 'No'; ?>
                        <li><span><strong>Neighbour Details - BGP</strong></span>
                            <ul>
                                <li><span class="ctitle">Loopback0</span>  : <span class="<?php echo (count($match['bgpNgbr']) && !in_array($row['e_ag1_l100'], $match['bgpNgbr'])) ? 'mismatch' : ''; ?>"><?php echo $row['e_ag1_l100']; ?></span></li>
                                <li><span class="ctitle">Loopback0</span>  : <span class="<?php echo (count($match['bgpNgbr']) && !in_array($row['w_ag1_l100'], $match['bgpNgbr'])) ? 'mismatch' : ''; ?>"><?php echo $row['w_ag1_l100']; ?></span></li>
                            </ul>
                        </li>
                        <div><a href="#" rel="<?php echo $row['id']; ?>" tbl="NDD Output Master" class="updateRecord">Update</a></div><hr /></li>
            </ul>
            <?php
        }
    }
    ?>
    <hr />
</li>
<li id="ag1outputli"><span><strong>AG1 Output Master</strong></span>
    <?php
    if (count($data['ag1OutputData'])) {
        foreach ($data['ag1OutputData'] as $row) {
            ?>
            <ul>
                <li><span class="ctitle">Sap ID</span>  : <span class="<?php echo (!empty($match['sapid']) && $match['sapid'] != $row['modified_sapid']) ? 'mismatch' : ''; ?>"><?php echo $row['modified_sapid']; ?></span></li>
                <li><span class="ctitle">Hostname</span>  : <span class="<?php echo (!empty($match['hostname']) && $match['hostname'] != $row['hostname']) ? 'mismatch' : ''; ?>"><?php echo $row['hostname']; ?></span></li>
                <li><span class="ctitle">Loopback0</span>  : <span class="<?php echo (!empty($match['loopback']) && $match['loopback'] != $row['loopback0']) ? 'mismatch' : ''; ?>"><?php echo $row['loopback0']; ?></span></li>
                <li><span class="ctitle">loopback0 ipv6</span>  : <?php echo $row['loopback0_ipv6']; ?></li>
                <li><span class="ctitle">loopback100</span>  : <?php echo $row['loopback100']; ?></li>
                <li><span class="ctitle">loopback100 ipv6</span>  : <?php echo $row['loopback100_ipv6']; ?></li>
                <li><span class="ctitle">Link no</span>  : <?php echo $row['link_no']; ?></li>
                <li><span class="ctitle">East int rwip</span>  : <?php echo $row['east_int_rwip']; ?></li>
                <li><span class="ctitle">East int cwip</span>  : <?php echo $row['east_int_cwip']; ?></li>
                <li><span class="ctitle">West int rwip</span>  : <?php echo $row['west_int_rwip']; ?></li>
                <li><span class="ctitle">West int cwip</span>  : <?php echo $row['west_int_cwip']; ?></li>
                <li><span class="ctitle">East int rnip</span>  : <?php echo $row['east_int_rnip']; ?></li>
                <li><span class="ctitle">West int rnip</span>  : <?php echo $row['west_int_rnip']; ?></li>
                <li><span class="ctitle">AG2 a hostname</span>  : <?php echo $row['ag2_a_hostname']; ?></li>
                <li><span class="ctitle">AG2 b hostname</span>  : <?php echo $row['ag2_b_hostname']; ?></li>
                <li><span class="ctitle">AG3 a hostname</span>  : <?php echo $row['ag3_a_hostname']; ?></li>
                <li><span class="ctitle">AG3 b hostname</span>  : <?php echo $row['ag3_b_hostname']; ?></li>
                <li><span class="ctitle">NN value</span>  : <?php echo $row['nn_value']; ?></li>
                <li><span class="ctitle">X2 value</span>  : <?php echo $row['x2_value']; ?></li>
                <li><span class="ctitle">Region</span>  : <?php echo $row['region']; ?></li>
                <li><span class="ctitle">PDF done</span>  : <?php echo ($row['pdf_done']) ? 'Yes' : 'No'; ?>
                    <div><a href="#" rel="<?php echo $row['id']; ?>" tbl="AG1 Output Master" class="updateRecord">Update</a></div><hr /></li>
            </ul>
            <?php
        }
    }
    ?>
    <hr />
</li>
</ul>
</div>
<div id="ipdata" style="border:1px; float: left; width: 33%">
    <h3><span class="textyellow">IP Data</span></h3>
    <ul>
        <li id="ranlbli"><span><strong>Ran LB</strong></span>
            <?php
            if (count($data['ranLbData'])) {
                foreach ($data['ranLbData'] as $row) {
                    ?>
                    <ul>
                        <li><span class="ctitle">Sap ID</span>  : <span class="<?php echo (!empty($match['sapid']) && $match['sapid'] != $row['modified_sapid']) ? 'mismatch' : ''; ?>"><?php echo $row['modified_sapid']; ?></span></li>
                        <li><span class="ctitle">Hostname</span>  : <span class="<?php echo (!empty($match['hostname']) && $match['hostname'] != $row['host_name']) ? 'mismatch' : ''; ?>"><?php echo $row['host_name']; ?></span></li>
                        <li><span class="ctitle">Loopback0</span>  : <span class="<?php echo (!empty($match['loopback']) && $match['loopback'] != $row['ipv4']) ? 'mismatch' : ''; ?>"><?php echo $row['ipv4']; ?></span></li>
                        <li><span class="ctitle">Loopback ipv6</span>  : <span class="<?php echo (!empty($match['loopback0_ipv6']) && $match['loopback0_ipv6'] != $row['ipv6']) ? 'mismatch' : ''; ?>"><?php echo $row['ipv6']; ?></span></li>
                        <li><span class="ctitle">Is usable</span>  : <?php echo $row['is_usable']; ?></li>
                        <li><span class="ctitle">Validate</span>  : <?php echo $row['validate']; ?>
                            <div><a href="#" rel="<?php echo $row['ran_lb_id']; ?>" tbl="Ran LB" class="updateRecord">Update</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" rel="<?php echo $row['ran_lb_id']; ?>" tbl="Ran LB" class="freeRecord">Free</a>
                            </div><hr /></li>
                    </ul>
                    <?php
                }
            }
            ?>
            <hr />
        </li>
        <li id="corelbli"><span><strong>Core LB</strong></span>
            <?php
            if (count($data['coreLbData'])) {
                foreach ($data['coreLbData'] as $row) {
                    ?>
                    <ul>
                        <li><span class="ctitle">Sap ID</span>  : <span class="<?php echo (!empty($match['sapid']) && $match['sapid'] != $row['modified_sapid']) ? 'mismatch' : ''; ?>"><?php echo $row['modified_sapid']; ?></span></li>
                        <li><span class="ctitle">Hostname</span>  : <span class="<?php echo (!empty($match['hostname']) && $match['hostname'] != $row['host_name']) ? 'mismatch' : ''; ?>"><?php echo $row['host_name']; ?></span></li>
                        <li><span class="ctitle">Loopback0</span>  : <span class="<?php echo (!empty($match['loopback']) && $match['loopback'] != $row['ipv4']) ? 'mismatch' : ''; ?>"><?php echo $row['ipv4']; ?></span></li>
                        <li><span class="ctitle">Loopback ipv6</span>  : <span class="<?php echo (!empty($match['loopback0_ipv6']) && $match['loopback0_ipv6'] != $row['ipv6']) ? 'mismatch' : ''; ?>"><?php echo $row['ipv6']; ?></span></li>
                        <li><span class="ctitle">Is usable</span>  : <?php echo $row['is_usable']; ?></li>
                        <li><span class="ctitle">validate</span>  : <?php echo $row['validate']; ?>
                            <div><a href="#" rel="<?php echo $row['core_lb_id']; ?>" tbl="Core LB" class="updateRecord">Update</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" rel="<?php echo $row['core_lb_id']; ?>" tbl="Core LB" class="freeRecord">Free</a>
                            </div><hr /></li>
                    </ul>
                    <?php
                }
            }
            ?>
            <hr />
        </li>
        <li id="ranwanli"><span><strong>Ran Wan : compare with hostname</strong></span>
            <?php
            if (count($data['ranWanData'])) {
                foreach ($data['ranWanData'] as $row) {
                    $fromClassIP = $toClassIP = $fromClassHN = $toClassHN = 'mismatch';
                    if (in_array($row['from_addr'], $data['local_interface']) || in_array($row['from_addr'], $isisNeighInterface)) {
                        $fromClassIP = '';
                    }
                    if ($row['from_host_name'] == $match['hostname'] || array_key_exists($row['from_host_name'], $isisNeighInterface)) {
                        $fromClassHN = '';
                    }
                    if (in_array($row['to_addr'], $data['local_interface']) || in_array($row['to_addr'], $isisNeighInterface)) {
                        $toClassIP = '';
                    }
                    if ($row['to_host_name'] == $match['hostname'] || array_key_exists($row['to_host_name'], $isisNeighInterface)) {
                        $toClassHN = '';
                    }
                    ?>
                    <ul>
                        <li><span class="ctitle">From modified sapid</span>  : <?php echo $row['from_modified_sapid']; ?></li>
                        <li><span class="ctitle">From hostname</span>  : <span class="<?php echo $fromClassHN; ?>"><?php echo $row['from_host_name']; ?></span></li>
                        <li><span class="ctitle">From addr</span>  : <span class="<?php echo $fromClassIP; ?>"><?php echo $row['from_addr']; ?></span></li>
                        <li><span class="ctitle">To modified sapid</span>  : <?php echo $row['to_modified_sapid']; ?></li>
                        <li><span class="ctitle">To hostname</span>  : <span class="<?php echo $toClassHN; ?>"><?php echo $row['to_host_name']; ?></span></li>
                        <li><span class="ctitle">To addr</span>  : <span class="<?php echo $toClassIP; ?>"><?php echo $row['to_addr']; ?></span></li>
                        <li><span class="ctitle">Is usable</span>  : <?php echo $row['is_usable']; ?></li>
                        <li><span class="ctitle">Validate</span>  : <?php echo $row['validate']; ?>
                            <div><a href="#" rel="<?php echo $row['ran_wan_id']; ?>" tbl="Ran Wan" class="updateRecord">Update</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" rel="<?php echo $row['ran_wan_id']; ?>" tbl="Ran Wan" class="freeRecord">Free</a>
                            </div><hr /></li>
                    </ul>
                    <?php
                }
            }
            ?>
            <hr />
        </li>
        <li id="ranwanli"><span><strong>Ran Wan : compare with interface</strong></span>
            <?php
            if (count($data['ranWanInterfaceData'])) {
                foreach ($data['ranWanInterfaceData'] as $row) {
                    $fromClassIP = $toClassIP = $fromClassHN = $toClassHN = 'mismatch';
                    if (in_array($row['from_addr'], $data['local_interface']) || in_array($row['from_addr'], $isisNeighInterface)) {
                        $fromClassIP = '';
                    }
                    if ($row['from_host_name'] == $match['hostname'] || array_key_exists($row['from_host_name'], $isisNeighInterface)) {
                        $fromClassHN = '';
                    }
                    if (in_array($row['to_addr'], $data['local_interface']) || in_array($row['to_addr'], $isisNeighInterface)) {
                        $toClassIP = '';
                    }
                    if ($row['to_host_name'] == $match['hostname'] || array_key_exists($row['to_host_name'], $isisNeighInterface)) {
                        $toClassHN = '';
                    }
                    ?>
                    <ul>
                        <li><span class="ctitle">From modified sapid</span>  : <?php echo $row['from_modified_sapid']; ?></li>
                        <li><span class="ctitle">From hostname</span>  : <span class="<?php echo $fromClassHN; ?>"><?php echo $row['from_host_name']; ?></span></li>
                        <li><span class="ctitle">From addr</span>  : <span class="<?php echo $fromClassIP; ?>"><?php echo $row['from_addr']; ?></span></li>
                        <li><span class="ctitle">To modified sapid</span>  : <?php echo $row['to_modified_sapid']; ?></li>
                        <li><span class="ctitle">To hostname</span>  : <span class="<?php echo $toClassHN; ?>"><?php echo $row['to_host_name']; ?></span></li>
                        <li><span class="ctitle">To addr</span>  : <span class="<?php echo $toClassIP; ?>"><?php echo $row['to_addr']; ?></span></li>
                        <li><span class="ctitle">Is usable</span>  : <?php echo $row['is_usable']; ?></li>
                        <li><span class="ctitle">Validate</span>  : <?php echo $row['validate']; ?>
                            <div><a href="#" rel="<?php echo $row['ran_wan_id']; ?>" tbl="Ran Wan" class="updateRecord">Update</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" rel="<?php echo $row['ran_wan_id']; ?>" tbl="Ran Wan" class="freeRecord">Free</a>
                            </div><hr /></li>
                    </ul>
                    <?php
                }
            }
            ?>
            <hr />
        </li>
        <li id="corewanli"><span><strong>Core Wan : compare with hostname</strong></span>
            <?php
            if (count($data['coreWanData'])) {
                foreach ($data['coreWanData'] as $row) {
                    $fromClassIP = $toClassIP = $fromClassHN = $toClassHN = 'mismatch';
                    if (in_array($row['from_addr'], $data['local_interface']) || in_array($row['from_addr'], $isisNeighInterface)) {
                        $fromClassIP = '';
                    }
                    if ($row['from_host_name'] == $match['hostname'] || array_key_exists($row['from_host_name'], $isisNeighInterface)) {
                        $fromClassHN = '';
                    }
                    if (in_array($row['to_addr'], $data['local_interface']) || in_array($row['to_addr'], $isisNeighInterface)) {
                        $toClassIP = '';
                    }
                    if ($row['to_host_name'] == $match['hostname'] || array_key_exists($row['to_host_name'], $isisNeighInterface)) {
                        $toClassHN = '';
                    }
                    ?>
                    <ul>
                        <li><span class="ctitle">From modified sapid</span>  : <?php echo $row['from_modified_sapid']; ?></li>
                        <li><span class="ctitle">From hostname</span>  : <span class="<?php echo $fromClassHN; ?>"><?php echo $row['from_host_name']; ?></span></li>
                        <li><span class="ctitle">From addr</span>  : <span class="<?php echo $fromClassIP; ?>"><?php echo $row['from_addr']; ?></span></li>
                        <li><span class="ctitle">To modified sapid</span>  : <?php echo $row['to_modified_sapid']; ?></li>
                        <li><span class="ctitle">To hostname</span>  : <span class="<?php echo $toClassHN; ?>"><?php echo $row['to_host_name']; ?></span></li>
                        <li><span class="ctitle">To addr</span>  : <span class="<?php echo $toClassIP; ?>"><?php echo $row['to_addr']; ?></span></li>
                        <li><span class="ctitle">Is usable</span>  : <?php echo $row['is_usable']; ?>
                            <div><a href="#" rel="<?php echo $row['core_wan_id']; ?>" tbl="Core Wan" class="updateRecord">Update</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" rel="<?php echo $row['core_wan_id']; ?>" tbl="Core Wan" class="freeRecord">Free</a>
                            </div><hr /></li>
                    </ul>
                    <?php
                }
            }
            ?>
            <hr />
        </li>
        <li id="corewanli"><span><strong>Core Wan : compare with interface</strong></span>
            <?php
            if (count($data['coreWanInterfaceData'])) {
                foreach ($data['coreWanInterfaceData'] as $row) {
                    $fromClassIP = $toClassIP = $fromClassHN = $toClassHN = 'mismatch';
                    if (in_array($row['from_addr'], $data['local_interface']) || in_array($row['from_addr'], $isisNeighInterface)) {
                        $fromClassIP = '';
                    }
                    if ($row['from_host_name'] == $match['hostname'] || array_key_exists($row['from_host_name'], $isisNeighInterface)) {
                        $fromClassHN = '';
                    }
                    if (in_array($row['to_addr'], $data['local_interface']) || in_array($row['to_addr'], $isisNeighInterface)) {
                        $toClassIP = '';
                    }
                    if ($row['to_host_name'] == $match['hostname'] || array_key_exists($row['to_host_name'], $isisNeighInterface)) {
                        $toClassHN = '';
                    }
                    ?>
                    <ul>
                        <li><span class="ctitle">From modified sapid</span>  : <?php echo $row['from_modified_sapid']; ?></li>
                        <li><span class="ctitle">From hostname</span>  : <span class="<?php echo $fromClassHN; ?>"><?php echo $row['from_host_name']; ?></span></li>
                        <li><span class="ctitle">From addr</span>  : <span class="<?php echo $fromClassIP; ?>"><?php echo $row['from_addr']; ?></span></li>
                        <li><span class="ctitle">To modified sapid</span>  : <?php echo $row['to_modified_sapid']; ?></li>
                        <li><span class="ctitle">To hostname</span>  : <span class="<?php echo $toClassHN; ?>"><?php echo $row['to_host_name']; ?></span></li>
                        <li><span class="ctitle">To addr</span>  : <span class="<?php echo $toClassIP; ?>"><?php echo $row['to_addr']; ?></span></li>
                        <li><span class="ctitle">Is usable</span>  : <?php echo $row['is_usable']; ?>
                            <div><a href="#" rel="<?php echo $row['core_wan_id']; ?>" tbl="Core Wan" class="updateRecord">Update</a>
                                &nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" rel="<?php echo $row['core_wan_id']; ?>" tbl="Core Wan" class="freeRecord">Free</a>
                            </div><hr /></li>
                    </ul>
                    <?php
                }
            }
            ?>
            <hr />
        </li>
    </ul>
</div>