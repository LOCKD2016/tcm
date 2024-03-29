<?php
/**
 * Created by PhpStorm.
 * User: lbbniu
 * Date: 16/11/4
 * Time: 上午10:48
 */

namespace App\SchemaExtend;


use Illuminate\Support\Fluent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Connection;
class MySqlGrammar extends \Illuminate\Database\Schema\Grammars\MySqlGrammar
{
    /**
     * Get the SQL for a "comment" column modifier.
     *
     * @param \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param \Illuminate\Support\Fluent             $column
     * @return string|null
     */
    protected function modifyComment(Blueprint $blueprint, Fluent $column)
    {
        if ( ! is_null($column->comment))
        {
            $comment = str_replace("'", "\'", $column->comment);
            return " comment '".$comment."'";
        }
    }
    /**
     * Compile a create table command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @param  \Illuminate\Database\Connection  $connection
     * @return string
     */
    public function compileCreate(Blueprint $blueprint, Fluent $command, Connection $connection)
    {
        $sql = parent::compileCreate($blueprint, $command, $connection);
        # 表注释支持
        if (isset($blueprint->comment))
        {
            $comment = str_replace("'", "\'", $blueprint->comment);
            $sql .= " comment = '".$comment."'";
        }
        return $sql;
    }
}