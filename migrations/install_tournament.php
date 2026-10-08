<?php
/**
 * Tournament database migration
 */

namespace kikileharani\tournament\migrations;

class install_tournament extends \phpbb\db\migration\migration
{
    public function effectively_installed()
    {
        return $this->db_tools->sql_table_exists($this->table_prefix . 'tournament');
    }

    static public function depends_on()
    {
        return ['\\phpbb\\db\\migration\\data\\v3_3_0\\extensions'];
    }

    public function update_schema()
    {
        return [
            'add_tables' => [
                $this->table_prefix . 'tournament' => [
                    'COLUMNS' => [
                        'tournament_id' => ['UINT', null, 'auto_increment'],
                        'tournament_name' => ['VCHAR', ''],
                        'tournament_description' => ['TEXT', ''],
                        'status' => ['VCHAR', 'pending'],
                        'start_date' => ['INT:11', 0],
                        'end_date' => ['INT:11', 0],
                        'created_date' => ['INT:11', 0],
                        'updated_date' => ['INT:11', 0],
                    ],
                    'PRIMARY_KEY' => 'tournament_id',
                ],
                $this->table_prefix . 'tournament_scores' => [
                    'COLUMNS' => [
                        'score_id' => ['UINT', null, 'auto_increment'],
                        'tournament_id' => ['UINT', 0],
                        'game_id' => ['UINT', 0],
                        'user_id' => ['UINT', 0],
                        'score' => ['BIGINT', 0],
                        'score_date' => ['INT:11', 0],
                    ],
                    'PRIMARY_KEY' => 'score_id',
                ],
                $this->table_prefix . 'tournament_participants' => [
                    'COLUMNS' => [
                        'participant_id' => ['UINT', null, 'auto_increment'],
                        'tournament_id' => ['UINT', 0],
                        'user_id' => ['UINT', 0],
                        'joined_date' => ['INT:11', 0],
                        'status' => ['VCHAR', 'active'],
                    ],
                    'PRIMARY_KEY' => 'participant_id',
                ],
            ],
            'add_columns' => [
                $this->table_prefix . 'users' => [
                    'user_tournament_score' => ['BIGINT', 0],
                ],
            ],
        ];
    }

    public function revert_schema()
    {
        return [
            'drop_tables' => [
                $this->table_prefix . 'tournament',
                $this->table_prefix . 'tournament_scores',
                $this->table_prefix . 'tournament_participants',
            ],
            'drop_columns' => [
                $this->table_prefix . 'users' => [
                    'user_tournament_score',
                ],
            ],
        ];
    }
}
