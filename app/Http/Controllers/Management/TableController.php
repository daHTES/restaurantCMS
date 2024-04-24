<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use MongoDB\Driver\Session;
use Nette\Utils\Random;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Table::all();
        return view('management.table')->with('tables', $tables);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('management.createTable');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tables|max:255'
        ]);

        $table = new Table();
        $table->name = $request->name;
        $table->save();
        $request->session()->flash('status', 'Table '. $request->name. ' is created successfully');
        return redirect('management/table');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $table = Table::find($id);
        return view('management.editTable')->with('table', $table);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:tables|max:255'
        ]);
        $table = Table::find($id);
        $table->name = $request->name;
        $table->save();
        $request->session()->flash('status', 'The table is updated to '.$request->name. ' successfully');
        return redirect('/management/table');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Table::destroy($id);
        Session()->flash('status', 'The Table is deleted successfully');
        return redirect('/management/table');
    }
}
