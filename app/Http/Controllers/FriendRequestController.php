<?php

namespace App\Http\Controllers;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use DB;

class FriendRequestController extends Controller
{
    public function viewFriendRequests()
    {
        $requests = auth()->user()->receivedFriendRequests()->with('sender')->get();
        return view('friends.requests', compact('requests'));
    }

    // Send a friend request
    public function sendFriendRequest($receiverId)
    {
        $sender = auth()->user();

        if ($sender->hasSentFriendRequestTo($receiverId) || $sender->friends()->where('friend_id', $receiverId)->exists()) {
            return redirect()->back()->withErrors('You have already sent a request or are already friends.');
        }

        FriendRequest::create([
            'send_by' => $sender->id,
            'send_to' => $receiverId,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Friend request sent successfully.');
    }

    // Accept a friend request
    public function acceptFriendRequest($requestId)
    {
        $friendRequest = FriendRequest::findOrFail($requestId);

        if ($friendRequest->status !== 'pending') {
            return redirect()->back()->withErrors('Request is no longer pending.');
        }

        $friendRequest->update(['status' => 'accepted']);

        // Add both ways to the friends table
        \DB::table('friends')->insert([
            ['user_id' => $friendRequest->send_by, 'friend_id' => $friendRequest->send_to],
            ['user_id' => $friendRequest->send_to, 'friend_id' => $friendRequest->send_by]
        ]);

        return redirect()->back()->with('success', 'Friend request accepted.');
    }

    // Cancel a sent friend request
    public function cancelFriendRequest($requestId)
    {
        $friendRequest = FriendRequest::where('id', $requestId)->where('send_by', auth()->id())->firstOrFail();
        $friendRequest->delete();

        return redirect()->back()->with('success', 'Friend request canceled.');
    }

    // Unfriend a user
    public function unfriend($friendId)
    {
        $user = auth()->user();

        \DB::table('friends')->where([
            ['user_id', $user->id],
            ['friend_id', $friendId]
        ])->orWhere([
            ['user_id', $friendId],
            ['friend_id', $user->id]
        ])->delete();

        return redirect()->back()->with('success', 'You have unfriended the user.');
    }

    // List users with the same skills
    public function usersWithSameSkills()
    {
        $userSkills = auth()->user()->skills->pluck('id');

        $users = User::whereHas('skills', function ($query) use ($userSkills) {
            $query->whereIn('skills.id', $userSkills);
        })->where('id', '!=', auth()->id())->with('detail')->get();

        return view('friends.same_skills', compact('users'));
    }

    // List friends of logged-in user
    public function listFriends()
    {
        $friends = auth()->user()->friends()->with('detail')->get();
        return view('friends.list', compact('friends'));
    }
}
