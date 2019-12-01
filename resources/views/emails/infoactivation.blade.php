<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
</head>
<body>
	<h2>Activación de Usuario</h2>

	<div>
		Estimado {{$msg["usuario"]}}:<br>
		Se le informa que se ha realizado la activación de su cuenta. Puede ingresar y loguearse desde la siguiente direccion:<br>
		<br>
		<a href="{{env('APP_URL')}}/#/access/signin">{{env('APP_URL')}}/#/access/signin</a>

		<br>
		Atentamente,<br>
		Centro de Desarrollo Industrial



	</div>
</body>
