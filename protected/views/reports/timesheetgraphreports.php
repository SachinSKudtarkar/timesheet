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


<h1>Resource Timesheet Reports</h1>
<div class="span11">

    <?php

            $employeeData = Employee::model()->findAll(array('select' => "emp_id,first_name,last_name,email", 'order' => 'first_name', 'condition' => 'is_active=1'));
            $emp_list[] = 'Select Employee';
            foreach ($employeeData as $key => $value) {
                $emp_list[$value['emp_id']] = $value['first_name'] . " " . $value['last_name']."  (".$value['email'].")";
            }
            // echo CHtml::label('Select Employee', '');
            echo CHtml::dropDownList("emplist", '', $emp_list);


        ?>
</div>
<div class="span11" id="graphDataDiv" style="display:none;">
    <div class="span6" id="treecontainer">

        <!-- <svg id="gameboard"></svg> -->
    </div>
    <div class="span6 text-center" id="graphreport">
        <div class="span6" style="margin-left: 40%">
            <table class="table table-bordered text-center">
                <tr>
                    <th class="hidden" id="prjrep"><a><h1 data-toggle="modal" data-target="#timesheetModal" class="timesheetBtn" data-loaddata="1">Project Timesheet </h1></a>
                        <input type="hidden" id="project_id">
                    </th>
                    <th><a><h1 data-toggle="modal" data-target="#timesheetModal" class="timesheetBtn" data-loaddata="2">All Timesheet </h1></a>
                        <input type="hidden" id="emp_id" value="<?php echo Yii::app()->session['login']['user_id']; ?>">
                        <input type="hidden" id="loaddata">
                    </th>
                    
                </tr>
            </table>
            <table class="table table-bordered text-center">
                <tr>
                    <th><h1 id="revenue_text">Total Revenue Generated </h1></th>
                </tr>
                <tr>
                    <td>
                        <h1 id="revenue_generated">
                            <?php echo !empty($data['utilized_budget']) ? $data['utilized_budget'] : '-'?>
                        </h1>
                    </td>
                </tr>
            </table>

            <table class="table table-bordered text-center">
                <tr>
                    <th><h1 id="hours_text">Total Hours Worked </h1></th>
                </tr>
                <tr>
                    <td><h1 id="hours_worked"><?php echo !empty($data['utilized_hrs']) ? $data['utilized_hrs'] : '-'?> Hrs</h1></td>

                </tr>
            </table>


            <table class="table table-bordered text-center">
                <tr>
                    <th><h1>Project Worked Percentage </h1></th>
                </tr>
                <tr>
                    <td><h1 id="project_per">-</h1></td>

                </tr>
            </table>
        </div>

    </div> 
</div>



<!-- Trigger the modal with a button -->
<!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#barChartModal" id="barChartBtn">Open Modal</button> -->

<!-- Modal -->
<div id="timesheetModal" class="modal fade" role="dialog" style="z-index:0;">
  <div class="modal-dialog" style="height:auto">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Project Timesheet Records</h4>
        </div>
        <div class="modal-body" style="padding: 10px;height:auto;overflow: scroll;" >
            <table id="timesheetreports" class="display table table-bordered table-striped" style="width:100%;height:auto">
                <thead>
                    <tr>
                        <th>Project Id</th>
                        <th>Project Name</th>
                        <th>Task ID</th>
                        <th>Task Name</th>
                        <th>Sub Task Id</th>
                        <th>Sub Task Name</th>
                        <th>Date</th>
                        <th>User Name</th>
                        <th>Hours</th>
                        <th>Comments</th>
                        
                        <!-- <th>Created At</th> -->

                    </tr>
                </thead>
<!--                 <tfoot>
                    <tr>
                        <td>Project Id</td>
                        <td>Project Name</td>
                        <td>Task ID</td>
                        <td>Task Name</td>
                        <td>Sub Task Id</td>
                        <td>Sub Task Name</td>
                        <td>Date</td>
                        <td>User Name</td>
                        <td>Hours</td>
                        <td>Comments</td>
                    </tr>
                </tfoot> -->
            </table>
        </div>
<!-- 
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
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
    var treeData = [];
    var barData = [];
    $('#emplist').change(function(e) {
        
        $("#emp_id").val($(this).val());
        
        if($('svg').length > 0) {
            $('svg').remove();
        }
        treeData = $.parseJSON($.ajax({
        url:  '<?php echo CHelper::createUrl('reports/fetchResourcesData') ?>',
        type: 'post',
        data: {emp_id: $(this).val() },
        dataType: "json", 
        async: false
        }).responseText);

        console.log(treeData.empdata);
        if(treeData.graphdata.children != '')
        {
            drawGraph(treeData.graphdata);
            var projectData = treeData.empdata;
            var revenue_generated = projectData.utilized_budget != null ? projectData.utilized_budget : 0;
            $("#revenue_generated").text(revenue_generated);
            $("#revenue_text").text('Project Revenue Generated');
            var hours_worked = projectData.utilized_hrs != null ? projectData.utilized_hrs : 0;
            $("#hours_worked").text(hours_worked);
            $("#hours_text").text('Project Hours Worked');
            
            $("#graphDataDiv").show();
        }else{
            alert('The selected user does not have any timesheet records.');
            $("#graphDataDiv").hide();
        }
        
        // drawBarChart();
    }); 

    
    function drawGraph(treeData)
    {
        

        // Set the dimensions and margins of the diagram
        var margin = {top: 20, right: 90, bottom: 30, left: 90},
            width = 960 - margin.left - margin.right,
            height = 500 - margin.top - margin.bottom;

        // append the svg object to the body of the page
        // appends a 'group' element to 'svg'
        // moves the 'group' element to the top left margin
        var svg = d3.select("#treecontainer").append("svg")
            .attr("width", width + margin.right + margin.left)
            .attr("height", height + margin.top + margin.bottom)
        .append("g")
            .attr("transform", "translate("
                + margin.left + "," + margin.top + ")");

        var i = 0,
            duration = 750,
            root;

        // declares a tree layout and assigns the size
        var treemap = d3.tree().size([height, width]);

        // Assigns parent, children, height, depth
        root = d3.hierarchy(treeData, function(d) { return d.children; });
        root.x0 = height / 2;
        root.y0 = 0;

        // Collapse after the second level
        root.children.forEach(collapse);

        update(root);

        // Collapse the node and all it's children
        function collapse(d) {
            if(d.children) {
                d._children = d.children
                d._children.forEach(collapse)
                d.children = null
            }
        }

        function update(source) {

            // Assigns the x and y position for the nodes
            var treeData = treemap(root);

            // Compute the new tree layout.
            var nodes = treeData.descendants(),
                links = treeData.descendants().slice(1);

            // Normalize for fixed-depth.
            nodes.forEach(function(d){ d.y = d.depth * 180});

            // ****************** Nodes section ***************************

            // Update the nodes...
            var node = svg.selectAll('g.node')
                .data(nodes, function(d) {return d.id || (d.id = ++i); });

            // Enter any new modes at the parent's previous position.
            var nodeEnter = node.enter().append('g')
                .attr('class', 'node')
                .attr("transform", function(d) {
                    return "translate(" + source.y0 + "," + source.x0 + ")";
                })
                .on('click', click)
                .attr('id',function(d) { return d.data.id; });

            // Add Circle for the nodes
            nodeEnter.append('circle')
                .attr('class', 'node')
                .attr('r', 1e-6)
                .style("fill", function(d) {
                    return d._children ? "lightsteelblue" : "#fff";
                });

            // Add labels for the nodes
            nodeEnter.append('text')
                .attr("dy", ".35em")
                .attr("x", function(d) {
                    return d.children || d._children ? -13 : 13;
                })
                .attr("text-anchor", function(d) {
                    return d.children || d._children ? "end" : "start";
                })
                .text(function(d) { return d.data.name; });

            // UPDATE
            var nodeUpdate = nodeEnter.merge(node);

            // Transition to the proper position for the node
            nodeUpdate.transition()
                .duration(duration)
                .attr("transform", function(d) { 
                    return "translate(" + d.y + "," + d.x + ")";
                });

            // Update the node attributes and style
            nodeUpdate.select('circle.node')
                .attr('r', 10)
                .style("fill", function(d) {
                    return d._children ? "lightsteelblue" : "#fff";
                })
                .attr('cursor', 'pointer');
                // .attr('id',function(d) { return d.data.id; });


            // Remove any exiting nodes
            var nodeExit = node.exit().transition()
                .duration(duration)
                .attr("transform", function(d) {
                    return "translate(" + source.y + "," + source.x + ")";
                })
                .remove();

            // On exit reduce the node circles size to 0
            nodeExit.select('circle')
                .attr('r', 1e-6);

            // On exit reduce the opacity of text labels
            nodeExit.select('text')
                .style('fill-opacity', 1e-6);

            // ****************** links section ***************************

            // Update the links...
            var link = svg.selectAll('path.link')
                .data(links, function(d) { return d.id; });

            // Enter any new links at the parent's previous position.
            var linkEnter = link.enter().insert('path', "g")
                .attr("class", "link")
                .attr('d', function(d){
                    var o = {x: source.x0, y: source.y0}
                    return diagonal(o, o)
                });

            // UPDATE
            var linkUpdate = linkEnter.merge(link);

            // Transition back to the parent element position
            linkUpdate.transition()
                .duration(duration)
                .attr('d', function(d){ return diagonal(d, d.parent) });

            // Remove any exiting links
            var linkExit = link.exit().transition()
                .duration(duration)
                .attr('d', function(d) {
                    var o = {x: source.x, y: source.y}
                    return diagonal(o, o)
                })
                .remove();

            // Store the old positions for transition.
            nodes.forEach(function(d){
                d.x0 = d.x;
                d.y0 = d.y;
            });

            // Creates a curved (diagonal) path from parent to the child nodes
            function diagonal(s, d) {

                path = `M ${s.y} ${s.x}
                        C ${(s.y + d.y) / 2} ${s.x},
                        ${(s.y + d.y) / 2} ${d.x},
                        ${d.y} ${d.x}`

                return path
            }

            // Toggle children on click.
            function click(d) {
                var node = d;
                if (d.children) {
                    d._children = d.children;
                    d.children = null;
                } else {
                    d.children = d._children;
                    d._children = null;
                }
                
                update(d);
                // console.log(node.data.id);
                getProjectData(d.data.id);
            }
        }

    }



    function getProjectData(project_id)
    {
        var project_arr = project_id.split('_');
        var project_id = project_arr[1];
        $(".custom-loader").show();
        if(project_arr[0] !=  'project') {
            $(".custom-loader").hide();
            // alert('Please select a valid project. It seems you have selected a program');
            return false;
        }
        projectData = $.parseJSON($.ajax({
            url:  '<?php echo CHelper::createUrl('reports/fetchProjectTimeData') ?>',
            type: 'post',
            data: {project_id: project_id,emp_id: $('#emp_id').val() },
            dataType: "json", 
            async: false
            }).responseText);
        // console.log(projectData);
        var revenue_generated = projectData.utilized.utilized_budget != null ? projectData.utilized.utilized_budget : 0;
        $("#revenue_generated").text(revenue_generated);
        $("#revenue_text").text('Project Revenue Generated');
        var hours_worked = projectData.utilized.utilized_hrs != null ? projectData.utilized.utilized_hrs : 0;
        $("#hours_worked").text(hours_worked);
        $("#hours_text").text('Project Hours Worked');
        var project_per = projectData.project_per > 0 ? projectData.project_per : 0;
        $("#project_per").text(project_per+'%');
        $("#project_id").val(projectData.project_id);
        $("#prjrep").removeClass('hidden');
        $(".custom-loader").hide();
        
    }

    
    $("#barChartModal").on("shown.bs.modal", function () { 
        // drawBarChart();
        var datatable = '';
        // datatable.destroy();
        // alert($("#project_id").val());
        var project_id = $("#project_id").val();
        
        $('#projectreports').DataTable( {
            "dom": "Bfrtip",
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
            ]
        });
    });


    $("#timesheetModal").on("shown.bs.modal", function () { 
        // drawBarChart();
        var datatable = '';
        // datatable.destroy();
        // alert($("#project_id").val());
        var project_id = $("#project_id").val();
        var loaddata = $("#loaddata").val();
        $('.modal').css('z-index',1050);
        $('#timesheetreports').DataTable( {
            "dom": "BfrtiS",
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "ajax": {
                "url": "<?php echo CHelper::createUrl('reports/fetchRTimesheetReport/') ?>",
                "type": "POST",
                "data": {"project_id":project_id,"emp_id": $("#emp_id").val(),"loaddata":loaddata},
                // "success":{alert('asdasd');}
            },
            "start":0,
            "length": 5,
            // "pageLength":3,
            // "pagingType": "full_numbers",
            // "dom": 'Bfrtip',
            "buttons": [
                {
                        "extend": 'excelHtml5',
                        "title": 'Export Timesheet Reports'
                }
            ],
            "scrollY": 260,
            "deferRender": true,
            // "deferLoading": 10,
            "scroller": {
                loadingIndicator: true
            }
        });


    });


    $("#barChartBtn").click(function(){
        $('#projectreports').DataTable().destroy();
    });

    $(".timesheetBtn").click(function(){

            var targetData = $(this).data('loaddata');
            $("#loaddata").val(targetData);
        
            $('#timesheetreports').DataTable().destroy();
    });


</script>

