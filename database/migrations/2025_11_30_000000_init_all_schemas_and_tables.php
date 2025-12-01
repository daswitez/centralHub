<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('CREATE SCHEMA IF NOT EXISTS cat');
        DB::statement('CREATE SCHEMA IF NOT EXISTS campo');
        DB::statement('CREATE SCHEMA IF NOT EXISTS planta');
        DB::statement('CREATE SCHEMA IF NOT EXISTS logistica');
        DB::statement('CREATE SCHEMA IF NOT EXISTS comercial');
        DB::statement('CREATE SCHEMA IF NOT EXISTS certificacion');
        DB::statement('CREATE SCHEMA IF NOT EXISTS almacen');
    }

    public function down()
    {
        DB::statement('DROP SCHEMA IF EXISTS almacen CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS certificacion CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS comercial CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS logistica CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS planta CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS campo CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS cat CASCADE');
    }
};
