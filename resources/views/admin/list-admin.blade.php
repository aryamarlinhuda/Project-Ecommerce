@extends('sidebar')
@section('title','Admin List | E-Commerce App')
@section('admin','active')
@section('list-admin')
<h1>Admin List</h1>
<hr>
<form action="{{ url('admin/list') }}" method="get">
    <div class="input-group mb-3">
        <input class="form-control" type="search" name="katakunci" value="{{ Request::get('katakunci') }}" placeholder="Enter the admin name" aria-label="Search">
        <button class="btn btn-secondary" type="submit" id="button-addon2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
            Search
        </button>
    </div>
</form>
<div class="container">
    <a href="{{ url('admin/create') }}" class="btn btn-success mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
            <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>
            <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"></path>
        </svg>
        Create Admin
    </a>
    @if(session('success'))
    <div class="alert alert-success alert-block dismissible show fade mt-4">
        <div class="alert-body">
            {{session('success')}}
        </div>
    </div>
    @endif
    <table class="table table-light table-striped">
        <thead>
        <tr>
            <th scope="col">No.</th>
            <th scope="col">Name</th>
            <th scope="col">Photo</th>
            <th scope="col">Phone Number or Email</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
            <tr>
                <td>{{ $index + $data->firstItem() }}.</td>
                <td>{{ $item->name }}</td>
                @if($item->photo)
                <td><img src="{{ asset('storage/'.$item->photo) }}" height="80px" class="rounded-circle" alt="Photo Profile"></td>
                @else
                <td><img src="{{ asset('default.jpg') }}" height="80px" class="rounded-circle" alt="Default Photo Profile"></td>
                @endif
                <td>{{ $item->phone_or_email }}</td>
                <td>
                    <a href="{{ url('admin/edit/'.$item->id) }}" class="btn btn-primary ms-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                        </svg>
                    Edit
                    </a>
                    <form action="{{ url('admin/delete/'.$item->id) }}" method="POST"class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"></path>
                            </svg>    
                            Delete
                        </button>
                    </form>
                </td>
                </td>
            </tr>    
            @endforeach
        </tbody>
    </table>
    {{ $data->links() }}
    @if(count($data) === 0)
        <h1 class="text-center text-secondary"><i><b>No Data!</b></i></h1>
    @endif
</div>
@endsection