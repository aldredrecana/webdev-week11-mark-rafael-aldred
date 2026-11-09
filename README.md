# Campus Events (Week 10: Relational Data Model & Queries)

A small Laravel + PostgreSQL project. Venues host events. Participants register for events. Notes can be attached to either an event or a venue.

## Our Relationships

| Link                  | Type                                  | Relationship methods                                                        | Foreign key                                              | ON DELETE | Why                                                                                                      |
| --------------------- | ------------------------------------- | --------------------------------------------------------------------------- | -------------------------------------------------------- | --------- | -------------------------------------------------------------------------------------------------------- |
| Venue and Event       | One-to-many                           | `Venue::events()` (hasMany), `Event::venue()` (belongsTo)                   | `events.venue_id`                                        | RESTRICT  | A venue that still has events cannot be deleted.                                                         |
| Event and Participant | Many-to-many (pivot: `registrations`) | `Event::participants()`, `Participant::events()` (belongsToMany)            | `registrations.event_id`, `registrations.participant_id` | CASCADE   | Deleting an event or a participant removes their registrations. A unique pair stops double registration. |
| Note and Event/Venue  | Polymorphic one-to-many               | `Event::notes()`, `Venue::notes()` (morphMany), `Note::notable()` (morphTo) | `notable_id` + `notable_type` (not a real FK)            | None      | PostgreSQL cannot enforce it, so orphan notes are possible.                                              |

## Setup

1. Copy `.env.example` to `.env` and set your PostgreSQL database settings.
2. Run `composer install`.
3. Run `php artisan key:generate`.
4. Run `php artisan migrate:fresh --seed`.
5. Run `php artisan serve` and open `/report`.
