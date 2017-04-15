(function() {
    var canvas, cx, makeHandle, plotBox, y_count, cxPlan, cxBuilt;
    var allAsPlanSpurs = new Array(), 
        allAsBuiltSpurs = new Array(),
        eastArr = new Array(),
        westArr = new Array();
    var ringDataAaBuilt, ringDataAsPlan, ag1PairsAsBuilt, ag1PairsAsPlan, plotBoxId, ag1PairsByAg2, canvasHeight, allIsisNbrs, ISAG1CONNECTED, NODELOOPBACKS; 
    y_count = 10;
    
    plotBox = null;

    canvas = null;

    cx = null;

    var showAllChilds = false;
    smoothConfig = {
        method: 'cubic',
        clip: 'mirror',
        lanczosFilterSize: 10,
        cubicTension: 0,
    };

    distance = function(a, b) {
        return Math.sqrt(Math.pow(a[0] - b[0], 2) + Math.pow(a[1] - b[1], 2));
    };
    
    plotBoxArea = function(plot_box_id, ringsDataAll, allSpurs, ag1Pairs, canvasHt, tracedDataArrayE, tracedDataArrayW, isAg1Connected, isisNbrs, nodeLoopbacks) {
        plotBox = plot_box_id;
        ISAG1CONNECTED = isAg1Connected;
        
        if(typeof(nodeLoopbacks) === 'string')
            NODELOOPBACKS = jQuery.parseJSON(nodeLoopbacks);
        
        plotBox.html('');   //Clear plot before initialize    
        var parsedAllSpurs;
        plotBoxId = plotBox.attr("id");
        canvasHeight = canvasHt;
        plotBoxWidth = plotBox.width();
        canvas = $("<canvas width="+plotBoxWidth+" height="+canvasHeight+" />").appendTo(plotBox);

        cx = canvas[0].getContext('2d');
        cx.font = "8pt Arial bold";
        
        //If not parsed string
        if(typeof(allSpurs) === 'string')
            allAsBuiltSpurs = jQuery.parseJSON(allSpurs);
        ringDataAaBuilt = ringsDataAll;
        
        if(typeof(isisNbrs) === 'string')
            allIsisNbrs = jQuery.parseJSON(isisNbrs);
        
        parsedAllSpurs = allAsBuiltSpurs;
        cxBuilt = cx;

        startPtX = 50;
        startPtY = 40;
        endPtX2 = plotBoxWidth-80;
        endPtY2 = 40;
        
        incrementer = 90;
        ag2Count = incrementer;
        
        ag2PointsE = Array();
        ag2PointsW = Array();
        
        tempstartPtX1 = startPtX + 10;
        tempstartPtY2 = tempstartPtY4 = endPtY2;
        tempstartPtX3 = endPtX2 + 10;
        
        endPtX = endPtX2;
        endPtY = endPtY2;
        
        tempstartPtX = startPtX;
        tempstartPtY = endPtY;
                
        flag = 0; 

        if(typeof(tracedDataArrayE) === 'string')
            eastArr = jQuery.parseJSON(tracedDataArrayE);
        
        if(typeof(tracedDataArrayW) === 'string')
            westArr = jQuery.parseJSON(tracedDataArrayW);

        eastArrLen = eastArr.length;
        westArrLen = westArr.length;
        
        $.each(eastArr, function(k,eastNode){log(eastNode);
            cx.fillText(eastNode, startPtX-40, startPtY - 10);
            makeHandle(startPtX, startPtY, 'node top_level_device', eastNode, 'open');
            if(k > 0)    
                ag2PointsE.push([tempstartPtX+10, tempstartPtY]);
            tempstartPtX = startPtX;
            tempstartPtY = endPtY;
            
            startPtY += incrementer;
            endPtY += incrementer;
                  
        });
        
        lastEX = startPtY - incrementer;
        startPtY = endPtY - incrementer;
        
        tempstartPtX = endPtX;
        tempstartPtY = endPtY2;
        
        $.each(westArr, function(k,westNode){
            cx.fillText(westNode, endPtX-40, endPtY2 - 10);
            makeHandle(endPtX, endPtY2, 'node top_level_device', westNode, 'open');
            
            if(k > 0)
                ag2PointsW.push([tempstartPtX+10, tempstartPtY]);
            tempstartPtX = endPtX;
            tempstartPtY = endPtY2;

            endPtY2 += incrementer;
        });
         
        if(endPtY > endPtY2){
            ag2Count= lastWY = endPtY2 = endPtY ;//- incrementer;
            startPtY += incrementer;
        }else
            ag2Count = startPtY = endPtY = lastWY = endPtY2;//- incrementer;
        
        ag2Count += incrementer;
        var lastArr = Array();
        lastArr.push(endPtX+10, endPtY);    //  For line between West AG1 and AG2
        
        if(typeof(ag1Pairs) === 'string')
            ag1PairsAsBuilt = jQuery.parseJSON(ag1Pairs);
        
        ag1Pairs = ag1PairsAsBuilt;

        cx.fillText(ag1Pairs.east_ag1.hostname, startPtX-40, startPtY-10);
        makeHandle(startPtX, startPtY, 'node', ag1Pairs.east_ag1.hostname,'','',ag1Pairs.east_ag1.router_id, ag1Pairs.east_ag1.loopback);
        showSpurCount(parsedAllSpurs, ag1Pairs.east_ag1.hostname);
        

        cx.fillText(ag1Pairs.west_ag1.hostname, endPtX-40, endPtY-10);
        makeHandle(endPtX, endPtY, 'node', ag1Pairs.west_ag1.hostname,'','',ag1Pairs.west_ag1.router_id, ag1Pairs.west_ag1.loopback);
        showSpurCount(parsedAllSpurs, ag1Pairs.west_ag1.hostname);
        
        if(ISAG1CONNECTED == 'Yes'){
            joinAG1 = Array();
            joinAG1.push([startPtX, startPtY]);
            joinAG1.push([endPtX+20, endPtY]);
        
            tmp1 = joinAG1[0][0];
            tmp2 = joinAG1[0][1];
            $.each(joinAG1, function(m, joinAG1Arr){//log(joinAG1Arr);
                drawStreightLine(tmp1, tmp2,joinAG1Arr, 'Direct', 2.5);
                tmp1 = joinAG1Arr[0];
                tmp2 = joinAG1Arr[1];
            })
        }
        
        controlPoint1x = (endPtX - startPtX)/2;
        controlPoint1y =  ag2Count;
        controlPoint2x = (endPtX - startPtX)/2;
        controlPoint2y =  ag2Count;
        x = endPtX;
        y = endPtY;

        var ringsData = jQuery.parseJSON(ringsDataAll);
        
        var spurs_direct_east_X = startPtX;
        var spurs_direct_east_Y = startPtY;
        
        var spurs_direct_west_X = endPtX;
        var spurs_direct_west_Y = endPtY;

        var ringCntrStart = startPtY + 10,
            ringCntrYEnd = endPtY + 10;

        $.each(ringsData,function(i, subRingsData){

            cpt1x   =  controlPoint1x;
            cpt1y   =  controlPoint1y;
            cpt2x   =  controlPoint2x;
            cpt2y   =  controlPoint2y;

            // Counter for rings
            if(!isNaN(i)){
                cx.fillText(i,startPtX+50, ringCntrStart);
                ringCntrStart += 15;
            
                cx.fillText(i,endPtX-50, ringCntrYEnd);
                ringCntrYEnd += 15;
            }

            te = 0.1;
            tw = 0.06;
            var directSpurPoints = new Array();
            var incompleteNodes = new Array();
            var incompleteNodeDevicesE = new Array();
            var incompleteNodeDevicesW = new Array();
                switch(i){
                    case 'spurs_direct_west':
                        /*directSpurPoints.push([spurs_direct_west_X, spurs_direct_west_Y]);
                        $.each(subRingsData, function(index, directSpurs) {
                            makeHandle(spurs_direct_west_X, spurs_direct_west_Y + 60, 'spur', directSpurs.hostname);
                            directSpurPoints.push([spurs_direct_west_X, spurs_direct_west_Y + 60]);
                            //cx.fillText(directSpurs.hostname, spurs_direct_west_X, spurs_direct_west_Y + 55);
                            spurs_direct_west_Y += 50;
                            spurs_direct_west_X += 15;
                            drawLine(directSpurPoints,  directSpurs.media_type);
                        });*/
                        return true;
                        break;
                    case 'spurs_direct_east':
                        /*directSpurPoints.push([spurs_direct_east_X, spurs_direct_east_Y]);
                        $.each(subRingsData, function(index, directSpurs) {
                            makeHandle(spurs_direct_east_X, spurs_direct_east_Y + 60, 'spur', directSpurs.hostname);
                            directSpurPoints.push([spurs_direct_east_X, spurs_direct_east_Y + 60]);
                            //cx.fillText(directSpurs.hostname, spurs_direct_east_X, spurs_direct_east_Y + 55);
                            spurs_direct_east_Y += 50;
                            spurs_direct_east_X += 15;
                            drawLine(directSpurPoints, directSpurs.media_type);
                        });*/
                        return true;
                        break;
            }

            var tmpStart1X = startPtX, 
                tmpStart1Y = startPtY,
                tmpStartWestX = endPtX,
                tmpStartWestY = endPtY;
            var cssDirection = '';

            //  Draw CSS devices on top of the rings.
            $.each(subRingsData, function(i, field) {

                if(typeof(field.css_sequence_no) !== 'undefined'){
                    cssDirection = field.css_sequence_no.split("-")[0];
                } 

                if(cssDirection == 'West'){

                    B0_t = Math.pow((1 - tw), 3);
                    B1_t = 3 * tw * Math.pow((1 - tw), 2);
                    B2_t = 3 * Math.pow(tw, 2) * (1 - tw);
                    B3_t = Math.pow(tw, 3);
                    
                    x_axis = (B0_t * endPtX) + (B1_t * cpt2x) + (B2_t * cpt1x) + (B3_t * startPtX);
                    y_axis = (B0_t * endPtY) + (B1_t * cpt2y) + (B2_t * cpt1y) + (B3_t * startPtY);
                    tw += 0.07;
                }else{
                    B0_t = Math.pow((1 - te), 3);
                    B1_t = 3 * te * Math.pow((1 - te), 2);
                    B2_t = 3 * Math.pow(te, 2) * (1 - te);
                    B3_t = Math.pow(te, 3);
                
                    x_axis = (B0_t * startPtX) + (B1_t * cpt2x) + (B2_t * cpt1x) + (B3_t * endPtX);
                    y_axis = (B0_t * startPtY) + (B1_t * cpt2y) + (B2_t * cpt1y) + (B3_t * endPtY);
                    
                    te += 0.07;
                }

                if(typeof field.hostname !== 'undefined'){
                    makeHandle(x_axis, y_axis,'top_level_device',field.hostname, field.ring_status, 'close', field.router_id , field.loopback);
                    if(showAllChilds === true)
                    $('.'+plotBoxId+'_'+field.hostname).trigger('click').trigger('click');
                    showSpurCount(parsedAllSpurs, field.hostname);
                    
                    if(cssDirection == 'West')
                        incompleteNodeDevicesW.push([field.hostname,field.ring_status,x_axis,y_axis]);   // To draw line from incomplete devices
                    else
                        incompleteNodeDevicesE.push([field.hostname,field.ring_status,x_axis,y_axis]);   // To draw line from incomplete devices
                }

                //Create array of incomplete nodes
                if( field.ring_status == 'Incomplete Last' ){
                    incompleteNodes.push([x_axis,y_axis]);  
                }
                
            });

            cx.lineWidth   = 2;
            cx.strokeStyle = '#3366FF';
            cx.lineJoin    = 'round';
            cx.lineCap     = 'round';
            cx.lineCap     = 'round';

            var incompleteNodesLength = incompleteNodes.length;
            //  If complete ring
            if(incompleteNodesLength < 1){
                cx.beginPath(); 
                cx.setLineDash([]);
                cx.moveTo(startPtX, startPtY);
                cx.bezierCurveTo(controlPoint1x,controlPoint1y,controlPoint2x,controlPoint2y,x+20,y);
                cx.stroke();
            }
            //  To draw line in between two points where only one / two devices are incomplete.
            else{
               
                for(k = 0; k<= incompleteNodeDevicesE.length-1; k++){
                    drawStreightLine(tmpStart1X, tmpStart1Y, [incompleteNodeDevicesE[k][2], incompleteNodeDevicesE[k][3]],'',2);

                    tmpStart1X = incompleteNodeDevicesE[k][2];
                    tmpStart1Y = incompleteNodeDevicesE[k][3];
                }
                for(k = 0; k<= incompleteNodeDevicesW.length-1; k++){
                    drawStreightLine(tmpStartWestX, tmpStartWestY, [incompleteNodeDevicesW[k][2], incompleteNodeDevicesW[k][3]],'',2);

                    tmpStartWestX = incompleteNodeDevicesW[k][2];
                    tmpStartWestY = incompleteNodeDevicesW[k][3];
                }
            }

            //  To break the line in between two incomplete devices
            if( incompleteNodesLength > 0){
                //if(typeof(incompleteNodes[0]) !== 'undefined' && typeof(incompleteNodes[1]) !== 'undefined')
                  //  drawStreightLine(  incompleteNodes[0][0], incompleteNodes[0][1], [incompleteNodes[1][0],incompleteNodes[1][1]], 'Incomplete Last');
            }
            controlPoint1y += 120;
            controlPoint2y += 120;

        });
        
        if(eastArrLen > 0)
            ag2PointsE.push([startPtX+10, startPtY]);   //  For line between East AG1 from pair to AG2's AG1
        
        $.each(ag2PointsE, function(j, pts) {
            drawStreightLine(tempstartPtX1, tempstartPtY2, pts, '', 2);//alert();
            tempstartPtX1 = pts[0];
            tempstartPtY2 = pts[1];
        });
        
       if(westArrLen > 0)
            ag2PointsW.push([endPtX+10, endPtY]);   //  For line between East AG1 from pair to AG2's AG1
        
        $.each(ag2PointsW, function(j, pts) {
            drawStreightLine(tempstartPtX3, tempstartPtY4, pts, '', 2);//alert();
            tempstartPtX3 = pts[0];
            tempstartPtY4 = pts[1];
        });
    };
    
    Object.size = function(obj) {
        var size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
    };
    
    makeHandle = function(x, y, type, hostname, ring_status, spur_status, router_id,  loopback) {//log(hostname+'--'+router_id);
        var deviceType = 'ag-'+hostname.substr(8,3);
        var handle;
        handle = $('<div/>').addClass(deviceType+ ' handle '+type+ ' ' + 'device-' +ring_status + ' ' +plotBoxId+ '_'+hostname).appendTo($('#'+plotBoxId)).css({
            left: x = x - 6,
            top: y = y - 6
        });
        
        handle.attr('hostname', hostname);
        
        if(typeof(allIsisNbrs[router_id]) !== 'undefined'){
            var nbrData = '';
            nbrIds = allIsisNbrs[router_id];
            $.each(nbrIds, function(n, nbrs) {
                var intf_port = '';
                if(nbrs.local_interface_port !== null){
                    intf_port = nbrs.local_interface_port + ' : ';
                }
                nbrData +=   intf_port + nbrs.interface + ' : ' + nbrs.local_interface_ip + '<br/>';
            });

            handle.attr('title', hostname + ' : '+loopback );
            handle.attr('data-content', hostname);
             $('.'+plotBoxId+ '_'+hostname).each(function () {
                var $elem = $(this);
                $elem.popover({
                    placement: getPosition(handle),
                    trigger: 'click',
                    html: true,
                    container: $elem,
                    animation: true,
                    content: '<div class="popover-content-data">'+nbrData+'</div>'
                });
            });
        }else if(typeof(NODELOOPBACKS[hostname]) !== 'undefined'){
            var content = '';
            var loopback = '';
            if(typeof(NODELOOPBACKS[hostname][0]) !== 'undefined'){
                var nbrData = '';
                $.each(NODELOOPBACKS[hostname], function(n, nbrs) {
                    var intf_port = '';
                    if(nbrs.local_interface_port !== null){
                        intf_port = nbrs.local_interface_port + ' : ';
                    }
                    nbrData +=   intf_port + nbrs.interface + ' : ' + nbrs.local_interface_ip + '<br/>';
                    loopback = nbrs.Loopback0;
                });
                content =  '<div class="popover-content-data">'+nbrData+'</div>';
                
            }else{
                loopback = NODELOOPBACKS[hostname].Loopback0;
            }
            
            handle.attr('title', hostname + ' : '+ loopback);
             $('.'+plotBoxId+ '_'+hostname).each(function () {
                var $elem = $(this);
                $elem.popover({
                    placement: getPosition(handle),
                    trigger: 'click',
                    html: true,
                    container: $elem,
                    animation: true,
                    content: content
                });
            });
        }
        else{
            handle.attr('title', hostname);
            handle.attr('data-toggle', 'tooltip');
            $("[data-toggle='tooltip']").tooltip(); 
        }
        
        handle.css({
            position: 'absolute'
        });
        if(typeof(spur_status) !== 'undefined' && spur_status !== 'close' && spur_status !== '')
            handle.attr('spur-status', spur_status);
        else
            handle.attr('spur-status', 'close');
        handle.attr({'x': Number((x).toFixed(1)), 'y': Number((y).toFixed(1))});
        handle.click(showDevice); 
        
        return handle;
    };
    
    function getPosition(obj){
        var myLeft = $(obj).offset().left;
        if (myLeft < 500) return 'right';
        return 'left';
    }
    addCurveSegment = function(context, i, points) {
        var averageLineLength, du, end, pieceCount, pieceLength, s, start, t, u, _ref, _ref2, _ref3;
        s = Smooth(points, smoothConfig);
        averageLineLength = 1;
        pieceCount = 2;
        for (t = 0, _ref = 1 / pieceCount; t < 1; t += _ref) {
            _ref2 = [s(i + t), s(i + t + 1 / pieceCount)], start = _ref2[0], end = _ref2[1];
            pieceLength = distance(start, end);
            du = averageLineLength / pieceLength;
            for (u = 0, _ref3 = 1 / pieceCount; 0 <= _ref3 ? u < _ref3 : u > _ref3; u += du) {
                context.lineTo.apply(context, s(i + t + u));
            }
        }
        return context.lineTo.apply(context, s(i + 1));
    };

    drawLine = function(points, media_type) {
        var i, lastIndex;
        if (points.length >= 2) {
            lastIndex = points.length;
            for (i = 1; 0 <= lastIndex ? i < lastIndex : i > lastIndex; 0 <= lastIndex ? i++ : i--) {
                cx.beginPath();
                if(typeof(media_type) !== 'undefined'){
                    if(media_type == "Microwave"){
                        cx.setLineDash([2, 5]);
                        cx.strokeStyle = 'red';
                    }else if(media_type == "Fiber Spur"){
                        cx.setLineDash([]);
                        cx.strokeStyle = 'blue';
                    }
                }
                cx.moveTo(points[0][0], points[0][1]);
                cx.lineTo(points[i][0], points[i][1]);
                cx.lineWidth = 2;
                cx.lineJoin = 'round';
                cx.lineCap = 'round';
                cx.stroke();
                cx.closePath();
            }
        }
    };
    
    
    drawFiberLine = function(points) {
        var i, lastIndex;
        if (points.length >= 2) {
            lastIndex = points.length;
            for (i = 1; 0 <= lastIndex ? i < lastIndex : i > lastIndex; 0 <= lastIndex ? i++ : i--) {
                cx.beginPath();
                cx.setLineDash([]);
                cx.moveTo(points[0][0], points[0][1]);
                cx.lineTo(points[i][0], points[i][1]);
                cx.lineWidth = 2;
                cx.strokeStyle = 'blue';
                cx.lineJoin = 'round';
                cx.lineCap = 'round';
                cx.stroke();
                cx.closePath();
            }
        }
    };


  drawStreightLine = function(startx,starty,points, ring_status, lineSize) {
    var i, lastIndex;
    if (points.length >= 2) {
      lastIndex = points.length;
      for (i = 1; 0 <= lastIndex ? i < lastIndex : i > lastIndex; 0 <= lastIndex ? i++ : i--) {
              cx.beginPath();
              cx.setLineDash([]);
              cx.moveTo(startx,starty);
              cx.lineTo(points[0],points[1]);
              //log(points);
              if(typeof(lineSize) !== 'undefined')
                cx.lineWidth = lineSize;
              else
                cx.lineWidth = 4;
            
              if(ring_status == 'Incomplete Last'){
                cx.strokeStyle = '#EEF5F7';
              }else if(ring_status == 'Direct'){
                cx.strokeStyle = '#000';
              }else
                cx.strokeStyle = '#3366FF';
            
              cx.lineJoin = 'round';
              cx.lineCap = 'round';
              cx.stroke();
              cx.closePath();
      }
    }
  };

    getSpurCount = function(parsedAllSpurs,hostname){
        var len = ' ';
        if(typeof(parsedAllSpurs[hostname]) !== 'undefined')
            len = Object.keys(parsedAllSpurs[hostname]).length;
        return len;
    };
    
    showSpurCount = function(parsedAllSpurs, hostname){
        var len = getSpurCount(parsedAllSpurs,hostname);
        $('.'+plotBoxId+'_'+hostname).html('&nbsp;' + len);
    };
    
    
    showDevice = function(){

        var $this = $(this);
        if ($this.hasClass('clicked')){
            $this.removeClass('clicked'); 

        }else{
             $this.addClass('clicked');
             setTimeout(function() { 
                 if ($this.hasClass('clicked')){
                    $this.removeClass('clicked'); 

                 }
             }, 300);  
             return;
        }
 
        if(!$(this).hasClass('node-ag2')){
            var parsedAllSpurs, ag1PairsByAg2Data;
            plotBoxId = $(this).parent().attr('id');
            if(plotBoxId == 'plot-box-1'){
                cx = cxPlan;
                parsedAllSpurs = allAsPlanSpurs;
                ag1PairsByAg2Data = ag1PairsAsBuilt;
            }else{
                cx = cxBuilt;
                parsedAllSpurs = allAsBuiltSpurs;
                ag1PairsByAg2Data = ag1PairsAsBuilt;
            }

            var x_axis = Number($(this).attr('x')),
                y_axis = Number($(this).attr('y')),
                hostname = $(this).attr('hostname'),
                spurStatus = $(this).attr('spur-status');
                var thisObj = $(this);

            if(thisObj.hasClass('top_level_device') && showAllChilds === false){
                if(spurStatus == 'close'){
                plotBoxArea($('#plot-box-2'),ringDataAaBuilt,allAsBuiltSpurs,ag1PairsByAg2Data, canvasHeight, eastArr, westArr, ISAG1CONNECTED);
                y_count = 10;
            }
            }
            log(hostname);            
            if(spurStatus == 'close'){
                thisObj.attr('spur-status','open');
                if(getSpurCount(parsedAllSpurs, hostname) == 1)
                    var extX = -50;     // To draw streight line for child
                else
                    var extX = -70;

                var extY = 70 +  + y_count;

                if(typeof(parsedAllSpurs[hostname]) !== 'undefined'){
                    y_count += 10;    log(parsedAllSpurs[hostname]);
                    $.each(parsedAllSpurs[hostname], function(si, spursOfSpur) {  //  Loop for all spurs
                        extX += 50;
                        extY += 10;
                        makeHandle(x_axis + extX, y_axis + extY, 'spur', spursOfSpur.hostname, '','', spursOfSpur.router_id,  spursOfSpur.loopback);
                        showSpurCount(parsedAllSpurs, spursOfSpur.hostname);
                        drawLine([[x_axis+7, y_axis+7],[x_axis + extX, y_axis + extY]], spursOfSpur.media_type);
                        if(showAllChilds === true)
                            $('.'+plotBoxId+'_'+spursOfSpur.hostname).trigger('click').trigger('click');    // Need to click twice
                   });
                }
             }
         }
    };

    log = function(data){
        console.log(data);
    }
    
  
    $(document).on('change','#showAllChilds',function () { 
        var status = $(this).val();
        
        if($(this).is(':checked')){
            showAllChilds = true;
        }else
            showAllChilds = false;
       y_count = 10;
        plotBoxArea($('#plot-box-2'),ringDataAaBuilt,allAsBuiltSpurs,ag1PairsAsBuilt, canvasHeight, eastArr, westArr, ISAG1CONNECTED);
    });
    
    $('#showAllChilds').click(function(){
        
    })
}).call(this);
