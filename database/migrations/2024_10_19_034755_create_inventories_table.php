<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
<<<<<<< HEAD
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tweet');
            $table->timestamps();
        });
    }
=======
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('inventories', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->integer('stock');
			$table->foreignId('user_id')->constrained()->cascadeOnDelete();
			$table->timestamps();
		});
	}
>>>>>>> eff47b0d001123a0cc89778f22169deefa11e664

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('inventories');
	}
};
