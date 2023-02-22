<?php

namespace App\Http\Livewire\Admin;

use App\Models\ProductAttribute;
use Livewire\Component;

class AdminAddProductAttributeComponent extends Component
{
    public $name;

    public function updated($fields)
    {
        $this->validateOnly($fields, [
            "name" => "required"
        ]);
    }
    public function store()
    {
        $this->validate([
            "name" => "required",
        ]);
        $productAttribute = new ProductAttribute();
        $productAttribute->name = $this->name;
        $productAttribute->save();

        session()->flash('message','Product Attribute has been created successfully!');

    }


    public function render()
    {
        return view('livewire.admin.admin-add-product-attribute-component')->layout('layouts.base');
    }
}
