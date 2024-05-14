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
                ['code' => 'afr'], // Afrikaans
                ['code' => 'eng'], // English
                ['code' => 'nbl'], // Ndebele
                ['code' => 'nso'], // Northern Sotho, Pedi
                ['code' => 'sot'], // Southern Sotho
                ['code' => 'ssw'], // Swati
                ['code' => 'tsn'], // Tswana
                ['code' => 'tso'], // Tsonga
                ['code' => 'ven'], // Venda
                ['code' => 'xho'], // Xhosa
                ['code' => 'zul'], // Zulu
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
