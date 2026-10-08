<?php
/**
 * French language file for Tournament Extension
 */

if (!isset($lang) || !is_array($lang)) {
    $lang = [];
}

$lang = array_merge($lang, [
    'ACP_TOURNAMENT' => 'Tournoi',
    'ACP_TOURNAMENT_OVERVIEW' => 'Vue d\'ensemble des tournois',
    'ACP_TOURNAMENT_MANAGE' => 'Gérer les tournois',
    'TOURNAMENT_TITLE' => 'Tournoi',
    'TOURNAMENT_LEADERBOARD' => 'Classement',
    'TOURNAMENT_NO_ACTIVE' => 'Aucun tournoi actif pour le moment.',
    'NO_AUTH_TOURNAMENT' => 'Vous n\'avez pas la permission de voir les tournois.',
    'TOURNAMENT_STATUS_ACTIVE' => 'Actif',
    'TOURNAMENT_STATUS_PENDING' => 'En attente',
    'TOURNAMENT_STATUS_ENDED' => 'Terminé',
]);
