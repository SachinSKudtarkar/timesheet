<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BuiltMasterCommands extends CApplicationComponent {

    public static function getShowLinks($model, $fields) {
        echo CHtml::Button('View', array('onclick' => "getForm(" . $model->id . ",'$fields');", 'rel' => $fields, 'class' => 'showDetails', 'style' => 'margin-left:auto'));
    }

    public static function setAlterTable($modelName = '', $value = '', $key = '') {
        $model = new $modelName();
        if ($model->setAttribute($key, $value) === false) {
            $command = $model->getDbConnection()->createCommand('ALTER TABLE `' . $model->tableName() . '` ADD `' . $key . '` VARCHAR(200)  NULL');
            $command->execute();
            $model->getDbConnection()->getSchema()->refresh();
            $model->refreshMetaData();
            return true;
        }
        return true;
    }

    public static function getHostname($val = '', $type = '') {
        $host_name = str_replace('hostname ', '', trim($val));
        $type = substr(trim($host_name), 8, 3);
        return array('host_name' => $host_name, 'type' => $type);
    }

    public static function getBannerData($rows = array(), &$key = 0, $type = '') {
        $sap_id = $ne_id = $fac_id = '';
        foreach ($rows as $key => $val) {
            while (trim($rows[$key]) != '!' && $rows[$key] != '') {
                if (preg_match('/^NE-ID /', trim($rows[$key])) && $model->ne_id == '') {
                    $ne_id = explode(' ', trim($rows[$key]));
                    if (is_array($ne_id) && count($ne_id) > 0) {
                        $ne_id = $ne_id[1];
                    }
                } elseif (preg_match('/^SAP-ID /', trim($rows[$key])) && $model->sap_id == '') {
                    $sap_id = explode(' ', trim($rows[$key]));
                    if (is_array($sap_id) && count($sap_id) > 0) {
                        $sap_id = $sap_id[1];
                    }
                } elseif (preg_match('/^FAC-ID /', trim($rows[$key])) && $model->fac_id == '') {
                    $fac_id = explode(' ', trim($rows[$key]));
                    if (is_array($fac_id) && count($fac_id) > 0) {
                        $fac_id = $fac_id[1];
                    }
                }
                $key++;
            }
            if (!empty($ne_id) && !empty($sap_id) && !empty($fac_id)) {
                break;
            }
        }
        return array('sap_id' => $sap_id, 'ne_id' => $ne_id, 'fac_id' => $fac_id);
    }

    public static function getLoopback($rows = array(), &$key = 0, $type = '') {
        $sap_id = $Loopback0 = $Loopback0_ipv6 = '';
        while (trim($rows[$key]) != '!' && $rows[$key] != '') {
            if (preg_match("/^description ## /", trim($rows[$key]))) {
                $temp = explode("description ## ", trim($rows[$key]));
                if (is_array($temp) && count($temp) > 0 && isset($temp[1])) {
                    if (preg_match('/\S{1}\-\S{2}\-\S{4}\-\S{3}\-\S{4}/', substr($temp[1], 0, 18))) {
                        $sap_id = substr($temp[1], 0, 18);
                    }
                }
            } elseif (preg_match("/^ipv4 address/", trim($rows[$key]))) {
                preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $temp);
                if (is_array($temp) && count($temp) > 0) {
                    $Loopback0 = trim($temp[0]);
                }
            } elseif (preg_match("/^ip address/", trim($rows[$key]))) {
                preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $temp);
                if (is_array($temp) && count($temp) > 0) {
                    $Loopback0 = trim($temp[0]);
                }
            } elseif (preg_match("/^ipv6 address/", trim($rows[$key]))) {
                $temp = explode("ipv6 address", trim($rows[$key]));
                if (is_array($temp) && count($temp) > 0 && isset($temp[1])) {
                    $Loopback0_ipv6 = trim(str_replace('/128', '', $temp[1]));
                }
            }
            $key++;
        }
        return array('sap_id' => $sap_id, 'Loopback0' => $Loopback0, 'Loopback0_ipv6' => $Loopback0_ipv6);
    }

    public static function getLoopback100($rows = array(), &$key = 0, $type = '') {
        $Loopback100 = $Loopback100_ipv6 = '';
        while (trim($rows[$key]) != '!' && $rows[$key] != '') {
            $ipv4 = $ipv6 = '';
            if (preg_match("/^ip address/", trim($rows[$key]))) {
                preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $ipv4);
                if (is_array($ipv4) && count($ipv4) > 0) {
                    $Loopback100 = $ipv4[0];
                }
            } else if (preg_match("/^ipv6 address/", trim($rows[$key]))) {
                $ipv6 = explode("ipv6 address", trim($rows[$key]));
                if (is_array($ipv6) && count($ipv6) > 0 && isset($ipv6[1])) {
                    $Loopback100_ipv6 = str_replace('/128', '', $ipv6[1]);
                }
            }
            $key++;

            if (!empty($Loopback100) && !empty($Loopback100_ipv6)) {
                break;
            }
        }
        return array('Loopback100' => $Loopback100, 'Loopback100_ipv6' => $Loopback100_ipv6);
    }

    public static function getLoopback200($rows = array(), &$key = 0, $type = '') {
        $Loopback200 = '';
        preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $temp);
        if (is_array($temp) && count($temp) > 0) {
            return trim($temp[0]);
        }
        return $Loopback200;
    }

    public static function getLoopback201($rows = array(), &$key = 0, $type = '') {
        $Loopback201 = '';
        preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $temp);
        if (is_array($temp) && count($temp) > 0 && isset($temp[0])) {
            return trim($temp[0]);
        }
        return $Loopback201;
    }

    public static function getRouterIsisRan($rows = array(), &$key = 0, $type = '') {
        $isis_ran = $temp = '';
        $temp = explode(' ', trim($rows[$key]));
        if (is_array($temp) && isset($temp[1])) {
            return $temp[1];
        }
        return $isis_ran;
    }

    public static function getRouterIsisCore($rows = array(), &$key = 0, $type = '') {
        $isis_core = $temp = '';
        if ($type == 'PAR' || $type == 'ESR') {
            $temp = explode(' ', trim($rows[$key]));
            if (is_array($temp) && isset($temp[1])) {
                return $temp[1];
            }
        } else {
            while (trim($rows[$key]) != '!' && trim($rows[$key]) != '') {
                if (preg_match('/^net/', trim($rows[$key]))) {
                    $isis_core = explode(' ', trim($rows[$key]));
                    return $isis_core[1];
                }
                $key++;
            }
        }
        return $isis_core;
    }

    public static function getTacacsIp($rows = array(), &$key = 0, $type = '') {
        $tacacs1 = $tacacs2 = '';
        foreach ($rows as $key => $val) {
            if (preg_match('/^address ipv4/', trim($rows[$key]))) {
                preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $temp);
                if (strpos($rows[$key - 1], 'TACACS1')) {
                    $tacacs1 = $temp[0];
                } else {
                    $tacacs2 = $temp[0];
                }
            }
        }
        return array('tacacs1' => $tacacs1, 'tacacs2' => $tacacs2);
    }

    public static function getShowIsisNeigh($rows = array(), &$key = 0, $type = '', $isisPortArr = array()) {
        $isis_neigh = array();
        if ($type == 'PAR' || $type == 'ESR') {
            $inc = 0;
            $isisNgbrArr = array();
            $local_port = $int_port_no = '';
            while (!preg_match("/^---/", trim($rows[$key]))) {
                if (preg_match("/^Tag/", trim($rows[$key]), $match)) {
                    $type = trim(str_replace(':', '', end((explode(' ', trim($rows[$key]))))));
                    $inc = 0;
                }
                $ngbrDtl = array_values(array_filter(explode(" ", $rows[$key])));
                if (is_array($ngbrDtl) and count($ngbrDtl) > 0 and strlen($ngbrDtl[0]) == 14) {
                    if (preg_match('/[0-9]{3}$/D', $ngbrDtl[2])) {
                        $int_port_no = substr($ngbrDtl[2], -3);
                    } else {
                        if (preg_match('/^[a-zA-Z].*\d{1,2}/', $ngbrDtl[2], $match)) {
                            $local_port = $match[0];
                            if (!empty($local_port)) {
                                $isisPortArr[$int_port_no] = $local_port;
                            }
                            $local_port = '';
                        }
                    }

                    $isisNgbrArr[] = array('neighbors_host_name' => $ngbrDtl[0], 'neighors_interface_ip' => $ngbrDtl[3], 'type' => $type, 'interface' => $ngbrDtl[2], 'local_interface_port' => $isisPortArr[$int_port_no]);
                    $inc = 0;
                }
                $key++;
                if ($inc > 3) {
                    break;
                }
                $inc++;
            }
            return $isisNgbrArr;
        } elseif ($type == 'CCR' || $type == 'AAR' || $type == 'CSR' || $type == 'CRR' || $type == 'CMR' || $type == 'AMR' || $type == 'IAR' || $type == 'IRR' || $type == 'CBR' || $type == 'URR') {
            $inc = $system_id = 0;
            while ($inc < 3 && !preg_match('/^Total/', trim($rows[$key]))) {
                if ($rows[$key] == '') {
                    $inc++;
                }
                if (preg_match('/^System Id/', trim($rows[$key]))) {
                    $system_id = 1;
                }
                if ($system_id == 1 && trim($rows[$key]) == '') {
                    break;
                }
                if (preg_match('/^[a-zA-Z].*\d{1,2}/', trim($rows[$key])) && $system_id == 1) {
                    $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
                    $explode = explode(' ', $remove_spaces);
                    if (is_array($explode) && count($explode) > 0) {
                        if ($type == 'CMR' || $type == 'AMR' || $type == 'IRR' || $type == 'CBR' || $type == 'URR') {
                            $isis_neigh['hostname'] = $explode[0];
                            $isis_neigh['type'] = $explode[1];
                            $isis_neigh['interface'] = $explode[2];
                            $isis_neigh['ip_address'] = $explode[3];
                            $isis_neigh['state'] = $explode[4];
                        } else {
                            $isis_neigh['hostname'] = $explode[0];
                            $isis_neigh['interface'] = $explode[1];
                            $isis_neigh['state'] = $explode[3];
                            $isis_neigh['type'] = $explode[5];
                        }
                    }
                    $shisisNgbrArr[] = $isis_neigh;
                }
                $key++;
            }
            return $shisisNgbrArr;
        }
    }

    public static function getShowIBgpSummary($rows = array(), &$key = 0, $type = '') {
        $key++;
        $inc = 0;
        $ngbr_found = 0;
        $bgp_summary = $ipBgpArr = array();
        while ($inc < 2 && !preg_match('/#show/', trim($rows[$key]))) {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match('/^Neighbor/', trim($rows[$key]))) {
                $ngbr_found = 1;
            }
            if ($ngbr_found == 1 && trim($rows[$key]) == '') {
                break;
            }
            if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', trim($rows[$key])) && $ngbr_found == 1) {
                $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
                $explode = explode(' ', $remove_spaces);
                if (is_array($explode) && count($explode) > 0) {
                    if ($type == 'CRR' || $type == "IRR" || $type == "URR") {
                        $bgp_summary['loopback'] = $explode[0];
                    } else {
                        $bgp_summary['loopback0'] = $explode[0];
                    }
                    $bgp_summary['state'] = $explode[9];
                }
                if (!empty($bgp_summary['loopback']) || !empty($bgp_summary['loopback0'])) {
                    if ($type == 'CRR' || $type == "IRR" || $type == "URR") {
                        $ipBgpArr[$bgp_summary['loopback']] = $bgp_summary;
                    } else {
                        $ipBgpArr[$bgp_summary['loopback0']] = $bgp_summary;
                    }
                }
            } elseif (preg_match('/^(((?=(?>.*?(::))(?!.+\3)))\3?|([\dA-F]{1,4}(\3|:(?!$)|$)|\2))(?4){5}((?4){2}|((2[0-4]|1\d|[1-9])?\d|25[0-5])(\.(?7)){3})\z/i', trim($rows[$key])) && $ngbr_found == 1) {
                $merge = $rows[$key] . $rows[$key + 1];
                $remove_spaces = preg_replace('/\s\s+/', ' ', trim($merge));
                $explode = explode(' ', $remove_spaces);
                if (count($explode) > 0) {
                    if ($type == 'CRR' || $type == "IRR") {
                        $bgp_summary['loopback'] = $explode[0];
                    } else {
                        $bgp_summary['loopback0'] = $explode[0];
                    }
                    $bgp_summary['state'] = $explode[9];
                }
                if (!empty($bgp_summary['loopback']) || !empty($bgp_summary['loopback0'])) {
                    if ($type == 'CRR' || $type == "IRR") {
                        $ipBgpArr[$bgp_summary['loopback']] = $bgp_summary;
                    } else {
                        $ipBgpArr[$bgp_summary['loopback0']] = $bgp_summary;
                    }
                }
            }
            $key++;
        }
        return $ipBgpArr;
    }

    public static function getShowCdpNgbr($rows = array(), &$key = 0, $type = '') {
        $inc = 0;
        $cdpNgbr = $cdpNgbrArr = array();
        while ($inc <= 3) {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match('/^Device ID/', trim($rows[$key]))) {
                $temp = explode(' ', trim(str_replace('Device ID:', '', trim($rows[$key]))));
                if (is_array($temp) && count($temp) > 0) {
                    $cdpNgbr['device_id'] = $temp[0];
                    $inc = 0;
                    if ($type == 'CCR' || $type == 'AAR' || $type == 'CSR' || $type == 'CRR' || $type == 'IAR' || $type == 'IRR' || $type == 'URR') {
                        $cdpNgbr['remote_hostname'] = substr($temp[0], 0, 14);
                    } elseif ($type == 'PAR' || $type == 'ESR' || $type == 'CMR' || $type == 'AMR' || $type == 'CBR') {
                        $cdpNgbr['device_host_name'] = substr($temp[0], 0, 14);
                    }
                }
            } elseif (preg_match('/^IP address/', trim($rows[$key])) || preg_match('/^IPv4 address/', trim($rows[$key]))) {
                preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $temp);
                if (is_array($temp) && count($temp) > 0) {
                    $cdpNgbr['interface_ipv4'] = $temp[0];
                }
            } elseif (preg_match("/^IPv6 address/", trim($rows[$key])) && empty($cdpNgbr['interface_ipv6'])) {
                if (preg_match('/\S\d*:\S+/', $rows[$key], $match)) {
                    $cdpNgbr['interface_ipv6'] = $match[0];
                }
            } elseif (preg_match('/^Interface/', trim($rows[$key]))) {
                $interfaceDtil = array_values(array_filter(explode(" ", $rows[$key])));
                $portDtil = array_values(array_filter(explode(":", $rows[$key + 1])));
                if ((is_array($interfaceDtil) && count($interfaceDtil) > 0) || (is_array($portDtil) && count($portDtil) > 0)) {
                    if ($type == 'CCR' || $type == 'AAR' || $type == 'CSR' || $type == 'CRR' || $type == 'IAR' || $type == 'IRR' || $type == 'URR') {
                        if ($type == 'URR' || $type == 'IRR') {
                            $cdpNgbr['remote_port'] = trim($interfaceDtil[6]);
                        } else {
                            $cdpNgbr['remote_port'] = trim($portDtil[1]);
                        }
                        $cdpNgbr['local_port'] = str_replace(',', '', trim($interfaceDtil[1]));
                    } elseif ($type == 'PAR' || $type == 'ESR' || $type == 'CMR' || $type == 'AMR' || $type == 'CBR') {
                        $cdpNgbr['remote_Interface'] = trim($interfaceDtil[6]);
                        $cdpNgbr['local_Interface'] = str_replace(',', '', trim($interfaceDtil[1]));
                    }
                }
                if (count($cdpNgbr) > 2)
                    $cdpNgbrArr[] = $cdpNgbr;
            }
            $key++;
        }
        return $cdpNgbrArr;
    }

    public static function getShowIpIntBrief($rows = array(), &$key = 0, $type = '') {
        $inc = 0;
        $interface_found = $space = 0;
        $ip_int = $ipIntBrief = array();
        if ($type == "ESR" || $type == "PAR" || $type == "CRR" || $type == "CMR" || $type == "AMR" || $type == "CNR" || $type == "IRR" || $type == "CBR" || $type == 'URR') {
            $space = 1;
        } elseif ($type == "CCR" || $type == "AAR" || $type == "CSR" || $type == "IAR") {
            $space = 3;
        }
        while ($inc < $space && !preg_match('/#show/', trim($rows[$key]))) {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match('/^Interface/', trim($rows[$key]))) {
                $interface_found = 1;
            }
            if ($interface_found == 1 && trim($rows[$key]) == '') {
                break;
            }
            if (preg_match('/^[a-zA-Z].*\d{1,2}/', trim($rows[$key])) && $interface_found == 1) {
                $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
                $explode = explode(' ', $remove_spaces);
                if (is_array($explode) && count($explode) > 0) {
                    $ip_int['interface'] = $explode[0];
                    $ip_int['ip_address'] = $explode[1];
                    if ($type == "ESR" || $type == "PAR" || $type == "CRR" || $type == "CMR" || $type == "AMR" || $type == "CBR" || $type == 'URR' || $type == "IRR") {
                        $ip_int['status'] = $explode[4];
                        $ip_int['protocol'] = $explode[5];
                    } elseif ($type == "CCR" || $type == "AAR" || $type == "CSR" || $type == "IAR" || $type == "CNR") {
                        $ip_int['status'] = $explode[2];
                        $ip_int['protocol'] = $explode[3];
                    }
                }
                $ipIntBrief[] = $ip_int;
            }
            $key++;
        }
        return $ipIntBrief;
    }

    public static function getShowIpCef($rows = array(), &$key = 0, $type = '') {
        $inc = 0;
        $shIpCef = array();
        while ($inc < 1 && !preg_match('/#show/', trim($rows[$key]))) {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match("/^nexthop /", trim($rows[$key]))) {
                preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $temp);
                $shIpCef['nexthop'][] = $temp[0];
            }
            if (preg_match("/^repair: /", trim($rows[$key]))) {
                preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $temp);
                $shIpCef['repair'][] = $temp[0];
            }
            $key++;
        }
        return $shIpCef;
    }

    public static function getInstallSummary($rows = array(), &$key = 0, $type = '') {
        $inc = 0;
        $installSummary = array();
        while ($inc <= 1) {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match('/^Committed Packages:/', trim($rows[$key]))) {
                $committed_found = 1;
            }
            if ($committed_found == 1 && trim($rows[$key]) == '') {
                break;
            }
            if (preg_match('/^disk0\S+/', trim($rows[$key]), $comm_temp) && $committed_found == 1) {
                $installSummary[] = $comm_temp[0];
            }
            $key++;
        }
        return $installSummary;
    }

    public static function getShowVersion($rows = array(), &$key = 0, $type = '') {
        $show_version = '';
        $inc = 0;
        while ($inc <= 3) {
            if (trim($rows[$key]) == '') {
                $inc++;
            }
            if ($type == 'CES' || $type == 'CAS') {
                if (preg_match('/^system:/', trim($rows[$key]))) {
                    if (preg_match("/\d+(?:\.\d+)\S+/", $rows[$key], $match)) {
                        return $match[0];
                    }
                }
            } elseif (preg_match('/^Cisco IOS XR Software/', trim($rows[$key]))) {
                if (preg_match("/\d+(?:\.\d+)+/", $rows[$key], $match)) {
                    return $match[0];
                }
            }
            $key++;
        }
        return $show_version;
    }

    public static function getShIpBgAllAllSum($rows = array(), &$key = 0, $type = '') {
        $inc = 0;
        $all_ip_bgp_summary = array();
        while ($inc < 25 && !preg_match('/#show/', trim($rows[$key]))) {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match('/^Address Family: VPNv4 Unicast/', trim($rows[$key])) || preg_match('/^Address Family: VPNv6 Unicast/', trim($rows[$key])) || preg_match('/^Address Family: IPv4 Labeled-unicast/', trim($rows[$key])) || preg_match('/^Address Family: IPv6 Labeled-unicast/', trim($rows[$key])) || preg_match('/^Address Family: IPv4 Unicast/', trim($rows[$key])) || preg_match('/^Address Family: RT Constraint/', trim($rows[$key])) || preg_match('/^Address Family: IPv6 Unicast/', trim($rows[$key]))) {
                $addr_family = explode('Address Family: ', trim($rows[$key]));
                $key++;
                $vpnv4 = 0;
                while ($vpnv4 < 6) {
                    if ($rows[$key] == '') {
                        $vpnv4++;
                    }
                    if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', trim($rows[$key])) || preg_match('/^(((?=(?>.*?(::))(?!.+\3)))\3?|([\dA-F]{1,4}(\3|:(?!$)|$)|\2))(?4){5}((?4){2}|((2[0-4]|1\d|[1-9])?\d|25[0-5])(\.(?7)){3})\z/i', trim($rows[$key]))) {
                        $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
                        $explode = explode(' ', $remove_spaces);
                        if (is_array($explode) && count($explode) > 0) {
                            $all_ip_bgp_summary['address_family'] = $addr_family[1];
                            $all_ip_bgp_summary['loopback'] = $explode[0];
                            $all_ip_bgp_summary['state'] = $explode[9];
                        }
                        if (count($explode) == 1) {
                            $all_ip_bgp_summary['address_family'] = $addr_family[1];
                            $all_ip_bgp_summary['loopback'] = $explode[0];
                            $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key + 1]));
                            $explode = explode(' ', $remove_spaces);
                            $all_ip_bgp_summary['state'] = $explode[8];
                        }
                        $shIpBgpSummary[] = $all_ip_bgp_summary;
                    }
                    $key++;
                }
            }
            $key++;
        }
        return $shIpBgpSummary;
    }

    public static function getShowInventory($rows = array(), &$key = 0, $type = '') {
        $inc = 0;
        $no = 1;
        $showInventoryAll = array();
        while (trim($rows[$key]) != ' ' && $inc < 2) {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match('/^NAME/', trim($rows[$key]))) {
                $explode_name_desc = explode('DESCR:', trim($rows[$key]));
                $explode_pid = explode(',', trim($rows[$key + 1]));
                $inc = 0;
                $name = explode(":", $explode_name_desc[0]);
                $pid = explode(":", $explode_pid[0]);
                $vid = explode(":", $explode_pid[1]);
                $sn = explode(":", $explode_pid[2]);

                $showInventoryAll[$no]['name'] = str_replace(",", "", trim(str_replace('"', '', $name[1])));
                $showInventoryAll[$no]['description'] = trim(str_replace('"', '', $explode_name_desc[1]));
                $showInventoryAll[$no]['pid'] = trim($pid[1]);
                $showInventoryAll[$no]['vid'] = trim($vid[1]);
                $showInventoryAll[$no]['sn'] = trim($sn[1]);
                $no++;
            }

            $key++;
        }
        return $showInventoryAll;
    }

    public static function getshIpv6UnicastSum($rows = array(), &$key = 0, $type = '') {
        $ipv6BgpSummary = array();
        $inc = 0;
        $ipv6_ngbr_found = 0;
        $bgp_ipv6 = array();
        $id = 1;
        while ($inc < 3 && !preg_match('/#show/', trim($rows[$key]))) {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match('/^Neighbor/', trim($rows[$key]))) {
                $ipv6_ngbr_found = 1;
            }
            if (preg_match('/^(((?=(?>.*?(::))(?!.+\3)))\3?|([\dA-F]{1,4}(\3|:(?!$)|$)|\2))(?4){5}((?4){2}|((2[0-4]|1\d|[1-9])?\d|25[0-5])(\.(?7)){3})\z/i', trim($rows[$key])) && $ipv6_ngbr_found == 1) {
                $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key + 1]));
                $explode = explode(' ', $remove_spaces);
                $ipv6BgpSummary[$id][] = $rows[$key];
                if (is_array($explode) && count($explode) > 0) {
                    $ipv6BgpSummary[$id][] = $explode[8];
                }
            }
            $id++;
            $key++;
        }
        return $ipv6BgpSummary;
    }

    public static function getshIpv6VrfAllBrief($rows = array(), &$key = 0, $type = '') {
        $rjil_signaling_enb = $rjil_bearer_enb = $rjil_oam_enb = '';
        $inc = 0;
        while ($inc < 2) {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match('/Vrf-Name: RJIL-SIGNALING-ENB/', trim($rows[$key])) && empty($sar_model->rjil_signaling_enb)) {
                $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key + 2]));
                $explode = explode(' ', $remove_spaces);
                if (is_array($explode) && count($explode) > 0) {
                    $rjil_signaling_enb = $explode[0];
                }
            }
            if (preg_match('/Vrf-Name: RJIL-BEARER-ENB/', trim($rows[$key])) && empty($sar_model->rjil_bearer_enb)) {
                $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key + 2]));
                $explode = explode(' ', $remove_spaces);
                if (is_array($explode) && count($explode) > 0) {
                    $rjil_bearer_enb = $explode[0];
                }
            }
            if (preg_match('/Vrf-Name: RJIL-OAM-ENB/', trim($rows[$key])) && empty($sar_model->rjil_oam_enb)) {
                $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key + 2]));
                $explode = explode(' ', $remove_spaces);
                if (is_array($explode) && count($explode) > 0) {
                    $rjil_oam_enb = $explode[0];
                }
            }
            $key++;
        }
        return array('rjil_signaling_enb' => $rjil_signaling_enb, 'rjil_bearer_enb' => $rjil_bearer_enb, 'rjil_oam_enb' => $rjil_oam_enb);
    }

    public static function getshIpslaSummary($rows = array(), &$key = 0, $type = '') {
        $inc = 0;
        $ip_summary = array();
        while ($inc < 2 && !preg_match('/#show/', trim($rows[$key]))) {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match('/^ID/', trim($rows[$key]))) {
                $id = 1;
            }
            if ((preg_match('/^\*\d+/', trim($rows[$key])) || preg_match('/^\^\d+/', trim($rows[$key])) || preg_match('/^\~\d+/', trim($rows[$key]))) && $id == 1) {
                $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
                $explode = explode(' ', $remove_spaces);
                if (is_array($explode) && count($explode) > 0) {
                    $ip_summary['probe_id'] = $explode[0];
                }
                if (is_array($explode) && count($explode) > 0) {
                    $ip_summary['type'] = $explode[1];
                }
                if (is_array($explode) && count($explode) > 0) {
                    $ip_summary['destination_ip'] = $explode[2];
                }
                if (is_array($explode) && count($explode) > 0) {
                    $ip_summary['stats'] = $explode[3];
                }
                if (is_array($explode) && count($explode) > 0) {
                    $ip_summary['return_code'] = $explode[4];
                }
                $shIpslaSummary[] = $ip_summary;
            }
            $key++;
        }
        return $shIpslaSummary;
    }

    public static function getShModule($rows = array(), &$key = 0, $type = '') {
        $key++;
        $num_found = 0;
        $shModule = array();
        while (!preg_match('/#show/', trim($rows[$key])) && !preg_match('/#sh/', trim($rows[$key]))) {
            if (preg_match('/^[0-9]/', trim($rows[$key]))) {
                $remove_spaces = preg_replace('/\s\s+/', '|', trim($rows[$key]));
                $explode_data = explode("|", $remove_spaces);
                if (is_array($explode_data) && count($explode_data) > 0) {
                    if (!isset($shModule[$explode_data[0]])) {
                        $shModule[$explode_data[0]]['mod'] = $explode_data[0];
                        $shModule[$explode_data[0]]['ports'] = $explode_data[1];
                        $shModule[$explode_data[0]]['module_type'] = $explode_data[2];
                        $shModule[$explode_data[0]]['model'] = $explode_data[3];
                    }
                }
            }
            if (preg_match('/Serial-Num/', trim($rows[$key]))) {
                $num_found = 1;
            }
            if ($num_found == 1 && trim($rows[$key]) == '') {
                break;
            }
            if (preg_match('/^[0-9]/', trim($rows[$key])) && $num_found == 1) {
                $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
                $explode_serial = explode(" ", $remove_spaces);
                if (is_array($explode_serial) && count($explode_serial) > 0) {
                    $shModule[$explode_serial[0]]['serial_no'] = $explode_serial[4];
                }
            }
            $key++;
        }
        return $shModule;
    }

    public static function getShFex($rows = array(), &$key = 0, $type = '') {
        $key++;
        $inc = 0;
        $fex_data = $shFex = array();
        while ($inc < 1) {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match('/^[0-9]/', trim($rows[$key]))) {
                $remove_spaces = preg_replace('/\s\s+/', '|', trim($rows[$key]));
                $explode = explode('|', $remove_spaces);
                $count_array = count($explode);
                if (is_array($explode) && count($explode) > 0) {
                    $fex_data['fex_no'] = $explode[0];
                    $fex_data['fex_state'] = $explode[$count_array - 3];
                    $fex_data['fex_model'] = $explode[$count_array - 2];
                    $fex_data['fex_serial'] = $explode[$count_array - 1];
                }
                $desc = implode(" ", array_slice($explode, 1, $count_array - 4));
                $fex_data['fex_description'] = trim($desc);
                $shFex[$explode[0]] = $fex_data;
            }

            $key++;
        }
        return $shFex;
    }

    public static function getShPortChannelSum($rows = array(), &$key = 0, $type = '') {
        $key++;
        $inc = 0;
        $port = $ShPortChannelSum = array();
        while ($inc < 3) {
            if (trim($rows[$key]) == '') {
                $inc++;
            }
            if (preg_match('/[a-zA-Z0-9]/', trim($rows[$key]))) {
                if (preg_match('/^[0-9]{1,4}/', trim($rows[$key]), $temp)) {
                    $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
                    $explode = explode(' ', $remove_spaces);
                    $count_array = count($explode);
                    if (is_array($explode) && count($explode) > 0) {
                        $port['port_group'] = $explode[0];
                        $port['port_channel'] = $explode[1];
                        $port['port_type'] = $explode[2];
                        $port['port_protocol'] = $explode[3];
                    }
                    $mem_ports = implode(" ", array_slice($explode, 4, $count_array));
                    $port['port_mem_ports'] = $mem_ports;
                    if (preg_match('/^Eth/', trim($rows[$key + 1]))) {
                        $remove_spaces = preg_replace('/\s\s*/', ' ', trim($rows[$key + 1]));
                        $port['port_mem_ports'] .= " " . $remove_spaces;
                    }
                    $ShPortChannelSum[$explode[0]] = $port;
                }
            }
            $key++;
        }
        return $ShPortChannelSum;
    }

    public static function getShInterfaceStatus($rows = array(), &$key = 0, $type = '') {
        $key++;
        $inc = 0;
        $status = $shInterfaceStatus = array();
        $title = '';
        while ($inc < 3 && !preg_match('/#(show|sh|access)/', trim($rows[$key]))) {
            if (trim($rows[$key]) == '') {
                $inc++;
            }
            if (preg_match('/^[a-zA-z0-9]/', trim($rows[$key])) && preg_match('/Port/', trim($rows[$key]))) {
                $title .= trim($rows[$key]);
            }
            if (preg_match('/^[a-zA-z0-9]/', trim($rows[$key])) && !preg_match('/Port/', trim($rows[$key]))) {
                $status['interface_port'] = substr(trim($rows[$key]), strpos($title, "Port"), (strpos($title, "Name")) - (strpos($title, "Port")));
                $status['interface_port_name'] = substr(trim($rows[$key]), strpos($title, "Name"), (strpos($title, "Status")) - (strpos($title, "Name")));
                $status['interface_port_status'] = substr(trim($rows[$key]), strpos($title, "Status"), (strpos($title, "Vlan")) - (strpos($title, "Status")));
                $status['interface_port_vlan'] = substr(trim($rows[$key]), strpos($title, "Vlan"), (strpos($title, "Duplex")) - (strpos($title, "Vlan")));
                $status['interface_port_dulpex'] = substr(trim($rows[$key]), strpos($title, "Duplex"), (strpos($title, "Speed")) - (strpos($title, "Duplex")));
                $status['interface_port_speed'] = substr(trim($rows[$key]), strpos($title, "Speed"), (strpos($title, "Type")) - (strpos($title, "Speed")));
                $status['interface_port_type'] = substr(trim($rows[$key]), strpos($title, "Type"));

                $shInterfaceStatus[trim($status['interface_port'])] = $status;
            }
            $key++;
        }

        return $shInterfaceStatus;
    }

    public static function getShMplsLdpNeig($rows = array(), &$key = 0, $type = '') {
        $inc = 0;
        $data = array();
        while (trim($rows[$key]) != ' ' && $inc <= 1) {
            if (trim($rows[$key]) == '') {
                $inc++;
            }
            if (preg_match('/^Peer LDP /', trim($rows[$key]))) {
                $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
                $explode = explode(' ', $remove_spaces);
                if (is_array($explode) && count($explode) > 0) {
                    if ($type == "CCR" || $type == "AAR" || $type == "CSR") {
                        $data['peer_ldp'] = preg_replace('/:.*/', '', $explode[3]);
                        $inc = 0;
                    } else {
                        $data['peer_ldp'] = preg_replace('/:.*/', '', $explode[3]);
                        $data['local_ldp'] = preg_replace('/:.*/', '', $explode[7]);
                    }
                }
                $shMplsLdpNeig[] = $data;
            }
            $key++;
        }
        return $shMplsLdpNeig;
    }

    public static function getGigabitInterfaceData($rows = array(), &$key = 1, $gigabit_ethernet = '', $value = '') {
        $key++;
        $inc = 0;
        $bridge_domain = $isisPortArr = array();
        $implode_bridge_domain = $explode_mtu = '';
        while ($rows[$key] != '!' && $inc < 3 && $rows[$key] != '') {
            if (preg_match('/^mtu \d{4,}/', trim($rows[$key]))) {
                $explode_mtu = explode(' ', trim($rows[$key]));
            }
            if (preg_match("/^bridge-domain/", trim($rows[$key]))) {
                $gigabit_ethernet_val = trim($rows[$key]);
                $isisPortArr[substr($gigabit_ethernet_val, 14, 3)] = str_replace('interface ', '', trim($value));
                $gigabit_ethernet_val_arr = explode(" ", $gigabit_ethernet_val);
                if (is_numeric($gigabit_ethernet_val_arr[1]))
                    $bridge_domain[] = $gigabit_ethernet_val_arr[1];
                $implode_bridge_domain = implode(",", $bridge_domain);
                //break;
            }
            $key++;
        }
        return array('bridgeDomain' => $implode_bridge_domain, 'portData' => $isisPortArr, 'mtu' => $explode_mtu[1]);
    }

    public static function getTengigInterfaceData($rows = array(), &$key = 1, $gigabit_ethernet = '', $value = '') {
        $key++;
        $inc = 0;
        $bridge_domain = $isisPortArr = array();
        $implode_bridge_domain = $explode_mtu = '';
        while ($rows[$key] != '!' && $inc < 3 && $rows[$key] != '') {
            if ($rows[$key] == '!') {
                $inc++;
            }
            if (preg_match('/^mtu \d{4,}/', trim($rows[$key]))) {
                $explode_mtu = explode(' ', trim($rows[$key]));
            }
            if (preg_match("/^ip address/", trim($rows[$key]))) {
                preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $temp);
                if (is_array($temp) && count($temp) > 0) {
                    $ipv4 = $temp[0];
                }
                break;
            }
            if (preg_match("/^bridge-domain/", trim($rows[$key]))) {
                $gigabit_ethernet_val = trim($rows[$key]);
                $isisPortArr[substr($gigabit_ethernet_val, 14, 3)] = str_replace('interface ', '', trim($value));
                $gigabit_ethernet_val_arr = explode(" ", $gigabit_ethernet_val);
                $bridge_domain[] = $gigabit_ethernet_val_arr[1];
                $implode_bridge_domain = implode(",", $bridge_domain);
                //break;
            }
            $key++;
        }
        return array('bridgeDomain' => $implode_bridge_domain, 'portData' => $isisPortArr, 'ipv4' => $ipv4, 'mtu' => $explode_mtu[1]);
    }

    public static function getShVlanBrief($rows = array(), &$key = 0, $type = '') {
        $key++;
        $inc = 0;
        $VlanDetails = array();
        $vlan_no = '';
        while ($inc <= 1) {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match('/[0-9]{1,4}/', trim($rows[$key]))) {
                $remove_spaces = preg_replace('/\s\s+/', ' ', $rows[$key]);
                $explode_data = explode(" ", $remove_spaces);
                $count_array = count($explode_data);
                if (is_array($explode_data) && count($explode_data) > 0) {
                    if (empty($explode_data[0])) {
                        $desc = implode(" ", array_slice($explode_data, 1, $count_array - 1));
                        $VlanDetails[$vlan_no]['vlan_ports'] .= ", " . $desc;
                    } else {
                        $desc = implode(" ", array_slice($explode_data, 3, $count_array - 1));
                        $vlan_no = $explode_data[0];
                        $VlanDetails[$vlan_no]['vlan_no'] = $explode_data[0];
                        $VlanDetails[$vlan_no]['vlan_name'] = $explode_data[1];
                        $VlanDetails[$vlan_no]['vlan_status'] = $explode_data[2];
                        $VlanDetails[$vlan_no]['vlan_ports'] .= $desc;
                    }
                }
            }
            $key++;
        }
        return $VlanDetails;
    }

    public static function getDirMemorySize($rows = array(), &$key = 0, $type = '') {
        $inc = 0;
        while ($inc < 3) {
            if (trim($rows[$key]) == '') {
                $inc++;
            }
            $ram_size = array();
            $fileSize = '';
            if (preg_match("/bytes total/", $rows[$key])) {
                $ram_size = explode(" ", $rows[$key]);
                if (isset($ram_size[3]) && !empty($ram_size[3])) {
                    $ram_size = trim(str_replace("(", '', $ram_size[3]));
                    $ram_size_mb = '';
                    if ($ram_size > 0) {
                        if ($ram_size >= 1073741824) {
                            $fileSize = round($ram_size / 1024 / 1024 / 1024, 1) . 'GB';
                        } elseif ($ram_size >= 1048576) {
                            $fileSize = round($ram_size / 1024 / 1024, 1) . 'MB';
                        } elseif ($ram_size >= 1024) {
                            $fileSize = round($ram_size / 1024, 1) . 'KB';
                        } else {
                            $fileSize = $ram_size . ' bytes';
                        }
                    }
                }
            }
            if (!empty($fileSize)) {
                return $fileSize . "($ram_size bytes)";
            }
            $key++;
        }
    }

    public static function getshowInterfaceDescription($rows = array(), &$key = 0, $type = '') {
        $shIntDesc = $int_desc = array();
        while (!preg_match('/#(show|sh|access)/', trim($rows[$key]))) {
            if (preg_match('/^Interface/', trim($rows[$key]))) {
                $interface_found = 1;
            }
            if ($interface_found == 1 && trim($rows[$key]) == '') {
                break;
            }
            if (preg_match('/^[a-zA-Z].*\d{1,2}/', trim($rows[$key])) && $interface_found == 1) {
                $remove_spaces = preg_replace('/\s\s+/', '|', trim($rows[$key]));
                $explode = explode('|', $remove_spaces);
                if (is_array($explode) && count($explode) > 0) {
                    $int_desc['interface'] = $explode[0];
                    $int_desc['status'] = $explode[1];
                    $int_desc['protocol'] = $explode[2];
                    $int_desc['description'] = preg_replace('/#|\*/', '', $explode[3]);
                }
                $shIntDesc[] = $int_desc;
            }
            $key++;
        }
        return $shIntDesc;
    }

    public static function getLoopback300($rows = array(), &$key = 0, $type = '') {
        $Loopback300 = '';
        preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $temp);
        if (is_array($temp) && count($temp) > 0 && isset($temp[0])) {
            return trim($temp[0]);
        }
        return $Loopback201;
    }

    public static function getLoopback301($rows = array(), &$key = 0, $type = '') {
        $Loopback300 = '';
        preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $temp);
        if (is_array($temp) && count($temp) > 0 && isset($temp[0])) {
            return trim($temp[0]);
        }
        return $Loopback301;
    }

    public static function getIpRoute($rows = array(), &$key = 0, $type = '') {
        if (!empty($rows[$key])) {
            preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $temp);
            $explode = explode(" ", $rows[$key]);
            if (is_array($temp) && count($temp) > 0 && isset($temp[0])) {
                return trim($temp[0]);
            }
        }
        return '';
    }

    public static function getClockSource($rows = array(), &$key = 0, $type = '') {
        $ClockSource = '';
        preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $temp);
        if (is_array($temp) && count($temp) > 0 && isset($temp[0])) {
            return trim($temp[0]);
        }
        return $ClockSource;
    }

    public static function getNetworkClockingInterface($rows = array(), &$key = 0, $type = '') {
        $interface = (preg_match('/TenGigabitEthernet.*/', trim($rows[$key]), $gigabit_ethernet));
        if (is_array($gigabit_ethernet) && count($gigabit_ethernet) > 0 && isset($gigabit_ethernet[0])) {
            return trim($gigabit_ethernet[0]);
        }
        return '';
    }

    public static function getPtpClockingDomain($rows = array(), &$key = 0, $type = '') {
        $key++;
        $inc = $stateFound = $ptpFound = 0;
        while ($inc < 7 && !preg_match('/#(show|sh|access)/', trim($rows[$key]))) {
            if (preg_match('/PTP Boundary Clock/', trim($rows[$key]))) {
                $ptpFound = 1;
                $key++;
            }
            if (preg_match('/State/', trim($rows[$key])) && $ptpFound == 1) {
                $stateFound = 1;
                $key++;
            }
            if ((trim($rows[$key]) == ' ' || trim($rows[$key]) == '')) {
                $inc++;
            }
            if ($stateFound == 1) {
                if (!empty($rows[$key]) && trim($rows[$key]) != '') {
                    $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
                    $explode = explode(' ', $remove_spaces);
                    if (is_array($explode) && count($explode) > 0) {
                        return $explode[0];
                    }
                }
            }

            $key++;
        }
        return '';
    }

    public static function getVrfDetails($rows = array(), &$key = 0, $type = '') {
        $vrfDetails = $vrf = array();
        $explode_vrf_name = explode('vrf definition ', trim($rows[$key]));
        while (trim($rows[$key]) != '!' && trim($rows[$key]) != '') {
            if (preg_match("/^rd/", trim($rows[$key]))) {
                $explode_vrf_value = explode(' ', trim($rows[$key]));
                $vrf['vrf_name'] = $explode_vrf_name[1];
                $vrf['rd_value'] = $explode_vrf_value[1];
            }

            if (preg_match("/^route-target/", trim($rows[$key]))) {
                $temp = '';
                $temp = explode(' ', trim($rows[$key]));
                if (is_array($temp) && count($temp) > 0 && $temp[1] == 'export') {
                    $vrf['export'][] = $temp[2];
                } else if ($temp[1] == 'import') {
                    $vrf['import'][] = $temp[2];
                }
            }
            $key++;
        }
        if (!empty($vrf))
            return $vrf;
    }

    public static function getBundleEtherDetails($rows = array(), &$key = 0, $bundlename = '') { //AG2,AG3,SAR
        $interface_bundle_ether = array();
        while (trim($rows[$key]) != '!' && $rows[$key] != '') {
            $interface_bundle_ether['interface_bundle_port'] = $bundlename[1];
            if (preg_match('/^description/', trim($rows[$key]), $temp) && preg_match('/[A-Z0-9]{14}/', trim($rows[$key]), $temp)) {
                if (is_array($temp) && count($temp) > 0) {
                    $interface_bundle_ether['interface_bundle_ether_host_name'] = $temp[0];
                    $hostname_bd = $temp[0];
                }
            } elseif (preg_match('/^bfd address-family ipv4 destination/i', trim($rows[$key]))) {
                if (preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $temp))
                    if (is_array($temp) && count($temp) > 0) {
                        $interface_bundle_ether['interface_bundle_ether_remote_ip'] = $temp[0];
                    }
            } elseif (preg_match('/^ipv4 address/', trim($rows[$key]))) {
                if (preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", trim($rows[$key]), $temp))
                    if (is_array($temp) && count($temp) > 0) {
                        $interface_bundle_ether['interface_bundle_ether_local_ip'] = $temp[0];
                    }
            } elseif (preg_match('/^ipv6 address/', trim($rows[$key]))) {
                $interface_bundle_ether['interface_bundle_ether_ipv6'] = end((explode(' ', trim($rows[$key]))));
            } elseif (preg_match('/^mtu \d{4,}/', trim($rows[$key]))) {
                $explode_mtu = explode(' ', trim($rows[$key]));
                $interface_bundle_ether['interface_bundle_ether_mtu'] = $explode_mtu[1];
            }
            $key++;
        }

        if (count($interface_bundle_ether) > 1) {
            return $interface_bundle_ether;
        }
    }

    public function getAg2PtpClockingDomain($rows = array(), &$key = 0, $type = '') {
        $key++;
        $inc = 0;
        $ptp_status = '';

        while ($inc < 7 && !preg_match('/#(show|sh|access)/', trim($rows[$key]))) {

            if (preg_match('/NOT found/', trim($rows[$key]))) {
                return '';
            }
            if ((trim($rows[$key]) == ' ' || trim($rows[$key]) == '')) {
                $inc++;
            }
            if (preg_match('/Device status/', trim($rows[$key]))) {
                $explode = explode(":", trim($rows[$key]));
                if (count($explode) > 1) {
                    return trim($explode[1]);
                }
            }
            $key++;
        }
        return '';
    }

    public static function getShIpslaResponder($rows = array(), &$key = 0, $type = '') {
        $key++;
        $inc = 0;
        $uniIpslaIp = array();
        while ($inc < 2) {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', trim($rows[$key]), $ip)) {
                $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
                $uniIpslaIp[] = $ip[0];
            }
            $key++;
        }
        return array_unique($uniIpslaIp);
    }

    public static function getCssAg1NetworkClocks($rows = array(), &$key = 0, $type = '') {
        $key++;
        $inc = 0;
        $ptp_status = '';
        $nominated_interface = $interface_found = 0;
        $details = array();
        while ($inc < 7 && !preg_match('/#(show|sh|access)/', trim($rows[$key]))) {
            if (preg_match("/Nominated Interfaces/", trim($rows[$key]))) {
                $nominated_interface = 1;
            }
            if ((trim($rows[$key]) == ' ' || trim($rows[$key]) == '')) {
                $inc++;
            }
            if ($nominated_interface == 1 AND preg_match("/^Interface/", trim($rows[$key]))) {
                $interface_found = 1;
            }
            if ($interface_found == 1 AND preg_match("/[a-zA-Z].*\d{1,2}/", trim($rows[$key])) AND ! preg_match("/Internal/", trim($rows[$key]))) {
                $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
                $explode = explode(" ", $remove_spaces);
                if (!empty($explode) AND count($explode) > 1) {
                    $data = array();
                    $data['interface'] = $explode[0];
                    $data['SigType'] = $explode[1];
                    $data['mode_QL'] = $explode[2];
                    $data['prio'] = $explode[3];
                    $data['QL_IN'] = $explode[4];
                    $data['ESMC_Tx'] = $explode[5];
                    $data['ESMC_Rx'] = $explode[6];
                    $details[] = $data;
                }
            }
            $key++;
        }
        return $details;
    }

    public static function getAg2NetworkClocks($rows = array(), &$key = 0, $type = '') {
        $key++;
        $inc = 0;
        $Flags = $interface_found = 0;
        $details = array();
        while ($inc < 7 && !preg_match('/#(show|sh|access)/', trim($rows[$key]))) {
            if (preg_match("/Flags/", trim($rows[$key]))) {
                $Flags = 1;
            }
            if ((trim($rows[$key]) == ' ' || trim($rows[$key]) == '')) {
                $inc++;
            }

            if ($Flags == 1 AND preg_match("/Output driven by/", trim($rows[$key]))) {
                $interface_found = 1;
            }

            if ($interface_found == 1 AND preg_match("/[a-zA-Z].*\d{1,2}/", trim($rows[$key]))) {
                $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
                $explode = explode(" ", $remove_spaces);
                if (!empty($explode) AND count($explode) > 1) {
                    $data = array();
                    $data['f_symbols'] = $explode[0];
                    $data['interface'] = $explode[1];
                    $data['QLrcv'] = $explode[2];
                    $data['QLuse'] = $explode[3];
                    $data['Pri'] = $explode[4];
                    $data['QLsnd'] = $explode[5];
                    $data['output_driven_by'] = $explode[6];
                    if (isset($explode[7])) {
                        $data['output_driven_by'] .= " " . $explode[7];
                    }
                    $details[] = $data;
                }
            }
            $key++;
        }
        return $details;
    }

    public static function getStatus($rows = array(), &$key = 0, $type = '') {
        $inc = 0;
        while ($inc < 2 && trim($rows[$key]) != '!') {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match('/\% No such configuration item\(s\)/', trim($rows[$key]))) {
                return "No";
            }
            if (preg_match('/No Nodes to show for the specified protocol instance/', trim($rows[$key]))) {
                return "No";
            }
            if (preg_match('/^(interface|TenGigE)/', trim($rows[$key]))) {
                return "Yes";
            }
            $key++;
        }
    }

    public static function getVrfMGMT($rows = array(), &$key = 0) {
        $isBreak = 0;
        $vrfIPType = $importExport = '';
        $vrfRjilCoreMgmt = array();
        while ($isBreak == 0) {
            $key++;
            if (preg_match('/^address-family/', trim($rows[$key]))) {
                preg_match("/\s\S{4}/", trim($rows[$key]), $temp);
                $vrfIPType = trim($temp[0]);
            }
            if (preg_match('/^import/', trim($rows[$key])) || preg_match('/^export/', trim($rows[$key]))) {
                preg_match("/\b[a-zA-Z]+\b/", trim($rows[$key]), $temp);
                $importExport = trim($temp[0]);
            }
            if (!empty($vrfIPType) && !empty($importExport) && preg_match("/^\d+:\d+/", trim($rows[$key]), $vrfIP)) {
                $vrfRjilCoreMgmt[$vrfIPType][$importExport][] = $vrfIP[0];
            }

            if (preg_match('/^vrf/', trim($rows[$key])) || ( preg_match('/^!/', trim($rows[$key])) && preg_match('/^!/', trim($rows[$key + 1])) && preg_match('/^!/', trim($rows[$key + 2])) )) {
                $isBreak = 1;
            }
        }
        return $vrfRjilCoreMgmt;
    }

    public static function getShPlatform($rows = array(), &$key = 0, $type = '') {
        $inc = 0;
        $shPlat = array();
        while (trim($rows[$key]) != ' ' && $inc <= 1) {
            if (trim($rows[$key]) == '') {
                $inc++;
            }
            if (preg_match('/^[0-9]/', trim($rows[$key]))) {
                $remove_spaces = preg_replace('/\s\s+/', '|', trim($rows[$key]));
                $explode = explode('|', trim($remove_spaces));
                if (is_array($explode) && count($explode) > 0) {
                    $shPlat['node'] = $explode[0];
                    $shPlat['type'] = $explode[1];
                    $shPlat['state'] = $explode[2];
                    $shPlat['config_state'] = $explode[3];
                }
                $shPlatform[] = $shPlat;
            }
            $key++;
        }
        return $shPlatform;
    }

    public static function getSFPVendorData($rows = array(), &$key = 0, $type = '') {
        $inc = 0;
        $vendorArray = array('description', 'transceiver_type', 'product_identifier_pid', 'vendor_revision', 'serial_number_sn', 'vendor_name', 'vendor_oui_ieee_company_id', 'clei_code', 'cisco_part_number', 'device_state', 'date_code_yymmdd', 'connector_type', 'encoding', 'nominal_bitrate', 'minimum_bit_rate_as_of_nominal_bit_rate', 'maximum_bit_rate_as_of_nominal_bit_rate');
        while ($inc < 1 && $rows[$key] != '') {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match('/^[a-zA-Z].*(=)/', trim($rows[$key]))) {
                $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
                $explode = explode('=', $remove_spaces);
                if (is_array($explode) && count($explode) > 0) {
                    $explode[0] = preg_replace('/[^a-zA-Z0-9_ [\]\.\&-]/s', '', $explode[0]);
                    $explode[0] = str_replace('  ', ' ', $explode[0]);
                    $columnName = str_replace(' ', '_', strtolower(trim($explode[0])));
                    if (in_array($columnName, $vendorArray)) {
                        $vendorData[$columnName] = trim($explode[1]);
                    }
                }
            }
            $key++;
        }
        return $vendorData;
    }

    public static function getShowUdldNeighbor($rows = array(), &$key = 0, $type = '') {
        $key++;
        $inc = 0;
        $udld_neigh = array();
        while ($inc <= 1) {
            if ($rows[$key] == '') {
                $inc++;
            }
            if (preg_match('/^Port/', trim($rows[$key]))) {
                $port_found = 1;
                $key++;
            }
            if (preg_match('/[a-zA-Z0-9]/', trim($rows[$key])) && $port_found == 1) {
                $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
                $explode_data = explode(" ", $remove_spaces);
                if (is_array($explode_data) && count($explode_data) > 0) {
                    $udld_neigh['udld_port'] = $explode_data[0];
                    $udld_neigh['udld_device_name'] = $explode_data[1];
                    $udld_neigh['udld_device_id'] = $explode_data[2];
                    $udld_neigh['udld_port_id'] = $explode_data[3];
                    $udld_neigh['udld_neighbour_state'] = $explode_data[4];
                }
                $udldNeigbour[] = $udld_neigh;
            }
            $key++;
        }
        return $udldNeigbour;
    }

    public static function getShowVpc($rows = array(), &$key = 0, $type = '', $host_name = '') {
        $break = 0;
        $vpc_data = $vpc_data_all = array();
        while ($break == 0) {
            $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
            if (preg_match('/^(vPC domain id|Peer status|vPC keep-alive status|Configuration consistency status|Per-vlan consistency status|Type-2 consistency status|vPC role|Number of vPCs configured|Peer Gateway|Peer gateway excluded VLANs|Dual-active excluded VLANs|Graceful Consistency Check|Auto-recovery status) \:/', $remove_spaces, $match)) {
                $tag_name = str_replace(' ', '_', str_replace(' :', '', strtolower($match[0])));
                $temp = explode($match[0], $remove_spaces);
                if (!empty($temp[1]))
                    $vpc_data_all['legend'][str_replace('-', '_', $tag_name)] = trim($temp[1]);
            }
            if (preg_match('/^vPC Peer-link status/', $remove_spaces)) {
                $vpc_found = 1;
            }

            if (preg_match('/[0-9]/', $remove_spaces) && $vpc_found == 1) {
                $tempData = preg_replace('/\s+/', '|', trim($rows[$key]));
                $explode = explode('|', $tempData);
                if (count($explode) >= 4) {
                    if (!empty($vpc_data)) {
                        $vpc_data_all['vpc'][] = $vpc_data;
                    }
                    $vpc_data = array();
                    $vpc_data['vpc_id'] = $explode[0];
                    $vpc_data['port'] = $explode[1];
                    $vpc_data['status'] = $explode[2];
                    if ($explode[0] == 1) {
                        $vpc_data['consistency'] = 'N/A';
                        $vpc_data['reason'] = 'N/A';
                        $vpc_data['active_vlans'] = $explode[3];
                    } else {
                        $vpc_data['consistency'] = $explode[3];
                        $vpc_data['reason'] = $explode[4];
                        $vpc_data['active_vlans'] = $explode[5];
                    }
                } else {
                    $vpc_data['active_vlans'] .= $explode[0];
                }
            }
            $key++;
            if (preg_match("/^$host_name#/", $rows[$key]) || $key > count($rows)) {
                $break = 1;
                $key--;
            }
        }
        if (!empty($vpc_data)) {
            $vpc_data_all['vpc'][] = $vpc_data;
        }
        return $vpc_data_all;
    }

    public function getShIntTransceiver($rows = array(), &$key = 0, $type = '', $host_name = '') {
        $key++;
        $break = 0;
        $trns_data = array();
        $ethernet_data = '';
        while ($break == 0) {
            $remove_spaces = preg_replace('/\s\s+/', ' ', trim($rows[$key]));
            if (preg_match('/^Ethernet.*$/', $remove_spaces, $ethernet)) {
                $ethernet_data = $ethernet[0];
                $etherFound = 1;
            }
            if (preg_match('/^(transceiver is|type is|name is|part number is|revision is|serial number is|nominal bitrate is|cisco id is|cisco extended id number is|cisco part number is|cisco product id is|cisco vendor id is)/', $remove_spaces, $match) && $etherFound == 1) {
                $tag_name = str_replace('_is', '', str_replace(' ', '_', strtolower($match[0])));
                $temp = explode($match[0], $remove_spaces);
                $trns_data[$ethernet_data][$tag_name] = trim($temp[1]);
            }
            $key++;
            if (preg_match("/^$host_name#/", $rows[$key]) || $key > count($rows)) {
                $break = 1;
                $key--;
            }
        }
        return $trns_data;
    }

}
