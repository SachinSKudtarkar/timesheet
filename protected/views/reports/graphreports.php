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
        <div class="span7">     
            <p>Click on the Project name to fetch the detailed report for that project.</p>
        </div>
        <div class="span5">
            <p><strong>Legend:</strong> <span style="display: inline-block;width:40px;height: 15px;background: #201F24;"></span> Program <span style="display: inline-block;width:40px;height: 15px;background: #e06a66;"></span> Project</p>
        </div>
        <svg id="gameboard"></svg>
    </div>
    <div class="span6 text-center" id="graphreport" style="display: none">
        <div class="span6" style="margin-left: 40%">
            <table class="table table-bordered text-center">
                <tr>
                    <th><a class="btn btn-primary"><h1 data-toggle="modal" data-target="#timesheetModal" class="" data-loaddata="1">Project Report </h1></a>
                        <input type="hidden" id="project_id">
                    </th>
                    <th><a class="btn btn-primary"><h1 data-toggle="modal" data-target="#timesheetModal" class="" data-loaddata="2">Timesheet Report </h1></a>
                       
                        <input type="hidden" id="loaddata">
                    </th>
                    
                </tr>
                <p>Click on the links given below to fetch the respective reports for the project.</p>
            </table>
            <table class="table table-bordered text-center">
                <tr colspan="3"><h1 class="budtext"><span>Project</span> Budget</h1></tr>
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
                <tr colspan="3"><h1 class="budtext"><span>Project</span> Hours</h1></tr>
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
                <tr colspan="3"><h1 class="budtext"><span>Project</span> Details</h1></tr>
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

        // drawGraph(nodeData);
        // drawBarChart();
        var margin = {top: 30, right: 20, bottom: 30, left: 20},
            width = 960,
            barHeight = 30,
            barWidth = (width - margin.left - margin.right) * 0.8;

        var i = 0,
            duration = 400,
            root;

        var diagonal = d3.linkHorizontal()
            .x(function(d) { return d.y; })
            .y(function(d) { return d.x; });

        var svg = d3.select("svg")
            .attr("width", width) // + margin.left + margin.right)
          .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        // d3.json("flare.json", function(error, flare) {
          // if (error) throw error;
          root = d3.hierarchy(nodeData);
          root.x0 = 0;
          root.y0 = 0;
          update(root);
        // });

        function update(source) {

          // Compute the flattened node list.
          var nodes = root.descendants();

          var height = Math.max(500, nodes.length * barHeight + margin.top + margin.bottom);

          d3.select("svg").transition()
              .duration(duration)
              .attr("height", height);

          d3.select(self.frameElement).transition()
              .duration(duration)
              .style("height", height + "px");

          // Compute the "layout". TODO https://github.com/d3/d3-hierarchy/issues/67
          var index = -1;
          root.eachBefore(function(n) {
            n.x = ++index * barHeight;
            n.y = n.depth * 20;
          });

          // Update the nodes…
          var node = svg.selectAll(".node")
            .data(nodes, function(d) { return d.id || (d.id = ++i); });

          var nodeEnter = node.enter().append("g")
              .attr("class", "node")
              .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
              .style("opacity", 0);

          // Enter any new nodes at the parent's previous position.
          nodeEnter.append("rect")
              .attr("y", -barHeight / 2)
              .attr("height", barHeight)
              .attr("width", barWidth)
              .style("fill", color)
              .on("click", click);

          nodeEnter.append("text")
              .attr("dy", 3.5)
              .attr("dx", 5.5)
              .text(function(d) { return d.data.name; })
              .attr("id", function(d) { return d.parent ? d.data.id : "" });

          // Transition nodes to their new position.
          nodeEnter.transition()
              .duration(duration)
              .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
              .style("opacity", 1);

          node.transition()
              .duration(duration)
              .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
              .style("opacity", 1)
            .select("rect")
              .style("fill", color);

          // Transition exiting nodes to the parent's new position.
          node.exit().transition()
              .duration(duration)
              .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
              .style("opacity", 0)
              .remove();

          // Update the links…
          var link = svg.selectAll(".link")
            .data(root.links(), function(d) { return d.target.id; });

          // Enter any new links at the parent's previous position.
          link.enter().insert("path", "g")
              .attr("class", "link")
              .attr("d", function(d) {
                var o = {x: source.x0, y: source.y0};
                return diagonal({source: o, target: o});
              })
            .transition()
              .duration(duration)
              .attr("d", diagonal);

          // Transition links to their new position.
          link.transition()
              .duration(duration)
              .attr("d", diagonal);

          // Transition exiting nodes to the parent's new position.
          link.exit().transition()
              .duration(duration)
              .attr("d", function(d) {
                var o = {x: source.x, y: source.y};
                return diagonal({source: o, target: o});
              })
              .remove();

          // Stash the old positions for transition.
          root.each(function(d) {
            d.x0 = d.x;
            d.y0 = d.y;
          });
        }

        // Toggle children on click.
        function click(d) {
          if (d.children) {
            d._children = d.children;
            d.children = null;
          } else {
            d.children = d._children;
            d._children = null;
          }
          update(d);
         
          getProjectData(d.data.id,d.data.name);
        }

        function color(d) {
          return d._children ? "#201F24" : d.children ? "#201F24" : "#E06A66";
        }
    }); 

    

    function getProjectData(project_id,project_name)
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
        $(".budtext span").text(project_name);
        $(".custom-loader").hide();
        $("#graphreport").show();
    }
    

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

    $(".timesheetBtn").click(function(){

            var targetData = $(this).data('loaddata');
            $("#loaddata").val(targetData);
            $('iframe').attr('src','');
    });


</script>

