<?php
/**
 * Tournament Controller - Frontend
 */

namespace kikileharani\tournament\controller;

class tournament_controller
{
    protected $template;
    protected $user;
    protected $auth;
    protected $db;
    protected $language;
    protected $tables;

    public function __construct(
        \phpbb\template\template $template,
        \phpbb\user $user,
        \phpbb\auth\auth $auth,
        \phpbb\db\driver\driver_interface $db,
        \phpbb\language\language $language
    ) {
        $this->template = $template;
        $this->user = $user;
        $this->auth = $auth;
        $this->db = $db;
        $this->language = $language;
        $this->tables = [
            'tournament' => 'phpbb_tournament',
            'tournament_participants' => 'phpbb_tournament_participants',
            'tournament_scores' => 'phpbb_tournament_scores',
        ];
    }

    /**
     * Main tournament page
     */
    public function index()
    {
        if (!$this->auth->acl_get('u_tournament_view')) {
            trigger_error('NO_AUTH_TOURNAMENT');
        }

        // Get active tournament
        $sql = 'SELECT * FROM ' . $this->tables['tournament'] . '
                WHERE status = "active"
                LIMIT 1';
        
        $result = $this->db->sql_query($sql);
        $tournament = $this->db->sql_fetchrow($result);
        $this->db->sql_freeresult($result);

        if (!$tournament) {
            $this->template->assign_vars([
                'NO_TOURNAMENT' => true,
                'MESSAGE' => 'No active tournament at the moment.',
            ]);
            return new \Symfony\Component\HttpFoundation\Response($this->template->parse('tournament_index.html'), 200);
        }

        // Get top scores
        $sql = 'SELECT ts.score, ts.user_id, u.username, u.user_colour
                FROM ' . $this->tables['tournament_scores'] . ' ts
                JOIN ' . USERS_TABLE . ' u ON ts.user_id = u.user_id
                WHERE ts.tournament_id = ' . (int)$tournament['tournament_id'] . '
                GROUP BY ts.user_id
                ORDER BY ts.score DESC
                LIMIT 20';
        
        $result = $this->db->sql_query($sql);
        $leaderboard = [];
        $rank = 1;
        while ($row = $this->db->sql_fetchrow($result)) {
            $row['rank'] = $rank++;
            $leaderboard[] = $row;
        }
        $this->db->sql_freeresult($result);

        $this->template->assign_vars([
            'TOURNAMENT_ID' => $tournament['tournament_id'],
            'TOURNAMENT_NAME' => $tournament['tournament_name'],
            'TOURNAMENT_STATUS' => $tournament['status'],
            'TOURNAMENT_START' => $this->user->format_date($tournament['start_date']),
            'TOURNAMENT_END' => $this->user->format_date($tournament['end_date']),
            'LEADERBOARD' => $leaderboard,
        ]);

        return new \Symfony\Component\HttpFoundation\Response($this->template->parse('tournament_index.html'), 200);
    }

    /**
     * Detailed leaderboard
     */
    public function leaderboard()
    {
        if (!$this->auth->acl_get('u_tournament_view')) {
            trigger_error('NO_AUTH_TOURNAMENT');
        }

        $sql = 'SELECT SUM(score) as total_score, user_id, username, user_colour
                FROM ' . $this->tables['tournament_scores'] . ' ts
                JOIN ' . USERS_TABLE . ' u ON ts.user_id = u.user_id
                GROUP BY ts.user_id
                ORDER BY total_score DESC
                LIMIT 100';
        
        $result = $this->db->sql_query($sql);
        $leaderboard = [];
        $rank = 1;
        while ($row = $this->db->sql_fetchrow($result)) {
            $row['rank'] = $rank++;
            $leaderboard[] = $row;
        }
        $this->db->sql_freeresult($result);

        $this->template->assign_vars([
            'FULL_LEADERBOARD' => $leaderboard,
        ]);

        return new \Symfony\Component\HttpFoundation\Response($this->template->parse('tournament_leaderboard.html'), 200);
    }

    /**
     * User profile tournament stats
     */
    public function user_stats($user_id)
    {
        $user_id = (int)$user_id;

        $sql = 'SELECT ts.score, g.game_name, ts.score_date
                FROM ' . $this->tables['tournament_scores'] . ' ts
                JOIN phpbb_arcade_games g ON ts.game_id = g.game_id
                WHERE ts.user_id = ' . $user_id . '
                ORDER BY ts.score DESC';
        
        $result = $this->db->sql_query($sql);
        $user_scores = [];
        while ($row = $this->db->sql_fetchrow($result)) {
            $user_scores[] = $row;
        }
        $this->db->sql_freeresult($result);

        return $user_scores;
    }
}
