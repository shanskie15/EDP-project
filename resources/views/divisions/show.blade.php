<h1 class="card-title text-center">{{$division->name}}</h1>
<ul class="list-unstyled text-center">
  <li class="text-success">Area: <strong>{{$division->area}}</strong></li>
  <li class="text-muted"> Agent: <strong>{{$division->firstname}} {{$division->lastname}}</strong></li>
  
  <li class="text-muted">Number of stores: {{$division->population}}</li>
</ul>
