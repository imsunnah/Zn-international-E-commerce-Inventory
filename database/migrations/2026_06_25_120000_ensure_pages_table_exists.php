<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->id();
                $table->string('title_en');
                $table->string('title_bn')->nullable();
                $table->string('slug')->unique();
                $table->text('description_en')->nullable();
                $table->text('description_bn')->nullable();
                $table->string('image')->nullable();
                $table->longText('content_en');
                $table->longText('content_bn')->nullable();
                $table->string('group')->default('Company');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            // If table exists, ensure it has the correct bilingual columns
            Schema::table('pages', function (Blueprint $table) {
                if (!Schema::hasColumn('pages', 'title_en')) {
                    if (Schema::hasColumn('pages', 'title')) {
                        $table->renameColumn('title', 'title_en');
                    } else {
                        $table->string('title_en')->after('id');
                    }
                }
                
                if (!Schema::hasColumn('pages', 'title_bn')) {
                    $table->string('title_bn')->nullable()->after('title_en');
                }

                if (!Schema::hasColumn('pages', 'description_en')) {
                    if (Schema::hasColumn('pages', 'description')) {
                        $table->renameColumn('description', 'description_en');
                    } else {
                        $table->text('description_en')->nullable()->after('slug');
                    }
                }

                if (!Schema::hasColumn('pages', 'description_bn')) {
                    $table->text('description_bn')->nullable()->after('description_en');
                }

                if (!Schema::hasColumn('pages', 'content_en')) {
                    if (Schema::hasColumn('pages', 'content')) {
                        $table->renameColumn('content', 'content_en');
                    } else {
                        $table->longText('content_en')->after('image');
                    }
                }

                if (!Schema::hasColumn('pages', 'content_bn')) {
                    $table->longText('content_bn')->nullable()->after('content_en');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down needed as this is a safety/ensure migration
    }
};
