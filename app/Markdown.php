<?php

namespace App;

use Parsedown;

/**
 * Just an inherited parser with disabled lists.
 * Was needed because every article starts with a date 201x. xx. xx
 * And the parser made it a list.
 *
 * @author     Sasszem
 */
class Markdown extends Parsedown
{
    protected $BlockTypes = [
        '#' => ['Header'],
        '*' => ['Rule', 'List'],
        '+' => ['List'],
        '-' => ['SetextHeader', 'Table', 'Rule', 'List'],
        ':' => ['Table'],
        '<' => ['Comment', 'Markup'],
        '=' => ['SetextHeader'],
        '>' => ['Quote'],
        '[' => ['Reference'],
        '_' => ['Rule'],
        '`' => ['FencedCode'],
        '|' => ['Table'],
        '~' => ['FencedCode'],
    ];
}
