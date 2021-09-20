<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowledgeBasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knowledge_bases', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->increments('id');
            $table->integer('group_id')->unsigned()->index('knowledge_bases_group_id_foreign_idx');
            $table->string('subject',290)->index('knowledge_bases_subject_index');
            $table->string('slug',290)->unique()->index('knowledge_bases_slug_index');
            $table->text('description')->nullable();
            $table->string('status',16)->index('knowledge_bases_status_index');
            $table->string('comments',8)->index('knowledge_bases_comments_index');
            $table->date('publish_date')->nullable()->index('knowledge_bases_publish_date_index');
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('knowledge_bases');
    }
}
