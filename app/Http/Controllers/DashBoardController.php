<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    // Display a list of skills
    public function index()
    {
        $user = auth()->user();

        // Retrieve the skills associated with the user
        $userSkills = $user->skills;
        return view('dashboard', compact('userSkills'));
    }
}
