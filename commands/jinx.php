<?php

$jinx = function ($who, $message, $type) {
    /*
        Work In Progress
        Contains a few errors
    */
    $bot = actionAPI::getBot();
    
    $jinxType = $message[1] ?? "mix";
    unset($message[0], $message[1]);
    
    $message = implode(' ', $message);
    
    $string = $message . $who;
    $Rand = 0;
    for ($i = 0; $i < strlen($string); $i++) {
        $Rand += ord($string[$i]) != 32 ? ord($string[$i]) : 0;
    }
    
    $seed = $Rand;

    $random = function() use (&$seed){
        $seed = ($seed ^ ($seed << 21));
        
        $a = $seed;
        $b = 35;
        $z = hexdec(80000000); 
        if ($z & $a) { 
            $a = ($a >> 1); 
            $a &= (~$z); 
            $a |= 0x40000000; 
            $a = ($a >> ($b - 1)); 
        } else { 
            $a = ($a >> $b); 
        } 
        
        $seed = ($seed ^ ($a));
        $seed = ($seed ^ ($seed << 4));
        return ($seed);
    };
    
    $Arg = 100 * (4 / 50);
    
    $message = explode(' ', $message);
    $_local_9 = [];

    for ($i = 0; $i < count($message); $i++) {
        if ($message[$i][0] == "(" && end($message[$i]) == ")"){
            $_local_9[] = $message[$i];
            $message[$i] = ">";
        };
    };
    
    switch (strtolower($jinxType)) {//JinxIt
        case "reverse":
            for ($i = 0; $i < count($message); $i++) {
                $message[$i] = strrev($message[$i]);
            }
            break;
        case "mix":
        default:
            for ($i = 0; $i < count($message); $i++) {
                $message[$i] = str_shuffle($message[$i]);
            }
            break;
        case "ends":
            $message2 = [];
            $messageTmp = "";
            
            for ($i = 0; $i < count($message); $i++) {
                $message2 = str_split($message[$i]);
                $messageTmp = $message2[0];
                $message2[0] = end($message2);
                $message2[count($message2) - 1] = $messageTmp;
                $message[$i] = implode($message2);
            }
            break;
        case "middle":
            $message2 = $message3 = [];
            $messageTmp = $messageTmp2 = "";
            
            for ($i = 0; $i < count($message); $i++) {
                $message2 = str_split($message[$i]);
                if (count($message2) > 3) {
                    $messageTmp = $message2[0];
                    $messageTmp2 = end($message2);
                    $message3 = array_slice($message2, 1, count($message2) - 1);
                    
                    usort($message3, function($a, $b) use (&$random) {
                        return ($random() & 1) ? -1 : 1;
                    });   
                    
                    array_unshift($message3, $messageTmp);
                    $message3[] = $messageTmp2;
                    $message[$i] = implode($message3);
                }
            }
            break;
        case "hang":
            $messageTmp = implode(" ", $message);
            $message2 = preg_replace("/[ >]/", "", $messageTmp);
            
            if (strlen($message2 > 0)) {
                $message2 = str_split($message2);
                $Arg = $Arg > count($message2) ? count($message2) : $Arg;
                
                for ($i = 0; $i < $Arg; $i++) {
                    $messageTmp = implode("_", explode($message2[$random() % count($message2)], $messageTmp));
                }
            }
            
            $message = explode(" ", $messageTmp);
            break;
        case "egg":
            $message = implode(' ', $message);
            $message = preg_replace( [
                    "/[EȄȆḔḖḘḚḜẸẺẼẾỀỂỄỆĒĔĖĘĚÈÉÊËeȅȇḕḗḙḛḝẹẻẽếềểễệēĕėęěèéêë]/",
                    "/[AÀÁÂÃÄÅĀĂĄǺȀȂẠẢẤẦẨẪẬẮẰẲẴẶḀÆǼaàáâãäåāăąǻȁȃạảấầẩẫậắằẳẵặḁæǽ]/",
                    "/[IȈȊḬḮỈỊĨĪĬĮİÌÍÎÏĲiȉȋḭḯỉịĩīĭįiìíîïĳ]/",
                    "/[OŒØǾȌȎṌṎṐṒỌỎỐỒỔỖỘỚỜỞỠỢŌÒÓŎŐÔÕÖoœøǿȍȏṍṏṑṓọỏốồổỗộớờởỡợōòóŏőôõö]/",
                    "/[UŨŪŬŮŰŲÙÚÛÜȔȖṲṴṶṸṺỤỦỨỪỬỮỰuũūŭůűųùúûüȕȗṳṵṷṹṻụủứừửữự]/",
                    "/[YẙỲỴỶỸŶŸÝyẙỳỵỷỹŷÿý]/"
                ], 
                ["egge", "egga", "eggi", "eggo", "eggu", "eggy"], 
                $message
            );
            $message = explode(" ", $message);
            break;
    }
    
    for ($i = 0; $i < count($message); $i++) {
        if ($message[$i] == ">"){
            $message[$i] = array_pop($_local_9);
        }
    }
    $message = implode(" ", $message);

    if (empty($message)) {
        return $bot->network->sendMessageAutoDetection($who, 'The message cannot be empty.', $type);
    } else {
        return $bot->network->sendMessageAutoDetection($who, in_array($message[0], ['/', '#']) ? '_' . $message : $message, $type);
    }
};