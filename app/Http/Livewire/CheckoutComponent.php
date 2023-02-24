<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipping;
use App\Models\Transaction;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CheckoutComponent extends Component
{
    public $ship_to_different;
public $firstname;
public $lastname;
public $email;
public $mobile;
public $line1;
public $line2;
public $city;
public $province;
public $country;
public $zipcode;
public $s_firstname;
public $s_lastname;
public $s_email;
public $s_mobile;
public $s_line1;
public $s_line2;
public $s_city;
public $s_province;
public $s_country;
public $s_zipcode;
public $paymentmode;
public $thankyou;
 // Payment with Stripe
 public $card_no;
 public $exp_month;
 public $exp_year;
 public $cvc;
public function updated($fields)
{
	$this->validateOnly($fields,[
		'firstname' => 'required',
		'lastname' => 'required',
		'email' => 'required|email',
		'mobile' => 'required|numeric',
		'line1' => 'required',
		'city' => 'required',
		'province' => 'required',
		'country' => 'required',
		'zipcode' => 'required',
		'paymentmode' => 'required'
	]);

	if($this->ship_to_different)
	{
		$this->validateOnly($fields,[
			's_firstname' => 'required',
			's_lastname' => 'required',
			's_email' => 'required|email',
			's_mobile' => 'required|numeric',
			's_line1' => 'required',
			's_city' => 'required',
			's_province' => 'required',
			's_country' => 'required',
			's_zipcode' => 'required'
		]);
	}

}
public function makeTransaction($order_id,$status)
{
    $transaction = new Transaction();
    $transaction->order_id = $order_id;
    $transaction->user_id = Auth::user()->id;
    $transaction->mode = $this->paymentmode;
    $transaction->status = $status;
    $transaction->save();
}


public function placeOrder()
{
	$this->validate([
		'firstname' => 'required',
		'lastname' => 'required',
		'email' => 'required|email',
		'mobile' => 'required|numeric',
		'line1' => 'required',
		'city' => 'required',
		'province' => 'required',
		'country' => 'required',
		'zipcode' => 'required',
		'paymentmode' => 'required'
	]);
	$order =  new Order();
	$order->user_id = Auth::user()->id;
	$order->discount = session('checkout')['discount'];
    $order->subtotal = session('checkout')['subtotal'];
    $order->tax = session('checkout')['tax'];
    $order->total = session('checkout')['total'];
	$order->firstname = $this->firstname;
	$order->lastname = $this->lastname;
	$order->email = $this->email;
	$order->mobile = $this->mobile;
	$order->line1 = $this->line1;
	$order->line2 = $this->line2;
	$order->city = $this->city;
	$order->province = $this->province;
	$order->country = $this->country;
	$order->zipcode = $this->zipcode;
	$order->status = 'ordered';
	$order->is_shipping_different = $this->ship_to_different ? 1:0;
	$order->save();


	foreach(Cart::instance('cart')->content() as $item)
	{
		$orderItem = new OrderItem();
		$orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->price = $item->price;
            if($item->options){
                $orderItem->options = serialize($item->options);
            }


            $orderItem->quantity = $item->qty;
            $orderItem->save();

        }

        if($this->ship_to_different)
        {
            $this->validate([
                's_firstname' => 'required',
                's_lastname' => 'required',
                's_email' => 'required|email',
                's_mobile' => 'required|numeric',
                's_line1' => 'required',
                's_city' => 'required',
                's_province' => 'required',
                's_country' => 'required',
                's_zipcode' => 'required'
            ]);

            $shipping = new Shipping();
            $shipping->order_id = $order->id;
            $shipping->firstname = $this->s_firstname;
            $shipping->lastname = $this->s_lastname;
            $shipping->email = $this->s_email;
            $shipping->mobile = $this->s_mobile;
            $shipping->line1 = $this->s_line1;
            $shipping->line2 = $this->s_line2;
            $shipping->city = $this->s_city;
            $shipping->province = $this->s_province;
            $shipping->country = $this->s_country;
             $shipping->discount = session('checkout')['discount'];
            $shipping->subtotal = session('checkout')['subtotal'];
            $shipping->tax = session('checkout')['tax'];
            $shipping->total = session('checkout')['total'];
            $shipping->zipcode = $this->s_zipcode;
            $shipping->save();
        }


        if($this->paymentmode == 'cod')
        {
            $this->makeTransaction($order->id,'pending');
            $this->resetCart();
        } else if ($this->paymentmode == 'card') {
            $stripe = Stripe::make(env('STRIPE_KEY'));

            try {
                $token = $stripe->tokens()->create([
                    'card' => [
                        'number' => $this->card_no,
                        'exp_month' => $this->exp_month,
                        'exp_year' => $this->exp_year,
                        'cvc' => $this->cvc
                    ]
                ]);

                if (!isset($token['id'])) {
                    session()->flash('stripe_error', 'The stripe token was not generated correctly!');
                    $this->thankyou = 0;
                }

                $customer = $stripe->customers()->create([
                    'name' => $this->firstname . ' ' . $this->lastname,
                    'email' => $this->email,
                    'phone' => $this->mobile,
                    'address' => [
                        'line1' => $this->line1,
                        'postal_code' => $this->zipcode,
                        'city' => $this->city,
                        'state' => $this->province,
                        'country' => $this->country
                    ],
                    'shipping' => [
                        'name' => $this->firstname . ' ' . $this->lastname,
                        'address' => [
                            'line1' => $this->line1,
                            'postal_code' => $this->zipcode,
                            'city' => $this->city,
                            'state' => $this->province,
                            'country' => $this->country
                        ],
                    ],
                    'source' => $token['id']
                ]);

                $charge = $stripe->charges()->create([
                    'customer' => $customer['id'],
                    'currency' => 'USD',
                    'amount' => session()->get('checkout')['total'],
                    'description' => 'Payment for order no ' . $order->id
                ]);

                if ($charge['status'] == 'succeeded') {
                    $this->makeTransaction($order->id, 'approved');
                    $this->resetCart();
                } else {
                    session()->flash('stripe_error', 'Error in Transaction!');
                    $this->thankyou = 0;
                }
            } catch (Exception $e) {
                session()->flash('stripe_error', $e->getMessage());
                $this->thankyou = 0;
            }
        }
    }

    public function resetCart()
    {
        $this->thankyou = 1;
        Cart::instance('cart')->destroy();
        session()->forget('checkout');
    }
    public function verifyForCheckOut()
    {

        if (!Auth::user()) {
            return redirect()->route('login');
        } elseif ($this->thankyou) {
            return redirect()->route('thankyou');
        } elseif (!session()->get('checkout')) {
            return redirect()->route('shop');
        }
    }

    public function render()
    {
        $this->verifyForCheckOut();
        //  dd(Cart::instance('cart'),session('checkout'),22222);
        return view('livewire.checkout-component')->layout('layouts.base');
    }
}
