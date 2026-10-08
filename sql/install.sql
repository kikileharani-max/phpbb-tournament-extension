-- Tournament Extension SQL Install Script
-- Compatible with phpBB 3.3.5 and Relax Arcade 1.0.28

-- Main tournaments table
CREATE TABLE IF NOT EXISTS `phpbb_tournament` (
  `tournament_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tournament_name` VARCHAR(255) NOT NULL,
  `tournament_description` TEXT,
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `start_date` INT UNSIGNED NOT NULL DEFAULT 0,
  `end_date` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_date` INT UNSIGNED NOT NULL DEFAULT 0,
  `updated_date` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`tournament_id`),
  KEY `status` (`status`),
  KEY `dates` (`start_date`, `end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tournament participants table
CREATE TABLE IF NOT EXISTS `phpbb_tournament_participants` (
  `participant_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tournament_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `joined_date` INT UNSIGNED NOT NULL DEFAULT 0,
  `status` VARCHAR(20) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`participant_id`),
  KEY `tournament` (`tournament_id`),
  KEY `user` (`user_id`),
  KEY `tournament_user` (`tournament_id`, `user_id`),
  FOREIGN KEY (`tournament_id`) REFERENCES `phpbb_tournament`(`tournament_id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `phpbb_users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tournament scores table
CREATE TABLE IF NOT EXISTS `phpbb_tournament_scores` (
  `score_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tournament_id` INT UNSIGNED NOT NULL,
  `game_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `score` BIGINT NOT NULL DEFAULT 0,
  `score_date` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`score_id`),
  KEY `tournament` (`tournament_id`),
  KEY `game` (`game_id`),
  KEY `user` (`user_id`),
  KEY `score_desc` (`score`),
  KEY `tournament_user` (`tournament_id`, `user_id`),
  FOREIGN KEY (`tournament_id`) REFERENCES `phpbb_tournament`(`tournament_id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `phpbb_users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add tournament score column to users table
ALTER TABLE `phpbb_users` ADD COLUMN `user_tournament_score` BIGINT DEFAULT 0;

-- Create index for tournament scores
CREATE INDEX idx_user_tournament_score ON `phpbb_users` (`user_tournament_score`);
