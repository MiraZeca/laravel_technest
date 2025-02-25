@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Import Products</h1>
        <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="csv_file">Upload CSV File</label>
                <input type="file" name="csv_file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Import</button>
        </form>
    </div>
@endsection
