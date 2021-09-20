<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('items', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
			$table->bigIncrements('id');
			$table->string('stock_id', 30)->unique()->comment('every product/service has unique individual stock_id');
			$table->integer('stock_category_id')->unsigned()->index('items_stock_category_id_foreign_idx');
			$table->string('item_type', 30)->index()->comment('\'product\', \'service\'');
			$table->integer('parent_id')->unsigned()->comment('If the product/service  is under a product/service as sub-product/service then it will refer an id of this table, if the product/service is main product/service then the parent id will 0.');
			$table->string('name', 199)->index();
			$table->integer('item_unit_id')->unsigned()->nullable()->index('items_item_unit_id_foreign_idx');
			$table->integer('tax_type_id')->unsigned()->nullable()->index('items_tax_type_id_foreign_idx');
			$table->string('available_variant', 100)->nullable();
			$table->string('size', 100)->nullable();
			$table->string('color', 100)->nullable();
			$table->decimal('weight', 16, 8)->nullable();
			$table->integer('weight_unit_id')->unsigned()->nullable()->index('items_weight_unit_id_foreign_idx');
			$table->boolean('is_stock_managed')->index('items_manage_stock_level_index')->comment('it means the product/service stock management 1 for on0 for off');
			$table->text('description', 65535)->nullable();
			$table->string('hsn', 50)->nullable();
			$table->boolean('is_active')->default(1)->index()->comment('0 for inactive \n1 for active');
			$table->integer('alert_quantity')->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('items');
	}

}
