<?php
/**
 * Tournament Extension - Database Migration
 * Creates necessary tables and fields
 */

namespace kikileharani\tournament\migrations;

class install_tournament extends \phpbb\db\migration\migration
{
    public function effectively_installed()
    {
        return isset($this->config['tournament_version']);
    }

    static public function depends_on()
    {
        return ['\phpbb\db\migration\data\v3_3_0\extensions'];
    }

    public function update_schema()
    {
        return [
            'add_tables' => [
                $this->table_prefix . 'tournament' => [
                    'COLUMNS' => [
                        'tournament_id' => ['UINT', null, 'auto_increment'],
                        'tournament_name' => ['VARBINARY(255)', ''],
                        'tournament_description' => ['TEXT', ''],
                        'status' => ['VARCHAR(20)', 'pending'],
                        'start_date' => ['INT', 0],
                        'end_date' => ['INT', 0],
                        'created_date' => ['INT', 0],
                        'updated_date' => ['INT', 0],
                    ],
                    'PRIMARY_KEY' => 'tournament_id',
                    'KEYS' => [
                        'status' => ['status'],
                        'dates' => ['start_date', 'end_date'],
                    ],
                ],
                $this->table_prefix . 'tournament_participants' => [
                    'COLUMNS' => [
                        'participant_id' => ['UINT', null, 'auto_increment'],
                        'tournament_id' => ['UINT', 0],
                        'user_id' => ['UINT', 0],
                        'joined_date' => ['INT', 0],
                        'status' => ['VARCHAR(20)', 'active'],
                    ],
                    'PRIMARY_KEY' => 'participant_id',
                    'KEYS' => [
                        'tournament' => ['tournament_id'],
                        'user' => ['user_id'],
                        'tournament_user' => ['tournament_id', 'user_id'],
                    ],
                ],
                $this->table_prefix . 'tournament_scores' => [
                    'COLUMNS' => [
                        'score_id' => ['UINT', null, 'auto_increment'],
                        'tournament_id' => ['UINT', 0],
                        'game_id' => ['UINT', 0],
                        'user_id' => ['UINT', 0],
                        'score' => ['BIGINT', 0],
                        'score_date' => ['INT', 0],
                    ],
                    'PRIMARY_KEY' => 'score_id',
                    'KEYS' => [
                        'tournament' => ['tournament_id'],
                        'game' => ['game_id'],
                        'user' => ['user_id'],
                        'score_desc' => ['score'],
                        'tournament_user' => ['tournament_id', 'user_id'],
                    ],
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
                $this->table_prefix . 'tournament_participants',
                $this->table_prefix . 'tournament_scores',
            ],
            'drop_columns' => [
                $this->table_prefix . 'users' => [
                    'user_tournament_score',
                ],
            ],
        ];
    }
}
