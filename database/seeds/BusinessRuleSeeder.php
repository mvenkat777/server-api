<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Platform\App\Commanding\DefaultCommandBus;
class BusinessRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Model::unguard();
        $this->call('BusinessRuleCategorySeeder');
        $this->command->info('Business Category seeded!');
        Model::reguard();
    }
}
class BusinessRuleCategorySeeder extends Seeder
{
    public function run()
    {
        DB::statement('TRUNCATE rules_category_name CASCADE');
        DB::statement('TRUNCATE category_rules CASCADE');
    }
}