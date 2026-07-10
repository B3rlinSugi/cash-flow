# Changelog

## [2.0.0] - 2026-06-01

### Changed
- **Security:** Complete eradication of MD5 hashing. Migrated to `bcrypt`.
- **Database:** Replaced outdated MySQLi/mysql_* functions with secure PDO Prepared Statements.
- **Architecture:** Upgraded schemas to support both PostgreSQL and MySQL (InnoDB).
- **UI:** Integrated Chart.js for real-time visual analytics.
