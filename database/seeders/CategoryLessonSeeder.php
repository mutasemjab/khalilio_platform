<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryLesson;

class CategoryLessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // Create root category first
        $rootCategory = CategoryLesson::create([
            'name' => 'الحصص',
        ]);

        // Define the structure: main categories and their sub-categories
        // Same structure as CategoryExam but under الحصص root
        $categoriesStructure = [
            'رياضيات متقدم' => ['تأسيس', 'المادة'],
            'رياضيات اعمال' => ['تأسيس', 'المادة'],
            'ثقافة مالية' => ['تأسيس', 'المادة']
        ];

        // Create main categories and their sub-categories
        foreach ($categoriesStructure as $mainCategoryName => $subCategories) {
            // Create main category under الحصص
            $mainCategory = CategoryLesson::create([
                'name' => $mainCategoryName,
                'parent_id' => $rootCategory->id
            ]);

            // Create sub-categories under this main category
            foreach ($subCategories as $subCategoryName) {
                CategoryLesson::create([
                    'name' => $subCategoryName,
                    'parent_id' => $mainCategory->id
                ]);
            }
        }

        $this->command->info('CategoryLesson seeder completed successfully!');
        $this->command->info('Created structure:');
        $this->command->info('- الحصص (Root)');
        
        foreach ($categoriesStructure as $mainCategory => $subCategories) {
            $this->command->info("  - {$mainCategory}");
            foreach ($subCategories as $subCategory) {
                $this->command->info("    - {$subCategory}");
            }
        }
    }
}