-- Final SQL install script for Tournament Extension
-- Compatible with phpBB 3.3.5 and Relax Arcade 1.0.28

CREATE TABLE IF NOT EXISTS `phpbb_tournament` (
  `tournament_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tournament_name` VARCHAR(255) NOT NULL,
  `tournament_description` TEXT NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `start_date` INT UNSIGNED NOT NULL DEFAULT 0,
  `end_date` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_date` INT UNSIGNED NOT NULL DEFAULT 0,
  `updated_date` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`tournament_id`),
  KEY `status_idx` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `phpbb_tournament_scores` (
  `score_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tournament_id` INT UNSIGNED NOT NULL,
  `game_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `score` BIGINT NOT NULL DEFAULT 0,
  `score_date` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`score_id`),
  KEY `tournament_idx` (`tournament_id`),
  KEY `user_idx` (`user_id`),
  KEY `score_idx` (`score`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `phpbb_tournament_participants` (
  `participant_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tournament_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `joined_date` INT UNSIGNED NOT NULL DEFAULT 0,
  `status` VARCHAR(20) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`participant_id`),
  KEY `tournament_idx` (`tournament_id`),
  KEY `user_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `phpbb_users`
  ADD COLUMN `user_tournament_score` BIGINT NOT NULL DEFAULT 0;
