@extends('adminlte::page')

@section('title', 'Products List')

@section('content_header')
<h1>
    {{ __('Products List') }}
</h1>
@stop

@section('content')
@if (session('status'))
<div class="alert alert-success" role="alert">
    {{ session('status') }}
</div>
@endif
<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Products</h3>

        <div class="card-tools">
            <form action="{{route('products.sync')}}" method="post">
                @csrf
                <button type="submit" class="btn btn-tool">
                    <i class="fas fa-sync"></i>
                </button>
                <a href={{route('products.create')}} class="btn btn-tool">
                    <i class="fas fa-plus"></i>
                </a>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped projects">
            <thead>
                <tr>
                    <th>
                        Title
                    </th>
                    <th>
                        Descriptions
                    </th>
                    <th>
                        Buy
                    </th>
                    <th>
                        Sell
                    </th>
                    <th style="width: 8%" class="text-center">
                        Picture
                    </th>
                    <th style="width: 30%" class="text-center">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $item)
                <tr>
                    <td>
                        {!! $item['title'] !!}
                    </td>
                    <td>
                        {!! Str::limit($item['description'], 20) !!}
                    </td>
                    <td>
                        @if (array_key_exists('sell', $item))
                        <span class="badge badge-warning"> ${{$item['sell']}}</span>
                        @else
                        <span class="badge badge-warning"> ${{round($item['price'] * 0.8)}}</span>
                        @endif
                    </td>
                    <td>
                        @if (array_key_exists('buy', $item))
                        <span class="badge badge-success"> ${{$item['buy']}}</span>
                        @else
                        <span class="badge badge-success"> ${{$item['price']}}</span>
                        @endif
                    </td>
                    <td class="project-state">
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                @if (array_key_exists('imageName', $item))
                                <img alt="Product image" class="table-avatar"
                                    src={{ asset('storage/'.$item['imageName']) }} />
                                @else
                                <img alt="Product image" class="table-avatar"
                                    src="https://dummyimage.com/600x400/000/fff" />
                                @endif
                            </li>
                        </ul>
                    </td>
                    <td class="project-actions text-right">
                        <form action={{route('products.destroy', ['product' => $item['id']])}} method="post">
                            <a class="btn btn-info btn-sm"
                                href={{ route('products.edit', ['product' => $item['id']]) }}>
                                <i class="fas fa-pencil-alt">
                                </i>
                                Edit
                            </a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash">
                                </i>
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
@stop

@section('css')
@stop

@section('js')
<script>
    console.log('Hi!');
</script>
@stop
