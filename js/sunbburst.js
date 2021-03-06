$(function () {
  // Thanks http://bl.ocks.org/metmajer/5480307!!!
  var root, node, tab_structure;

  d3.json("bible_structure.json", function (error, bvsa) {
    if (error) {
      return console.error(error);
    } else {
      tab_structure = bvsa;
      root = d3.stratify().id(function (d) { return d.idx; }).parentId(function (d) { return d.parent; })(bvsa);
      update();
    }

  });

  // Global Variables
  var gWidth = $('#gameboard').width(),   // Width of the svg palette
    gHeight = $('#gameboard').height(),   // Height of the svg palette
    radius = (Math.min(gWidth, gHeight) / 2) - 10,
    mode = $('.mode:checked').val(), // linear or grouped, based on radiobuttons
    svg = d3.select("svg").append("g").attr("id", "bigG").attr("transform", "translate(" + gWidth / 2 + "," + (gHeight / 2) + ")"),
    color_palettes = [['#4abdac', '#fc4a1a', '#f7b733'], ['#f03b20', '#feb24c', '#ffeda0'], ['#007849', '#0375b4', '#ffce00'], ['#373737', '#dcd0c0', '#c0b283'], ['#e37222', '#07889b', '#eeaa7b'], ['#062f4f', '#813772', '#b82601'], ['#565656', '#76323f', '#c09f80']];


  var x = d3.scaleLinear().range([0, 2 * Math.PI]),
    y = d3.scaleLinear().range([0, radius]), //scaleSqrt
    color = d3.scaleLinear().domain([0, 0.5, 1]).range(color_palettes[~~(Math.random() * 6)]),
    partition = d3.partition();

  // Calculate the d path for each slice.
  var arc = d3.arc()
    .startAngle(function(d) { return Math.max(0, Math.min(2 * Math.PI, x(d.x0))); })
    .endAngle(function(d) { return Math.max(0, Math.min(2 * Math.PI, x(d.x1))); })
    .innerRadius(function(d) { return Math.max(0, y(d.y0)); })
    .outerRadius(function(d) { return Math.max(0, y(d.y1)); });


  // Build the sunburst.
  var first_build = true;
  function update() {

    if (mode == "linear") {      // Determine how to size the slices.
      root.sum(function (d) { return d.size; });
    } else {
      root.sum(function (d) { return d.grpsize; });
    }

    if (first_build) {
      // Add a <path d="[shape]" style="fill: [color];"><title>[popup text]</title></path>
      //   to each <g> element; add click handler; save slice widths for tweening
      var gSlices = svg.selectAll("g").data(partition(root).descendants(), function (d) { return d.data.id; }).enter().append("g");
      gSlices.exit().remove();
      gSlices.append("path").style("fill", function (d) { return d.parent ? color(d.x0) : "white"; })
        .on("click", click)
        .append("title").text(function (d) { return d.data.name; });  // Return white for root.
      gSlices.append("text").attr("dy", ".35em").text(function (d) { return d.parent ? d.data.name : ""; }).attr("id", function (d) { return "w" + d.data.name; }); // TODO: was d.data.word
      svg.selectAll("path").append("title").text(function (d) { return d.data.word; })

      first_build = false;
    } else {
      svg.selectAll("path").data(partition(root).descendants());
    }


    svg.selectAll("path").transition("update").duration(750).attrTween("d", function (d, i) {
      return arcTweenPath(d, i);
    });
    svg.selectAll("text").transition("update").duration(750).attrTween("transform", function (d, i) { return arcTweenText(d, i); })
      .attr('text-anchor', function (d) { return d.textAngle > 180 ? "start" : "end"; })
      .attr("dx", function (d) { return d.textAngle > 180 ? -13 : 13; })
      .attr("opacity", function (e) { return e.x1 - e.x0 > 0.01 ? 1 : 0; });
  }


  // Respond to radio click.
  $('.mode').on("change", function change() {
    mode = $('.mode:checked').val();
    update();
  });


  // Respond to slice click.
  function click(d) {
    node = d;

    svg.selectAll("path").transition("click").duration(750).attrTween("d", function (d, i) { return arcTweenPath(d, i); });
    svg.selectAll("text").transition("click").duration(750).attrTween("transform", function (d, i) { return arcTweenText(d, i); })
      .attr('text-anchor', function (d) { return d.textAngle > 180 ? "start" : "end"; })
      .attr("dx", function (d) { return d.textAngle > 180 ? -13 : 13; })
      .attr("opacity", function (e) {
        if (e.x0 >= d.x0 && e.x1 <= d.x1) {
          return (e.x1 - e.x0 > 0.01 ? 1 : 0);
        } else {
          return 0;
        }
      })
   }


  // When switching data: interpolate the arcs in data space.
  //$("#w1Jo").attr("transform").substring(10,$("#w1Jo").attr("transform").search(",")) 
  function arcTweenText(a, i) {

    var oi = d3.interpolate({ x0: (a.x0s ? a.x0s : 0), x1: (a.x1s ? a.x1s : 0), y0: (a.y0s ? a.y0s : 0), y1: (a.y1s ? a.y1s : 0) }, a);
    function tween(t) {
      var b = oi(t);
      var ang = ((x((b.x0 + b.x1) / 2) - Math.PI / 2) / Math.PI * 180);
      b.textAngle = (ang > 90) ? 180 + ang : ang;
      a.centroid = arc.centroid(b);
      //b.opacity = (b.x1 - b.x0) > 0.01 ? 0 : 0;
      //console.log(b.data.name + " x1:" + b.x1 + " x0:" + b.x0);
      return "translate(" + arc.centroid(b) + ")rotate(" + b.textAngle + ")";
    }
    return tween;
  }

  // When switching data: interpolate the arcs in data space.
  function arcTweenPath(a, i) {
    // (a.x0s ? a.x0s : 0) -- grab the prev saved x0 or set to 0 (for 1st time through)
    // avoids the stash() and allows the sunburst to grow into being
    var oi = d3.interpolate({ x0: (a.x0s ? a.x0s : 0), x1: (a.x1s ? a.x1s : 0), y0: (a.y0s ? a.y0s : 0), y1: (a.y1s ? a.y1s : 0) }, a);
    function tween(t) {
      var b = oi(t);
      a.x0s = b.x0;
      a.x1s = b.x1;
      a.y0s = b.y0;
      a.y1s = b.y1;
      return arc(b);
    }
    if (i == 0 && node) {  // If we are on the first arc, adjust the x domain to match the root node at the current zoom level.
      var xd = d3.interpolate(x.domain(), [node.x0, node.x1]);
      var yd = d3.interpolate(y.domain(), [node.y0, 1]);
      var yr = d3.interpolate(y.range(), [node.y0 ? 40 : 0, radius]);

      return function (t) {
        x.domain(xd(t));
        y.domain(yd(t)).range(yr(t));
        return tween(t);
      };
    } else {
      return tween;
    }
  }
  
});