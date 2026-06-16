<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('cabin_lock', function (Blueprint $table): void {
            $table->index('key', 'cabin_lock_key_index');
            $table->index(['key', 'session_id'], 'cabin_lock_key_session_id_index');
            $table->index('locked_at', 'cabin_lock_locked_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('cabin_lock', function (Blueprint $table): void {
            $table->dropIndex('cabin_lock_key_index');
            $table->dropIndex('cabin_lock_key_session_id_index');
            $table->dropIndex('cabin_lock_locked_at_index');
        });
    }
};
