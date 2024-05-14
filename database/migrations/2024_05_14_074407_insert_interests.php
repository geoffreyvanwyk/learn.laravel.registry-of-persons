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
        Schema::table('interests', function (Blueprint $table) {
            DB::table('interests')->insert([
                ['name' => 'History'],
                ['name' => 'Geography'],
                ['name' => 'Physics'],
                ['name' => 'Music'],
                ['name' => 'Chemistry'],
                ['name' => 'Guitar'],
                ['name' => 'Languages'],
                ['name' => 'Violin'],
                ['name' => 'Mathematics'],
                ['name' => 'Videography'],
                ['name' => 'Photography'],
                ['name' => 'Computer Science'],
                ['name' => 'Cyber Security'],
                ['name' => 'Politics'],
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interests', function (Blueprint $table) {
            DB::table('interests')->whereIn('name', [
                'History',
                'Geography',
                'Physics',
                'Music',
                'Chemistry',
                'Guitar',
                'Languages',
                'Violin',
                'Mathematics',
                'Videography',
                'Photography',
                'Computer Science',
                'Cyber Security',
                'Politics',
            ])->delete();
        });
    }
};
