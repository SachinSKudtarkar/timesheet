/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var totalNodes = $('#totalNodes').val();
var width = totalNodes > 50 ? 2100 : 1480;
var height = totalNodes > 50 ? 1800 : 1000;
var mainView; 

nx.define('MyTopology', nx.ui.Component, {
    
   view: {
       content: [
                {
                    type: 'search.device.ActionPanel'
                },{
                name: 'topo',
                type: 'nx.graphic.Topology',
                props: {
                   // adaptive: true,
                    identityKey: 'id',
                    autoLayout:true,
                    //dataProcessor: 'force',
                    width: width,
                    height: height,
                    newNode: '{newNode}',
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
                            console.log(link.getData()['linksbetween']);
                            if(link.getData()['linksbetween'] == 'CCR'){
                                    return 'red';
                            }else if(link.getData()['linksbetween'] == 'CSR'){
                                    return 'Green';
                            }else if(link.getData()['linksbetween'] == 'Direct'){
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
       }
   }]
   },  
                methods:{
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
                }/*,{
                    tag: 'button',
                    props:{
                            'class': 'btn btn-link',
                            'loopback0' : '{#node.model.loopback0}'
                        },
                    events: {
                                'click': '{#openTerminal}'
                        },    
                    content: 'Terminal'
                }*/],
                props:{'class': 'n-topology-tooltip-header'}
            }]
        },
        methods:{
              openTerminal:function(sender, events){
              var loopback0 = sender.getBinding('loopback0')._actualValue;
              var myWindow = window.open('http://localhost/cisco/DevicePair/ShellInaBox?loopback0='+loopback0,'_blank','status=0,top=50,left=300,location=0,height=500px,width=800px,scrollbars=yes')
            }
        }
    });    
    
var App = nx.define(nx.ui.Application, {
    methods: {
        getContainer: function() {
            return new nx.dom.Element(document.getElementById('clocking_delta_topology'));
        },
        start: function() {
             mainView = new MyTopology();
             mainView.attach(this);
        }
    }
});

var app = new App();
app.start();


/*---------------------------------------
 Search Action Panel
-----------------------------------------*/

(function (nx) {
    nx.define('search.device.ActionPanel', nx.ui.Component, {
        view: {
            content: [
                {
                    content: [
                        {
                            tag: 'label',
                            content: 'Hostname:'
                        },
                        {
                            name: '_hostname',
                            tag: 'input'
                        },
                        {
                            tag: 'button',
                            props: {
                                type: 'button',
                                'class': 'btn btn-default'
                            },
                            content: 'Search',
                            events: {
                                click: '{#_onSearch}'
                            }
                        }
                    ]
                }
            ]
        },
        properties: {
            _hostname: null,
        },
        methods: {
            _onSearch: function (inSender, inEvent) {
                var hostname = this.view('_hostname');
                var hostnameVal = hostname.get('value');
                if (!hostnameVal) {
                    hostname.dom().focus();
                }
                 var   topo = mainView.view('topo');
                 //fade out all layer
                nx.each(topo.layers(), function(layer) {
                    layer.fadeOut(true);
                }, this);
               
               //highlight related node
               topo.highlightRelatedNode(topo.getNode(hostnameVal));
              
              /* 
                //highlight single node or nodes
                var nodeLayer = topo.getLayer('nodes');
                var nodeLayerHighlightElements = nodeLayer.highlightedElements();
                nodeLayerHighlightElements.add(topo.getNode(hostnameVal));
               // nodeLayerHighlightElements.add(topo.getNode('SLGRAIRSCCR002'));
               */
                
               /* 
                //highlight links
                var linksLayer = topo.getLayer('links');
                var linksLayerHighlightElements = linksLayer.highlightedElements();
                linksLayerHighlightElements.addRange(nx.util.values(topo.getNode(hostnameVal).links()));*/
            }
        }
    })
})(nx);
