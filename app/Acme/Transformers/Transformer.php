<?php
/**
 * Created by PhpStorm.
 * User: ggalia84
 * Date: 17/01/16
 * Time: 23:05
 */
namespace Acme\Transformers;
abstract class Transformer
{
    public function transformCollection(array $items)
    {
        return array_map([$this, 'transform'], $items);
    }

    public abstract function transform($item);
}