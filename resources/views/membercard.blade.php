<!DOCTYPE html>
<html>
<head>
	<title>Membercard Gofit</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>

	<div style="padding: 5px; width: 300px; height: 200px; border: solid black 1px;">
		<h7><b>{{$title}}</b></h7><br>
		<h7>{{$address_gym}}</h7><br><br>
		<h6><b>{{$subtitle}}</b></h6>
		<h7>Member ID	: {{$member_id}}</h7><br>
		<h7>Nama		: {{$nama}}</h7><br>
		<h7>Alamat		: {{$alamat}}</h7><br>
		<h7>Telpon		: {{$telpon}}</h7>
	</div>
 
</body>
</html>