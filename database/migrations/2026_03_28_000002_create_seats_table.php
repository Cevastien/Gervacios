<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seats')) {
            Schema::create('seats', function (Blueprint $table) {
                $table->id();
                $table->foreignId('table_id')->constrained()->cascadeOnDelete();
                $table->unsignedTinyInteger('seat_index');
                $table->enum('status', ['free', 'reserved', 'occupied'])->default('free');
                $table->decimal('pos_x', 8, 4)->default(50);
                $table->decimal('pos_y', 8, 4)->default(50);
                $table->timestamps();

                $table->unique(['table_id', 'seat_index']);
                $table->index('status');
            });

            return;
        }

        Schema::table('seats', function (Blueprint $table) {
            if (! Schema::hasColumn('seats', 'pos_x')) {
                $table->decimal('pos_x', 8, 4)->default(50);
            }
            if (! Schema::hasColumn('seats', 'pos_y')) {
                $table->decimal('pos_y', 8, 4)->default(50);
            }
        });

        if (Schema::hasColumn('seats', 'svg_x') && Schema::hasColumn('seats', 'svg_y')) {
            DB::statement('UPDATE seats SET pos_x = svg_x, pos_y = svg_y');

            Schema::table('seats', function (Blueprint $table) {
                $table->dropColumn(['svg_x', 'svg_y']);
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('seats')) {
            return;
        }

        Schema::table('seats', function (Blueprint $table) {
            if (Schema::hasColumn('seats', 'pos_x')) {
                $table->dropColumn('pos_x');
            }
            if (Schema::hasColumn('seats', 'pos_y')) {
                $table->dropColumn('pos_y');
            }
        });
    }
};
