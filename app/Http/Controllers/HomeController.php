<?php

namespace App\Http\Controllers;
use App\Models\Product;

class HomeController extends Controller
{
    function home() {
        return view('welcome');
    }

    function products() {
        $data = Product::with(['primaryImage', 'images', 'variants'])->paginate(8);
        return view('pages.product.products', compact('data'));
    }

    function about() {
        return view("pages.about");
    }
    
    function detailProduct($id) {
        $product = Product::where("id", $id)->with(['images', 'variants'])->firstOrFail();
        return view("pages.product.detail", compact("product"));
    }
}