<?php

namespace App\Http\Controllers;

class kosarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kosar = Kosar::all();
        return view('Kosar.index', compact('Kosar'));
    }

    /**
     * Show the form for creating a new resource. 
     */
    public function create()
    {
        return view ('Kosar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $return = $request->has('return');
        if($return){
            $request->merge(['return' => true]);
        }

        $request->validate(
            ['name' => 'required|min:3|max:255',],
            ['name.min' => 'A termék legalább 3 karakter hosszú legyen.',],
            ['return' => 'boolean',]
        );

        Kosar::create($request->all());

        return redirect()->route('Kosar.index')->with('success', 'A kosár tartalma sikeresen felvéve.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kosar = Kosar::find($id);
        return view('Kosar.show', compact('kosar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kosar = Kosar::find($id);
        return view('Kosar.edit', compact('kosar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            ['name' => 'required|min:3|max:255',],
            ['name.min' => 'A termék elnevezése legalább 3 karakter hosszú legyen.',]
        );

        //a kosár tulajdonságai jönnek ide
        $kosar = Kosar::find($id);
        $kosar->name = $request->name;
        $kosar->indulasVaros = $request->indulasVaros;
        $kosar->erkezesVaros = $request->erkezesVaros;
        $kosar->indulas = $request->indulas;
        $kosar->erkezes = $request->erkezes;
        $kosar->return = $request->return;
        $kosar->save();

        return redirect()->route('Kosar.index')->with('success', 'A kosár adatainak módosítása sikeresen megtörtént.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kosar = Kosar::find($id);
        $kosar->delete();

        return redirect()->route('Kosar.index')->with('success', 'A kosár törlése sikeresen megtörtént.');
    }
}
?>