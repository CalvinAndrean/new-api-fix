<!DOCTYPE html>
<html>
<head>
	<title>Laporan Pendapatan Tahunan</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
	<script type="text/javascript" src="{{asset('assets/js/jquery.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>	
	<script type="text/javascript" src="{{asset('assets/js/echarts.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.0"></script>
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>

	<div style="padding: 5px; width: 705px; height: 557px; border: solid black 1px;">
		<h7><b>
			{{$title}}
		</h7><br>
		<h7>
			{{$address_gym}}
		</h7><br><br>
        <h7><b>LAPORAN PENDAPATAN BULANAN</b></h7><br>
        <h7>PERIODE : &nbsp;&nbsp;: {{$periode}}</h7><br>
        <h7>Tanggal cetak&nbsp;&nbsp;&nbsp;&nbsp;: {{$tanggal_cetak}}</h7><br>
		<table style="border: 1px solid black;">
            <tr>
                <th width="165px" style="border: 1px solid black; padding: 5px;">Bulan</th>
                <th width="165px" style="border: 1px solid black; padding: 5px;">Aktivasi</th>
                <th width="165px" style="border: 1px solid black; padding: 5px;">Deposit</th>
                <th width="165px" style="border: 1px solid black; padding: 5px;">Total</th>
            </tr>

            @foreach ($data_passing as $item)
                <tr>
                    <td style="border: 1px solid black; padding: 5px;">{{$item['month']}}</td>
                    <td style="border: 1px solid black; padding: 5px;">{{$item['aktivasi']}}</td>
                    <td style="border: 1px solid black; padding: 5px;">{{$item['deposit']}}</td>
                    <td style="border: 1px solid black; padding: 5px;">{{$item['total_perbulan']}}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="3" style="align-text: right;" style="border: 1px solid black; padding: 5px;">Total</td>
                <td style="border: 1px solid black; padding: 5px;">{{$total}}</td>
            </tr>
        </table>
        <div>
            <canvas id="myChart" width="400" height="400"></canvas>
        </div>
	</div>
    </body>
    <script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const data = {!! json_encode($total_perbulan_1) !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                    'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ],
            datasets: [{
                label: 'Total Perbulan',
                data: data,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</html>