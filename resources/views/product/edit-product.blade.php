@extends('sidebar')
@section('title','Edit Product | E - Commerce App')
@section('product','active')
@section('edit-product')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<a href="{{ url('product/list') }}" class="btn btn-info my-3">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
<path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
</svg>
Back
</a>
<h1>Edit Product</h1>
<hr>
<form action="{{ url('product/edit/'.$data->id.'/process') }}" method="POST" class="col-md-8" enctype="multipart/form-data">
    @csrf
        <div class="mt-3 mb-4">
            <label for="name" class="form-label">Product Name</label>
            <input
            type="text"
            class="form-control"
            id="name"
            name="name"
            value="{{ $data->name }}"
            />
            @if ($errors->has('name'))
                <p class="text-danger fst-italic">{{ $errors->first('name') }}</p>
            @endif
            @if(session('unique'))
                <p class="text-danger fst-italic">{{session('unique')}}</p>
            @endif
        </div>
        <div class="mt-3 mb-4">
            <label for="name" class="form-label">Photo</label>
            <br>
            <div class="row">
                @foreach ($photos as $photo)
                <div class="card col me-3 mb-4" style="width: 15rem;">
                    <img src="{{ asset('storage/'.$photo->image) }}" class="card-img-top" alt="product-photo">
                    <div class="card-body">
                        <a href="{{ url('product/delete-photo/'.$photo->id) }}" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            @endforeach
            </div>
            <input type="file" name="files[]" multiple>
            <br>
            <p class="text-secondary"><i>*can multiple photos</i></p>
            @if ($errors->has('files'))
                <p class="text-danger fst-italic">{{ $errors->first('files') }}</p>
            @endif
            @if(session('format'))
                <p class="text-danger fst-italic">{{session('format')}}</p>
            @endif
            @if(session('deleted'))
                <p class="text-success fst-italic">{{session('deleted')}}</p>
            @endif
        </div>
        <div class="mt-3 mb-4">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" id="description" cols="7" rows="7">{{ $data->description }}</textarea>
            @if ($errors->has('description'))
                <p class="text-danger fst-italic">{{ $errors->first('description') }}</p>
            @endif
        </div>
        <div class="mt-3 mb-4">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" aria-label="Default select example" name="category_id" id="category_id">
                <option value="{{ $data->category_id }}" selected >{{ $data->category->name }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mt-3 mb-4">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" name="price" id="price" value="{{ $data->price }}">
            @if ($errors->has('price'))
                <p class="text-danger fst-italic">{{ $errors->first('price') }}</p>
            @endif
        </div>
        <div class="mt-3 mb-4">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" name="stock" id="stock" value="{{ $data->stock }}">
            @if ($errors->has('stock'))
                <p class="text-danger fst-italic">{{ $errors->first('stock') }}</p>
            @endif
        </div>
        <button type="submit" class="btn btn-primary mb-3">Edit Product</button>
</form>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
@endsection