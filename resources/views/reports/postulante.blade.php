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
    <h1>Ficha del Postulante</h1>
    <small>Impreso el {{date('d/m/Y')}}</small>
    <hr class="line-dashed"/>

  </div>
  <div class="panel-body">

    <h2>Datos Postulante</h2>

    <table>
       <tr>
           <td colspan="2">

               <img src="{{ ($entity->usuario->imagen!=='') ? 'upload/user/'.$entity->usuario->imagen : 'img/default-user.png' }}"  width="250" />
           </td>
       </tr>
        <tr>
              <td style="width:250px"><strong>Razon Social:</strong></td>
              <td>{{$entity->razonsocial}}</td>

        </tr>
        <tr>
             <td style="width:250px"><strong>RUC:</strong></td>
             <td>{{$entity->ruc}}</td>
        </tr>
        <tr>
              <td style="width:250px"><strong>Direccion:</strong></td>
              <td>{{$entity->direccion}}</td>

        </tr>
        <tr>
            <td style="width:250px"><strong>Telefono:</strong></td>
              <td>{{$entity->telefono}}</td>
        </tr>
        <tr>
              <td style="width:250px"><strong>Web:</strong></td>
              <td>{{$entity->web}}</td>

        </tr>
        <tr>
              <td style="width:250px"><strong>Fax:</strong></td>
              <td>{{$entity->fax}}</td>
        </tr>
    </table>
    <hr class="line-dashed"/>
    <h2>Categorias</h2>
    <table style="width:100%">
        @foreach($entity->categorias as $categoria)
            <tr>
              <td>

              {{$categoria->catalogo->nombre}} -
                @if ($categoria->tipo === 1)
                    Grandes
                @elseif ($categoria->tipo === 2)
                    Itermedias
                @else
                    Pequeñas
                @endif

              </td>
            </tr>

        @endforeach


    </table>
    <hr class="line-dashed"/>
    <h2>Contactos</h2>
    <table class="table table-bordered">
      <thead>
          <th>Nombre</th>
          <th>Cargo</th>
          <th>Teléfono</th>
          <th>Fax</th>
          <th>Email</th>
      </thead>
       <tbody>
        @foreach($entity->contactos as $contacto)
            <tr>
              <td>

              {{$contacto->nombre}}

              </td>
              <td>

              {{$contacto->cargo}}

              </td>
              <td>

              {{$contacto->telefono}}

              </td>
              <td>

              {{$contacto->fax}}

              </td>
              <td>

              {{$contacto->email}}

              </td>
            </tr>

        @endforeach
        </tbody>

    </table>
  </div>



</div>


</body>
</html>
