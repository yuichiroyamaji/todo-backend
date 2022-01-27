<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->integer('in_charge_user_id')->comment('担当ユーザーID');
            $table->integer('report_to_user_id')->default(1)->comment('報告対象ユーザーID');
            $table->string('task_title')->comment('タスクタイトル');
            $table->text('task_content')->nullable()->comment('タスク内容');
            $table->integer('priority')->comment('優先度');
            $table->timestamp('due_date')->comment('期日');
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
