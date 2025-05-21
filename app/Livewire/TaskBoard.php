<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;

class TaskBoard extends Component
{
    public $tasks;
    public $title, $description;
    public $openForm = false;

    public function mount()
    {
        $this->loadTasks();
    }

    public function loadTasks()
    {
        $this->tasks = Task::all();
    }

    public function createTask()
    {
        $this->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'status' => 'To Do',
        ]);

        $this->reset(['title', 'description', 'openForm']);
        $this->loadTasks();
    }

    public function render()
    {
        return view('livewire.task-board');
    }
}
