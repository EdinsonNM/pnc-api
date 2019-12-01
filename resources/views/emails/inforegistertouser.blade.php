		<table style="border-bottom:0px;width:748px;padding-bottom:5px;font-family:Arial,Helvetica,sans-serif;color:#000;border-spacing:0">
			<tr>
				<td style="color: #0070C0;">
				<img src="{{env('APP_URL')}}/img/pnc.png" alt="PNC y RGPM" class="img-responsive" width="200"><h2></h2>
				</td>
			</tr>
			<tr>
				<td style="color: #0070C0;text-align:center;">
					<h2>Confirmaci&oacute;n de registro y siguientes pasos</h2>
				</td>
			</tr>
			<tr>
				<td style="text-align:justify;">
					Hola  {{$msg["first_name"]}} {{$msg['last_name']}}, <br><br>

					Te damos la bienvenida al Software de Evaluaci&oacute;n del Premio Nacional a la Calidad y Reconocimiento a la Gesti&oacute;n de Proyectos de Mejora-Per&uacute;.
					<br><br>
					Esta herramienta te permitir&aacute; participar en las diferentes etapas del proceso de evaluaci&oacute;n, ya seas evaluador o postulante.
					<br><br>
					<table style="background:#DEEAF6;border: 1px solid #000;width: 100%;padding: 10px;display: block;text-align: justify;">
					<tr>
						<td>
							Deseamos asegurarnos que estas de acuerdo con este registro, para lo cual te pedimos que lo confirmes, haciendo click en el v&iacute;nculo que colocamos a continuaci&oacute;n: <br><br>
							<div style="text-align:center;">
							<a href="{{env('APP_URL')}}/#/access/activation/{{$msg['activation_code']}}" style="color: #0070C0;">
							{{env('APP_URL')}}/#/access/activation/{{$msg['activation_code']}}
							</a>
							</div>
						</td>
					</tr>

					</table>
					<div class="content">
					<br>Si el link no funciona, por favor copiar y pegar en la barra de direcci&oacute;n de tu navegador. <br>
					<br>No respondas este mensaje ya que ha sido generado de forma autom&aacute;tica.
					<br><br>
					Muchas gracias, <br>
					<strong>CDI - Secretaria T&eacute;cnica CGC</strong>
				</td>
			</tr>
		</table>

