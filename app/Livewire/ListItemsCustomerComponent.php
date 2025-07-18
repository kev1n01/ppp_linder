<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class ListItemsCustomerComponent extends Component
{
    use WithPagination;

    public $selectedType = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSelectedType()
    {
        $this->resetPage(); 
    }

    public function render()
    {
        $query = Item::where('ite_status', true);

        if ($this->selectedType) {
            $query->where('ite_type', $this->selectedType);
        }

        return view('livewire.list-items-customer-component', [
            'items' => $query->paginate(6)
        ]);
    }
}
