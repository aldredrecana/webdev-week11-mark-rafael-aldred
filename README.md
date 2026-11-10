# Campus Events at Scale (Week 11)

Laravel + PostgreSQL project. Tests how queries behave with 20,000 events.
Rebuild everything with: `php artisan migrate:fresh --seed`

## 1. Query counts (Step 4: N+1 problem)

| Version                       | Page               | Queries sent |
| ----------------------------- | ------------------ | ------------ |
| Naive (lazy loading)          | /events?mode=naive | 101          |
| Fixed (eager loading + count) | /events?mode=fixed | 2            |

**Explanation:** The naive version sent about 101 queries because it loaded the
venue and the participants again for every one of the 50 events. The fixed
version sends \_\*\* queries because `with('venue')` loads all venues together and
`withCount('participants')` counts participants inside the main query.

## 2. Query plans (Step 7: EXPLAIN ANALYZE)

| Query                       | Index that exists                | Plan type                 | Execution time |
| --------------------------- | -------------------------------- | ------------------------- | -------------- |
| Q1 arrow-form JSONB filter  | none                             | Seq Scan                  | 7.833 ms       |
| Q2 containment JSONB filter | none                             | Seq Scan                  | 9.235 ms       |
| Q3 full-text search         | none                             | Seq Scan                  | 191.022 ms     |
| Q1 arrow-form JSONB filter  | events_dress_code_idx (btree)    | Bitmap Index Scan         | 0.331 ms       |
| Q2 containment JSONB filter | events_details_gin_idx (GIN)     | Bitmap Index Scan         | 0.199 ms       |
| Q3 full-text search         | events_description_fts_idx (GIN) | Bitmap Index Scan         | 0.193 ms       |
| Q2 containment JSONB filter | only events_dress_code_idx       | Seq Scan (index not used) | 9.001 ms       |

**Why the last row does not use the index:** The btree index is built on
`details->>'dress_code'`, but the containment operator `@>` asks a different
question. Only a GIN index on `details` can answer it.

## 3. Step 8: Why indexes are in a migration with raw SQL

All indexes are in the migration `add_indexes_to_events_table`, so they come
back every time `php artisan migrate:fresh --seed` runs. Raw SQL (`DB::statement`)
is needed because Laravel's schema builder cannot create an index on an
expression like `details->>'dress_code'`, and it cannot choose the
`jsonb_path_ops` operator class.

## 4. Step 9: Choosing columns wisely

- **Kept in the JSONB column `details`:** `dress_code` (also `capacity`, `tags`).
  Reason: Not every event needs it, and the extra details can differ per event.
- **Kept in a normal column:** `starts_at` (or `venue_id`).
  Reason: Normal column (starts_at): Every event has it, and we sort and filter by it all the time.

## 5. Local scopes

| Scope                       | What it does                                                         |
| --------------------------- | -------------------------------------------------------------------- |
| `upcoming()`                | Events whose `starts_at` is now or later                             |
| `atVenue($venueId)`         | Events at one venue                                                  |
| `withDressCode($dressCode)` | Events with one `dress_code` value in `details` (JSONB arrow filter) |
| `search($text)`             | Full-text search on `description`                                    |

Chained example: `Event::search($q)->atVenue(3)->limit(50)->get()`

## 6. What I Learned

This week we learned that the N+1 problem happens when a loop sends one extra query for every row. On our /events page, the naive version sent 101 queries for 50 events, but after we used with('venue') and withCount('participants'), it sent only 2.
We also learned that an index only helps when the query matches the index and the value is rare: “telescope” and “barong” appear in less than 1% of our 20,000 events, so PostgreSQL can jump straight to them instead of reading every row.
Using EXPLAIN (ANALYZE) showed us what PostgreSQL really did, such as switching from a Seq Scan to an index scan, so we measured instead of guessing.
One thing we found confusing is why a GIN index is needed for the containment filter (@>) while a btree index works for the arrow filter (->>), and we want to explore how PostgreSQL chooses between them.
