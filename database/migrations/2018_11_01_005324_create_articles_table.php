<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0); // 用户 id
            $table->unsignedInteger('node_id')->default(0); // 文章所在目录 id
            $table->string('title');    // 文章标题
            $table->string('tag');  //　文章标签
            $table->string('type');  //　文章所属类型
            $table->string('picture')->nullable();    // 图片链接
            $table->integer('cnt')->default(0);    // 浏览次数
            $table->string('author')->default('');  // 作者
            $table->string('abstract')->default('');  // 摘要
            $table->text('content');
            $table->timestamp('post_time')->nullable(); // 发布时间
            $table->tinyInteger('is_posted')->default(0); // 文章发布标志 0表示没有发布任务，1表示有发布任务，但没有发布，2表示已发布
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
