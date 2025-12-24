<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateSettingGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('setting_groups', function($table) {
			$table->increments('id');
			$table->string('slug')->index()->unique();
			$table->string('name');
			$table->string('description')->nullable();
			$table->integer('sort_order')->nullable()->default(0);
			$table->boolean('is_name_editable')->default(true);
			$table->timestamps();
		});

		Schema::table('settings', function (Blueprint $table) {
			$table->unsignedInteger('setting_group_id')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('settings', function (Blueprint $table) {
			$table->dropColumn('setting_group_id');
		});
		Schema::dropIfExists('setting_groups');
    }
}
