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
    {{$concurso->nombreconcurso}}
        <small>
        {{$concurso->fechaInicio}} - {{$concurso->fechaFin}}
        </small>   
    </h2> 
  </div>
  <div class="panel-body">
      <h4>{{ $title }}</h4>
  
    <div id="grid" style="width:100%">
      <table id="treeGrid" class="table tree-2 table-bordered table-striped table-condensed table-striped" style="width:100%;border-color:#000;" border='1'>
        <thead>
            <tr >

                <th class="grid-header" style="text-align: left;width:70px;">Código</th>
                <th class="grid-header" style="text-align: center;">Descripción</th>
                <th class="grid-header" style="text-align: center;">Tipo</th>
                <th class="grid-header text-center" style="text-align: center;width:80px;">Puntaje</th>
                
            </tr>
        </thead>
        <tbody>
         
         @foreach ($entities as $entity)
            <tr>
                <td>{{ $entity->codigo }}&nbsp;</td>
                <td><b>{{ $entity->descripcion }}</b></td>
                <td>{{ $entity->tipoarbol }}</td>
                <td>{{ $entity->puntaje }}</td>
                
            </tr>
            @foreach ($entity->children as $entity2)
            <tr>
                <td>{{ $entity2->codigo }}&nbsp;</td>
                <td>&nbsp;&nbsp;{{ $entity2->descripcion }}</td>
                <td>{{ $entity2->tipoarbol }}</td>
                <td>{{ $entity2->puntaje }}</td>
                
            </tr>
              @foreach ($entity2->children as $entity3)
              <tr>
                <td>{{ $entity3->codigo }}&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $entity3->descripcion }}</td>
                <td>{{ $entity3->tipoarbol }}</td>
                <td>{{ $entity3->puntaje }}</td>

              </tr>
              
              @endforeach
            @endforeach
        @endforeach
        </tbody>
    </table>

   </div>
  </div>



</div>
  

</body>
</html>

