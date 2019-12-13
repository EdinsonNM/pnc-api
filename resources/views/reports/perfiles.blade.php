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

                <th class="grid-header" style="text-align: left;">ID</th>
                <th class="grid-header" style="text-align: center;">NOMBRE</th>

                
            </tr>
        </thead>
        <tbody>
        @foreach ($entities as $entity)
            <tr>
                <td>{{ $entity->id }} </td>
                <td>{{ $entity->name }}</td>
                
            </tr>
         @endforeach
                
        </tbody>
    </table>

   </div>
  </div>



</div>
  

</body>
</html>

