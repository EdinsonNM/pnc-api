<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>

    <style>
        body{
            font-family: sans-serif;
            font-size: 13px;
            padding: 25px;
        }
        h1{
            margin-bottom: 5px
        }
        h2{
            margin-bottom: 15px;
        }
        table{
            width: 100%;
            font-size: 12px;
        }
        hr.line-dashed{
            border-style:hidden ;
            border-bottom: 1px dashed;
            height: 2px;
            color: #ccc;
            margin-top: 15px;
            margin-bottom: 15px;
            position: relative;

        }
        .row{
            width: 100%;
            display: block;
        }
        .col-sm-6{
            width: 50%;
            float: left;
        }
        label{
            font-weight: bold;
        }
        table tr td{
            padding: 5px;
        }
        img {
            height: auto;
            max-width: 100%;
        }

        .table-bordered {
            border: 1px solid #DDD;
        }
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }
        table {
            background-color: transparent;
        }
        table {
            border-spacing: 0px;
            border-collapse: collapse;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #ddd !important;
        }

    </style>
</head>
<body>

<div class="panel panel-default">
  <div class="panel-heading panel-primary ">
    <h1>Ficha del Evaluador</h1>
    <small>Impreso el {{date('d/m/Y')}}</small>
    <hr class="line-dashed"/>

  </div>
  <div class="panel-body">

    <h2>Datos Evaluador</h2>

    <table>
       <tr>
           <td colspan="2">
               <img src="upload/user/{{$entity->usuario->imagen}}" alt="" width="250"/>
           </td>
       </tr>
        <tr>
              <td style="width:250px"><strong>Apellidos y Nombres:</strong></td>
              <td>{{$entity->apellidos}} {{$entity->nombres}}</td>

        </tr>
        <tr>
             <td style="width:250px"><strong>DNI:</strong></td>
             <td>{{$entity->numdoc}}</td>
        </tr>
        <tr>
              <td style="width:250px"><strong>Sexo:</strong></td>
              <td>{{$entity->sexo->nombre}}</td>

        </tr>
        <tr>
            <td style="width:250px"><strong>Profesión:</strong></td>
              <td>{{$entity->profesion}}</td>
        </tr>
        <tr>
              <td style="width:250px"><strong>Especialización:</strong></td>
              <td>{{$entity->especializacion}}</td>

        </tr>
        <tr>
              <td style="width:250px"><strong>Disponibilidad para viajar:</strong></td>
              <td>{{($entity->disponibleviaje==1) ? 'Si': 'No'}}</td>

        </tr>

    </table>
    <hr class="line-dashed"/>
    <h2>Datos Domicilio</h2>
    <table>
       <tr>
              <td style="width:250px"><strong>Dirección:</strong></td>
              <td>{{$entity->direccion}}</td>
        </tr>
        <tr>
              <td style="width:250px"><strong>Teléfono:</strong></td>
              <td>{{$entity->telefono}}</td>

        </tr>
        <tr>
             <td style="width:250px"><strong>Celular:</strong></td>
             <td>{{$entity->celular}}</td>
        </tr>
        <tr>
              <td style="width:250px"><strong>Sexo:</strong></td>
              <td>{{$entity->email1}}</td>

        </tr>
        <tr>
            <td style="width:250px"><strong>Profesión:</strong></td>
              <td>{{$entity->email2}}</td>
        </tr>

    </table>
    <hr class="line-dashed"/>
    <h2>Información del Trabajo</h2>
    <table>
       <tr>
              <td style="width:250px"><strong>Empresa:</strong></td>
              <td>{{$entity->empresa}}</td>
        </tr>
        <tr>
              <td style="width:250px"><strong>Dirección:</strong></td>
              <td>{{$entity->direccionempresa}}</td>

        </tr>
        <tr>
             <td style="width:250px"><strong>Cargo:</strong></td>
             <td>{{$entity->cargo}}</td>
        </tr>
        <tr>
              <td style="width:250px"><strong>Sexo:</strong></td>
              <td>{{$entity->distritoemp}}</td>

        </tr>
        <tr>
            <td style="width:250px"><strong>Teléfono:</strong></td>
              <td>{{$entity->telefonoemp}}</td>
        </tr>
        <tr>
            <td style="width:250px"><strong>Email:</strong></td>
              <td>{{$entity->emailemp}}</td>
        </tr>

    </table>
  </div>



</div>


</body>
</html>
