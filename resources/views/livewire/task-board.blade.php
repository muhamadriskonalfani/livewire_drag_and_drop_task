<div>
    <div>
        <h5>Task Board</h5>

        @if ($openForm)
            <div class="mt-3">
                <form wire:submit.prevent="createTask" style="width: 400px;">
                    <div class="mb-3">
                        <label for="title">Task</label>
                        <input type="text" id="title" class="form-control" wire:model="title" placeholder="Input title" required>
                        @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description">Description</label>
                        <input type="text" id="description" class="form-control" wire:model="description" placeholder="Input description" required>
                        @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Save Task</button>
                    </div>
                </form>
            </div>
        @else
            <div class="mt-3">
                <button type="button" class="btn btn-primary" wire:click="$set('openForm', true)">Create Task</button>
            </div>
            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="bg-dark border rounded p-2" style="height: 450px;">
                        <p class="fw-bold">To Do</p>
                        <div class="drop-zone" data-status="To Do" style="height: 100%;">
                            @foreach ($tasks as $task)
                                @if ($task->status === 'To Do')
                                    <div class="task-card card bg-dark border text-white mb-2 p-2" draggable="true" data-id="{{ $task->id }}">
                                        <div><strong>{{ $task->title }}</strong></div>
                                        <div><small>{{ $task->description }}</small></div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-dark border rounded p-2" style="height: 450px;">
                        <p class="fw-bold">In Progress</p>
                        <div class="drop-zone" data-status="In Progress" style="height: 100%;">
                            @foreach ($tasks as $task)
                                @if ($task->status === 'In Progress')
                                    <div class="task-card card bg-dark border text-white mb-2 p-2" draggable="true" data-id="{{ $task->id }}">
                                        <div><strong>{{ $task->title }}</strong></div>
                                        <div><small>{{ $task->description }}</small></div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-dark border rounded p-2" style="height: 450px;">
                        <p class="fw-bold">Revision</p>
                        <div class="drop-zone" data-status="Revision" style="height: 100%;">
                            @foreach ($tasks as $task)
                                @if ($task->status === 'Revision')
                                    <div class="task-card card bg-dark border text-white mb-2 p-2" draggable="true" data-id="{{ $task->id }}">
                                        <div><strong>{{ $task->title }}</strong></div>
                                        <div><small>{{ $task->description }}</small></div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-dark border rounded p-2" style="height: 450px;">
                        <p class="fw-bold">Done</p>
                        <div class="drop-zone" data-status="Done" style="height: 100%;">
                            @foreach ($tasks as $task)
                                @if ($task->status === 'Done')
                                    <div class="task-card card bg-dark border text-white mb-2 p-2" draggable="true" data-id="{{ $task->id }}">
                                        <div><strong>{{ $task->title }}</strong></div>
                                        <div><small>{{ $task->description }}</small></div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropZones = document.querySelectorAll('.drop-zone');

        dropZones.forEach(dropZone => {
            new Sortable(dropZone, {
                group: 'shared',
                animation: 150,
                onAdd: function (evt) {
                    const taskId = evt.item.dataset.id;
                    const newStatus = evt.to.dataset.status;

                    // Panggil Livewire method
                    Livewire.dispatch('updateTaskStatus', {
                        taskId: taskId,
                        newStatus: newStatus
                    });
                }
            });
        });
    });
</script>
@endpush
