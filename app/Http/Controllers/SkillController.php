<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\UserSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SkillController extends Controller
{
    public function create()
    {
        return view('skills.create');
    }

    // Store a new skill
    public function store(Request $request)
    {
        // Define the validation rules
        $rules = [
            'name' => 'required|string',  // Ensure the name is unique in the skills table
        ];
        // $messages = [
        //     'name.required' => 'The skill name is required.',
        //     'name.unique' => 'This skill name has already been taken.',
        // ];
        // Create the validator
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {

            // Redirect back with errors
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // // If validation passes, create the skill
        // $skill = Skill::create($request->all());
        // Check if the skill already exists in the skills table
        $skill = Skill::where('name', $request->input('name'))->first();
        $user = Auth::user();
        if ($skill) {
            // Check if the user already has this skill
            $userHasSkill = $user->skills()->where('skill_id', $skill->id)->exists();

            if ($userHasSkill) {
                return redirect()->back()->withErrors(['name' => 'You already have this skill assigned.'])->withInput();
            }

            // Associate the existing skill with the user
            $user->skills()->attach($skill->id);
        } else {
            // If the skill does not exist, create it and associate it with the user
            $skill = Skill::create(['name' => $request->input('name')]);
            $user->skills()->attach($skill->id);
        }

        // Redirect to the dashboard with success message
        return redirect()->route('dashboard')->with('success', 'Skill added successfully!');
    }

    // Show the form to edit an existing skill
    public function edit($id)
    {
        $skill = Skill::findOrFail($id);
        return view('skills.edit', compact('skill'));
    }

    // Update an existing skill
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|unique:skills,name,' . $id,  // Exclude current skill from unique rule
        ];
        $messages = [
            'name.required' => 'The skill name is required.',
            'name.unique' => 'This skill name has already been taken.',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $skill = Skill::findOrFail($id);
        $skill->update($request->all());

        return redirect()->route('dashboard')->with('success', 'Skill updated successfully!');
    }

    // Delete an existing skill
    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return redirect()->route('dashboard')->with('success', 'Skill deleted successfully');
    }
}
