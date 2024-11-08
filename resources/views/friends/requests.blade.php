@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
        @foreach ($requests as $request)
            <div class="friend-request">
                {{-- <img src="{{ $request->sender->detail->photo }}" alt="User photo" class="user-photo"> --}}
                <p>{{ $request->sender->detail->first_name }} {{ $request->sender->detail->last_name }}</p>
                <form action="{{ route('acceptFriendRequest', $request->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Accept</button>
                </form>
                <form action="{{ route('cancelFriendRequest', $request->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
