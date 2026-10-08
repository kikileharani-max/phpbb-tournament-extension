<?php
/**
 * ACP Tournament Module
 */

namespace kikileharani\tournament\acp;

class main_module
{
    public $page_title;
    public $tpl_name;
    public $u_action;

    public function main($id, $mode)
    {
        global $request, $template, $user, $phpbb_log;

        switch ($mode) {
            case 'overview':
                $this->page_title = $user->lang('ACP_TOURNAMENT_OVERVIEW');
                $this->tpl_name = 'acp_tournament_overview';
                break;

            case 'manage':
                $this->page_title = $user->lang('ACP_TOURNAMENT_MANAGE');
                $this->tpl_name = 'acp_tournament_manage';
                break;
        }
    }
}
