# Tournament Extension for phpBB

**Version:** 1.0.0  
**phpBB:** 3.3.5+  
**Compatibility:** Relax Arcade 1.0.28  
**License:** GPL-2.0-only

## Description

The Tournament Extension adds a complete tournament management system to phpBB 3.3.5 with seamless integration to the Relax Arcade extension. This extension allows administrators to create, manage, and run tournaments while automatically tracking scores from arcade games.

## Features

✅ **Tournament Management**
- Create and manage multiple tournaments
- Set start and end dates
- Track tournament status (Pending, Active, Ended)
- Detailed tournament descriptions

✅ **Score Tracking**
- Automatic score recording from Relax Arcade games
- Per-game and total scores
- User tournament statistics

✅ **Leaderboards**
- Real-time leaderboard updates
- Top 20 current scores
- Full leaderboard with 100 top players
- Per-user tournament statistics

✅ **User Integration**
- Participant management
- User profile tournament stats
- Tournament score display

✅ **ACP Administration**
- Full tournament CRUD operations
- Status management
- Score monitoring
- User logging for audit trail

✅ **Multilingual Support**
- English (en)
- French (fr)
- Easy to extend with additional languages

## Installation

### Method 1: Via ACP
1. Download the extension as ZIP
2. Extract to `ext/kikileharani/tournament/`
3. Go to ACP → Customize → Manage Extensions
4. Find "Tournament Extension" and click **Enable**
5. The database tables will be created automatically

### Method 2: Manual Database Setup
If automatic migration fails:

1. Import `sql/install.sql` into your phpBB database
2. Place extension files in `ext/kikileharani/tournament/`
3. Enable extension through ACP

## Database Tables

### phpbb_tournament
Main tournaments table
- `tournament_id` - Primary key
- `tournament_name` - Tournament name
- `tournament_description` - Tournament description
- `status` - Current status (pending/active/ended)
- `start_date` - Unix timestamp
- `end_date` - Unix timestamp
- `created_date` - Unix timestamp
- `updated_date` - Unix timestamp

### phpbb_tournament_participants
Tournament participants tracking
- `participant_id` - Primary key
- `tournament_id` - Foreign key
- `user_id` - Foreign key
- `joined_date` - Unix timestamp
- `status` - Participant status (active/inactive)

### phpbb_tournament_scores
Gameplay scores
- `score_id` - Primary key
- `tournament_id` - Foreign key
- `game_id` - Arcade game ID
- `user_id` - Foreign key
- `score` - Game score value
- `score_date` - Unix timestamp

### phpbb_users (modified)
Added column:
- `user_tournament_score` - Total tournament score

## Relax Arcade Integration

This extension hooks into Relax Arcade events:

- **arcade_game_end** - Records game completion
- **arcade_submit_score** - Updates tournament scores
- **arcade_page_header** - Displays tournament info on arcade pages

## Permissions

New permissions added:
- `u_tournament_view` - User can view tournaments
- `a_tournament_manage` - Administrator can manage tournaments

## Configuration

No additional configuration needed. The extension works out of the box with default settings.

## API Events

### Available Events for Extension Developers

```php
// When arcade game ends
'arcade_game_end' => $event[
    'game_id' => (int),
    'user_id' => (int),
    'score' => (int),
]

// When score is submitted
'arcade_submit_score' => $event[
    'game_id' => (int),
    'user_id' => (int),
    'score' => (int),
]

// Arcade page header
'arcade_page_header' => $event[
    'tournament_active' => (bool),
    'tournament_name' => (string),
    'tournament_id' => (int),
]
```

## SQL Queries Reference

### Get Active Tournament
```sql
SELECT * FROM phpbb_tournament WHERE status = 'active' LIMIT 1;
```

### Get Top 20 Scores
```sql
SELECT ts.score, ts.user_id, u.username, u.user_colour
FROM phpbb_tournament_scores ts
JOIN phpbb_users u ON ts.user_id = u.user_id
WHERE ts.tournament_id = {tournament_id}
GROUP BY ts.user_id
ORDER BY ts.score DESC
LIMIT 20;
```

### Get User Tournament Stats
```sql
SELECT SUM(score) as total_score, COUNT(*) as games_played
FROM phpbb_tournament_scores
WHERE tournament_id = {tournament_id} AND user_id = {user_id};
```

### Get All Participants
```sql
SELECT u.user_id, u.username, tp.joined_date, tp.status
FROM phpbb_tournament_participants tp
JOIN phpbb_users u ON tp.user_id = u.user_id
WHERE tp.tournament_id = {tournament_id};
```

## Uninstallation

1. Go to ACP → Customize → Manage Extensions
2. Click **Disable** on Tournament Extension
3. Click **Delete Data** (optional - to remove database tables)
4. Delete the `ext/kikileharani/tournament/` folder

Alternatively, manually import `sql/uninstall.sql` before deleting files.

## Support

For issues and feature requests, please visit:
https://github.com/kikileharani-max/phpbb-tournament-extension/issues

## Changelog

### 1.0.0 (2026-10-08)
- Initial release
- Relax Arcade 1.0.28 integration
- Tournament CRUD operations
- Score tracking and leaderboards
- Multilingual support (EN/FR)
- ACP management interface

## License

GNU General Public License v2.0 only (GPL-2.0-only)

## Author

**kikileharani-max**
