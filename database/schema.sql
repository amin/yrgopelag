CREATE TABLE IF NOT EXISTS rooms (
    id SERIAL PRIMARY KEY,
    type VARCHAR(20) NOT NULL CHECK (type IN ('budget', 'standard', 'luxury')),
    price INT NOT NULL
);

CREATE TABLE IF NOT EXISTS tier_pricing (
    tier VARCHAR(20) PRIMARY KEY,
    price INT NOT NULL
);

CREATE TABLE IF NOT EXISTS bookings (
    id SERIAL PRIMARY KEY,
    room_id INT REFERENCES rooms(id),
    guest_name VARCHAR(100) NOT NULL,
    arrival_date DATE NOT NULL,
    departure_date DATE NOT NULL,
    total_cost INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS booking_features (
    id SERIAL PRIMARY KEY,
    booking_id INT REFERENCES bookings(id),
    activity VARCHAR(20) NOT NULL,
    tier VARCHAR(20) REFERENCES tier_pricing(tier),
    price INT NOT NULL DEFAULT 0
);