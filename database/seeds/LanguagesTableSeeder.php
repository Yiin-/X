<?php

use Illuminate\Database\Seeder;
use App\Domain\Model\Documents\Passive\Language;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // https://github.com/caouecs/Laravel-lang
        // https://www.loc.gov/standards/iso639-2/php/code_list.php

        $languages = [
            ['name' => 'English', 'locale' => 'en'],
            ['name' => 'Lietuvių', 'locale' => 'lt']
        ];

        foreach ($languages as $language) {
            $record = Language::whereLocale($language['locale'])->first();
            if ($record) {
                $record->name = $language['name'];
                $record->save();
            } else {
                Language::create($language);
            }
        }
    }
}
