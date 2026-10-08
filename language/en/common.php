<?php
/**
 * English language file - Tournament Extension
 */

if (!isset($lang) || !is_array($lang)) {
    $lang = [];
}

$lang = array_merge($lang, [
    'TOURNAMENT' => 'Tournament',
    'TOURNAMENT_TITLE' => 'Tournament',
    'TOURNAMENT_LEADERBOARD' => 'Leaderboard',
    'TOURNAMENT_STATUS' => 'Status',
    'TOURNAMENT_ACTIVE' => 'Active',
    'TOURNAMENT_ENDED' => 'Ended',
    'TOURNAMENT_NOT_STARTED' => 'Not Started',
    'TOURNAMENT_PENDING' => 'Pending',
    'TOURNAMENT_SCORE' => 'Score',
    'TOURNAMENT_RANK' => 'Rank',
    'TOURNAMENT_PLAYER' => 'Player',
    'TOURNAMENT_DATE' => 'Date',
    'TOURNAMENT_START_DATE' => 'Start Date',
    'TOURNAMENT_END_DATE' => 'End Date',
    'TOURNAMENT_DESCRIPTION' => 'Description',
    'TOURNAMENT_PARTICIPANTS' => 'Participants',
    'TOURNAMENT_NO_ACTIVE' => 'No active tournament at the moment.',
    'TOURNAMENT_TOP_SCORES' => 'Top Scores',
    'TOURNAMENT_YOUR_STATS' => 'Your Tournament Stats',
    'NO_AUTH_TOURNAMENT' => 'You do not have permission to view tournaments.',
    'NO_TOURNAMENT' => 'No Tournament Available',
    'INVALID_TOURNAMENT_NAME' => 'Invalid tournament name.',
    'TOURNAMENT_CREATED_SUCCESS' => 'Tournament created successfully!',
    'TOURNAMENT_UPDATED_SUCCESS' => 'Tournament updated successfully!',
    'TOURNAMENT_NOT_FOUND' => 'Tournament not found.',
    'ACP_TOURNAMENT_CREATED' => 'Tournament created: %s',
    'ACP_TOURNAMENT_EDITED' => 'Tournament edited: %s',
]);
