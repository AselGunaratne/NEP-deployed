@extends('index')

@section('reporting') 
<div class="container">
    <div class="row p-1 bg-white">
        <div class="col border border-muted rounded-lg mr-1 p-2" style="width:50vw">
            <!-- top -->
            <canvas id="TreeRemovalAreaChart"></canvas>
            <a id="chart1" download="TreeRemovalChartImage.png" href="" class="btn btn-primary float-right bg-flat-color-1">
                <!-- Download Icon -->
                <i class="fa fa-download"></i> Download
            </a>
        </div>
    </div>
    <div class="row p-4 bg-white">
        <div class="col border border-muted rounded-lg mr-1 p-2">
            <!-- bottom left -->
            <canvas id="ProvinceTreeRemovalPieChart" ></canvas>
            <a id="chart2" download="ProvinceTreeRemovalPieChartImage.png" href="" class="btn btn-primary float-right bg-flat-color-1">
                <!-- Download Icon -->
                <i class="fa fa-download"></i> Download
            </a>
        </div>
        <div class="col border border-muted rounded-lg mr-1 p-2">
            <!-- bottom left -->
            <canvas id="DistrictTreeRemovalPieChart"></canvas>
            <a id="chart3" download="DistrictTreeRemovalPieChartImage.png" href="" class="btn btn-primary float-right bg-flat-color-1">
                <!-- Download Icon -->
                <i class="fa fa-download"></i> Download
            </a>
        </div>
    </div>
</div>
<script>
//Tree Removal Charts

//Download Monthly Tree Removal Chart Image
document.getElementById("chart1").addEventListener('click', function () {
	/*Get image of canvas element*/
	var url_base64jp = document.getElementById("TreeRemovalAreaChart").toDataURL("image/png");
	/*get download button (tag: <a></a>) */
	var a = document.getElementById("chart1");
	/*insert chart image url to download button (tag: <a></a>) */
	a.href = url_base64jp;
}); 

//Download Tree Removals requests by Province Pie Chart Image
document.getElementById("chart2").addEventListener('click', function () {
	/*Get image of canvas element*/
	var url_base64jp = document.getElementById("ProvinceTreeRemovalPieChart").toDataURL("image/png");
	/*get download button (tag: <a></a>) */
	var a = document.getElementById("chart2");
	/*insert chart image url to download button (tag: <a></a>) */
	a.href = url_base64jp;
}); 

//Download Tree Removals per district Pie Chart Image
document.getElementById("chart3").addEventListener('click', function () {
	/*Get image of canvas element*/
	var url_base64jp = document.getElementById("DistrictTreeRemovalPieChart").toDataURL("image/png");
	/*get download button (tag: <a></a>) */
	var a = document.getElementById("chart3");
	/*insert chart image url to download button (tag: <a></a>) */
	a.href = url_base64jp;
});
</script>
@endsection