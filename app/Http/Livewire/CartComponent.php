<?php

namespace App\Http\Livewire;

use App\Models\Coupon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

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

    public function switchToSaveForLater($rowId)
    {
        $item = Cart::instance('cart')->get($rowId);
        Cart::instance('cart')->remove($rowId);
        Cart::instance('saveForLater')->add($item->id,$item->name,1,$item->price)->associate('App\Models\Product');
        $this->emitTo('cart-count-component','refreshComponent');
        session()->flash('success_message','Item has been saved for later');
    }
    public function moveToCart($rowId)
    {
        $item = Cart::instance('saveForLater')->get($rowId);
        Cart::instance('saveForLater')->remove($rowId);
        Cart::instance('cart')->add($item->id,$item->name,1,$item->price)->associate('App\\Models\\Product');
        $this->emitTo('cart-count-component','refreshComponent');
        session()->flash('s_success_message','Item has been moved to cart');
    }

    public function deleteFromSaveForLater($rowId)
    {
        Cart::instance('saveForLater')->remove($rowId);
        session()->flash('s_success_message','Item has been removed from save for later');
    }
    public $haveCouponCode;
    public $couponCode;
    public $subtotalAfterDiscount;
    public $taxAfterDiscount;
    public $totalAfterDiscount;
    public function applyCouponCode()
    {
        $coupon = Coupon::where('code',$this->couponCode)->where('expiry_date','>=', Carbon::today())->where('cart_value','<=',Cart::instance('cart')->subtotal())->first();
        if (is_null($coupon)) { {
                session()->flash('coupon_message', 'Coupon code is invalid!');

            }
        } else {
            session()->put('coupon',[
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'cart_value' => $coupon->cart_value
            ]);
            $this->calculateDiscounts();
        }

    }

    public function calculateDiscounts()
    {
        if (session()->get('coupon')['type'] == 'fixed') {
            $this->subtotalAfterDiscount = Cart::instance('cart')->subtotal() - session()->get('coupon')['value'];
            // dd($this->subtotalAfterDiscount);
        } else {
            $this->subtotalAfterDiscount = (Cart::instance('cart')->subtotal() * session()->get('coupon')['value']) / 100;
        }
        $this->taxAfterDiscount =  ($this->subtotalAfterDiscount * Cart::tax()) / 100;
        $this->totalAfterDiscount = $this->subtotalAfterDiscount + $this->taxAfterDiscount;

    }

    public function removeCoupon()
    {
        session()->forget('coupon');
    }

    public function checkout()
{
	if (Auth::user()) {
        if (Session::has('coupon')) {
            session()->put('checkout', [
                'discount' => session('coupon')['value'],
                'subtotal' => $this->subtotalAfterDiscount,
                'tax' => $this->taxAfterDiscount,
                'total' => $this->totalAfterDiscount,
            ]);
        } else {
            session()->put('checkout', [
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'total' => Cart::instance('cart')->total()
            ]);
        }

        return redirect()->route('checkout');
        // dd(session('coupon'));
    } else {
        return redirect()->route('login');
    }
}

    public function render()
    {

        if (Session::has('coupon')) {
            if (Cart::instance('cart')->subtotal() < session()->get('coupon')['value']) {

                session()->forget('coupon');
            } else {
                $this->handleCouponValid();
            }
        }
        $products = Cart::instance('cart')->content();
        return view('livewire.cart-component', [
            'products' => $products,
        ])->layout('layouts.base');

    }
}
