<?php
/**
 * Fichier de langue française - Extension Tournament
 */

if (!isset($lang) || !is_array($lang)) {
    $lang = [];
}

$lang = array_merge($lang, [
    'TOURNAMENT' => 'Tournoi',
    'TOURNAMENT_TITLE' => 'Tournoi',
    'TOURNAMENT_LEADERBOARD' => 'Classement',
    'TOURNAMENT_STATUS' => 'Statut',
    'TOURNAMENT_ACTIVE' => 'Actif',
    'TOURNAMENT_ENDED' => 'Terminé',
    'TOURNAMENT_NOT_STARTED' => 'Non commencé',
    'TOURNAMENT_PENDING' => 'En attente',
    'TOURNAMENT_SCORE' => 'Score',
    'TOURNAMENT_RANK' => 'Classement',
    'TOURNAMENT_PLAYER' => 'Joueur',
    'TOURNAMENT_DATE' => 'Date',
    'TOURNAMENT_START_DATE' => 'Date de début',
    'TOURNAMENT_END_DATE' => 'Date de fin',
    'TOURNAMENT_DESCRIPTION' => 'Description',
    'TOURNAMENT_PARTICIPANTS' => 'Participants',
    'TOURNAMENT_NO_ACTIVE' => 'Aucun tournoi actif en ce moment.',
    'TOURNAMENT_TOP_SCORES' => 'Meilleurs scores',
    'TOURNAMENT_YOUR_STATS' => 'Vos statistiques du tournoi',
    'NO_AUTH_TOURNAMENT' => 'Vous n\'avez pas la permission de consulter les tournois.',
    'NO_TOURNAMENT' => 'Aucun tournoi disponible',
    'INVALID_TOURNAMENT_NAME' => 'Nom du tournoi invalide.',
    'TOURNAMENT_CREATED_SUCCESS' => 'Tournoi créé avec succès !',
    'TOURNAMENT_UPDATED_SUCCESS' => 'Tournoi mis à jour avec succès !',
    'TOURNAMENT_NOT_FOUND' => 'Tournoi non trouvé.',
    'ACP_TOURNAMENT_CREATED' => 'Tournoi créé : %s',
    'ACP_TOURNAMENT_EDITED' => 'Tournoi modifié : %s',
]);
