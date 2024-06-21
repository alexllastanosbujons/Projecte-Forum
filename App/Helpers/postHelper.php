<?php



function titleEmpty($title){
    if (empty($title)) {
        return "Has de posar el títol per poder publicar-lo";
    } else {
        return null;
    }
}
function subtitleEmpty($subtitle){
    if (empty($subtitle)) {
        return "Has de posar el subtítol per poder publicar-lo";
    } else {
        return null;
    }
}
function descriptionEmpty($description){
    if (empty($description)) {
        return "Has de posar la descripció per poder publicar-lo";
    } else {
        return null;
    }
}
