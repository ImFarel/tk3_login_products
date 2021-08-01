@extends('adminlte::page')

@section('title', 'Products List')

@section('content_header')
<h1>
    {{ __('Create') }}
</h1>
@stop

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Default box -->
<form action={{ route('products.update', ['product' => $data['id']]) }} method="post" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Add new</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="title">Product Title</label>
                <input type="text" id="title" class="form-control" value={{$data['title']}} name="title"
                    autocomplete="false">
            </div>
            <x-adminlte-textarea label="Product Description" name="description" rows=4 >
                {!!$data['description']!!}
            </x-adminlte-textarea>
            <div class="form-group">
                <label for="buy">Buy Price</label>
                <input type="text" id="buy" class="form-control" value={{$data['buy']}} name="buy" autocomplete="false">
            </div>
            <div class="form-group">
                <label for="sell">Sell Price</label>
                <input type="text" id="sell" class="form-control" value={{$data['sell']}} name="sell"
                    autocomplete="false">
            </div>
            {{-- With label and feedback disabled --}}
            <x-adminlte-input-file name="image" label="Image" placeholder="Choose an image..." disable-feedback />
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <a href={{ route('products.index') }} class="btn btn-secondary">Cancel</a>
            <input type="submit" value="Save" class="btn btn-success float-right">
        </div>
    </div>
</form>
@stop

@section('css')
@stop

@section('js')
<script>
    console.log('Hi!');
</script>
@stop
