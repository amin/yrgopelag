PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS rooms (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type TEXT NOT NULL CHECK (type IN ('budget', 'standard', 'luxury')),
    description TEXT
    price INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS tier_pricing (
    tier TEXT PRIMARY KEY,
    price INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS bookings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    room_id INTEGER REFERENCES rooms(id),
    guest_name TEXT NOT NULL,
    arrival_date TEXT NOT NULL,
    departure_date TEXT NOT NULL,
    total_cost INTEGER NOT NULL,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS booking_features (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    booking_id INTEGER REFERENCES bookings(id),
    activity TEXT NOT NULL,
    tier TEXT REFERENCES tier_pricing(tier),
    price INTEGER NOT NULL DEFAULT 0
);
