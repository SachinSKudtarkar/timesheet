/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

nx.define('MyTopology', nx.ui.Component, {
    
   view: {
       content: {
                name: 'topo',
                type: 'nx.graphic.Topology',
                props: {
                    adaptive: true,
                    identityKey: 'id',
                    autoLayout:true,
                    dataProcessor: 'force',
                    width: 1000,
                    height: 800,
                    nodeConfig: {
                        label: function(vertex) {
                            return vertex.get('hostname');
                        },
                        iconType: 'model.iconType'
                    },
                    linkConfig: {
                        // multiple link type is curve, could change to 'parallel' to use parallel link
                        linkType: 'curve',
                        color: function(link,model) {
                            console.log(link.getData()['media_type']);
                            if(link.getData()['media_type'] == 'Microwave'){
                                    return 'red';
                            }else if(link.getData()['media_type'] == 'Fiber Spur'){
                                    return 'Blue';
                            }else if(link.getData()['media_type'] == 'Direct'){
                                    return 'Black';
                            }
                            //return colorTable[Math.floor(Math.random() * 5)];
                        },
                        width:2.5
                        
                    },
                    tooltipManagerConfig: {
                        nodeTooltipContentClass: 'MyNodeTooltip'
                    },
                    showIcon: true,
                    data: topologyData,
       },
       events: {
                  'topologyGenerated': '{#_main}'
               }
   }
   },
   methods: {
            _main: function(sender, events) {
                sender.attachLayer("status", "NodeStatus");
            }
        }
});


//DEFINE A LAYER
    nx.define("NodeStatus", nx.graphic.Topology.Layer, {
        methods: {
            draw: function() {
                var topo = this.topology();
                topo.eachNode(function(node) {
                    if(node.model().get('ring_status') == 'Incomplete Last'){
                    var dot = new nx.graphic.Circle({
                        r: 6,
                        cx: -20,
                        cy: -20
                    });
                    var color = "#f00";
                    if (node.model().get("id") > 2) {
                        color = "#0f0";
                    }
                    dot.set("fill", color);
                    dot.attach(node);
                    node.dot = dot;
                }
                }, this);
            }
        }
    });
    
/*  Customised tooltip  */
nx.define('MyNodeTooltip', nx.ui.Component, {
        properties: {
            node: {},
            topology: {}
        },
        view: {
            content: [{
                tag: 'div',
                content:[{
                    tag: 'span',
                    props:{
                            'class': 'n-topology-tooltip-header-text'
                        },
                    content: '{#node.id}'
                },{
                    tag: 'button',
                    props:{
                            'class': 'btn btn-link',
                            'loopback0' : '{#node.model.loopback0}'
                        },
                    events: {
                                'click': '{#openTerminal}'
                        },    
                    content: 'Terminal'
                }],
                props:{'class': 'n-topology-tooltip-header'}
            }, {
                    tag: 'span',
                    content: '{#node.model.loopback0}'
            }, 
            {
                tag: "table",
                props: {
                    class: "col-md-12",
                    border: "1"
                },
                content: [{
                    tag: "thead",
                    content: {
                        tag: "tr",
                        content: [{
                            tag: "td",
                            content: "Port"
                        }, {
                            tag: "td",
                            content: "Interface"
                        }, {
                            tag: "td",
                            content: "Interface Ip"
                        }]
                    }
                }, {
                    tag: "tbody",
                    props: {
                        items: "{#node.model.local_ports}",
                        template: {
                            tag: "tr",
                            content: [{
                                tag: "td",
                                content: "{local_interface_port}"
                            }, {
                                tag: "td",
                                content: "{interface}"
                            }, {
                                tag: "td",
                                content: "{local_interface_ip}"
                            }]
                        }
                    }
                }]
            }]
        },
        methods:{
            openTerminal:function(sender, events){
               var loopback0 = sender.getBinding('loopback0')._actualValue;
               var myWindow = window.open(BASE_URL+'/DevicePair/ShellInaBox/loopback0/'+loopback0,'_blank','status=0,top=50,left=300,location=0,height=500px,width=800px,scrollbars=yes')
// $('#sshTerminal .modal-body').html('<iframe id="sshTerminalFrame" style="overflow: hidden!important;" width="100%" height="80%" src="'+BASE_URL+'/DevicePair/ShellInaBox/loopback0/'+loopback0+'" ></iframe>');
               //$('#sshTerminal').modal('show');  
                
            }
        }
    });    
    

var App = nx.define(nx.ui.Application, {
    methods: {
        getContainer: function() {
            return new nx.dom.Element(document.getElementById('topodemo'));
        },
        start: function() {
            var comp = new MyTopology();
            comp.attach(this);
        }
    }
});

var app = new App();
app.start();