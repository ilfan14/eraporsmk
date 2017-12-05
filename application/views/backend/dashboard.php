<?php
$setting = $this->settings->get(1);
$sekolah = $this->sekolah->get($user->sekolah_id);
?>
<div class="row">
	<div class="col-md-12">
		<div class="box box-solid" style="padding-bottom:25px;">
		    <div class="box-header with-border">
        		<h3 class="text-center box-title">Selamat Datang <?php echo $user->username; ?></h3>
	    	</div><!-- /.box-header -->
    		<div class="box-body">
			<?php
			$super_admin = array(1,2);
			if($this->ion_auth->in_group($super_admin)){
				$this->load->view('backend/dashboard/admin');
			}
			if ($this->ion_auth->in_group('siswa')){
				$this->load->view('backend/dashboard/siswa');
			}
			if ($this->ion_auth->in_group('guru')){
				$this->load->view('backend/dashboard/guru');
			} 
			if ($this->ion_auth->in_group('bk')){
				$this->load->view('backend/dashboard/bk');
			} 
			if ($this->ion_auth->in_group('kasek')){
				$this->load->view('backend/dashboard/kasek');
			} ?>
			</div><!-- /.box-body -->
			<div style="clear:both;"></div>
		</div><!--/-->
	</div>
</div>
<?php
$group = array('guru', 'siswa');
if(!$this->ion_auth->in_group($group)){
?>
<!--script src="<?php echo base_url(); ?>assets/plugins/chartjs/Chart.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var PieData = [];
	$.ajax({
		url: "<?php echo site_url('admin/ajax/get_chart');?>",
		method: "GET",
		success: function(response) {
			var result = $.parseJSON(response);
			console.log(result);
			$.each(result.result, function (i, item) {
				PieData.push({
					value: item.value,
					color: item.color,
					highlight: item.highlight,
					label: item.label
				});
			});
			var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
			var pieChart = new Chart(pieChartCanvas);
			var pieOptions = {
				segmentShowStroke: true,
				segmentStrokeColor: "#fff",
				segmentStrokeWidth: 2,
				percentageInnerCutout: 50, // This is 0 for Pie charts
				animationSteps: 100,
				animationEasing: "easeOutBounce",
				animateRotate: true,
				animateScale: false,
				responsive: true,
				maintainAspectRatio: true,
				legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
			};
			pieChart.Doughnut(PieData, pieOptions);
			var areaChartData = {
				labels: ["January", "February", "March", "April", "May", "June", "July"],
				datasets: [
					{
						label: "Pengetahuan",
						fillColor: "rgba(210, 214, 222, 1)",
						strokeColor: "rgba(210, 214, 222, 1)",
						pointColor: "rgba(210, 214, 222, 1)",
						pointStrokeColor: "#c1c7d1",
						pointHighlightFill: "#fff",
						pointHighlightStroke: "rgba(220,220,220,1)",
						data: [65, 59, 80, 81, 56, 55, 40]
					},
					{
						label: "Keterampilan",
						fillColor: "rgba(60,141,188,0.9)",
						strokeColor: "rgba(60,141,188,0.8)",
						pointColor: "#3b8bba",
						pointStrokeColor: "rgba(60,141,188,1)",
						pointHighlightFill: "#fff",
						pointHighlightStroke: "rgba(60,141,188,1)",
						data: [28, 48, 40, 19, 86, 27, 90]
					}
				]
			};
			var barChartCanvas = $("#barChart").get(0).getContext("2d");
			var barChart = new Chart(barChartCanvas);
			var barChartData = areaChartData;
			barChartData.datasets[1].fillColor = "#00a65a";
			barChartData.datasets[1].strokeColor = "#00a65a";
			barChartData.datasets[1].pointColor = "#00a65a";
			var barChartOptions = {
			  scaleBeginAtZero: true,
			  scaleShowGridLines: true,
			  scaleGridLineColor: "rgba(0,0,0,.05)",
			  scaleGridLineWidth: 1,
			  scaleShowHorizontalLines: true,
			  scaleShowVerticalLines: true,
			  barShowStroke: true,
			  barStrokeWidth: 2,
			  barValueSpacing: 5,
			  barDatasetSpacing: 1,
			  legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
			  responsive: true,
			  maintainAspectRatio: true
			};
		
			barChartOptions.datasetFill = false;
			barChart.Bar(barChartData, barChartOptions);
		}
	});
});
</script-->
<?php } ?>