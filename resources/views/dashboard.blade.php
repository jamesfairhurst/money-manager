@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">This Week</div>

                <div class="panel-body">
                    {{ number_format(App\Transaction::week()->sum('amount'), 2) }}
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">Last Week</div>

                <div class="panel-body">
                    {{ number_format(App\Transaction::lastWeek()->sum('amount'), 2) }}
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">This Month</div>

                <div class="panel-body">
                    {{ number_format(App\Transaction::month()->sum('amount'), 2) }}
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">Last Month</div>

                <div class="panel-body">
                    {{ number_format(App\Transaction::lastMonth()->sum('amount'), 2) }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">This Week</div>

                <div class="panel-body">
                    <div id="this-week"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">This Month</div>

                <div class="panel-body">
                    <div id="this-month"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawThisWeekChart);
google.charts.setOnLoadCallback(drawThisMonthChart);
function drawThisWeekChart() {
    var data = google.visualization.arrayToDataTable([
        ['Tag', 'Amount'],
@foreach ($tagsThisWeek as $tag)
        ['{{ $tag['name'] }}', {{ round($tag['amount'], 2) }}],
@endforeach
    ]);

    var options = {
        legend: 'none',
        pieSliceText: 'label',
        chartArea: {left:0,top:'5%',width:'100%',height:'90%'},
        colors: ['#50514F','#F25F5C','#FFE066','#247BA0','#70C1B3'],
        tooltip: {text: 'value'}
    };

    var chart = new google.visualization.PieChart(document.getElementById('this-week'));

    chart.draw(data, options);
}
function drawThisMonthChart() {
    var data = google.visualization.arrayToDataTable([
        ['Tag', 'Amount'],
@foreach ($tagsThisMonth as $tag)
        ['{{ $tag['name'] }}', {{ round($tag['amount'], 2) }}],
@endforeach
    ]);

    var options = {
        legend: 'none',
        pieSliceText: 'label',
        chartArea: {left:0,top:'5%',width:'100%',height:'90%'},
        colors: ['#50514F','#F25F5C','#FFE066','#247BA0','#70C1B3'],
        tooltip: {text: 'value'}
    };

    var chart = new google.visualization.PieChart(document.getElementById('this-month'));

    chart.draw(data, options);
}
</script>
@endpush
