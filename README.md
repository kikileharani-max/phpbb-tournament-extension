# Final Tournament Extension

This extension is a compatible base for phpBB 3.3.5 and Relax Arcade 1.0.28.

## Features
- tournament table creation
- score tracking from arcade events
- public leaderboard page
- ACP language strings
- migration support

## Installation
1. Upload to `ext/kikileharani/tournament/`
2. Enable extension in ACP
3. Run migration or import `sql/install.sql`

## Important Note
Relax Arcade 1.0.28 may use event names slightly different depending on the installed version. This extension uses the standard events `arcade_game_end`, `arcade_submit_score`, and `arcade_page_header`.
