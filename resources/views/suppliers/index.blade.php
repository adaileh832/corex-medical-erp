@extends('layouts.app')

@section('content')

<h1>Suppliers</h1>

<a href="{{route('suppliers.create')}}" class="btn btn-primary mb-3">
Add Supplier
</a>

<table class="table table-bordered">

<thead>

<tr>
<th>Name</th>
<th>Phone</th>
<th>Address</th>
</tr>

</thead>

<tbody>

@foreach($suppliers as $supplier)

<tr>

<td>{{$supplier->name}}</td>
<td>{{$supplier->phone}}</td>
<td>{{$supplier->address}}</td>

</tr>

@endforeach

</tbody>

</table>

{{$suppliers->links()}}

@endsection