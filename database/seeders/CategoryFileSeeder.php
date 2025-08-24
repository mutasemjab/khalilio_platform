<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryFile;

class CategoryFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        // Create root category first
        $rootCategory = CategoryFile::create([
            'name' => 'الملفات',
        ]);

        // Define the sub-categories under الجداول الدراسية
        $subCategories = [
            'الجداول الدراسية',
            'جدول مواعيد حصص التأسيس',
            'جدول مواعيد حصص شرح المادة',
        ];

        // Create sub-categories under the root category
        foreach ($subCategories as $subCategoryName) {
            CategoryFile::create([
                'name' => $subCategoryName,
                'parent_id' => $rootCategory->id
            ]);
        }

        $this->command->info('CategoryFile seeder completed successfully!');
        $this->command->info('Created structure:');
        $this->command->info('- الملفات (Root)');
        
        foreach ($subCategories as $subCategory) {
            $this->command->info("  - {$subCategory}");
        }
    }
}