<?php
/**
 * Tournament Event Listener
 * Hooks into Relax Arcade events
 */

namespace kikileharani\tournament\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
    protected $db;
    protected $user;
    protected $tables;

    public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\user $user)
    {
        $this->db = $db;
        $this->user = $user;
        $this->tables = [
            'tournament' => 'phpbb_tournament',
            'tournament_participants' => 'phpbb_tournament_participants',
            'tournament_scores' => 'phpbb_tournament_scores',
        ];
    }

    static public function getSubscribedEvents()
    {
        return [
            'arcade_game_end' => 'on_arcade_game_end',
            'arcade_submit_score' => 'on_arcade_submit_score',
            'arcade_page_header' => 'on_arcade_page_header',
            'core_user_setup' => 'on_user_setup',
        ];
    }

    /**
     * Event: Arcade game ended
     * Update tournament scores
     */
    public function on_arcade_game_end($event)
    {
        $game_id = isset($event['game_id']) ? (int)$event['game_id'] : 0;
        $user_id = isset($event['user_id']) ? (int)$event['user_id'] : 0;
        $score = isset($event['score']) ? (int)$event['score'] : 0;

        if (!$user_id || !$game_id) {
            return;
        }

        // Insert or update tournament score
        $sql = 'SELECT score_id FROM ' . $this->tables['tournament_scores'] . '
                WHERE game_id = ' . $game_id . '
                AND user_id = ' . $user_id . '
                AND tournament_id = (SELECT tournament_id FROM ' . $this->tables['tournament'] . ' WHERE status = "active" LIMIT 1)';
        
        $result = $this->db->sql_query($sql);
        $row = $this->db->sql_fetchrow($result);
        $this->db->sql_freeresult($result);

        if ($row) {
            // Update existing score
            $sql = 'UPDATE ' . $this->tables['tournament_scores'] . '
                    SET score = ' . $score . ',
                    score_date = ' . time() . '
                    WHERE score_id = ' . (int)$row['score_id'];
        } else {
            // Insert new score
            $sql = 'INSERT INTO ' . $this->tables['tournament_scores'] . '
                    (tournament_id, game_id, user_id, score, score_date)
                    SELECT tournament_id, ' . $game_id . ', ' . $user_id . ', ' . $score . ', ' . time() . '
                    FROM ' . $this->tables['tournament'] . '
                    WHERE status = "active"
                    LIMIT 1';
        }

        $this->db->sql_query($sql);
    }

    /**
     * Event: Score submitted in Arcade
     * Process tournament ranking update
     */
    public function on_arcade_submit_score($event)
    {
        $game_id = isset($event['game_id']) ? (int)$event['game_id'] : 0;
        $user_id = isset($event['user_id']) ? (int)$event['user_id'] : 0;
        $score = isset($event['score']) ? (int)$event['score'] : 0;

        if (!$user_id) {
            return;
        }

        // Update user tournament total score
        $sql = 'UPDATE ' . USERS_TABLE . '
                SET user_tournament_score = user_tournament_score + ' . $score . '
                WHERE user_id = ' . $user_id;
        
        $this->db->sql_query($sql);
    }

    /**
     * Event: Arcade page header
     * Add tournament banner to arcade
     */
    public function on_arcade_page_header($event)
    {
        // Get active tournament info
        $sql = 'SELECT * FROM ' . $this->tables['tournament'] . '
                WHERE status = "active"
                LIMIT 1';
        
        $result = $this->db->sql_query($sql);
        $tournament = $this->db->sql_fetchrow($result);
        $this->db->sql_freeresult($result);

        if ($tournament) {
            $event['tournament_active'] = true;
            $event['tournament_name'] = $tournament['tournament_name'];
            $event['tournament_id'] = $tournament['tournament_id'];
        }
    }

    /**
     * Event: User setup
     * Add tournament language strings
     */
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
