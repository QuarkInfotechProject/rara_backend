<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addMissingIndexes('products', [
            'slug', 'short_code', 'type', 'manager_id', 'status', 'region', 'is_occupied', 'display_homepage'
        ]);

        $this->addMissingIndexes('managers', ['email', 'status']);

        $this->addMissingIndexes('product_homestay_hosts', ['product_id']);

        $this->addMissingIndexes('product_faqs', ['product_id', 'order']);

        $this->addMissingIndexes('product_related_products', ['relation_type']);
        $this->addMissingCompositeIndex('product_related_products', ['product_id', 'related_product_id']);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['short_code']);
            $table->dropIndex(['manager_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['region']);
            $table->dropIndex(['is_occupied']);
            $table->dropIndex(['display_homepage']);
        });

        // Remove indexes from the managers table
        Schema::table('managers', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['status']);
        });

        // Remove indexes from the product_homestay_hosts table
        Schema::table('product_homestay_hosts', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });

        // Remove indexes from the product_faqs table
        Schema::table('product_faqs', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['order']);
        });

        // Remove indexes from the product_related_products table
        Schema::table('product_related_products', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'related_product_id']);
            $table->dropIndex(['relation_type']);
        });
    }

    private function addMissingIndexes(string $table, array $columns): void
    {
        Schema::table($table, function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                $indexName = $this->getIndexName($table->getTable(), $column);
                if (!$this->indexExists($table->getTable(), $indexName)) {
                    $table->index($column);
                }
            }
        });
    }

    private function addMissingCompositeIndex(string $table, array $columns): void
    {
        Schema::table($table, function (Blueprint $table) use ($columns) {
            $indexName = $this->getIndexName($table->getTable(), implode('_', $columns));
            if (!$this->indexExists($table->getTable(), $indexName)) {
                $table->index($columns);
            }
        });
    }

    private function getIndexName(string $table, string $column): string
    {
        return "{$table}_{$column}_index";
    }

    private function indexExists(string $table, string $indexName): bool
    {
        return \Illuminate\Support\Facades\DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]) ? true : false;
    }
};
