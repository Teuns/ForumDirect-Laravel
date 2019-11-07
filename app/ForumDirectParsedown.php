<?php

namespace App;

use Parsedown;

use Emojione\Client;
use Emojione\Ruleset;

class ForumDirectParsedown extends Parsedown
{
    public function line($text, $nonNestables = [])
    {
        $text = parent::line($text, $nonNestables);
        $client = new Client(new Ruleset());
        $client->ascii = true;
        $client->unicodeAlt = true;
        $text = $client->toImage($text);

        return $text;
    }
 
}