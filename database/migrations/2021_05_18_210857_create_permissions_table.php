<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
        });

        DB::table('permissions')->insert([
            ['name' => 'Δημιουργία'],
            ['name' => 'Αλλαγή'],
            ['name' => 'Διαγραφή'],
            ['name' => 'Ορισμός δικαιωμάτων'],
            ['name' => 'Εσωτερικός έλεγχος'],
            ['name' => 'Μόνο προβολή'],
        ]);
        
        Schema::create('permission_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('user_id');

            $table->timestamps();
        
            $table->unique(['permission_id', 'user_id']);

            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        DB::table('permissions')->insert([
            ['user_id' => '1' ,'permission_id' => '1'],
            ['user_id' => '1' ,'permission_id' => '2'],
            ['user_id' => '1' ,'permission_id' => '3'],
            ['user_id' => '1' ,'permission_id' => '4'],
            ['user_id' => '1' ,'permission_id' => '5'],
            ['user_id' => '1' ,'permission_id' => '6'],
         
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission');
    }
}
