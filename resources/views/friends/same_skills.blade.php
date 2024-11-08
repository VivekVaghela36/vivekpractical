@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('message'))
            <div class="alert alert-info">{{ session('message') }}</div>
        @endif
        @foreach ($users as $user)
            <div class="user-card">
                {{-- <img src="{{ $user->userDetail->photo }}" alt="User photo" class="user-photo"> --}}
                <p>{{ $user->detail->first_name }} {{ $user->detail->last_name }}</p>
                <p>Skills: {{ $user->skills->pluck('name')->join(', ') }}</p>
                <form action="{{ route('sendFriendRequest', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Send Friend Request</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
