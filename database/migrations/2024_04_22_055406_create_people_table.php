<?php

// This file is part of registry-of-persons.
//
// registry-of-persons is free software: you can redistribute it and/or modify it under the
// terms of the GNU General Public License as published by the Free Software
// Foundation, either version 3 of the License, or (at your option) any later
// version.
//
// registry-of-persons is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License along with
// registry-of-persons. If not, see <https://www.gnu.org/licenses/>.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates a database table for storing Person instances.
 *
 * @see        {@linkhttps://laravel.com/docs/11.x/migrations }
 * @author     Geoffrey Bernardo van Wyk <geoffrey@vanwyk.biz>
 * @copyright  2024 Geoffrey Bernardo van Wyk {@link https://geoffreyvanwyk.dev}
 * @license    {@link http://www.gnu.org/copyleft/gpl.html} GNU GPL v3 or later
 */
return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('south_african_id');
            $table->string('mobile_number');
            $table->string('email');
            $table->date('birth_date');
            $table->foreignId('language_id')->references('id')->on('languages');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
