<?php

namespace App\Livewire;

use App\Enums\Status;
use App\Models\Task;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class TaskComponent extends Component
{
    use WithPagination;

    public $taskId;

    #[Rule('required|min:3|max:255')]
    public $title;

    #[Rule('required|min:3')]
    public $description;

    #[Rule(['required', new Enum(Status::class)])]
    public $status;

    public $isOpen = false;

    public function create(): void
    {
        $this->openModal();
    }

    public function store(): void
    {
        $this->validate();
        Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
        ]);
        session()->flash('success', 'Task created successfully.');

        $this->reset('title', 'description', 'status');
        $this->closeModal();
    }

    public function edit($id): void
    {
        $task = Task::findOrFail($id);
        $this->taskId = $id;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->status = $task->status;

        $this->openModal();
    }

    public function update(): void
    {
        if ($this->taskId) {
            $task = Task::findOrFail($this->taskId);
            $task->update([
                'title' => $this->title,
                'description' => $this->description,
                'status' => $this->status,
            ]);

            session()->flash('success', 'Task updated successfully.');
            $this->closeModal();
            $this->reset('title', 'description','status', 'taskId');
        }
    }

    public function delete($id): void
    {
        Task::find($id)->delete();
        session()->flash('success', 'Task deleted successfully.');
    }

    public function render()
    {
        return view('livewire.task-component', [
            'tasks' => Task::latest()->paginate(5),
        ]);
    }

    public function openModal(): void
    {
        $this->isOpen = true;
        $this->resetValidation();
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
    }

    public function dismiss(): void
    {
        session()->forget('success');
    }

    public function finishTask($id): void
    {
        $task = Task::findOrFail($id);
        $task->update([
            'status' => Status::DONE
        ]);
    }
}
