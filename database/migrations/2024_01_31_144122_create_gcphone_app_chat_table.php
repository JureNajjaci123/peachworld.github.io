<?php
// Auto generated by the build:migrations command

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGcphoneAppChatTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Make enums work pre laravel 10
		Schema::getConnection()->getDoctrineConnection()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");

		$tableExists = Schema::hasTable("gcphone_app_chat");

		$indexes = $tableExists ? $this->getIndexedColumns() : [];
		$columns = $tableExists ? $this->getColumns() : [];

		$func = $tableExists ? "table" : "create";

		Schema::$func("gcphone_app_chat", function (Blueprint $table) use ($columns, $indexes) {
			!in_array("id", $columns) && $table->integer("id")->autoIncrement();
			!in_array("channel", $columns) && $table->string("channel", 20)->nullable();
			!in_array("message", $columns) && $table->string("message", 255)->nullable();
			!in_array("time", $columns) && $table->timestamp("time")->useCurrent();
			!in_array("license_identifier", $columns) && $table->string("license_identifier", 50)->nullable();
			!in_array("character_id", $columns) && $table->integer("character_id")->nullable();

			!in_array("channel", $indexes) && $table->index("channel");
			!in_array("time", $indexes) && $table->index("time");
			!in_array("license_identifier", $indexes) && $table->index("license_identifier");
			!in_array("character_id", $indexes) && $table->index("character_id");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists("gcphone_app_chat");
	}

	/**
	 * Get all columns.
	 *
	 * @return array
	 */
	private function getColumns(): array
	{
		$columns = Schema::getConnection()->select("SHOW COLUMNS FROM `gcphone_app_chat`");

		return array_map(function ($column) {
			return $column->Field;
		}, $columns);
	}

	/**
	 * Get all indexed columns.
	 *
	 * @return array
	 */
	private function getIndexedColumns(): array
	{
		$indexes = Schema::getConnection()->select("SHOW INDEXES FROM `gcphone_app_chat` WHERE Key_name != 'PRIMARY'");

		return array_map(function ($index) {
			return $index->Column_name;
		}, $indexes);
	}
}