<?php
/**
 * Created by PhpStorm.
 * User: ggalia84
 * Date: 17/01/16
 * Time: 23:07
 */

namespace Acme\Transformers;
class TaskTransformer extends Transformer
{
    public function transform($task)
    {
        return [
            'name' => $task['name'],
            'some_bool' => (boolean) $task['done'],
            'priority' => (int)$task['priority'],
        ];
    }
}