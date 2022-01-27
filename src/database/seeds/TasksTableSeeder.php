<?php

use App\Models\Task;
use Illuminate\Database\Seeder;
use App\Enums\TaskPriority;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Task::create([
            'in_charge_user_id' => '4',
            'report_to_user_id' => '3',
            'task_status' => '0',
            'task_title' => 'タスク4-1',
            'task_content' => 'タスク4-1タスク4-1タスク4-1タスク4-1タスク4-1',
            'priority' => TaskPriority::HIGH,
            'due_date' => '2022-01-30',
        ]);

        Task::create([
            'in_charge_user_id' => '4',
            'report_to_user_id' => '3',
            'task_status' => '1',
            'task_title' => 'タスク4-2',
            'task_content' => 'タスク4-2タスク4-2タスク4-2タスク4-2タスク4-2',
            'priority' => TaskPriority::MIDDLE,
            'due_date' => '2022-02-30',
        ]);

        Task::create([
            'in_charge_user_id' => '4',
            'report_to_user_id' => '3',
            'task_status' => '0',
            'task_title' => 'タスク4-3',
            'task_content' => 'タスク4-3タスク4-3タスク4-3タスク4-3タスク4-3',
            'priority' => TaskPriority::LOW,
            'due_date' => '2022-03-30',
        ]);
        
        Task::create([
            'in_charge_user_id' => '5',
            'report_to_user_id' => '3',
            'task_status' => '0',
            'task_title' => 'タスク5-1',
            'task_content' => 'タスク5-1タスク5-1タスク5-1タスク5-1タスク5-1',
            'priority' => TaskPriority::HIGH,
            'due_date' => '2022-01-30',
        ]);

        Task::create([
            'in_charge_user_id' => '5',
            'report_to_user_id' => '3',
            'task_status' => '1',
            'task_title' => 'タスク5-2',
            'task_content' => 'タスク5-2タスク5-2タスク5-2タスク5-2タスク5-2',
            'priority' => TaskPriority::MIDDLE,
            'due_date' => '2022-02-30',
        ]);

        Task::create([
            'in_charge_user_id' => '6',
            'report_to_user_id' => '3',
            'task_status' => '0',
            'task_title' => 'タスク6-1',
            'task_content' => 'タスク6-1タスク6-1タスク6-1タスク6-1タスク6-1',
            'priority' => TaskPriority::LOW,
            'due_date' => '2022-03-30',
        ]);
        
        Task::create([
            'in_charge_user_id' => '6',
            'report_to_user_id' => '3',
            'task_status' => '1',
            'task_title' => 'タスク6-2',
            'task_content' => 'タスク6-2タスク6-2タスク6-2タスク6-2タスク6-2',
            'priority' => TaskPriority::HIGH,
            'due_date' => '2022-01-30',
        ]);

        Task::create([
            'in_charge_user_id' => '7',
            'report_to_user_id' => '3',
            'task_status' => '0',
            'task_title' => 'タスク7-1',
            'task_content' => 'タスク7-1タスク7-1タスク7-1タスク7-1タスク7-1',
            'priority' => TaskPriority::MIDDLE,
            'due_date' => '2022-02-30',
        ]);

        Task::create([
            'in_charge_user_id' => '8',
            'report_to_user_id' => '3',
            'task_status' => '1',
            'task_title' => 'タスク8-1',
            'task_content' => 'タスク8-1タスク8-1タスク8-1タスク8-1タスク8-1',
            'priority' => TaskPriority::LOW,
            'due_date' => '2022-03-30',
        ]);
        
        Task::create([
            'in_charge_user_id' => '8',
            'report_to_user_id' => '3',
            'task_status' => '0',
            'task_title' => 'タスク8-2',
            'task_content' => 'タスク8-2タスク8-2タスク8-2タスク8-2タスク8-2',
            'priority' => TaskPriority::HIGH,
            'due_date' => '2022-01-30',
        ]);

        Task::create([
            'in_charge_user_id' => '8',
            'report_to_user_id' => '3',
            'task_status' => '0',
            'task_title' => 'タスク8-3',
            'task_content' => 'タスク8-3タスク8-3タスク8-3タスク8-3タスク8-3',
            'priority' => TaskPriority::MIDDLE,
            'due_date' => '2022-02-30',
        ]);

        Task::create([
            'in_charge_user_id' => '3',
            'report_to_user_id' => '1',
            'task_status' => '0',
            'task_title' => 'タスク3-1',
            'task_content' => 'タスク3-1タスク3-1タスク3-1タスク3-1タスク3-1',
            'priority' => TaskPriority::LOW,
            'due_date' => '2022-03-30',
        ]);
        
        Task::create([
            'in_charge_user_id' => '3',
            'report_to_user_id' => '1',
            'task_status' => '1',
            'task_title' => 'タスク3-2',
            'task_content' => 'タスク3-2タスク3-2タスク3-2タスク3-2タスク3-2',
            'priority' => TaskPriority::HIGH,
            'due_date' => '2022-01-30',
        ]);

        Task::create([
            'in_charge_user_id' => '2',
            'report_to_user_id' => '1',
            'task_status' => '0',
            'task_title' => 'タスク2-1',
            'task_content' => 'タスク2-1タスク2-1タスク2-1タスク2-1タスク2-1',
            'priority' => TaskPriority::MIDDLE,
            'due_date' => '2022-02-30',
        ]);

        Task::create([
            'in_charge_user_id' => '2',
            'report_to_user_id' => '1',
            'task_status' => '0',
            'task_title' => 'タスク2-2',
            'task_content' => 'タスク2-2タスク2-2タスク2-2タスク2-2タスク2-2',
            'priority' => TaskPriority::LOW,
            'due_date' => '2022-03-30',
        ]);
        
        
    }
}
