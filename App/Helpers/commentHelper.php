<?php
function textCommentEmpty($text)
{
    if (empty($text)) {
        return "Has de posar text al comentari per poder publicar-lo";
    } else {
        return null;
    }
}

function comprovarLikesDislikes($idComment)
{

    $userModel = new User();
    $userLogged = $userModel->getUserLogged();
    $commentModel = new Comment();
    $comments = $commentModel->getById($idComment);
    $commentModel = new Comment();
    $commentLikes = $commentModel->getCommentLikes($idComment);
    $commentModel = new Comment();
    $commentDislikes = $commentModel->getCommentDislikes($idComment);
    foreach ($commentLikes as $like) {
        if ($userLogged['id'] == $like['idUser']) {
            return "like";
        }
    }
    foreach ($commentDislikes as $disLikes) {
        if ($userLogged['id'] == $disLikes['idUser']) {
            return "dislike";
        }
        break;
    }

    return "nothing";
}
