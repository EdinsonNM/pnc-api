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

                <th class="grid-header" style="text-align: left;">Razon Social</th>
                <th class="grid-header text-center" style="text-align: center;">RUC</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Dirección</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Teléfono</th>
                <th class="grid-header text-center" style="text-align: center;width:180px;">Web</th>
                <th class="grid-header text-center" style="text-align: center;width:120px;">Fax</th>
                
                
                
                
            </tr>
        </thead>
        <tbody>
        @foreach ($entities as $entity)
            <tr>
                <td>{{ $entity->razonsocial }}</td>
                <td>{{ $entity->ruc }}</td>
                <td>{{ $entity->direccion }}</td>
                <td>{{ $entity->telefono }}</td>
                <td>{{ $entity->web }}</td>
                <td>{{ $entity->fax }}</td>
                                
            </tr>
         @endforeach
                
        </tbody>
    </table>

   </div>
  </div>



</div>
  

</body>
</html>

