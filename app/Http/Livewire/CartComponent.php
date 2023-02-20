<?php

namespace App\Http\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class CartComponent extends Component
{
    public function increaseQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId,$qty);
        $this->emitTo('cart-count-component', 'refreshComponent');

    }

    public function decreaseQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId,$qty);
        $this->emitTo('cart-count-component', 'refreshComponent');

    }
    public function destroy($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        session()->flash('success_message','Item has been removed');
        $this->emitTo('cart-count-component', 'refreshComponent');

    }
    public function destroyAll()
{
	Cart::instance('cart')->destroy();
	session()->flash('success_message','All Item are removed');
    $this->emitTo('cart-count-component', 'refreshComponent');

}
    public function render()
    {
        return view('livewire.cart-component')->layout('layouts.base');
    }
}
