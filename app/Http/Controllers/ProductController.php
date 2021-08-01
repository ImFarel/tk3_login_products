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
            $get = Http::get('https://fakestoreapi.com/products?limit=5')->json();
            $return = [];
            foreach ($get as $value) {
                $return[] = [
                    'id' => $value['id'],
                    'title' => $value['title'],
                    'description' => $value['description'],
                    'sell' => round($value['price'] * 0.8),
                    'buy' => $value['price'],
                    'image' => $value['image'],
                ];
            }
            return $return;
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cache = Cache::get('products');
        $product = $cache[$id - 1];

        return view('products.edit')->with('data', $product);
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
        $validate = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'buy' => 'required|numeric',
            'sell' => 'required|numeric',
            'image' => 'nullable|file|max:1000',
        ]);
        try {
            $cache = Cache::get('products');
            $currect_product = $cache[$id - 1];
            $current_image = $currect_product['imageName'] ?? false;
            // dd(arra);
            // if current_image not the same as cached image
            if ($request->image && $current_image != $request->file('image')->getClientOriginalName()) {
                Storage::disk('local')->put('public/' . $request->file('image')->getClientOriginalName(), $request->file('image')->getContent());
                $currect_product['imageName'] = $request->file('image')->getClientOriginalName();
            }

            $currect_product['title'] = $request->title;
            $currect_product['description'] = $request->description;
            $currect_product['buy'] = $request->buy;
            $currect_product['sell'] = $request->sell;
            $new_cache = array_replace($cache, array(($id - 1) => $currect_product));

            Cache::put('products', $new_cache, now()->addHours(2));
        } catch (\Throwable $th) {
            dd($th);
            return redirect('products')->with('status', 'Error');
        }

        return redirect('products')->with('status', 'Saved!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cache = Cache::get('products');
        array_splice($cache, $id - 1, 1);
        // dd($cache);
        Cache::put('products', $cache, now()->addHours(2));
        return redirect('products')->with('status', 'Deleted !');
    }
}
