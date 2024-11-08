@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
        <h1>Add New Skill</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('skills.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Skill Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <!-- Display error message -->

            <button type="submit" class="btn btn-success mt-3">Add Skill</button>
        </form>
    </div>
@endsection
