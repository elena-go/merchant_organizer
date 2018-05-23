@extends('layout')
@section('content')
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="{{ asset('js/filters.js') }}"></script>
<div id="user_overview_top_row" class="row top-row">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h2>Statistics</h2>
    </div>
</div>
@if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
<script>
$(function () {
$("#accordion").accordion({
collapsible: true,
        active: 0,
        animate: 200
        });
$("#accordion2").accordion({
collapsible: true,
        active: 999,
        heightStyle: "content"
        });
        $("#accordion3").accordion({
collapsible: true,
        active: 999,
        heightStyle: "content"
        });
var date = new Date();
var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
$('input[name="date"]').daterangepicker({
locale: {
format: 'YYYY-MM-DD'
        },
        startDate: firstDay,
        endDate: lastDay
        });
var f = new Filters('statistics_filters', {{ Session::get('user_id') }});
//f.clear();
var savedFilters = f.load();
for (var i in savedFilters) {
if (savedFilters[i] instanceof Array) {
for (var j in savedFilters[i]) {
if (!savedFilters[i][j].checked) {
$('[name="' + i + '"][value="' + savedFilters[i][j].name + '"]').removeAttr('checked');
}
}
} else {
$('[name="' + i + '"]').val(savedFilters[i]);
}
}
$('input[name="search"]').click(function () {
var obj = {};
$('.accordion-inner-wrapper input[type="text"], .accordion-inner-wrapper select').each(function () {
obj[$(this).attr('name')] = $(this).val();
});
$('.accordion-inner-wrapper input[type="checkbox"]').each(function () {
if (!obj[$(this).attr('name')])
        obj[$(this).attr('name')] = [];
obj[$(this).attr('name')].push({name: $(this).val(), checked: $(this).is(':checked')});
});
f.save(obj);
});
});
</script>
<div class="row">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 statistic_balance_wrapper">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <div id="accordion">
                <h3><span class="glyphicon glyphicon-cog  icon-filter"></span> Statistic Filter</h3>
                <div class="accordion-inner-wrapper">
                    <form method="GET" action="">
                        <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6">
                            <p class="p-filter"><span class="glyphicon glyphicon-calendar"></span> Filter by Date</p>
                            <input type="text" name="date">
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6">
                            <p class="p-filter"><span class="glyphicon glyphicon-briefcase"></span> Filter by Merchant</p>
                            <select name="merchant">
                                <option value="-">All merchants</option>
                                @foreach($merchants as $merch)
                                <option value="{{ $merch->merch_id }}">{{ $merch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6">
                            <input type="submit" name="search" class="filter-search" value="Search">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
            @if(!empty($count_wires_per_curr))
            <div class="row wire_count_wrapper">
                <table>
                    <tr>
                        <td>
                            <table class="balance_title_tbl">
                                <tr>
                                    <td>Currency:</td>
                                </tr>
                                <tr>
                                    <td>Total wires:</td>
                                </tr>
                            </table>
                        </td>
                        @foreach($count_wires_per_curr as $count)
                        @if(!empty($count->currency))
                        <td>
                            <table class="balance_values_tbl">
                                <tr>
                                    <td class="curr_title">{{ $count->currency }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $count->wire_count }}</td>
                                </tr>
                            </table>
                        </td>
                        @endif
                        @endforeach
                    </tr>
                </table>
            </div>
            @endif
            @if(!empty($sum_received_wires_per_curr))
            <div class="row balance_table_wrapper">
                <table>
                    <tr>
                        <td>
                            <table class="balance_title_tbl">
                                <tr>
                                    <td>Currency:</td>
                                </tr>
                                <tr>
                                    <td>Received:</td>
                                </tr>
                                <tr>
                                    <td>Fee:</td>
                                </tr>
                                <tr>
                                    <td>Total:</td>
                                </tr>
                            </table>
                        </td>
                        @foreach($sum_received_wires_per_curr as $received_amount)
                        @if(!empty($received_amount->currency))
                        <td>
                            <table class="balance_values_tbl">
                                <tr>
                                    <td class="curr_title">{{ $received_amount->currency }}</td>
                                </tr>
                                <tr>
                                    <td>{{ number_format(floatval($received_amount->total_received),2,".",",") }}</td>
                                </tr>
                                <tr>
                                    <td>{{ number_format(floatval($received_amount->total_percent),2,".",",") }}</td>
                                </tr>
                                <tr>
                                    <td>{{ number_format(floatval($received_amount->total_after_percent),2,".",",") }}</td>
                                </tr>
                            </table>
                        </td>
                        @endif
                        @endforeach
                        
                    </tr>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@if(!empty($sum_received_wires_per_merch))
<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
        <h2>Company's Details</h2>
        @if(!empty($sum_received_wires_per_merch))
        <div id="accordion2" class="statistics_info_accordion">
            @foreach($sum_received_wires_per_merch as $m_stat)
            @if(!empty($m_stat))
            <h3>{{ $m_stat[0]->m_name }}</h3>
            <div>
                @foreach($m_stat as $row)
                @if(!empty($row->total_received))
                <table>
                    <tr>
                        <td>Amount Received:</td>
                        <td>{{ number_format(floatval($row->total_received),2,".",",") }} {{ $row->currency }}</td>
                    </tr>
                    <tr>
                        <td>Amount of Fee:</td>
                        <td>{{ number_format(floatval($row->total_percent),2,".",",") }} {{ $row->currency }}</td>
                    </tr>
                    <tr>
                        <td>Amount after Fee:</td>
                        <td>{{ number_format(floatval($row->total_after_percent),2,".",",") }} {{ $row->currency }}</td>
                    </tr>
                </table>
                <hr>
                @endif
                @endforeach
            </div>
            @endif
            @endforeach
        </div>
        @else
        <div class="col-md-12 col-lg-10 col-lg-offset-1 col-sm-12 col-xs-12" style="background-color: #ffffff; height: 250px; margin-bottom: 3px;">
            <h1>No data...</h1>
        </div>
        @endif
    </div>
    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
        <h2>Bank's Details</h2>
        @if(!empty($sum_received_wires_per_bank))
        <div id="accordion3" class="statistics_info_accordion">
            @foreach($sum_received_wires_per_bank as $b_stat)
            @if(!empty($b_stat))
            <h3>{{ $b_stat[0]->b_name }}</h3>
            <div>
                @foreach($b_stat as $b)
                <table>
                    <tr>
                        <td>Currency:</td>
                        <td>{{ $b->currency }}</td>
                    </tr>
                    <tr>
                        <td>Amount Received:</td>
                        <td>{{ number_format(floatval($b->total_received),2,".",",") }} {{ $b->currency }}</td>
                    </tr>
                    <tr>
                        <td>Amount of Fee:</td>
                        <td>{{ number_format(floatval($b->total_percent),2,".",",") }} {{ $b->currency }}</td>
                    </tr>
                    <tr>
                        <td>Amount after Fee:</td>
                        <td>{{ number_format(floatval($b->total_after_percent),2,".",",") }} {{ $b->currency }}</td>
                    </tr>
                </table>
                @endforeach
            </div>
            @endif
            @endforeach
        </div>
        @else
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="background-color: #ffffff; height: 250px; margin-bottom: 3px;">
                <h1>No data...</h1>
            </div>
            @endif
    </div>
</div>
@else
<div class="col-md-12">
    <div class="col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1"><img src="{{ asset('images/no_data.png') }}" alt="Nothing to display..." title="Nothing to display..."/></div>
</div>
@endif
@endif
@stop