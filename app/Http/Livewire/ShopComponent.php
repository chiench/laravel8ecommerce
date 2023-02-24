<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use Livewire\WithPagination;

class ShopComponent extends Component
{


    public $sorting;
    public $pagesize;

    public $min_price=1;
    public $max_price=1000;

    public function mount()
    {
        $this->sorting = "default";
        $this->pagesize = 12;
    }
    public function store($product_id,$product_name,$product_price)
    {
        Cart::instance('cart')->add($product_id,$product_name,1,$product_price)->associate('App\Models\Product');
        session()->flash('success_message','Item added in Cart');
        $this->emitTo('cart-count-component', 'refreshComponent');

        return redirect()->route('product.cart');

    }
    public function addToWishlist($product_id,$product_name,$product_price)
    {
        Cart::instance('wishlist')->add($product_id,$product_name,1,$product_price)->associate('App\Models\Product');
        $this->emitTo('whishlist-count-component', 'refreshComponent');
    }
    public function removeFromWishlist($product_id)
    {
        foreach(Cart::instance('wishlist')->content() as $witem)
        {
            if($witem->id == $product_id)
            {
                Cart::instance('wishlist')->remove($witem->rowId);
                $this->emitTo('whishlist-count-component','refreshComponent');
                return;
            }
        }
    }
    public function render()
    {
        $categories = Category::all();
        {
            if($this->sorting=='date')
            {
                $products = Product::whereBetween('regular_price',[$this->min_price,$this->max_price])->orderBy('created_at','DESC')->paginate($this->pagesize);
            }
            else if($this->sorting=="price")
            {
                $products = Product::whereBetween('regular_price',[$this->min_price,$this->max_price])->orderBy('regular_price','ASC')->paginate($this->pagesize);
            }
            else if($this->sorting=="price-desc")
            {
                $products = Product::whereBetween('regular_price',[$this->min_price,$this->max_price])->orderBy('regular_price','DESC')->paginate($this->pagesize);
            }
            else{
                $products = Product::whereBetween('regular_price',[$this->min_price,$this->max_price])->paginate($this->pagesize);
            }

            $categories = Category::all();
            $witems = Cart::instance('wishlist')->content();
            $productAll = Product::all();

            return view('livewire.shop-component', [ 'witems' => $witems,'productAll'=> $productAll ,'products'=> $products,'categories'=>$categories])->layout("layouts.base");
        }
    }
}
