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

	<div style="padding: 5px; width: 350px; height: 330px; border: solid black 1px;">
		<h7><b>{{$title}}</b></h7><br>
		<h7>{{$address_gym}}</h7><br><br>
		<h7><b>{{$subtitle}}</b></h7><br>
        <h7>No Struk : {{$no_struk}}</h7><br>
        <h7>Tanggal : {{$tanggal}}</h7><br><br>
		<h7><b>Member</b>	: {{$member_id}} / {{$fullname_member}}</h7><br>
		<h7>Kelas		: {{$class_name}}</h7><br>
		<h7>Instruktur		: {{$fullname_instructor}}</h7><br>
		<h7>Sisa Deposit		: {{$remaining_deposit}}x</h7><br>
        <h7>Berlaku sampai		: {{$expired_date}}</h7>
	</div>
 
</body>
</html>