<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    public function index(Request $request)
    {

        $search=$request->search;

        $suppliers=Supplier::query()
        ->when($search,function($q)use($search){

            $q->where('name','like',"%$search%")
            ->orWhere('phone','like',"%$search%");

        })
        ->latest()
        ->paginate(10);

        return view('suppliers.index',compact('suppliers','search'));

    }

    public function create()
    {

        return view('suppliers.create');

    }

    public function store(Request $request)
    {

        $data=$request->validate([
            'name'=>'required',
            'phone'=>'nullable',
            'address'=>'nullable',
            'notes'=>'nullable'
        ]);

        $data['created_by']=auth()->id();

        Supplier::create($data);

        return redirect()->route('suppliers.index')
        ->with('success','Supplier created');

    }

}