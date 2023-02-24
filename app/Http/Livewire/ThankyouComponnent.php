<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ThankyouComponnent extends Component
{
    public function sendMail()
    {
        $name = "Nguyễn Văn Chiến";
        $data = ['name' => 'Chiến'];
        Mail::send('mail.mail-order', compact(['data','name']), function ($message) use($name) {
            $message->from('john@johndoe.com', 'John Doe');
            // $message->sender('john@johndoe.com', 'John Doe');
            $message->to('nguyenchienb53@gmail.com', $name);

            $message->subject('Đây là tiêu đề gửi mail');

            // $message->attach('pathToFile');
        });
    }
    public function render()
    {
        return view('livewire.thankyou-componnent')->layout('layouts.base');
    }
}
