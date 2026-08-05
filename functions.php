<?php
function formaterDate($date){
    $timestamp = strtotime($date);
    return date ('d/m/Y', $timestamp);
}

function genererExtrait($texte, $taille = 100){
    if (strlen($texte)<= $taille){
        return $texte;
    }
    return substr($texte, 0, $taille) . '...';
}
?>