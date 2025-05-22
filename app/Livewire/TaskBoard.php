<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
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
        $this->tasks = Task::orderBy('updated_at', 'desc')->get();
    }

    public function createTask()
    {
        $this->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            
            Task::create([
                'title' => $this->title,
                'description' => $this->description,
                'status' => 'To Do',
            ]);

            $this->reset(['title', 'description', 'openForm']);
            $this->loadTasks();
            $this->dispatch('initSortableJS');
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            // Kirim ke storage/logs/laravel.log untuk debugging developer 
            Log::error('Gagal membuat task: ' . $e->getMessage());

            // Berikan feedback umum ke user tanpa bocorkan detail teknis. Bisa juga ditampilkan pada console
            $this->dispatch('alert', 'Terjadi kesalahan saat membuat task. Silakan coba lagi.');
        }

        /*
        |--------------------------------------------------------------------------
        | Penanganan Error
        |--------------------------------------------------------------------------
        |
        | 1. $this->validate([ ... ]) untuk pencegahan
        | 2. try-catch untuk keamanan 
        | 3. log untuk debugging developer, alert atau console untuk feedback ke user
        | 4. database transaction untuk keamanan database, 
        |    jika ada yang error maka akan dibatalkan semua,
        |    biasa digunakan jika ada lebih dari 1 perubahan ke DB,
        |    menggunakan prinsip all-or-nothing,
        |    jika ada 1 error maka semua proses ke DB dibatalkan
        |
        */
    }

    public function render()
    {
        return view('livewire.task-board');
    }

    #[On('updateTaskStatus')]
    public function updateTaskStatus($taskId, $newStatus)
    {
        $validStatuses = ['To Do', 'In Progress', 'Revision', 'Done'];

        if (!in_array($newStatus, $validStatuses)) {
            $this->dispatch('alert', 'Status tidak valid.');
            return;
        }

        $task = Task::find($taskId);

        if (!$task) {
            $this->dispatch('alert', 'Task tidak ditemukan.');
            return;
        }
        
        if ($task) {
            $task->status = $newStatus;
            $task->save();
            $this->loadTasks();
            $this->dispatch('alert', 'Berhasil dipindahkan.');
        }
    }
}
