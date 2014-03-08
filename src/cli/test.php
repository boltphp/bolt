<?php

namespace app\cli;

class test extends \bolt\cli\command {

    static $ns = 'app';

    static $name = 'test';

    static $configure = [
        'arguments' => [
            'say' => [
                'mode' => self::REQUIRED,
                'description' => 'What would you like to say'
            ]
        ],
        'options' => [
            'yell' => [
                'mode' => self::VALUE_NONE,
                'description' => 'Should I yell?'
            ]
        ]
    ];

    public function call($say) {

        if ($this->opt('yell')) {
            $say = "<options=bold>".strtoupper($say)."</options=bold>";
        }

        $this->writeln($say);

    }

}