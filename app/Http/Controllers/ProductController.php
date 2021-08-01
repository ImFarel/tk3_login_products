<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $response = Http::get('https://fakestoreapi.com/products?limit=5')->json();
        $response = Cache::remember('products', now()->addHours(2), function () {
            return Http::get('https://fakestoreapi.com/products?limit=5')->json();
        });
        // dd($response);
        debugbar()->info($response);
        return view('products.index')->with('data', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'buy' => 'required|numeric',
            'sell' => 'required|numeric',
            'image' => 'file|max:1000',
        ]);
        // dd($request->file('image')->getClientOriginalName(), $request->file('image')->getContent());
        $cache = Cache::get('products');
        Storage::disk('local')->put('public/' . $request->file('image')->getClientOriginalName(), $request->file('image')->getContent());
        $merged = $request->merge(['id' => (count($cache) + 1), 'imageName' => $request->file('image')->getClientOriginalName()]);

        array_push($cache, $merged->except(['_token', 'image']));
        Cache::put('products', $cache, now()->addHours(2));

        return redirect('products')->with('status', 'Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
