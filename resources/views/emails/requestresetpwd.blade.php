<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
</head>
<body>
	<h2>Cambio de Contraseña</h2>

	<div>
		Estimado {{$nombres}},<br>
		Recibimos una solicitud de cambio de contraseña. Para confirmar tu nueva contraseña haz click en el siguiente enlace:
		<br>
		<br>
		Usuario: {{$username}} <br>
		<a href="http://concurso.pnc.org.pe/#/access/changepwd/{{$resetcode}}">http://concurso.pnc.org.pe/#/access/changepwd/{{$resetcode}}</a>

		<br><br>
		Atentamente,<br>
		Centro de Desarrollo Industrial<br><br>

		<small>Por favor, ignora este mensaje en el caso que no hayas solicitado un cambio de contraseña de tu cuenta. </small>



	</div>
</body>