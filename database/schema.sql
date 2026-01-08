CREATE TABLE if NOT exists rooms (
    id SERIAL PRIMARY KEY,
    type VARCHAR(20) NOT NULL CHECK (type IN ('budget', 'standard', 'luxury')),
    price INT NOT NULL
);

CREATE TABLE if NOT exists bookings (
    id SERIAL PRIMARY KEY,
    room_id INT REFERENCES rooms(id),
    guest_name VARCHAR(100) NOT NULL,
    arrival_date DATE NOT NULL,
    departure_date DATE NOT NULL,
    transfer_code VARCHAR(255),
    total_cost INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE if NOT exists booking_features (
    id SERIAL PRIMARY KEY,
    booking_id INT REFERENCES bookings(id),
    activity VARCHAR(20) NOT NULL,
    tier VARCHAR(20) NOT NULL,
    price INT NOT NULL DEFAULT 0
);