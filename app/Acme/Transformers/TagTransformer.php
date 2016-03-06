<?php
/**
 * Created by PhpStorm.
 * User: ggalia84
 * Date: 17/01/16
 * Time: 23:06
 */
namespace Acme\Transformers;
class TagTransformer extends Transformer
{
    public function transform($tag)
    {
        return [
            'title' => $tag['title']
//            'some_bool' => (boolean) $tag['prova'],
        ];
    }
}