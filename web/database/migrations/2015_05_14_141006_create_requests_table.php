<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('requests', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('template_id')->unsigned();
			$table->enum('type', ['pdf', 'docx'])->default('pdf');
			$table->text('data')->nullable();
			$table->string('callback_url')->nullable();
			$table->datetime('generated_at')->nullable();
			$table->datetime('failed_at')->nullable();
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('template_id')->references('id')->on('templates');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('requests');
	}

}
