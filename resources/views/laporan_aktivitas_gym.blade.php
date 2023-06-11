<!DOCTYPE html>
<html>
<head>
	<title>Laporan Pendapatan Tahunan</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>

	<div style="padding: 5px; width: 355px; height: 557px; border: solid black 1px;">
		<h7><b>
			{{$title}}
		</h7><br>
		<h7>
			{{$address_gym}}
		</h7><br><br>
        <h7><b>LAPORAN AKTIVITAS GYM</b></h7><br>
        <h7>Bulan : {{$bulan}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tahun : {{$tahun}}</h7><br>
        <h7>Tanggal cetak : {{$tanggal_cetak}}</h7><br>
		<table style="border: 1px solid black;">
            <tr>
                <th width="165px" style="border: 1px solid black; padding: 5px;">Tanggal</th>
                <th width="165px" style="border: 1px solid black; padding: 5px;">Jumlah Member</th>
            </tr>

            @foreach ($aktivitas_gym as $item)
                <tr>
                    <td style="border: 1px solid black; padding: 5px;">{{$item['date']}}</td>
                    <td style="border: 1px solid black; padding: 5px;">{{$item['jumlah_member']}}</td>
                </tr>
            @endforeach

                <tr>
                    <td style="border: 1px solid black; padding: 5px;">Total</td>
                    <td style="border: 1px solid black; padding: 5px;">{{$total}}</td>
                </tr>
        </table>
	</div>
    <div id="columnchart_values" style="width: 900px; height: 300px;"></div>
 
</body>
</html>