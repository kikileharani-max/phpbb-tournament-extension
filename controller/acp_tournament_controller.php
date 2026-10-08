<?php
/**
 * ACP Tournament Controller - Administration
 */

namespace kikileharani\tournament\controller;

class acp_tournament_controller
{
    protected $template;
    protected $user;
    protected $auth;
    protected $db;
    protected $request;
    protected $log;
    protected $tables;

    public function __construct(
        \phpbb\template\template $template,
        \phpbb\user $user,
        \phpbb\auth\auth $auth,
        \phpbb\db\driver\driver_interface $db,
        \phpbb\request\request $request,
        \phpbb\log\log $log
    ) {
        $this->template = $template;
        $this->user = $user;
        $this->auth = $auth;
        $this->db = $db;
        $this->request = $request;
        $this->log = $log;
        $this->tables = [
            'tournament' => 'phpbb_tournament',
            'tournament_participants' => 'phpbb_tournament_participants',
            'tournament_scores' => 'phpbb_tournament_scores',
        ];
    }

    /**
     * Main ACP page
     */
    public function index()
    {
        if (!$this->auth->acl_get('a_tournament_manage')) {
            trigger_error('NO_AUTH_TOURNAMENT');
        }

        // List all tournaments
        $sql = 'SELECT * FROM ' . $this->tables['tournament'] . ' ORDER BY tournament_id DESC';
        $result = $this->db->sql_query($sql);
        $tournaments = [];
        while ($row = $this->db->sql_fetchrow($result)) {
            $tournaments[] = $row;
        }
        $this->db->sql_freeresult($result);

        $this->template->assign_vars([
            'TOURNAMENTS' => $tournaments,
        ]);

        return new \Symfony\Component\HttpFoundation\Response($this->template->parse('acp_tournament_index.html'), 200);
    }

    /**
     * Create new tournament
     */
    public function create()
    {
        if (!$this->auth->acl_get('a_tournament_manage')) {
            trigger_error('NO_AUTH_TOURNAMENT');
        }

        if ($this->request->is_set_post('submit')) {
            $name = $this->request->variable('tournament_name', '', true);
            $description = $this->request->variable('tournament_description', '', true);
            $start_date = $this->request->variable('start_date', 0);
            $end_date = $this->request->variable('end_date', 0);
            $status = $this->request->variable('status', 'pending');

            if (!$name) {
                trigger_error('INVALID_TOURNAMENT_NAME');
            }

            $sql = 'INSERT INTO ' . $this->tables['tournament'] . '
                    (tournament_name, tournament_description, start_date, end_date, status, created_date)
                    VALUES (\'' . $this->db->sql_escape($name) . '\', \'' . $this->db->sql_escape($description) . '\', '
                    . (int)$start_date . ', ' . (int)$end_date . ', \'' . $this->db->sql_escape($status) . '\', ' . time() . ')';

            $this->db->sql_query($sql);
            $tournament_id = $this->db->sql_nextid();

            $this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'ACP_TOURNAMENT_CREATED', false, ['tournament_id' => $tournament_id]);

            trigger_error('TOURNAMENT_CREATED_SUCCESS');
        }

        $this->template->assign_vars([
            'MODE' => 'create',
        ]);

        return new \Symfony\Component\HttpFoundation\Response($this->template->parse('acp_tournament_edit.html'), 200);
    }

    /**
     * Edit tournament
     */
    public function edit($tournament_id = 0)
    {
        $tournament_id = (int)$tournament_id;

        if (!$this->auth->acl_get('a_tournament_manage')) {
            trigger_error('NO_AUTH_TOURNAMENT');
        }

        $sql = 'SELECT * FROM ' . $this->tables['tournament'] . ' WHERE tournament_id = ' . $tournament_id;
        $result = $this->db->sql_query($sql);
        $tournament = $this->db->sql_fetchrow($result);
        $this->db->sql_freeresult($result);

        if (!$tournament) {
            trigger_error('TOURNAMENT_NOT_FOUND');
        }

        if ($this->request->is_set_post('submit')) {
            $name = $this->request->variable('tournament_name', '', true);
            $status = $this->request->variable('status', 'pending');

            $sql = 'UPDATE ' . $this->tables['tournament'] . '
                    SET tournament_name = \'' . $this->db->sql_escape($name) . '\',
                    status = \'' . $this->db->sql_escape($status) . '\'
                    WHERE tournament_id = ' . $tournament_id;

            $this->db->sql_query($sql);
            $this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'ACP_TOURNAMENT_EDITED', false, ['tournament_id' => $tournament_id]);

            trigger_error('TOURNAMENT_UPDATED_SUCCESS');
        }

        $this->template->assign_vars([
            'MODE' => 'edit',
            'TOURNAMENT_ID' => $tournament['tournament_id'],
            'TOURNAMENT_NAME' => $tournament['tournament_name'],
            'TOURNAMENT_STATUS' => $tournament['status'],
        ]);

        return new \Symfony\Component\HttpFoundation\Response($this->template->parse('acp_tournament_edit.html'), 200);
    }
}
