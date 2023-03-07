<?php

namespace App\Http\Livewire\User;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserDashboardComponent extends Component
{
    public  $orders ;


    public function __construct()
    {
        $this->orders = Order::orderBy('created_at','DESC')->where('user_id',Auth::user()->id)->take(5)->get();

    }
    public function generatePDF()
    {

        $data = [
            'date' => date('m/d/Y'),
            'orders' => $this->orders,

        ];


        $pdf = Pdf::loadView('pdf.user-dashboard-pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        $fileName = 'data-dashboard-'.date('m/d/Y').'.pdf';
        return $pdf->stream($fileName);

    }
    public function render()
    {
        $totalCost = Order::where('status','!=','canceled')->where('user_id',Auth::user()->id)->sum('total');
        $totalPurchase = Order::where('status','!=','canceled')->where('user_id',Auth::user()->id)->count();
        $totalDeliverd = Order::where('status','delivered')->where('user_id',Auth::user()->id)->count();
        $totalCanceled = Order::where('status','canceled')->where('user_id',Auth::user()->id)->count();
        return view('livewire.user.user-dashboard-component',['orders'=>$this->orders,'totalCost'=>$totalCost,'totalPurchase'=>$totalPurchase,'totalDeliverd'=>$totalDeliverd,'totalCanceled'=>$totalCanceled])->layout('layouts.base');
    }
}
