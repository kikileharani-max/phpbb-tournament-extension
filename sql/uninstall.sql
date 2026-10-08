-- Tournament Extension SQL Uninstall Script

-- Drop indexes
DROP INDEX idx_user_tournament_score ON `phpbb_users`;

-- Drop column from users table
ALTER TABLE `phpbb_users` DROP COLUMN `user_tournament_score`;

-- Drop tables
DROP TABLE IF EXISTS `phpbb_tournament_scores`;
DROP TABLE IF EXISTS `phpbb_tournament_participants`;
DROP TABLE IF EXISTS `phpbb_tournament`;
