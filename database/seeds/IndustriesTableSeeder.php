<?php

use Illuminate\Database\Seeder;

use App\Domain\Model\Documents\Passive\Industry;

class IndustriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $industries = [
            ['name' => 'Accounting & Legal'],
            ['name' => 'Advertising'],
            ['name' => 'Aerospace'],
            ['name' => 'Agriculture'],
            ['name' => 'Automotive'],
            ['name' => 'Banking & Finance'],
            ['name' => 'Biotechnology'],
            ['name' => 'Broadcasting'],
            ['name' => 'Business Services'],
            ['name' => 'Carpentry'],
            ['name' => 'Chemical Industry'],
            ['name' => 'Cloud Computing & Services'],
            ['name' => 'Commodities & Chemicals'],
            ['name' => 'Communications'],
            ['name' => 'Computers & Hightech'],
            ['name' => 'Consulting & Auditing'],
            ['name' => 'Defense'],
            ['name' => 'Dropshipping'],
            ['name' => 'E-Commerce'],
            ['name' => 'Energy'],
            ['name' => 'Entertainment'],
            ['name' => 'Fast-Moving Consumer Goods'],
            ['name' => 'Gambling'],
            ['name' => 'Geology & Landscaping'],
            ['name' => 'Government'],
            ['name' => 'Graphic Design & Multimedia'],
            ['name' => 'Healthcare & Life Sciences'],
            ['name' => 'Information Technology'],
            ['name' => 'Insurance'],
            ['name' => 'Logistics'],
            ['name' => 'Machinery'],
            ['name' => 'Manufacturing'],
            ['name' => 'Marketing'],
            ['name' => 'Media'],
            ['name' => 'Metallurgy'],
            ['name' => 'Military'],
            ['name' => 'Mining'],
            ['name' => 'Nonprofit & Higher Ed'],
            ['name' => 'Pharmaceuticals'],
            ['name' => 'Professional Services & Consulting'],
            ['name' => 'Real Estate'],
            ['name' => 'Retail & Wholesale'],
            ['name' => 'Science & Research'],
            ['name' => 'Security'],
            ['name' => 'Shipment'],
            ['name' => 'Sports'],
            ['name' => 'Textile Industry'],
            ['name' => 'Transportation'],
            ['name' => 'Travel & Luxury'],
            ['name' => 'Photography'],
            ['name' => 'Construction'],
            ['name' => 'Restaurant & Catering'],
            ['name' => 'Web Development'],
            ['name' => 'Wood Industry'],
            ['name' => 'Other'],
        ];

        foreach ($industries as $industry) {
            $record = Industry::whereName($industry['name'])->first();
            if (! $record) {
                Industry::create($industry);
            }
        }
    }
}
