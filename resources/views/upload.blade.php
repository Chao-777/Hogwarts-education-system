@extends('layouts.master')

@section('title', 'Upload Course File')

@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container">
    <div class="card mt-4">
        <div class="card-header">
            <h3>Upload Course File</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('home.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="course_file" class="form-label">Select Course File (Text File)</label>
                    <input type="file" name="course_file" id="course_file" class="form-control" accept=".txt" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
</div>
@endsection