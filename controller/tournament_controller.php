<?php
/**
 * Tournament controller
 */

namespace kikileharani\tournament\controller;

use phpbb\controller\helper;
use phpbb\template\template;
use phpbb\user;
use phpbb\auth\auth;
use phpbb\db\driver\driver_interface;
use phpbb\config\config;

class tournament_controller
{
    protected $helper;
    protected $template;
    protected $user;
    protected $auth;
    protected $db;
    protected $config;

    public function __construct(helper $helper, template $template, user $user, auth $auth, driver_interface $db, config $config)
    {
        $this->helper = $helper;
        $this->template = $template;
        $this->user = $user;
        $this->auth = $auth;
        $this->db = $db;
        $this->config = $config;
    }

    public function index()
    {
        if (!$this->auth->acl_get('u_tournament_view')) {
            trigger_error('NO_AUTH_TOURNAMENT');
        }

        $table_prefix = $this->config['table_prefix'];
        $tournament_table = $table_prefix . 'tournament';

        if (!$this->db->sql_table_exists($tournament_table)) {
            return $this->helper->message('The Tournament extension is not installed yet.', [], 'ERROR');
        }

        $sql = 'SELECT * FROM ' . $tournament_table . ' WHERE status = "active" ORDER BY tournament_id DESC LIMIT 1';
        $result = $this->db->sql_query($sql);
        $tournament = $this->db->sql_fetchrow($result);
        $this->db->sql_freeresult($result);

        if (!$tournament) {
            $this->template->assign_vars([
                'NO_TOURNAMENT' => true,
                'TOURNAMENT_MESSAGE' => $this->user->lang('TOURNAMENT_NO_ACTIVE'),
            ]);

            return $this->helper->render('tournament_index.html', $this->user->lang('TOURNAMENT_TITLE'));
        }

        $score_table = $table_prefix . 'tournament_scores';
        $users_table = $table_prefix . 'users';

        $sql = 'SELECT ts.score, ts.user_id, u.username, u.user_colour
                FROM ' . $score_table . ' ts
                LEFT JOIN ' . $users_table . ' u ON (u.user_id = ts.user_id)
                WHERE ts.tournament_id = ' . (int) $tournament['tournament_id'] . '
                ORDER BY ts.score DESC, ts.score_date ASC
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
            'TOURNAMENT_ID' => (int) $tournament['tournament_id'],
            'TOURNAMENT_NAME' => $tournament['tournament_name'],
            'TOURNAMENT_STATUS' => $tournament['status'],
            'TOURNAMENT_START' => $this->user->format_date($tournament['start_date']),
            'TOURNAMENT_END' => $this->user->format_date($tournament['end_date']),
            'LEADERBOARD' => $leaderboard,
        ]);

        return $this->helper->render('tournament_index.html', $this->user->lang('TOURNAMENT_TITLE'));
    }

    public function leaderboard()
    {
        if (!$this->auth->acl_get('u_tournament_view')) {
            trigger_error('NO_AUTH_TOURNAMENT');
        }

        $table_prefix = $this->config['table_prefix'];
        $score_table = $table_prefix . 'tournament_scores';
        $users_table = $table_prefix . 'users';

        $sql = 'SELECT ts.user_id, u.username, u.user_colour, SUM(ts.score) AS total_score
                FROM ' . $score_table . ' ts
                LEFT JOIN ' . $users_table . ' u ON (u.user_id = ts.user_id)
                GROUP BY ts.user_id, u.username, u.user_colour
                ORDER BY total_score DESC
                LIMIT 100';

        $result = $this->db->sql_query($sql);
        $rows = [];
        $rank = 1;
        while ($row = $this->db->sql_fetchrow($result)) {
            $row['rank'] = $rank++;
            $rows[] = $row;
        }
        $this->db->sql_freeresult($result);

        $this->template->assign_vars([
            'FULL_LEADERBOARD' => $rows,
        ]);

        return $this->helper->render('tournament_leaderboard.html', $this->user->lang('TOURNAMENT_LEADERBOARD'));
    }
}
