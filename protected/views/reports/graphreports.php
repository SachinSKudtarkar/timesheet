<?php
/* @var $this DayCommentController */
/* @var $model DayComment */

$this->breadcrumbs = array(
    'Reports ' => array('allreports'),
);

Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
$cs = Yii::app()->getClientScript();

// $cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
// $cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");
// $cs->registerScriptFile("https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css");

?>
<link rel="stylesheet"  type="text/css" href="<?php echo Yii::app()->baseUrl."/css/jquery.dataTables.min.css"; ?>">
<link rel="stylesheet"  type="text/css" href="<?php echo Yii::app()->baseUrl."/css/buttons.dataTables.min.css"; ?>">

<h1>All Reports</h1>
<div class="span11">
    <div class="span6">

        <svg id="gameboard"></svg>
    </div>
    <div class="span6 text-center" id="graphreport">
        <div class="span6" style="margin-left: 40%">
            <table class="table table-bordered text-center">
                <tr>
                    <th><a><h1 data-toggle="modal" data-target="#timesheetModal" class="timesheetBtn" data-loaddata="1">Project Report </h1></a>
                        <input type="hidden" id="project_id">
                    </th>
                    <th><a><h1 data-toggle="modal" data-target="#timesheetModal" id="timesheetBtn" data-loaddata="2">Timesheet Report </h1></a>
                       
                        <input type="hidden" id="loaddata">
                    </th>
                    
                </tr>

            </table>
            <table class="table table-bordered text-center">
                <tr colspan="3"><h1>Project Budget</h1></tr>
                <tr>
                    <th><h1>Estimated </h1></th>
                    <th><h1>Allocated </h1></th>
                    <th><h1>Utilized </h1></th>
                </tr>
                <tr>
                    <td><h1 id="estimated_budget">-</h1></td>
                    <td><h1 id="allocated_budget">-</h1></td>
                    <td><h1 id="utilized_budget">-</h1></td>
                </tr>
            </table>

            <table class="table table-bordered text-center">
                <tr colspan="3"><h1>Project Hours</h1></tr>
                <tr>
                    <th><h1>Estimated </h1></th>
                    <th><h1>Allocated </h1></th>
                    <th><h1>Utilized </h1></th>
                </tr>
                <tr>
                    <td><h1 id="estimated_hrs">-</h1></td>
                    <td><h1 id="allocated_hrs">-</h1></td>
                    <td><h1 id="utilized_hrs">-</h1></td>
                </tr>
            </table>


            <table class="table table-bordered text-center">
                <tr colspan="3"><h1>Project Details</h1></tr>
                <tr>
                    <th><h1>Tasks </h1></th>
                    <th><h1>Sub Tasks </h1></th>
                    <th><h1>Resources </h1></th>
                </tr>
                <tr>
                    <td><h1 id="tasks">-</h1></td>
                    <td><h1 id="sub_tasks">-</h1></td>
                    <td><h1 id="resources">-</h1></td>
                </tr>
            </table>
        </div>

    </div> 
</div>



<!-- Trigger the modal with a button -->
<!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#barChartModal" id="barChartBtn">Open Modal</button> -->


<!-- Modal -->
<div id="timesheetModal" class="modal fade" role="dialog" style="z-index:-1;">
  <div class="modal-dialog" style="height:100%">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header" style="display: none">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Project Timesheet Records</h4>
        </div>
        <div class="modal-body" style="padding: 10px;overflow: none;max-height: 460px!important" >
            <iframe src="" style="display: none"></iframe>
            
        </div>

    </div>

  </div>
</div>
<?php 
Yii::app()->clientScript->registerCoreScript('jquery.ui');
$cs = Yii::app()->getClientScript();
// $cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
// $cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");




Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
?>
    <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
    <script src="<?php echo Yii::app()->baseUrl."/js/d3.v4.min.js"; ?>"></script>
    <script src="<?php echo Yii::app()->baseUrl."/js/jquery.dataTables.min.js"?>"></script>
    <script src="<?php echo Yii::app()->baseUrl."/js/dataTables.buttons.min.js"?>"></script>
    <script src="<?php echo Yii::app()->baseUrl."/js/buttons.flash.min.js"?>"></script>
    <script src="<?php echo Yii::app()->baseUrl."/js/jszip.min.js"?>"></script>
    <script src="<?php echo Yii::app()->baseUrl."/js/buttons.html5.min.js"?>"></script>


<?php 



?>
<script type="text/javascript">
    var nodeData = [];
    var barData = [];
    $(document).ready(function() {
        
  

        nodeData = $.parseJSON($.ajax({
        url:  '<?php echo CHelper::createUrl('reports/fetchGraphData') ?>',
        dataType: "json", 
        async: false
        }).responseText);

        drawGraph(nodeData);
        // drawBarChart();
    }); 

    
    function drawGraph(nodeData)
    {

    // Variables
        var width = 700;
        var height = 700;
        var radius = Math.min(width, height) / 2;
        var color = d3.scaleOrdinal(d3.schemeCategory20b).range(["#FF3D00", "#FF5916", "#FF1744", "#00BFA5", "#880E4F", "#E5ACB6", "#ff8c00"]);
        var color2 = d3.scaleOrdinal(d3.schemeCategory20b).range(["#FFAD36", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);

        d3.selectAll('button').style("background-color",
        color2()
        );

        // Size our <svg> element, add a <g> element, and move translate 0,0 to the center of the element.
        var g = d3.select('svg')
            .attr('width', width)
            .attr('height', height)
            .append('g')
            .attr('transform', 'translate(' + width / 2 + ',' + height / 2 + ')');

        // Create our sunburst data structure and size it.
        var partition = d3.partition()
            .size([2 * Math.PI, radius]);

        drawSunburst(nodeData,partition,g,color);
    }

    function drawSunburst(data,partition,g,color) {

        // Find the root node, calculate the node.value, and sort our nodes by node.value
        root = d3.hierarchy(data)
            .sum(function (d) { return d.size; })
            .sort(function (a, b) { return b.value - a.value; });

        // Calculate the size of each arc; save the initial angles for tweening.
        partition(root);
        arc = d3.arc()
            .startAngle(function (d) { d.x0s = d.x0; return d.x0; })
            .endAngle(function (d) { d.x1s = d.x1; return d.x1; })
            .innerRadius(function (d) { return d.y0; })
            .outerRadius(function (d) { return d.y1; });

        // Add a <g> element for each node; create the slice variable since we'll refer to this selection many times
        slice = g.selectAll('g.node').data(root.descendants(), function(d) { return d.data.name; }); // .enter().append('g').attr("class", "node");
        newSlice = slice.enter().append('g').attr("class", "node").merge(slice);
        slice.exit().remove();

        slice.selectAll('path').remove();
        newSlice.append('path').attr("display", function (d) { return d.depth ? null : "none"; })
            .attr("d", arc)
            .style('stroke', '#fff')
            .style("fill", function (d) { return color((d.children ? d : d.parent).data.name); });

        // Populate the <text> elements with our data-driven titles.
        slice.selectAll('text').remove();
        newSlice.append("text")
            .attr("transform", function(d) {
                return "translate(" + arc.centroid(d) + ")rotate(" + computeTextRotation(d) + ")"; })
            .attr("dx", "-20")
            .attr("dy", ".5em")
            .text(function(d) { return d.parent ? d.data.name : "" })
            .attr("id", function(d) { return d.parent ? d.data.id : "" });

        newSlice.on("click", highlightSelectedSlice);
    };

    d3.selectAll(".showSelect").on("click", showTopTopics);
    d3.selectAll(".sizeSelect").on("click", sliceSizer);

    // Redraw the Sunburst Based on User Input
    function highlightSelectedSlice(c,i) {

        clicked = c;

        var rootPath = clicked.path(root).reverse();
        rootPath.shift(); // remove root node from the array

        newSlice.style("opacity", 0.4);
        newSlice.filter(function(d) {
            if (d === clicked && d.prevClicked) {
                d.prevClicked = false;
                newSlice.style("opacity", 1);
                return true;

            } else if (d === clicked) {
                d.prevClicked = true;
                return true;
            } else {
                d.prevClicked = false;
                return (rootPath.indexOf(d) >= 0);
            }
        })
            .style("opacity", 1);

        //d3.select("#sidebar").text("another!");
        getProjectData(c.data.id);
    };

    // Redraw the Sunburst Based on User Input
    function sliceSizer(r, i) {

        // Determine how to size the slices.
        if (this.value === "size") {
            root.sum(function (d) { return d.size; });
        } else {
            root.count();
        }
        root.sort(function(a, b) { return b.value - a.value; });

        partition(root);

        newSlice.selectAll("path").transition().duration(750).attrTween("d", arcTweenPath);
        newSlice.selectAll("text").transition().duration(750).attrTween("transform", arcTweenText);
    };

    // Redraw the Sunburst Based on User Input
    function showTopTopics(r, i) {
        //alert(this.value);
        var showCount;

        // Determine how to size the slices.
        if (this.value === "top3") {
            showCount = 3;
        } else if (this.value === "top6") {
            showCount = 6;
        } else {
            showCount = 100;
        }

        var showNodes = JSON.parse(JSON.stringify(allNodes));
        showNodes.children.splice(showCount, (showNodes.children.length - showCount));

        drawSunburst(showNodes);

    };

    /**
     * When switching data: interpolate the arcs in data space.
     * @param {Node} a
     * @param {Number} i
     * @return {Number}
     */
    function arcTweenPath(a, i) {

        var oi = d3.interpolate({ x0: a.x0s, x1: a.x1s }, a);

        function tween(t) {
            var b = oi(t);
            a.x0s = b.x0;
            a.x1s = b.x1;
            return arc(b);
        }

        return tween;
    }

    /**
     * When switching data: interpolate the text centroids and rotation.
     * @param {Node} a
     * @param {Number} i
     * @return {Number}
     */
    function arcTweenText(a, i) {

        var oi = d3.interpolate({ x0: a.x0s, x1: a.x1s }, a);
        function tween(t) {
            var b = oi(t);
            return "translate(" + arc.centroid(b) + ")rotate(" + computeTextRotation(b) + ")";
        }
        return tween;
    }

    /**
     * Calculate the correct distance to rotate each label based on its location in the sunburst.
     * @param {Node} d
     * @return {Number}
     */
    function computeTextRotation(d) {
        var angle = (d.x0 + d.x1) / Math.PI * 90;

        // Avoid upside-down labels
        // return 0;
        // return (angle < 120 || angle > 270) ? angle : angle;  // labels as rims
        return (angle < 180) ? angle - 90 : angle + 90;  // labels as spokes
    }


    function getProjectData(project_id)
    {
        var project_arr = project_id.split('_');
        var project_id = project_arr[1];
        $(".custom-loader").show();
        if(project_arr[0] !=  'project') {
            $(".custom-loader").hide();
            alert('Please select a valid project. It seems you have selected a program');
            return false;
        }
        projectData = $.parseJSON($.ajax({
            url:  '<?php echo CHelper::createUrl('reports/fetchProjectData') ?>',
            type: 'post',
            data: {project_id: project_id },
            dataType: "json", 
            async: false
            }).responseText);
        // console.log(projectData);
        var estimated_hrs = projectData.estimated.estimated_hrs != null ? projectData.estimated.estimated_hrs : 0;
        $("#estimated_hrs").text(estimated_hrs);
        var estimated_budget = projectData.estimated.estimated_budget != null ? projectData.estimated.estimated_budget : 0;
        $("#estimated_budget").text(estimated_budget);
        var allocated_hrs = projectData.allocated.allocated_hrs != null ? projectData.allocated.allocated_hrs : 0;
        $("#allocated_hrs").text(allocated_hrs);
        var allocated_budget = projectData.allocated.allocated_budget != null ? projectData.allocated.allocated_budget : 0;
        $("#allocated_budget").text(allocated_budget);
        var utilized_hrs = projectData.utilized.utilized_hrs != null ? projectData.utilized.utilized_hrs : 0;
        $("#utilized_hrs").text(utilized_hrs);
        var utilized_budget = projectData.utilized.utilized_budget != null ? projectData.utilized.utilized_budget : 0;
        $("#utilized_budget").text(utilized_budget);
        var tasks = projectData.tasks.tasks != null ? projectData.tasks.tasks : 0;
        $("#tasks").text(tasks);
        var sub_tasks = projectData.sub_tasks.sub_tasks != null ? projectData.sub_tasks.sub_tasks : 0;
        $("#sub_tasks").text(sub_tasks);
        var resources = projectData.resources != null ? projectData.resources : 0;
        $("#resources").text(resources);
        // alert(projectData.project_id);
        $("#project_id").val(projectData.project_id);
        $(".custom-loader").hide();
    }

    
    $("#barChartModal").on("shown.bs.modal", function () { 
        // drawBarChart();
        var datatable = '';
        // datatable.destroy();
        // alert($("#project_id").val());
        $('.modal').css('z-index',1050);
        var project_id = $("#project_id").val();
        
        $('#projectreports').DataTable( {
            "dom": "BfrtiS",
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo CHelper::createUrl('reports/fetchProjectReport/') ?>",
                "type": "POST",
                "data": {"project_id": $("#project_id").val()}
            },
            // "dom": 'Bfrtip',
            "buttons": [
                {
                        "extend": 'excelHtml5',
                        "title": 'Export Project Reports'
                }
            ],
            "scrollY": 300,
            "deferRender": true,
            "scroller": {
                loadingIndicator: true
            }
        });
    });

    $("#timesheetModal").on("shown.bs.modal", function () { 
        // drawBarChart();
        // alert('adsa');
        var datatable = '';
        // datatable.destroy();
        // alert($("#project_id").val());
        var project_id = $("#project_id").val();
        var emp_id = $("#emp_id").val()
        var loaddata = $("#loaddata").val();
        $('.modal').css('z-index',1050);
        $('iframe').show();
        if(loaddata == 1)
        {
            var src = "<?php echo Yii::app()->baseUrl.'/reports/getreports?';?>project_id="+project_id;
        }else{
            
            var src = "<?php echo Yii::app()->baseUrl.'/reports/gettimesheet?';?>project_id="+project_id;
        }
        $('iframe').attr('src',src);

    });

    $("#timesheetModal").on("hidden.bs.modal", function () { 
        $('iframe').hide();
        $('iframe').attr('src','');
        $('.modal').css('z-index',-1);
    });
    
    $("#barChartBtn").click(function(){
        $('#projectreports').DataTable().destroy();
    });

    $(".timesheetBtn").click(function(){

            var targetData = $(this).data('loaddata');
            $("#loaddata").val(targetData);
            $('iframe').attr('src','');
            // $('#timesheetreports').DataTable().destroy();
    });


</script>

