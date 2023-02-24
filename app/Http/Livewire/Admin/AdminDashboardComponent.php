<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use App\Models\User;


use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Livewire\Component;

class AdminDashboardComponent extends Component
{
    public  $orders;
    public  $totalSales;
    public  $totalRevenue ;
    public  $todaySales;
    public  $todayRevenue ;


    public function __construct()
    {
        $this->orders  = Order::orderBy('created_at','DESC')->get()->take(10);
        $this->totalSales = Order::where('status','delivered')->count();
        $this->totalRevenue= Order::where('status','delivered')->sum('total');
        $this->todaySales = Order::where('status','delivered')->whereDate('created_at',Carbon::today())->count();
        $this->todayRevenue = Order::where('status','delivered')->whereDate('created_at',Carbon::today())->sum('total');
    }

    public function generatePDF()
    {

        $data = [
            'date' => date('m/d/Y'),
            'orders' => $this->orders,
            'totalSales' => $this->totalSales,
            'totalRevenue' => $this->totalRevenue,
            'todaySales' => $this->todaySales,
            'todayRevenue' => $this->todayRevenue,
        ];


        $pdf = PDF::loadView('pdf.dashboard-pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        $fileName = 'data-dashboard-'.date('m/d/Y').'.pdf';
        return $pdf->stream($fileName);

    }
    public function render()
    {
        // $orders = Order::orderBy('created_at','DESC')->get()->take(10);
        // $totalSales = Order::where('status','delivered')->count();
        // $totalRevenue = Order::where('status','delivered')->sum('total');
        // $todaySales = Order::where('status','delivered')->whereDate('created_at',Carbon::today())->count();
        // $todayRevenue = Order::where('status','delivered')->whereDate('created_at',Carbon::today())->sum('total');
        return view('livewire.admin.admin-dashboard-component',[
            'orders'=> $this->orders,
            'totalSales'=> $this->totalSales,
            'totalRevenue'=> $this->totalRevenue,
            'todaySales' =>  $this->todaySales,
            'todayRevenue' =>  $this->todayRevenue
            ])->layout('layouts.base');
    }
}
