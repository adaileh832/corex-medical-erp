@extends('layouts.app')

@section('content')

<h1>Add Supplier</h1>

<form method="POST" action="{{route('suppliers.store')}}">

@csrf

<div class="mb-3">

<label>Name</label>

<input type="text" name="name" class="form-control">

</div>

<div class="mb-3">

<label>Phone</label>

<input type="text" name="phone" class="form-control">

</div>

<div class="mb-3">

<label>Address</label>

<input type="text" name="address" class="form-control">

</div>

<div class="mb-3">

<label>Notes</label>

<textarea name="notes" class="form-control"></textarea>

</div>

<button class="btn btn-success">

Save

</button>

</form>

@endsection