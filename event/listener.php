<?php
/**
 * Tournament Event Listener
 * Compatible with phpBB 3.3.5 / Relax Arcade 1.0.28
 */

namespace kikileharani\tournament\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
    protected $db;
    protected $user;
    protected $config;

    public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\user $user, \phpbb\config\config $config)
    {
        $this->db = $db;
        $this->user = $user;
        $this->config = $config;
    }

    public static function getSubscribedEvents()
    {
        return [
            'arcade_game_end' => 'on_arcade_game_end',
            'arcade_submit_score' => 'on_arcade_submit_score',
            'arcade_page_header' => 'on_arcade_page_header',
            'core.user_setup' => 'on_user_setup',
        ];
    }

    public function on_arcade_game_end($event)
    {
        $game_id = isset($event['game_id']) ? (int) $event['game_id'] : 0;
        $user_id = isset($event['user_id']) ? (int) $event['user_id'] : 0;
        $score = isset($event['score']) ? (int) $event['score'] : 0;

        if (!$game_id || !$user_id) {
            return;
        }

        $table_prefix = $this->config['table_prefix'];
        $tournament_table = $table_prefix . 'tournament';
        $score_table = $table_prefix . 'tournament_scores';

        if (!$this->db->sql_table_exists($tournament_table) || !$this->db->sql_table_exists($score_table)) {
            return;
        }

        $active_tournament = $this->db->sql_query_limit(
            'SELECT tournament_id FROM ' . $tournament_table . ' WHERE status = \"active\" ORDER BY tournament_id DESC',
            1
        );
        $active_row = $this->db->sql_fetchrow($active_tournament);
        $this->db->sql_freeresult($active_tournament);

        if (!$active_row) {
            return;
        }

        $tournament_id = (int) $active_row['tournament_id'];

        $sql = 'SELECT score_id FROM ' . $score_table . '
                WHERE tournament_id = ' . $tournament_id . '
                  AND game_id = ' . $game_id . '
                  AND user_id = ' . $user_id;
        $result = $this->db->sql_query($sql);
        $row = $this->db->sql_fetchrow($result);
        $this->db->sql_freeresult($result);

        if ($row) {
            $sql = 'UPDATE ' . $score_table . ' SET score = ' . $score . ', score_date = ' . time() . '
                    WHERE score_id = ' . (int) $row['score_id'];
        } else {
            $sql = 'INSERT INTO ' . $score_table . ' (tournament_id, game_id, user_id, score, score_date)
                    VALUES (' . $tournament_id . ', ' . $game_id . ', ' . $user_id . ', ' . $score . ', ' . time() . ')';
        }

        $this->db->sql_query($sql);
    }

    public function on_arcade_submit_score($event)
    {
        $user_id = isset($event['user_id']) ? (int) $event['user_id'] : 0;
        $score = isset($event['score']) ? (int) $event['score'] : 0;

        if (!$user_id) {
            return;
        }

        $table_prefix = $this->config['table_prefix'];
        $users_table = $table_prefix . 'users';

        if (!$this->db->sql_table_exists($users_table)) {
            return;
        }

        $sql = 'UPDATE ' . $users_table . ' SET user_tournament_score = user_tournament_score + ' . $score . ' WHERE user_id = ' . $user_id;
        $this->db->sql_query($sql);
    }

    public function on_arcade_page_header($event)
    {
        $table_prefix = $this->config['table_prefix'];
        $tournament_table = $table_prefix . 'tournament';

        if (!$this->db->sql_table_exists($tournament_table)) {
            return;
        }

        $sql = 'SELECT tournament_id, tournament_name FROM ' . $tournament_table . ' WHERE status = "active" ORDER BY tournament_id DESC LIMIT 1';
        $result = $this->db->sql_query($sql);
        $row = $this->db->sql_fetchrow($result);
        $this->db->sql_freeresult($result);

        if (!$row) {
            return;
        }

        $event['tournament_active'] = true;
        $event['tournament_name'] = $row['tournament_name'];
        $event['tournament_id'] = (int) $row['tournament_id'];
    }

    public function on_user_setup($event)
    {
        $lang_set_ext = $event['lang_set_ext'];
        $lang_set_ext[] = [
            'ext_name' => 'kikileharani/tournament',
            'lang_set' => 'common',
        ];
        $event['lang_set_ext'] = $lang_set_ext;
    }
}
