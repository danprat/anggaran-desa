<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix any existing data that might have JSON issues
        DB::table('desa_profiles')->whereNotNull('additional_settings')->update([
            'additional_settings' => DB::raw("CASE
                WHEN JSON_VALID(additional_settings) = 1 THEN additional_settings
                ELSE NULL
            END")
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this fix
    }
};
