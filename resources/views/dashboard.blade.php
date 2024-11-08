@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <a href="{{ route('skills.create') }}" class="btn btn-primary">Add New Skill</a>
        <a href="{{ route('usersWithSameSkills') }}" class="btn btn-primary">Same Skill</a>
        <a href="{{ route('listFriends') }}" class="btn btn-primary">listFriends</a>
        <a href="{{ route('viewFriendRequests') }}" class="btn btn-primary">viewFriendRequests</a>
        <form action="{{ route('logout') }}" method="POST" class="d-inline-block">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if ($userSkills)
                    @foreach ($userSkills as $skill)
                        <tr>
                            <td>{{ $skill->name }}</td>
                            <td>
                                <a href="{{ route('skills.edit', $skill->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('skills.destroy', $skill->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>No Data Found</td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>
@endsection
