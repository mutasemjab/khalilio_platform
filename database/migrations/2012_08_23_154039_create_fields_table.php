<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('fields')->insert([
            ['name' => 'حقل اللغات والعلوم الاجتماعية'],
            ['name' => 'حقل القانون والعلوم الشرعية'],
            ['name' => 'الحقل الصحي'],
            ['name' => 'الحقل الهندسي'],
            ['name' => 'حقل العلوم والتكنولوجيا'],
            ['name' => 'حقل الأعمال'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
    }
};
