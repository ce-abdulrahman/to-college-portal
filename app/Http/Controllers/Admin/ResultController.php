<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResultDep;
use App\Models\System;
use App\Models\Province;
use App\Models\User;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $results = ResultDep::all();
        $systems = System::where('status', 1)->get();
        $provinces = Province::where('status', 1)->get();
        $users = User::where([
            'role' => 'student',
            'status' => 1
        ]);

        if($request->search){
            $users->where(function ($query) use ($request) {
                $query
                    ->where('name', 'LIKE', '%' . $request->search . '%');
            });
        }


        $users = $users->get();

        return view('website.web.admin.result.index', compact('results','systems','provinces','users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
