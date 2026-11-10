<?php

namespace Database\Seeders;

use App\Models\Participant;
use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LargeDatasetSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // 1. More venues (17 new + the 3 or more from the Week 10 seeder)
        $venues = [];
        for ($i = 1; $i <= 17; $i++) {
            $venues[] = [
                'name' => "Campus Hall $i",
                'address' => fake()->streetAddress(),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('venues')->insert($venues);

        // 2. 250 participants
        $participants = [];
        for ($i = 1; $i <= 250; $i++) {
            $participants[] = [
                'name' => fake()->name(),
                'email' => "student$i@campus.test",
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('participants')->insert($participants);

        $venueIds = Venue::pluck('id')->all();
        $participantIds = Participant::pluck('id')->all();

        $words = ['music', 'talk', 'workshop', 'seminar', 'sports', 'art', 'career', 'food', 'club', 'orientation'];
        $dress = ['casual', 'formal', 'uniform', 'smart casual', 'costume'];
        $tags  = ['campus', 'students', 'free', 'outdoor', 'indoor', 'online', 'club'];

        // 3. 20,000 events, inserted in chunks of 1,000
        for ($c = 0; $c < 20; $c++) {
            $rows = [];
            for ($j = 1; $j <= 1000; $j++) {
                $i = $c * 1000 + $j;

                $description = 'A ' . implode(' ', array_rand(array_flip($words), 5))
                    . ' event. ' . fake()->sentence(8);
                if ($i % 200 === 0) {
                    $description .= ' telescope';          // rare word: 100 events = 0.5%
                }

                $start = now()->addDays(rand(-30, 90))->setTime(rand(8, 18), rand(0, 1) * 30);

                $rows[] = [
                    'venue_id'    => $venueIds[array_rand($venueIds)],
                    'title'       => fake()->sentence(3),
                    'event_date'  => $start->toDateString(),
                    'starts_at'   => $start,
                    'description' => $description,
                    'details'     => json_encode([
                        'dress_code' => $i % 250 === 0 ? 'barong' : $dress[array_rand($dress)], // rare value: 80 events = 0.4%
                        'capacity'   => rand(20, 500),
                        'tags'       => array_rand(array_flip($tags), 2),
                    ]),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
            DB::table('events')->insert($rows);
        }

        // 4. Registrations for the first 100 events
        $registrations = [];
        $eventIds = DB::table('events')->orderBy('id')->limit(100)->pluck('id');
        foreach ($eventIds as $eventId) {
            shuffle($participantIds);
            foreach (array_slice($participantIds, 0, rand(3, 10)) as $k => $pid) {
                $registrations[] = [
                    'event_id'       => $eventId,
                    'participant_id' => $pid,
                    'status'         => $k % 4 === 0 ? 'waitlisted' : 'registered',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ];
            }
        }
        // insertOrIgnore skips pairs the Week 10 seeder already created (unique event+participant)
        DB::table('registrations')->insertOrIgnore($registrations);
    }
}