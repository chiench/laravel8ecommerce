<?php

namespace App\Http\Livewire;

use App\Models\Contact;
use Livewire\Component;

class AdminContactComponent extends Component
{
    public function render()
    {
        $contacts = Contact::paginate(12);
        return view('livewire.admin-contact-component',['contacts'=>$contacts])->layout('layouts.base');
    }
}
