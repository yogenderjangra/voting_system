# AmiVote - University/Classroom Online Voting System
A modern PHP and MySQL based online voting system, designed for secure, fair, and transparent polling for colleges and universities.
 
## 🚀 Features(Detailed features at the bottom of page )

- **Admin Panel:** Create/manage polls, add candidates, archive results,have graphs to see the growth,view voters.
- **Student Dashboard:** Vote in active polls, see final results (only after poll ends).
- **Photo Uploads:** Candidates have images                 (only add photo present in /assets/images).
- **Voting Security:** Each user can only vote once per poll.
- **Professional UI:** Glassmorphism cards, mobile responsive, beautiful DataTables.
- **Result Visualization:** Winner highlighting, vote counts, and percentages.



## Database Setup

1. Open phpMyAdmin (or MySQL command line).
2. Create a database named `voting_system`.
3. Import the `voting_system.sql` file from this repo:
   - In phpMyAdmin, select your database, click "Import", choose `voting_system.sql`, and click "Go".
4. Update your database connection details in `includes/db.php` if needed.


---

## 🗂️ Main Database Tables (see .sql for details)

| Table        | Description                            |
|--------------|----------------------------------------|
| admins       | Login info for admins                  |
| polls        | Each poll (title, description, dates)  |
| candidates   | Candidates (linked to polls)           |
| users        | Student/user data                      |
| votes        | Vote records (user, candidate, poll)   |
| feedback     | Feedback from users (optional)         |

---

## 🔑 Sample Admin Login

| Username | Password |
|----------|----------|
| ram      | 221      |
| rohit    | 12341    |

_Default users may be included in sample data; make your own for production use._

---

## 🌟 Highlights

- **Only final poll results shown to students after poll ends.**
- **Modern UI:** Glass-style cards, responsive layouts, DataTables for exporting.
- **Secure:** All voting/use must be logged in; voting is double-checked to prevent abuse.
- **Easily extendable:** Add charts, authentication, other features as needed.

---

## 👩‍💻 Developer Notes

- Compatible with PHP 8x+ and MySQL 5.7+/MariaDB.
- For best experience, use modern browsers (Chrome, Edge, Firefox).
- Images should be placed in `/assets/images/` for candidates.
- You can adjust styling in the provided CSS or use Bootstrap classes.

---

## 📣 Credits

- UI/UX & backend: [yogender]
- Database design: [Yogender]
- Powered by [phpMyAdmin](https://www.phpmyadmin.net/), [Bootstrap](https://getbootstrap.com/), [Font Awesome](https://fontawesome.com/), [DataTables JS](https://datatables.net/).

---

## 📬 Feedback / Questions

Open issues or contact [Your GitHub Username](https://github.com/yogenderjangra).

---
  🚀 detailed Features
User Authentication & Security

Separate login and privilege systems for admins and students.

Secure sessions for all authenticated actions.

Modern Student Dashboard

Active Polls: Dynamically lists currently available and upcoming polls.

Voting: Simple, one-vote-per-user system per poll, strictly enforced.

Candidate Display: See candidates for each poll with names, positions, status, and photos.

Responsive Design: Fluid layout, glassmorphism cards, and campus background adapt to any device or screen size.

Voting UI/UX: Animated cards, smooth transitions, auto-scroll to sections, and a floating back-to-top button.

Results Visualization: After a poll ends, users can view a highlighted final results table with votes, percentages, and winner banners.

Admin Management Panel

Poll Creation & Management: Create new polls, edit titles/descriptions/dates, and auto-archive expired polls.

Candidate Management: Add, edit, and upload candidate images. Poll-specific links for fast entry.

Voter Overview: Admins can view voters, see who-voted-for-whom, and review activity.

Interactive Dashboards: Real-time statistics for users, candidates, votes, and poll statuses using Bootstrap cards.

Charts & Analytics: Visualize votes/month, new voter registrations, and poll status with Chart.js.

Advanced Web UI & Interaction

Bootstrap 5 & DataTables: Professional tables—search, export (CSV/PDF), printing, columns toggle, responsive design.

Card Animations: Sections gently fade/reveal as users scroll.

Sticky Navbar: Quick navigation with scrolling indicator and mobile menu.

Custom Scrollbars & Button Feedback: Visual cues for modern web feel.

Notification Alerts: Inform users and admins of vote success, errors, and major actions.

System Integrity & Data Best Practices

Input Validation/Sanitization: All user inputs checked and safe.

Image Upload Validation: Only image files allowed for candidate photo uploads.

Hashed Passwords: All secrets are securely stored.

Robust SQL Structure: Fully normalized database, with foreign key constraints and indexes for speed and reliability.

Easy Reset/Setup: voting_system.sql included for cloning and quick database setup.

Other Key Features

Feedback Module: Optional user feedback to admin table.

Archiving: Automatic and manual archiving/unarchiving of expired polls—keeps dashboard clean.

Mobile-First: Designed and tested for mobile, tablet, and desktop devices.

Easy for New Developers: Simple file structure, clear variable names, and plenty of comments/documentation.

