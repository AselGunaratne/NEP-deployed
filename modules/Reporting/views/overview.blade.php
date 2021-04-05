@extends('index')

@section('reporting')
<div class="container">
    <div class="row p-1 bg-white">
        <div class="col border border-muted rounded-lg mr-1 p-2" style="width:50vw">
            <!-- top -->
            <canvas id="ProcessItemsAreaChart"></canvas>
            <a id="download1" download="ProcessItemsAreaChartImage.png" href="" class="btn btn-primary float-right bg-flat-color-1">
                <!-- Download Icon -->
                <i class="fa fa-download"></i> Download
            </a>
        </div>
    </div>

    <div class="row p-1 bg-white" style="height:auto">
        <div class="col border border-muted rounded-lg mr-1 p-2 height:100%">
            <!-- middle left -->
            <canvas id="processItemTypeChart"></canvas>
            <a id="download2" download="processItemTypeChart.png" href="" class="btn btn-primary float-right bg-flat-color-1">
                <!-- Download Icon -->
                <i class="fa fa-download"></i> Download
            </a>
        </div>
        <div class="col border border-muted rounded-lg mr-1 p-2 height:100%"">
            <!-- middle right -->
            <canvas id="AssignedOrganizationChart"></canvas>
            <a id="download3" download="AssignedOrganizationChart.png" href="" class="btn btn-primary float-right bg-flat-color-1">
                <!-- Download Icon -->
                <i class="fa fa-download"></i> Download
            </a>
        </div>
    </div>
</div>
<script>
//Process Items Charts

//Download Monthly Process Items Chart Image
document.getElementById("download1").addEventListener('click', function () {
	/*Get image of canvas element*/
	var url_base64jp = document.getElementById("ProcessItemsAreaChart").toDataURL("image/png");
	/*get download button (tag: <a></a>) */
	var a = document.getElementById("download1");
	/*insert chart image url to download button (tag: <a></a>) */
	a.href = url_base64jp;
}); 

//Download Process Items Type Bar Chart Image
document.getElementById("download2").addEventListener('click', function () {
	/*Get image of canvas element*/
	var url_base64jp = document.getElementById("processItemTypeChart").toDataURL("image/png");
	/*get download button (tag: <a></a>) */
	var a = document.getElementById("download2");
	/*insert chart image url to download button (tag: <a></a>) */
	a.href = url_base64jp;
}); 

//Download Monthly Process Items Assigned Organization Pie Chart Image
document.getElementById("download3").addEventListener('click', function () {
	/*Get image of canvas element*/
	var url_base64jp = document.getElementById("AssignedOrganizationChart").toDataURL("image/png");
	/*get download button (tag: <a></a>) */
	var a = document.getElementById("download3");
	/*insert chart image url to download button (tag: <a></a>) */
	a.href = url_base64jp;
}); 
</script>
@endsection