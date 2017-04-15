<?php

$modGctTemplateArray = array(
    'VRF_CONFIGURATION' => array(
        'RJIL-OAM-ENB' => 'vrf RJIL-OAM-ENB
 address-family ipv6 unicast
  import route-target
   64720:1{{$NN}}
  !
  export route-target
   64720:1{{$NN}}01
  !
 !
!',
        'RJIL-CORE-MGMT' => 'vrf RJIL-CORE-MGMT
 address-family ipv4 unicast
  import route-target
   64790:1{{$NN}}01
   64790:10001
  !
  export route-target
   64790:1{{$NN}}
   64790:100
  !
 !
 address-family ipv6 unicast
  import route-target
   64790:1{{$NN}}01
  !
  export route-target
   64790:1{{$NN}}
  !
 !
!
',
        'RJIL-IMS-BEARER-LTE' => 'vrf RJIL-IMS-BEARER-LTE
 address-family ipv4 unicast
  import route-target
   64770:40
  !
  export route-target
   64770:40
  !
 !
 address-family ipv6 unicast
  import route-target
   64770:40
  !
  export route-target
   64770:40
  !
 !
!
',
        'RJIL-WLS-CORE-SIGNALING' => 'vrf RJIL-WLS-CORE-SIGNALING
address-family ipv4 unicast
import route-target
64770:10
!
export route-target
64770:10
!
!
address-family ipv6 unicast
import route-target
64770:10
!
export route-target
64770:10
!
!
!
'
    ),
    'INTERFACE_GIGABITETHERNAET' =>
    'interface GigabitEthernet0/{{$PORTMAP}}/0/{{$PORT}}
 description To - {{$DESC}}
!
interface GigabitEthernet0/{{$PORTMAP}}/0/{{$PORT}} l2transport
 description To - {{$DESC}}
!
!
'
    ,
    'INTERFACE_BVI' => 'interface BVI{{$VLANID}}
vrf {{$LOGICALINTERFACE}}
 ipv6 address {{$IPV4V6ADDRESS}}
!
',
    'VRF_ROUTER_STATIC' => array(
        'VRF_ROUTER_STATIC_START' => 'router static',
        'VRF_ROUTER_STATIC_MID' => ' vrf {{$LOGICALINTERFACE}}
  address-family ipv4 unicast
  <IP-Address/Mask> <IP-Address> description #{{$LOGICALINTERFACE}}#
  !
 !
',
        'VRF_ROUTER_STATIC_END' => '!',
    ),
    'ROUTER_BGP' => array(
        'ROUTER_BGP_START' => 'router bgp 55836',
        'ROUTER_BGP_MID' => array(
            'RJIL-WLS-CORE-SIGNALING' => ' vrf {{$LOGICALINTERFACE}}
  rd {{$LOOPBACK0}}:7
  address-family ipv4 unicast
   redistribute connected
!
',
            'RJIL-CORE-MGMT' => ' vrf {{$LOGICALINTERFACE}}
  rd {{$LOOPBACK0}}:9
  address-family ipv4 unicast
   redistribute connected
!
',
            'RJIL-IMS-BEARER-LTE' => ' vrf {{$LOGICALINTERFACE}}
  rd {{$LOOPBACK0}}:11
  address-family ipv4 unicast
   redistribute connected
!
',
            'RJIL-OAM-ENB' => ' vrf {{$LOGICALINTERFACE}}
  rd {{$LOOPBACK0}}:3
  address-family ipv4 unicast
   redistribute connected
!
',
            'RJIL-BEARER-ENB' => ' vrf {{$LOGICALINTERFACE}}
  rd {{$LOOPBACK0}}:2
  address-family ipv4 unicast
   maximum-paths ibgp 2
   redistribute connected
!   
',
            'RJIL-SIGNALING-ENB' => ' vrf {{$LOGICALINTERFACE}}
  rd {{$LOOPBACK0}}:1
  address-family ipv4 unicast
   maximum-paths ibgp 2
   redistribute connected
!   
',
            'RJIL-IME' => ' vrf {{$LOGICALINTERFACE}}
  rd {{$LOOPBACK0}}:6
  address-family ipv4 unicast
   maximum-paths ibgp 2
   redistribute connected
!   
',
            'RJIL-WIFI-CISCO' => 'vrf {{$LOGICALINTERFACE}}
  rd {{$LOOPBACK0}}:4
  address-family ipv4 unicast
   maximum-paths ibgp 2
   redistribute connected
!   
'
        )
    ),
    'L2VPN' => array(
        'L2VPN_START' => 'l2vpn
 !',
        'BGP_GROUP_DOMAIN_START' => ' bridge group {{$VLANID}}
  bridge-domain {{$VLANID}}',
        'INTERFACE_GIGABITETHERNET_START' => '   interface GigabitEthernet0/{{$PORTMAP}}/0/{{$PORT}}',
        'INTERFACE_GIGABITETHERNET_END' => '   !',
        'INTERFACE_GIGABITETHERNET_VLAN_START' => '   interface GigabitEthernet0/{{$PORTMAP}}/0/{{$PORT}}.{{$VLANID}}',
        'INTERFACE_GIGABITETHERNET_VLAN_END' => '   !',
        'BGP_GROUP_DOMAIN_END' => '   routed interface BVI{{$VLANID}}
  !',
        'L2VPN_END' => ' !
!
'
    ),
    'ROUTER_VRRP' => 'router vrrp
 interface BVI{{$VLANID}}
  address-family ipv4
   vrrp {{$VLANID}}
    priority {{$PRIORITY}}
    preempt delay 10
    address {{$VIP}}
!
'
);

?>