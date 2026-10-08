<?php
/**
 * English language file for Tournament Extension
 */

if (!isset($lang) || !is_array($lang)) {
    $lang = [];
}

$lang = array_merge($lang, [
    'ACP_TOURNAMENT' => 'Tournament',
    'ACP_TOURNAMENT_OVERVIEW' => 'Tournament Overview',
    'ACP_TOURNAMENT_MANAGE' => 'Manage Tournaments',
    'TOURNAMENT_TITLE' => 'Tournament',
    'TOURNAMENT_LEADERBOARD' => 'Leaderboard',
    'TOURNAMENT_NO_ACTIVE' => 'No active tournament at the moment.',
    'NO_AUTH_TOURNAMENT' => 'You do not have permission to view tournaments.',
    'TOURNAMENT_STATUS_ACTIVE' => 'Active',
    'TOURNAMENT_STATUS_PENDING' => 'Pending',
    'TOURNAMENT_STATUS_ENDED' => 'Ended',
]);
