@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
        @foreach ($friends as $friend)
            <div class="friend-card">
                {{-- <img src="{{ $friend->userDetail->photo }}" alt="User photo" class="user-photo"> --}}
                <p>{{ $friend->detail->first_name }} {{ $friend->detail->last_name }}</p>
                {{-- <p>Friend since {{ $friend->pivot->created_at->format('d M, Y') }}</p> --}}
                <form action="{{ route('unfriend', $friend->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Unfriend</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
