<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryExam;

class CategoryExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // Create root category first
        $rootCategory = CategoryExam::create([
            'name' => 'الامتحانات الرئيسية',
        ]);

        // Define the structure: main categories and their sub-categories
        $categoriesStructure = [
            'رياضيات متقدم' => ['تأسيس', 'المادة'],
            'رياضيات اعمال' => ['تأسيس', 'المادة'],
            'ثقافة مالية' => ['تأسيس', 'المادة']
        ];

        // Create main categories and their sub-categories
        foreach ($categoriesStructure as $mainCategoryName => $subCategories) {
            // Create main category
            $mainCategory = CategoryExam::create([
                'name' => $mainCategoryName,
                'parent_id' => $rootCategory->id
            ]);

            // Create sub-categories under this main category
            foreach ($subCategories as $subCategoryName) {
                CategoryExam::create([
                    'name' => $subCategoryName,
                    'parent_id' => $mainCategory->id
                ]);
            }
        }

        $this->command->info('CategoryExam seeder completed successfully!');
        $this->command->info('Created structure:');
        $this->command->info('- الامتحانات الرئيسية (Root)');
        
        foreach ($categoriesStructure as $mainCategory => $subCategories) {
            $this->command->info("  - {$mainCategory}");
            foreach ($subCategories as $subCategory) {
                $this->command->info("    - {$subCategory}");
            }
        }
    }
}