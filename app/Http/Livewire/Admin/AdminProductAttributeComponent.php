<?php

namespace App\Http\Livewire\Admin;

use App\Models\ProductAttribute;
use Livewire\Component;

class AdminProductAttributeComponent extends Component
{

    public function deleteAttribute($id)
    {
        $productAttr = ProductAttribute::find($id);
        $productAttr->delete();
        session()->flash('message','Product Attribute has been deleted successfully!');
    }
    public function render()
    {
        $productAttribues = ProductAttribute::paginate(5);
        return view('livewire.admin.admin-product-attribute-component',[
            'productAttribues' => $productAttribues,
        ]

        )->layout('layouts.base');
    }
}
