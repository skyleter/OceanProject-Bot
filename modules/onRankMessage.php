<?php

$onRankMessage = function ($who, $message, $reason, $array) {
    $bot = actionAPI::getBot();
    
    if ($message[0] == "/") {
        $action = str_replace(range(0,9), '', $message);
        $duration = substr($message, strlen($action) - 1);
        switch (substr($action, 0, 2)) {
            case '/u': //unban
                if (substr_count($reason, ',') > 0) {// gameban
                    $reason  = explode(',', $reason);
                    $game    = $reason[0];
                    $seconds = $reason[1];
                    $hours   = $reason[2];
                    $powers  = xatVariables::getPowers();
                    if ($seconds <= 0) {//cheated
                        return;
                    }
                    //$bot->network->sendMessage($who . ' finished a ' . $hours . ' hour ' . $powers[$game]['name'] . ' in ' . $seconds . ' seconds.');
                    /* 
                        TODO
                        Save highscores to database
                    */
                } else {
                    //regular unban
                }
                break;
            case '/g': //ban
                if (isset($array['w'])) {
                    //game banned
                }
                return; //for now
                break;
            case '/m': //change rank (mem,guest,mod,owner)
                return; //for now
                break;
            default:
                return;
                break;
        }
    }
};
