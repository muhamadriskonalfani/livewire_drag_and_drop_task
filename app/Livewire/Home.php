<?php

namespace App\Livewire;

use Livewire\Component;

class Home extends Component
{
    public $fileContent;
    public $openInformation = false;

    public function mount()
    {
        $filePath = base_path('livewire_drag_and_drop_task.txt');

        if (file_exists($filePath)) {
            $this->fileContent = file_get_contents($filePath);
        } else {
            $this->fileContent = "File tidak ditemukan.";
        }
    }
    
    public function render()
    {
        return view('livewire.home');
    }
}
