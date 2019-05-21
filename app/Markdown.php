<?php
namespace App;
use Parsedown;

/**
* Just an inherited parser with disabled lists.
* Was needed because every article starts with a date 201x. xx. xx
* And the parser made it a lsit
* @author     Sasszem
*/
class Markdown extends Parsedown
{
        protected $BlockTypes = array(
        '#' => array('Header'),
        '*' => array('Rule', 'List'),
        '+' => array('List'),
        '-' => array('SetextHeader', 'Table', 'Rule', 'List'),
        ':' => array('Table'),
        '<' => array('Comment', 'Markup'),
        '=' => array('SetextHeader'),
        '>' => array('Quote'),
        '[' => array('Reference'),
        '_' => array('Rule'),
        '`' => array('FencedCode'),
        '|' => array('Table'),
        '~' => array('FencedCode'),
    );
}