<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE INDEX events_dress_code_idx ON events ((details->>'dress_code'))");
        DB::statement("CREATE INDEX events_details_gin_idx ON events USING GIN (details jsonb_path_ops)");
        DB::statement("CREATE INDEX events_description_fts_idx ON events USING GIN (to_tsvector('english', description))");
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS events_dress_code_idx');
        DB::statement('DROP INDEX IF EXISTS events_details_gin_idx');
        DB::statement('DROP INDEX IF EXISTS events_description_fts_idx');
    }
};