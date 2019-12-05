<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
</head>
<body>
	<h2>Cambio de Contraseña</h2>
	<div>
		Estimado {{$msg["first_name"]}},<br>
		Recibimos una solicitud de cambio de contraseña. Para confirmar tu nueva contraseña haz click en el siguiente enlace:
		<br>
		<br>
		Usuario: {{$msg["username"]}} <br>
		<a href="{{env('APP_URL')}}/#/access/changepwd/{{$msg["reset_password_code"]}}">http://concurso.pnc.org.pe/#/access/changepwd/{{$msg["reset_password_code"]}}</a>

		<br><br>
		Atentamente,<br>
		Centro de Desarrollo Industrial<br><br>

		<small>Por favor, ignora este mensaje en el caso que no hayas solicitado un cambio de contraseña de tu cuenta. </small>



	</div>
</body>
