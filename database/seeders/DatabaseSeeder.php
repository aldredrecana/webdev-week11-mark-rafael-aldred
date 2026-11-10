<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Note;
use App\Models\Participant;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 3 venues
        $gym = Venue::create(['name' => 'University Gym', 'address' => 'Main Campus']);
        $hall = Venue::create(['name' => 'Audio Visual Hall', 'address' => 'Building A']);
        $court = Venue::create(['name' => 'Open Court', 'address' => 'Back Campus']);

        // 6 events
        $e1 = Event::create(['venue_id' => $gym->id, 'title' => 'Intramurals Opening', 'event_date' => '2026-12-01']);
        $e2 = Event::create(['venue_id' => $gym->id, 'title' => 'Basketball Tournament', 'event_date' => '2026-12-05']);
        $e3 = Event::create(['venue_id' => $hall->id, 'title' => 'IT Seminar', 'event_date' => '2026-12-08']);
        $e4 = Event::create(['venue_id' => $hall->id, 'title' => 'Film Showing', 'event_date' => '2026-12-10']);
        $e5 = Event::create(['venue_id' => $court->id, 'title' => 'Zumba Day', 'event_date' => '2026-12-12']);
        $e6 = Event::create(['venue_id' => $court->id, 'title' => 'Foundation Day Program', 'event_date' => '2026-12-15']);

        // 8 participants
        $names = ['Mark', 'Eduard', 'Rafael', 'Jaycieh', 'Aldred', 'Maria', 'Juan', 'Ana'];
        $p = [];
        foreach ($names as $name) {
            $p[] = Participant::create([
                'name' => $name,
                'email' => strtolower($name) . '@example.com',
            ]);
        }

        // 12 registrations (both statuses, no duplicate pairs)
        $e1->participants()->attach($p[0]->id, ['status' => 'registered']);
        $e1->participants()->attach($p[1]->id, ['status' => 'registered']);
        $e1->participants()->attach($p[2]->id, ['status' => 'waitlisted']);

        $e2->participants()->attach($p[0]->id, ['status' => 'registered']);
        $e2->participants()->attach($p[3]->id, ['status' => 'waitlisted']);

        $e3->participants()->attach($p[4]->id, ['status' => 'registered']);
        $e3->participants()->attach($p[5]->id, ['status' => 'registered']);
        $e3->participants()->attach($p[6]->id, ['status' => 'registered']);

        $e4->participants()->attach($p[7]->id, ['status' => 'registered']);
        $e4->participants()->attach($p[1]->id, ['status' => 'waitlisted']);

        $e5->participants()->attach($p[5]->id, ['status' => 'registered']);
        $e6->participants()->attach($p[6]->id, ['status' => 'registered']);

        // 6 notes (3 on events, 3 on venues)
        $e1->notes()->create(['body' => 'Bring your ID.']);
        $e3->notes()->create(['body' => 'Certificates will be given.']);
        $e5->notes()->create(['body' => 'Wear comfortable clothes.']);

        $gym->notes()->create(['body' => 'Gym closes at 9 PM.']);
        $hall->notes()->create(['body' => 'Aircon needs checking.']);
        $court->notes()->create(['body' => 'Court is open to all students.']);

        $this->call(LargeDatasetSeeder::class);
    }
}