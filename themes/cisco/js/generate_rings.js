(function() {
    var canvas, cx, makeHandle, plotBox, y_count, cxPlan, cxBuilt;
    var allAsPlanSpurs = new Array(), 
        allAsBuiltSpurs = new Array();
    var ringDataAaBuilt, ringDataAaPlan, ag1PairsAsBuilt, ag1PairsAsPlan, plotBoxId, ISAG1CONNECTED; 
    y_count = 10;
    
    plotBox = null;

    canvas = null;

    cx = null;

    smoothConfig = {
        method: 'cubic',
        clip: 'mirror',
        lanczosFilterSize: 10,
        cubicTension: 0,
    };

    distance = function(a, b) {
        return Math.sqrt(Math.pow(a[0] - b[0], 2) + Math.pow(a[1] - b[1], 2));
    };
    
    plotBoxArea = function(plot_box_id, ringsDataAll, allSpurs, ag1Pairs, isAg1PairConnectedAsBuilt) {
        plotBox = plot_box_id;
        plotBox.html('');   //Clear plot before initialize    
        var parsedAllSpurs;
        plotBoxId = plotBox.attr("id");
        ISAG1CONNECTED = isAg1PairConnectedAsBuilt;
        
        canvas = $("<canvas width=1000 height=800 />").appendTo(plotBox);

        cx = canvas[0].getContext('2d');
        cx.font = "8pt Arial bold";
        
        if(plotBoxId == 'plot-box-1'){
            //If not parsed string
            if(typeof(allSpurs) == 'string')
                allAsPlanSpurs = jQuery.parseJSON(allSpurs);
            ringDataAaPlan= ringsDataAll;
            ag1PairsAsPlan= ag1Pairs;
            
            parsedAllSpurs = allAsPlanSpurs;
            cxPlan = cx;
        }else{
            //If not parsed string
            if(typeof(allSpurs) == 'string')
                allAsBuiltSpurs = jQuery.parseJSON(allSpurs);
            ringDataAaBuilt = ringsDataAll;
            ag1PairsAsBuilt = ag1Pairs;
            
            parsedAllSpurs = allAsBuiltSpurs;
            cxBuilt = cx;
        }

        startPtX = 50;
        startPtY = 30;
        
        endPtX = 900;
        endPtY = 30;
        
        ag1Pairs = jQuery.parseJSON(ag1Pairs);
        cx.fillText(ag1Pairs.east_ag1, startPtX, startPtY - 10);
        makeHandle(startPtX, startPtY, 'node top_level_device', ag1Pairs.east_ag1, 'open');
        showSpurCount(parsedAllSpurs, ag1Pairs.east_ag1);
        
        cx.fillText(ag1Pairs.west_ag1, endPtX, endPtY - 10);
        makeHandle(endPtX, endPtY, 'node top_level_device', ag1Pairs.west_ag1, 'open');
        showSpurCount(parsedAllSpurs, ag1Pairs.west_ag1);
        
        if(typeof(ISAG1CONNECTED) !== 'undefined' && ISAG1CONNECTED === 'true'){
                joinAG1 = Array();
                joinAG1.push([startPtX, startPtY]);
                joinAG1.push([endPtX+20, endPtY]);

                tmp1 = joinAG1[0][0];
                tmp2 = joinAG1[0][1];
                $.each(joinAG1, function(m, joinAG1Arr){
                    drawStreightLine(tmp1, tmp2,joinAG1Arr, 'Direct', 2.5);
                    tmp1 = joinAG1Arr[0];
                    tmp2 = joinAG1Arr[1];
                })
            }
            
        controlPoint1x = 400;
        controlPoint1y = 100;
        controlPoint2x = 400;
        controlPoint2y = 100;
        x = endPtX;
        y = endPtY;

        var ringsData = jQuery.parseJSON(ringsDataAll);
        
        var spurs_direct_east_X = startPtX;
        var spurs_direct_east_Y = startPtY;
        
        var spurs_direct_west_X = endPtX;
        var spurs_direct_west_Y = endPtY;

        var ringCntrX = startPtX + 50, 
            ringCntrY = startPtY + 5;

        $.each(ringsData,function(i, subRingsData){

            cpt1x   =  controlPoint1x;
            cpt1y   =  controlPoint1y;
            cpt2x   =  controlPoint2x;
            cpt2y   =  controlPoint2y;

            // Counter for rings
            if(!isNaN(i)){
                cx.fillText(i,ringCntrX, ringCntrY);
                ringCntrY = ringCntrY + 20;
            }

            te = 0.1;
            tw = 0.1;
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
                    makeHandle(x_axis, y_axis,'top_level_device',field.hostname, field.ring_status, 'close' );
                    //$('.'+plotBoxId+'_'+field.hostname).trigger('click');
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
            //  To draw line in between two points where only one device is incomplete
            /*if(incompleteNodesLength == 1)*/
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
                if(typeof(incompleteNodes[0]) !== 'undefined' && typeof(incompleteNodes[1]) !== 'undefined')
                    drawStreightLine(  incompleteNodes[0][0], incompleteNodes[0][1], [incompleteNodes[1][0],incompleteNodes[1][1]], 'Incomplete Last');
            }
            controlPoint1y += 120;
            controlPoint2y += 120;

        });
    };
    
    Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};
    
    makeHandle = function(x, y, type, hostname, ring_status, spur_status) {
       
        var handle;
        handle = $('<div/>').addClass('handle '+type+ ' ' + 'device-' +ring_status + ' ' +plotBoxId+ '_'+hostname).appendTo($('#'+plotBoxId)).css({
            left: x = x - 6,
            top: y = y - 6
        });
        handle.attr('title', hostname);
        handle.attr('hostname', hostname);
        
        handle.attr('data-toggle', 'tooltip');
        $("[data-toggle='tooltip']").tooltip(); 
        
        handle.css({
            position: 'absolute'
        });
        if(typeof(spur_status) !== 'undefined' && spur_status != 'close')
            handle.attr('spur-status', spur_status);
        else
            handle.attr('spur-status', 'close');
        handle.attr({'x': Number((x).toFixed(1)), 'y': Number((y).toFixed(1))});
        handle.click(showDevice);
        
        return handle;
    };

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
        var parsedAllSpurs;
        plotBoxId = $(this).parent().attr('id');
        if(plotBoxId == 'plot-box-1'){
            cx = cxPlan;
            parsedAllSpurs = allAsPlanSpurs;
        }else{
            cx = cxBuilt;
            parsedAllSpurs = allAsBuiltSpurs;
        }

        var x_axis = Number($(this).attr('x')),
            y_axis = Number($(this).attr('y')),
            hostname = $(this).attr('hostname'),
            spurStatus = $(this).attr('spur-status');
            var thisObj = $(this);

        if(thisObj.hasClass('top_level_device')){
            //if(spurStatus == 'close'){
                if(plotBoxId == 'plot-box-1')
                    plotBoxArea($('#plot-box-1'),ringDataAaPlan,allAsPlanSpurs,ag1PairsAsPlan);
                else    
                    plotBoxArea($('#plot-box-2'),ringDataAaBuilt,allAsBuiltSpurs,ag1PairsAsBuilt, ISAG1CONNECTED);
                y_count = 10;
            //}
        }
log(hostname);               
        if(spurStatus == 'close'){
            thisObj.attr('spur-status','open');
            if(getSpurCount(parsedAllSpurs, hostname) == 1)
                var extX = -33;     // To draw streight line for child
            else
                var extX = -70;

            var extY = 50 +  + y_count;

            if(typeof(parsedAllSpurs[hostname]) !== 'undefined'){
                y_count += 10;    
                $.each(parsedAllSpurs[hostname], function(si, spursOfSpur) {  //  Loop for all spurs
                    extX += 40;
                    extY += 10;
                    makeHandle(x_axis + extX, y_axis + extY, 'spur', spursOfSpur.hostname);
                    //cx.fillText(spursOfSpur.hostname, x_axis + extX, y_axis + extY - 5);
                    showSpurCount(parsedAllSpurs, spursOfSpur.hostname);
                    drawLine([[x_axis+7, y_axis+7],[x_axis + extX, y_axis + extY]], spursOfSpur.media_type);
//                    $('.'+plotBoxId+'_'+spursOfSpur.hostname).trigger('click');

//                  cx.canvas.height += 100;
               });
            }
         }
    };

    log = function(data){
        console.log(data);
    }
}).call(this);
