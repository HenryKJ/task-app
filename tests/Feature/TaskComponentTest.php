<?php

namespace Tests\Feature;

use App\Enums\Status;
use App\Livewire\TaskComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Models\Task;
use Tests\TestCase;

class TaskComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_task()
    {
        Livewire::test(TaskComponent::class)
            ->set('title', 'New Task')
            ->set('description', 'Description for the task')
            ->set('status', 'to_do')
            ->call('store');

        $this->assertTrue(Task::where('title', 'New Task')->exists());
    }

    /** @test */
    public function it_can_update_a_task()
    {
        $task = Task::create([
            'title' => 'Old Task',
            'description' => 'Old description',
            'status' => 'to_do',
        ]);

        Livewire::test(TaskComponent::class)
            ->set('title', 'Updated Task')
            ->set('description', 'Updated description')
            ->set('status', 'in_progress')
            ->set('taskId', $task->id)
            ->call('update');

        $this->assertEquals('Updated Task', $task->fresh()->title);
        $this->assertEquals('Updated description', $task->fresh()->description);
        $this->assertEquals(Status::IN_PROGRESS, $task->fresh()->status);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        $task = Task::create([
            'title' => 'Task to Delete',
            'description' => 'Description to delete',
            'status' => 'to_do',
        ]);

        Livewire::test(TaskComponent::class)
            ->call('delete', $task->id);

        $this->assertFalse(Task::where('title', 'Task to Delete')->exists());
    }
}
