<?php
namespace Radio\Util;

class Formatter
{

    public $transformers;

    public function __construct()
    {
        $this->transformers = [
            'legacy' => new LegacyTransformer(),
            'normal' => new NormalTransformer()
        ];
    }

    public function format($type, $content)
    {
        if (key_exists($type, $this->transformers)) {
            return $this->transformers[$type]->format($content);
        } else {
            return null;
        }
    }
}
?>