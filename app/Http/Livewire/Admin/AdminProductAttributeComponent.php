<?php

namespace App\Http\Livewire\Admin;

use App\Models\ProductAttribute;
use Livewire\Component;

class AdminProductAttributeComponent extends Component
{
    public function render()
    {
        $productAttribues = ProductAttribute::paginate(5);
        return view('livewire.admin.admin-product-attribute-component',[
            'productAttribues' => $productAttribues,
        ]

        )->layout('layouts.base');
    }
}
