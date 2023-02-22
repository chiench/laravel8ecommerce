<?php

namespace App\Http\Livewire\Admin;

use App\Models\ProductAttribute;
use Livewire\Component;

class AdminEditProductAttributeComponent extends Component
{
    public $name;
    public $productAttribute_id;

    public function mount($product_attribute_name)
    {
            $productAttribute = ProductAttribute::where('name',$product_attribute_name)->first();

            $this->name = $productAttribute->name;
            $this->productAttribute_id = $productAttribute->id;
    }



    public function updated($fields)
    {
        $this->validateOnly($fields,[
            'name' => 'required',

        ]);
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',

        ]);

        $productAttribute = ProductAttribute::find($this->productAttribute_id);
        $productAttribute->name = $this->name;
        $productAttribute->save();
        session()->flash('message','Product Attribute has been updated successfully!');
    }
    public function render()
    {
        return view('livewire.admin.admin-edit-product-attribute-component')->layout('layouts.base');
    }
}
