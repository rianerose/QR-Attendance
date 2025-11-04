# QR Attendance Recorder

Modern PHP-based web app for recording student attendance with QR codes. Add students, auto-generate printable QR badges, and scan them via webcam to log attendance in real time.

## Features
- Add students with unique QR tokens generated through a public API.
- Responsive dashboard with quick stats and recent activity.
- Student directory with printable QR codes and copyable tokens.
- Web-based QR scanner powered by `html5-qrcode` for instant attendance logging.
- Attendance log with date/name filters.
- Clean UI using a white, blue, and yellow palette.

## Requirements
- PHP 8.1+ with PDO extension for MySQL.
- MySQL or MariaDB server.
- Web server (Apache, Nginx, or PHP's built-in server for development).
- Internet access for loading external QR and JS libraries.

## Installation
1. Clone or copy this repository into your web server root.
2. Install the database schema:
   ```bash
   mysql -u <user> -p < database/schema.sql
   ```
3. Update `config.php` with your database credentials and base app URL.
4. Ensure the web server can read/write the project directory.
5. Serve the project. For quick testing you can run:
   ```bash
   php -S localhost:8000 -t /workspace
   ```

## QR Workflow
- When you add a student, the system generates a random token and stores it.
- The students list displays a QR image sourced from `https://api.qrserver.com/` with the token embedded.
- Printing or sharing the QR allows scanners to read the token string.
- The scanner page reads tokens via camera, posts them to `record_attendance.php`, and logs the event.

## Configuration Notes
- Tokens are stored in the `students.qr_token` column and must be unique.
- `record_attendance.php` prevents duplicate scans within 60 seconds to avoid double entries.
- Timestamps are stored in server time (`attendance.recorded_at`). Adjust PHP timezone via `date.timezone` if needed.

## Troubleshooting
- If you see “Database connection failed”, double-check `config.php` and that the MySQL server is running.
- Camera access prompts come from the browser; allow them for the scanner page to work.
- External assets (Google Fonts, QR API, html5-qrcode) require internet access.
