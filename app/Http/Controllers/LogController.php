<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    // Display a listing of the logs
    public function index()
    {
        $logs = Log::all();

        return view('logs.index', compact('logs'));
    }

    // Show the form for creating a new log
    public function create()
    {
        return view('logs.create');
    }

    // Store a newly created log in the database
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'text' => 'required|string|max:255',
            'user' => 'required|string|max:100',
        ]);

        Log::create([
            'date' => $request->date,
            'text' => $request->text,
            'user' => $request->user,
        ]);

        return redirect()->route('logs.index')->with('success', 'Log created successfully!');
    }

    // Display the specified log
    public function show($id)
    {
        $log = Log::findOrFail($id);
        return view('logs.show', compact('log'));
    }

    // Show the form for editing the specified log
    public function edit($id)
    {
        $log = Log::findOrFail($id);
        return view('logs.edit', compact('log'));
    }

    // Update the specified log in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'text' => 'required|string|max:255',
            'user' => 'required|string|max:100',
        ]);

        $log = Log::findOrFail($id);
        $log->update([
            'date' => $request->date,
            'text' => $request->text,
            'user' => $request->user,
        ]);

        return redirect()->route('logs.index')->with('success', 'Log updated successfully!');
    }

    // Remove the specified log from the database
    public function destroy($id)
    {
        $log = Log::findOrFail($id);
        $log->delete();

        return redirect()->route('logs.index')->with('success', 'Log deleted successfully!');
    }
    
    public function newLog($user,$text){
        Log::create([
            'text' => $text,
            'user' => $user,
        ]);
    }
}
