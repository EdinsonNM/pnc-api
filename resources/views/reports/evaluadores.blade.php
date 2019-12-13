<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
 </head>
<body>

<div class="panel panel-default">
  <div class="panel-heading panel-primary ">
    <h2>
    {{ $title }}
    </h2> 
  </div>
  <div class="panel-body">
  
    <div id="grid" style="width:100%">
      <table id="treeGrid" class="table tree-2 table-bordered table-striped table-condensed table-striped" style="width:100%;border-color:#000;" border='1'>
        <thead>
            <tr >

                <th class="grid-header" style="text-align: left;">Apellidos y Nombres</th>
                <th class="grid-header" style="text-align: center;">N° Documento</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Dirección</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Teléfono</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Celular</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Profesión</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Especialización</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Correo 1</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Correo 2</th>
                <th class="grid-header" style="text-align: center;">Empresa</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Cargo</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Dirección Empresa</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Distrito Empresa</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Teléfono Empresa</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Correo Empresa</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Fax Empresa</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Dispo. Viaje</th>
                
                <th class="grid-header text-center" style="text-align: center;width:200px;">Conflictos de Interes</th>
                <th class="grid-header text-center" style="text-align: center;width:200px;">Historial</th>
                
                
            </tr>
        </thead>
        <tbody>
        @foreach ($entities as $entity)
            <tr>
                <td>{{ $entity->apellidos }} {{ $entity->nombres }}</td>
                <td>{{ $entity->numdoc }}</td>
                <td>{{ $entity->direccion }}</td>
                <td>{{ $entity->telefono }}</td>
                <td>{{ $entity->celular }}</td>
                <td>{{ $entity->profesion }}</td>
                <td>{{ $entity->especializacion }}</td>
                <td>{{ $entity->email1 }}</td>
                <td>{{ $entity->email2 }}</td>
                <td>{{ $entity->empresa }}</td>
                <td>{{ $entity->cargo }}</td>
                <td>{{ $entity->direccionempresa }}</td>
                <td>{{ $entity->distritoemp }}</td>
                <td>{{ $entity->telefonoemp }}</td>
                <td>{{ $entity->emailemp }}</td>
                <td>{{ $entity->faxemp }}</td>
                <td>
                  @if ($entity->disponibleviaje==1 )
                    SI
                  @else
                    NO
                  @endif
                </td>
                <td>
                 {{ $entity->conflictos }}
                </td>
                <td>
                 {{ $entity->historial }}
                </td>
                                
            </tr>
         @endforeach
                
        </tbody>
    </table>

   </div>
  </div>



</div>
  

</body>
</html>

