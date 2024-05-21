<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            DB::table('languages')->insert([
                ['code' => 'afr', 'name' => 'Afrikaans'],
                ['code' => 'eng', 'name' => 'English'],
                ['code' => 'nbl', 'name' => 'Ndebele'],
                ['code' => 'nso', 'name' => 'Pedi'],
                ['code' => 'sot', 'name' => 'Sotho'],
                ['code' => 'ssw', 'name' => 'Swati'],
                ['code' => 'tsn', 'name' => 'Tswana'],
                ['code' => 'tso', 'name' => 'Tsonga'],
                ['code' => 'ven', 'name' => 'Venda'],
                ['code' => 'xho', 'name' => 'Xhosa'],
                ['code' => 'zul', 'name' => 'Zulu'],
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            DB::table('languages')->whereIn('code', [
                'afr',
                'eng',
                'nbl',
                'nso',
                'sot',
                'ssw',
                'tsn',
                'tso',
                'ven',
                'xho',
                'zul',
            ])->delete();
        });
    }
};
