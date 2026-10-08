<?php
/**
 * ACP Tournament Module Info
 */

namespace kikileharani\tournament\acp;

class main_info
{
    public function module()
    {
        return [
            'filename' => '\\kikileharani\\tournament\\acp\\main_module',
            'title' => 'ACP_TOURNAMENT',
            'modes' => [
                'overview' => ['title' => 'ACP_TOURNAMENT_OVERVIEW', 'auth' => 'ext_kikileharani/tournament && a_tournament_manage', 'cat' => ['ACP_CAT_ARCADE']],
                'manage' => ['title' => 'ACP_TOURNAMENT_MANAGE', 'auth' => 'ext_kikileharani/tournament && a_tournament_manage', 'cat' => ['ACP_CAT_ARCADE']],
            ],
        ];
    }
}
