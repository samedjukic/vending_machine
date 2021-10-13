<?php

function return_change($amount){
    $message = '';
    $hundreds = (int)($amount / 100);
    $fifties = (int)(($amount - 100 * $hundreds )/50);
    $twenties = (int)(($amount - 100 * $hundreds - 50 * $fifties )/20);
    $tens = (int)(($amount - 100 * $hundreds - 50 * $fifties - 20 * $twenties)/10);
    $fives = (int)(($amount - 100 * $hundreds - 50 * $fifties - 20 * $twenties - 10 * $tens)/5);
    
    if ($hundreds > 0) $message .= $hundreds. ' x 100 ';
    if ($fifties > 0) $message .= $fifties. ' x 50 ';
    if ($twenties > 0) $message .= $twenties. ' x 20 ';
    if ($tens > 0) $message .= $tens. ' x 10 ';
    if ($fives > 0) $message .= $fives. ' x 5';
    return $message;
}