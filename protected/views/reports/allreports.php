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

$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");

?>


<h1>All Reports</h1>

<?php //$this->renderPartial('_count', array('model'=>$model,'allcount'=>$allcount)); ?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>

<div class="row span11" style="margin-bottom: 100px">
      

<?php  
  
$x = $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'day-comment-grid',
    // 'dataProvider' => $model->searchAll(),
   'dataProvider' => new CArrayDataProvider($data, array()),
    'filter' => $model,
    'columns' => array(
        //'pid',

        array(
            'header' => 'Program ID',
            'name' => 'program_id'
        ),
        array(
            'header' => 'Program Name',
            'name' => 'program_name',
        ),
        array(
            'header' => 'Project ID',
            'name' => 'project_id',

        ),
        array(
            'header' => 'Project Name',
            'name' => 'project_name',

        ),
        array(
            'header' => 'Allocated Hrs',
            'name' => 'allocated',     
        ),
        array(
            'header' => 'Task Type',
            'name' => 'task_name',

        ),
        array(
            'header' => 'Task ID',
            'name' => 'project_task_id',
        ),
        array(
            'header' => 'Task Title',
            'name' => 'task_title',
        ),
        array(
            'header' => 'Task Description',
            'name' => 'task_description',
        ),
        array(
            'header' => 'Sub Task ID',
            'name' => 'sub_task_id',
        ),
        array(
            'header' => 'Sub Task Name',
            'name' => 'sub_task_name',
        ),
        array(
            'header' => 'Estimated Hrs',
            'name' => 'est_hrs',
        ),
        array(
            'header' => 'Utilized Hrs',
            'name' => 'utilized_hours',
        ),
        array(
            'header' => 'Created By',
            'name' => 'created_by',
        ),
        array(
            'header' => 'Created At',
            'name' => 'created_at',
        ),
         
    ),
));
 
echo $this->renderExportGridButton_Report($x, 'Export Grid Results', array('class' => 'btn btn-primary pull-left clearfix mr-tp-20'));


?>
</div>
<?php 
Yii::app()->clientScript->registerCoreScript('jquery.ui');
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery-ui-timepicker-addon.js");
$cs->registerCssFile(Yii::app()->baseUrl . "/css/jquery-ui-timepicker-addon.css");




Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
?>
    <script src="https://d3js.org/d3.v4.min.js"></script>
    
<?php 



?>
<script type="text/javascript">
    var nodeData = [];
    $(document).ready(function() {
        
        $('.datepicker').datepicker({
             dateFormat: 'yy-m-d',
             onSelect: function(dateText) {
                var type = $(this).attr('id');
                var date = $(this).val();
              },
            }).attr('readonly','readonly');


        nodeData = $.parseJSON($.ajax({
        url:  '<?php echo CHelper::createUrl('reports/fetchGraphData') ?>',
        dataType: "json", 
        async: false
        }).responseText);

        drawGraph(nodeData);
    }); 

    
    function drawGraph(nodeData)
    {

    // Variables
        var width = 700;
        var height = 700;
        var radius = Math.min(width, height) / 2;
        var color = d3.scaleOrdinal(d3.schemeCategory20b).range(["#FF3D00", "#FF5916", "#FF1744", "#00BFA5", "#880E4F", "#E5ACB6", "#ff8c00"]);;
        var color2 = d3.scaleOrdinal(d3.schemeCategory20b).range(["#FFAD36", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);;

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
        return (angle < 120 || angle > 270) ? angle : angle + 180;  // labels as rims
        //return (angle < 180) ? angle - 90 : angle + 90;  // labels as spokes
    }


    function drawBarChart(){
        // https://insights.stackoverflow.com/survey/2018/#technology-most-loved-dreaded-and-wanted-languages
        const sample = [
          {
            language: 'Rust',
            value: 78.9,
            color: '#000000'
          },
          {
            language: 'Kotlin',
            value: 75.1,
            color: '#00a2ee'
          },
          {
            language: 'Python',
            value: 68.0,
            color: '#fbcb39'
          },
          {
            language: 'TypeScript',
            value: 67.0,
            color: '#007bc8'
          },
          {
            language: 'Go',
            value: 65.6,
            color: '#65cedb'
          },
          {
            language: 'Swift',
            value: 65.1,
            color: '#ff6e52'
          },
          {
            language: 'JavaScript',
            value: 61.9,
            color: '#f9de3f'
          },
          {
            language: 'C#',
            value: 60.4,
            color: '#5d2f8e'
          },
          {
            language: 'F#',
            value: 59.6,
            color: '#008fc9'
          },
          {
            language: 'Clojure',
            value: 59.6,
            color: '#507dca'
          }
        ];

        const svg = d3.select('svg');
        const svgContainer = d3.select('#container');
        
        const margin = 80;
        const width = 1000 - 2 * margin;
        const height = 600 - 2 * margin;

        const chart = svg.append('g')
          .attr('transform', `translate(${margin}, ${margin})`);

        const xScale = d3.scaleBand()
          .range([0, width])
          .domain(sample.map((s) => s.language))
          .padding(0.4)
        
        const yScale = d3.scaleLinear()
          .range([height, 0])
          .domain([0, 100]);

        // vertical grid lines
        // const makeXLines = () => d3.axisBottom()
        //   .scale(xScale)

        const makeYLines = () => d3.axisLeft()
          .scale(yScale)

        chart.append('g')
          .attr('transform', `translate(0, ${height})`)
          .call(d3.axisBottom(xScale));

        chart.append('g')
          .call(d3.axisLeft(yScale));

        // vertical grid lines
        // chart.append('g')
        //   .attr('class', 'grid')
        //   .attr('transform', `translate(0, ${height})`)
        //   .call(makeXLines()
        //     .tickSize(-height, 0, 0)
        //     .tickFormat('')
        //   )

        chart.append('g')
          .attr('class', 'grid')
          .call(makeYLines()
            .tickSize(-width, 0, 0)
            .tickFormat('')
          )

        const barGroups = chart.selectAll()
          .data(sample)
          .enter()
          .append('g')

        barGroups
          .append('rect')
          .attr('class', 'bar')
          .attr('x', (g) => xScale(g.language))
          .attr('y', (g) => yScale(g.value))
          .attr('height', (g) => height - yScale(g.value))
          .attr('width', xScale.bandwidth())
          .on('mouseenter', function (actual, i) {
            d3.selectAll('.value')
              .attr('opacity', 0)

            d3.select(this)
              .transition()
              .duration(300)
              .attr('opacity', 0.6)
              .attr('x', (a) => xScale(a.language) - 5)
              .attr('width', xScale.bandwidth() + 10)

            const y = yScale(actual.value)

            line = chart.append('line')
              .attr('id', 'limit')
              .attr('x1', 0)
              .attr('y1', y)
              .attr('x2', width)
              .attr('y2', y)

            barGroups.append('text')
              .attr('class', 'divergence')
              .attr('x', (a) => xScale(a.language) + xScale.bandwidth() / 2)
              .attr('y', (a) => yScale(a.value) + 30)
              .attr('fill', 'white')
              .attr('text-anchor', 'middle')
              .text((a, idx) => {
                const divergence = (a.value - actual.value).toFixed(1)
                
                let text = ''
                if (divergence > 0) text += '+'
                text += `${divergence}%`

                return idx !== i ? text : '';
              })

          })
          .on('mouseleave', function () {
            d3.selectAll('.value')
              .attr('opacity', 1)

            d3.select(this)
              .transition()
              .duration(300)
              .attr('opacity', 1)
              .attr('x', (a) => xScale(a.language))
              .attr('width', xScale.bandwidth())

            chart.selectAll('#limit').remove()
            chart.selectAll('.divergence').remove()
          })

        barGroups 
          .append('text')
          .attr('class', 'value')
          .attr('x', (a) => xScale(a.language) + xScale.bandwidth() / 2)
          .attr('y', (a) => yScale(a.value) + 30)
          .attr('text-anchor', 'middle')
          .text((a) => `${a.value}%`)
        
        svg
          .append('text')
          .attr('class', 'label')
          .attr('x', -(height / 2) - margin)
          .attr('y', margin / 2.4)
          .attr('transform', 'rotate(-90)')
          .attr('text-anchor', 'middle')
          .text('Love meter (%)')

        svg.append('text')
          .attr('class', 'label')
          .attr('x', width / 2 + margin)
          .attr('y', height + margin * 1.7)
          .attr('text-anchor', 'middle')
          .text('Languages')

        svg.append('text')
          .attr('class', 'title')
          .attr('x', width / 2 + margin)
          .attr('y', 40)
          .attr('text-anchor', 'middle')
          .text('Most loved programming languages in 2018')

        svg.append('text')
          .attr('class', 'source')
          .attr('x', width - margin / 2)
          .attr('y', height + margin * 1.7)
          .attr('text-anchor', 'start')
          .text('Source: Stack Overflow, 2018')
      
    }
</script>

