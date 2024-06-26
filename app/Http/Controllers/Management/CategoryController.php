<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use MongoDB\Driver\Session;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate(12);
        return view("management.category")->with('categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('management.createCategory');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories|max:255'
        ]);
        $category = new Category;
        $name = $request->name;
        $category->name = $name;
        $category->save();
        $request->session()->flash('status', $request->name. " is saved successfully");
        return(redirect('/management/category'));

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
        $category = Category::find($id);
        return view('management.editCategory')->with('category', $category);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:categories|max:255'
        ]);

        $category = Category::find($id);
        $category->name = $request->name;
        $category->save();
        $request->session()->flash('status', $request->name. " is updated successfully");
        return(redirect('/management/category'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Category::destroy($id);
        Session()->flash('status', 'The category is deleted successfully');
        return redirect('/management/category');
    }
}
