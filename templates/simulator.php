<div class="col-md-12">
<h3>Simulator</h3>
			Number of shares: <input name="NumberOfShares" type="number">
			<br/>
			<input type="button" name="Buy" value="Buy" class="buyorsell"> 
			<input type="button" name="Sell" value="Sell" class="buyorsell"> 
		<br/>
		<br/>
			<b>Data management:</b>
		<br/>
			Starting value: <?php echo $StartingValue?>
		<br/>
			Starting time: <?php echo $StartingTime?> 


</div>
<div id="containerAjax">
	
</div>


<p><?php echo $Username;?> - <a href="?action=index">Entra con un altro profilo</a></p>
			<p> <a href="?action=startValue">Cambia i valori di partenza</a></p>
			

<?php
	$jqcode .= '

				var callNumber = 0;
				var timer;  
				var value="";
				var _time="' .  $UpdateTime  . '";
				var svg;

				$(".buyorsell").click(function(){
				var posted_data = {value:$(this).val(), input:$("input[name=\"NumberOfShares\"]").val()}
					$.post("?action=ajaxsimulator", posted_data , function(data){
						$("#containerAjax").html(data);
					});
					return false;
					});
				function updateValue() {
				  console.log("Call: " + ++callNumber);
				  $.ajax("?action=ajaxsimulator")
				  .done(function(data) {
					value=data;
					$("#containerAjax").html(value);
				  })
				  .fail(function() {
					$("#containerAjax").html("Unsuccessful request");
				  })
				}

				updateValue();
				timer = setInterval(updateValue, _time);

				';
?>
