<?php

use Illuminate\Database\Seeder;

class SkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = [
          'Microsoft Office',
          'Programming'
        ];

        foreach ($skills as $skill){

            $check = \App\Skill::where('name',$skill)->exists();

            if (!$check){
                $saveSkill = new \App\Skill();
                $saveSkill->name = $skill;
                $saveSkill->save();
                $this->command->info('Seeded '.$skill);
            }else{
                $this->command->warn('Seeded '.$skill);
            }
        }
    }
}
