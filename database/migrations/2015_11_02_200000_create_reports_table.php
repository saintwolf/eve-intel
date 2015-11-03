<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reports', function (Blueprint $table) {
			$table->increments('id'         );
			$table->string    ('hash'       );
			$table->string    ('submitter'  );
			$table->text      ('raw'        );
			$table->text      ('interpreted');
			$table->timestamps(             );
		});

		Schema::create('report_systems', function (Blueprint $table) {
			$table->increments('id'      );
			$table->integer   ('reportID');
			$table->integer   ('systemID');
			$table->unique    (['reportID', 'systemID']);
		});

		Schema::create('report_uploaders', function (Blueprint $table) {
			$table->increments('id'      );
			$table->integer   ('reportID');
			$table->integer   ('userID'  );
			$table->unique    (['reportID', 'userID']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reports'         );
		Schema::drop('report_systems'  );
		Schema::drop('report_uploaders');
	}
}
