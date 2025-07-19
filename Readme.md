## Database Setup

1. Open phpMyAdmin (or MySQL command line).
2. Create a database named `voting_system`.
3. Import the `voting_system.sql` file from this repo:
   - In phpMyAdmin, select your database, click "Import", choose `voting_system.sql`, and click "Go".
4. Update your database connection details in `includes/db.php` if needed.

**Database Tables:**
- users
- candidates
- polls
- votes
- (any others you have)

**Each table's columns and types are defined in the SQL file.**
