# Yrgopelag

A hotel booking system for a fictional island in the Yrgopelag archipelago. Built as a school project for the courses Programmering and Datakällor.

## Description

This web application allows guests to browse rooms, check availability, and book stays at the hotel. The system integrates with the Yrgopelag Central Bank API for handling payments via transfer codes.

## Technologies

- **PHP** - Backend logic and API integration
- **SQLite** - Database for storing bookings and room data
- **JavaScript** - Frontend interactivity
- **Composer** - PHP dependency management
- **HTML/CSS** - Frontend markup and styling

## Installation

1. Clone the repository
   ```bash
   git clone https://github.com/yourusername/yrgopelag.git
   cd yrgopelag
   ```

2. Install dependencies
   ```bash
   composer install
   ```

3. Copy the environment file and add your API credentials
   ```bash
   cp .env.example .env
   ```

4. Update `.env` with your Central Bank credentials
   ```
   CENTRALBANK_USER=YourName
   CENTRALBANK_API_KEY=your-api-key
   ```

5. Start the development server
   ```bash
   cd public
   php -S localhost:8000
   ```

6. Open http://localhost:8000 in your browser

## Features

- Three room types: Budget, Standard, and Luxury
- Booking calendar for January 2026
- Integration with Yrgopelag Central Bank for payments
- Room availability display

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.