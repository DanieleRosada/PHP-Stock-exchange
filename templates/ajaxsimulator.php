
<div class="col-md-5">
			Last update: <?php echo $Uptime?>
		<br/>
			Starting investiment: <?php echo $InvestimentTime?> 
		<br/>
			Economic result: <?php echo $EconomicResult?>
		<br/>
			Number of shares: <?php echo ($buyOrSell." ".$NumberOfShares)?>
		<br/>
			Value of shares: <?php echo $ValueOfShares?> 
		<br/>
			Current value: <?php echo $CurrentValue?>
		<br/>
			<b><?php echo $ErrorMessage?></b>
		<br/>
</div>

<div class="col-md-7">


               
		<!--Grafico w=480 --- h=250-->
		<h3>Graph</h3>

		<svg width="480" height="250"></svg>
		<script>
		
		var svg = d3.select("svg"),
			margin = {top: 20, right: 20, bottom: 30, left: 50},
			width = +svg.attr("width") - margin.left - margin.right,
			height = +svg.attr("height") - margin.top - margin.bottom,
			g = svg.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");

		var parseTime = d3.timeParse("%H:%M:%S");

		var x = d3.scaleTime()
			.rangeRound([0, width]);

		var y = d3.scaleLinear()
			.rangeRound([height, 0]);

		var line = d3.line()
			.x(function(d) { return x(d.date); })
			.y(function(d) { return y(d.close); });

		d3.tsv("data.tsv", function(d) {
		  d.date = parseTime(d.date);
		  d.close = +d.close;
		  return d;
		}, function(error, data) {
		  if (error) throw error;

		  x.domain(d3.extent(data, function(d) { return d.date; }));
		  y.domain(d3.extent(data, function(d) { return d.close; }));

		  g.append("g")
			  .attr("transform", "translate(0," + height + ")")
			  .call(d3.axisBottom(x))
			.select(".domain")
			  .remove();

		  g.append("g")
			  .call(d3.axisLeft(y))
			.append("text")
			  .attr("fill", "#000")
			  .attr("transform", "rotate(-90)")
			  .attr("y", 6)
			  .attr("dy", "0.71em")
			  .attr("text-anchor", "end")
			  .text("Price ($)");

		  g.append("path")
			  .datum(data)
			  .attr("fill", "none")
			  .attr("stroke", "steelblue")
			  .attr("stroke-linejoin", "round")
			  .attr("stroke-linecap", "round")
			  .attr("stroke-width", 1.5)
			  .attr("d", line);
		});
	

		</script>

		</div>
</div>
<pre><?php echo file_get_contents("data.tsv"); ?> </pre>

