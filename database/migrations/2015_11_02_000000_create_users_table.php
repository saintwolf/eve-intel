<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->increments   ('id'            );
			$table->integer      ('characterID'   );
			$table->string       ('characterName' );
			$table->string       ('uploader_token');
			$table->text         ('settings'      )->default( null);
			$table->boolean      ('isBanned'      )->default(false);
			$table->rememberToken(                );
			$table->timestamp    ('uploaded_at'   );
			$table->timestamps   (                );
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}
}
