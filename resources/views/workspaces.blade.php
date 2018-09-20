<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title></title>
  <link rel="stylesheet" href="{{asset('workspacestyle/css/style.css')}}">
  <style type="text/css">
    .pagination
    {
      display: flex;
      flex-direction: row;
      justify-content: flex-start; 
      list-style: none;
      padding: 10 px;
      white-space: nowrap;
    }

    .pagination li>a{
      list-style-position:inside;
      padding: 4px;
      margin: 4px;
      color: #ffffff;
    }
  </style>
</head>

<body>
  <h1><span class="blue"></span>WorkSpace<span class="blue"></span> <span class="yellow">List</pan></h1>
  <h2><a href="{{route('workspaces.create')}}"> Create New WorkSpace</a> | <a href="{{route('users.create')}}" style="color:#4DC3FA"> Create New User</a> | <a href="{{route('logout')}}" style="color:#ffoooo"> LOGOUT</a></h2>

  <table class="container">
    <thead>
      <tr>
        <th><h1>WORKSPACE</h1></th>
        <th><h1>NOTES</h1></th>
        <th><h1>CREATED ON</h1></th>
        <th><h1>LAST UPDATED</h1></th>
        <th><h1>ACTION</h1></th>
      </tr>
    </thead>
    <tbody>


      @foreach($workspaces as $workspace)
      <tr>
        <td><a href="{{route('dashboard',$workspace->id)}}" style="text-decoration: none; color:#FB667A">{{$workspace->workspace}}</a></td>
        <td>{{$workspace->description}}</td>
        <td>{{$workspace->created_at}}</td>
        <td>{{$workspace->updated_at}}</td>
        <td><a href="{{route('deleteworkspace',$workspace->id)}}" style="text-decoration: none; color:#FB667A">DELETE</a></td>
      </tr>
      @endforeach
      @if($workspaces->links())

      <tr>
        <td style="list-style:none;height: 15px;background-color: #0E1119; padding-bottom: 0%;
        padding-top: 0%;
        padding-left: 2%;"> {{$workspaces->links()}}</td>
      </tr>
      @endif
    </tbody>
  </table>
  
  
</body>
</html>