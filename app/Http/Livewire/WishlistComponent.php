<?php

namespace App\Http\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class WishlistComponent extends Component
{
    public function moveProductFromWishlistToCart($rowId)
    {
        $item = Cart::instance('wishlist')->get($rowId);
        Cart::instance('wishlist')->remove($rowId);
        Cart::instance('cart')->add($item->id,$item->name,1,$item->price)->associate('App\Models\Product');
        $this->emitTo('whishlist-count-component', 'refreshComponent');
        $this->emitTo('cart-count-component','refreshComponent');
    }
    public function render()
    {
        return view('livewire.wishlist-component')->layout('layouts.base');
    }
}
