# Entity Relationship Diagram

```mermaid
erDiagram
    roles ||--o{ users : authorizes
    students ||--o| users : authenticates
    students ||--o{ voters : enrolled
    elections ||--o{ voters : includes
    elections ||--o{ candidate_requirements : defines
    elections ||--o{ candidate_registrations : receives
    candidate_registrations ||--|{ registration_members : reserves
    students ||--o{ registration_members : participates
    candidate_registrations ||--o{ candidate_registration_documents : versions
    candidate_requirements ||--o{ candidate_registration_documents : requires
    candidate_registrations ||--o{ requirement_answers : answers
    candidate_requirements ||--o{ requirement_answers : asks
    candidate_registrations ||--o{ candidate_registration_histories : records
    candidate_registrations ||--o{ candidate_programs : proposes
    candidate_registrations ||--o| candidates : establishes
    elections ||--o{ candidates : publishes
    elections ||--o{ voting_participations : counts
    voters ||--o| voting_participations : participates
    elections ||--o{ ballots : contains
    candidates ||--o{ ballots : receives
    users ||--o{ audit_logs : performs
    users ||--o{ notifications : receives
    users ||--o{ import_batches : previews
    students {
        bigint id PK
        string nim UK
        string email UK
        int semester
        string student_status
        string google_id UK
    }
    voters {
        bigint id PK
        bigint election_id FK
        bigint student_id FK
        string voter_status
    }
    voting_participations {
        bigint id PK
        bigint election_id FK
        bigint voter_id FK
        datetime voted_at
        datetime created_at
    }
    ballots {
        uuid id PK
        bigint election_id FK
        bigint candidate_id FK
        uuid ballot_uuid UK
        string integrity_hash
        datetime submitted_at
        datetime created_at
    }
```

Tidak ada hubungan ballot–voter/participation/student/user. `ballots.submitted_at` dan `created_at` memakai penutupan periode yang sama; bukan waktu aktual submit.

Constraint tambahan: voters(election_id, student_id), registration_members(election_id, student_id), candidates(election_id, candidate_number), candidates(candidate_registration_id), voting_participations(election_id, voter_id), registration_number. Composite FK memastikan election pada ballot/participation cocok dengan kandidat/voter.
