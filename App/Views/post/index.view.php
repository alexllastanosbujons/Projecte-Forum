<?php
include_once(__DIR__ . "/../../Helpers/helper.php");
include_once(__DIR__ . "/../../Helpers/commentHelper.php");

?>
<div class="container mt-5 justify-content-center">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <?php foreach ($llista['users'] as $user) {
                if ($user['id'] == $llista['post']['idUser']) {
            ?>
                    <div class="card">
                        <div class="card-header bg-none">
                            <a class="text-black" href="/user/userProfile/?idUser=<?php echo $llista['post']['idUser'] ?>"><img class="rounded-circle w-50 h-50 me-3" style="max-width: 30px;" src='/Public/Assets/img/profiles/<?php echo $user['profile'] ?>' alt=""><?php echo  $user['username'] ?></a>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h2><?php echo $llista['post']['title'] ?></h2>
                                <h4><?php echo $llista['post']['subtitle'] ?></h4>

                            </div>
                            <div class="card-body">
                                <p><?php echo $llista['post']['description'] ?></p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-group me-2" role="group">
                                    <a href="/post/likes/?idPost=<?php echo $llista['post']['id'] ?>">

                                        <?php
                                        if ($llista['comprovacioLikesP'] == "like") { ?>
                                            <i class="bi bi-hand-thumbs-up-fill me-2" style="color:green"></i>
                                        <?php } else { ?>
                                            <i class="bi bi-hand-thumbs-up me-2"></i>
                                        <?php } ?>
                                    </a>
                                    <span><?php echo $llista['post']['likes'] ?> Likes</span>
                                </div>

                                <div class="btn-group" role="group">
                                    <a href="/post/dislikes/?idPost=<?php echo $llista['post']['id'] ?>">
                                        <?php
                                        if ($llista['comprovacioLikesP'] == "dislike") { ?>
                                            <i class="bi bi-hand-thumbs-down-fill me-2" style="color:red"></i>
                                        <?php } else { ?>
                                            <i class="bi bi-hand-thumbs-down me-2"></i>

                                        <?php   } ?>
                                    </a>
                                    <span><?php echo $llista['post']['dislikes'] ?> Dislikes</span>
                                </div>
                            </div>


                            <?php
                            if (isset($llista['updatePost'])) {
                                foreach ($llista['updatePost'] as $comprovacio) {
                                    if ($comprovacio != null) {

                            ?>
                                        <small class="text-success ms-3"> <?php echo $comprovacio ?></small>
                                        </br>

                            <?php

                                    }
                                }
                                unset($_SESSION['updatePost']);
                            } ?>
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h3>Crear Comentari: </h3>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="/comment/store">
                                        <div class="mb-3">
                                            <label for="comentari" class="form-label">Comentari:</label>
                                            <textarea class="form-control" id="text" name="text" rows="4"></textarea>
                                        </div>
                                        <?php
                                        if (isset($llista['createComment'])) { ?>
                                            <small class="text-danger "> <?php echo $llista['createComment']; ?></small>
                                            </br>
                                        <?php
                                        }
                                        ?>
                                        <input type="hidden" name="idPost" id="inputName" value='<?php echo $llista['post']['id'] ?>'>
                                        <button type="submit" class="btn btn-primary">Publicar Comentari</button>
                                    </form>
                                </div>

                                <div class="card-header">
                                    <h3>Comentaris</h3>
                                </div>
                                <?php
                            }
                        }
                        foreach ($llista['comments'] as $comment) {
                            if ($llista['post']['id'] == $comment['idPost']) {
                                foreach ($llista['users'] as $user) {
                                    if ($user['id'] == $comment['idUser']) {
                                ?>
                                        <div class="m-3 p-3 border position-relative">
                                            <a class="text-black" href="/user/userProfile/?idUser=<?php echo $comment['idUser'] ?>">
                                                <img class="rounded-circle w-50 h-50 me-3" style="max-width: 30px;" src='/Public/Assets/img/profiles/<?php echo $user['profile'] ?>' alt=""><?php echo  $user['username'] ?>
                                            </a>
                                            <?php
                                            if ($llista['userLogged']['id'] == $user['id'] || $llista['userLogged']['admin'] == true) {
                                            ?>
                                                <div class="dropdown" style="position: absolute; top: 0; right: 0;">
                                                    <button class="btn btn-secondary btn-no-arrow bg-transparent border-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="bi bi-three-dots text-dark"></i>
                                                    </button>
                                                    <div class="dropdown-menu bg-transparent dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item" href="/comment/delete/?commentId=<?php echo $comment['id'] ?>&postId=<?php echo $llista['post']['id'] ?>"><i class="fas fa-trash"></i> Eliminar</a>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <div class="mb-3">
                                                <label for="comentari" class="form-label">Comentari:</label>
                                                <p><?php echo $comment['text'] ?></p>
                                                <p class="text-black">Penjat fa <?php echo compararDates($comment['date'])  ?></p>

                                            </div>
                                            <div class="card-footer">
                                                <div class="btn-group me-2" role="group">
                                                    <a href="/comment/likes/?idComment=<?php echo $comment['id'] ?>&idPost=<?php echo $llista['post']['id'] ?>">

                                                        <?php
                                                        $llista['comprovacioLikesC'] = comprovarLikesDislikes($comment['id']);
                                                        if ($llista['comprovacioLikesC'] == "like") { ?>
                                                            <i class="bi bi-hand-thumbs-up-fill me-2" style="color:green"></i>
                                                        <?php } else { ?>
                                                            <i class="bi bi-hand-thumbs-up me-2"></i>
                                                        <?php } ?>
                                                    </a>
                                                    <span><?php echo $comment['likes'] ?> Likes</span>
                                                </div>

                                                <div class="btn-group" role="group">
                                                    <a href="/comment/dislikes/?idComment=<?php echo $comment['id'] ?>&idPost=<?php echo $llista['post']['id'] ?>">
                                                        <?php
                                                        if ($llista['comprovacioLikesC'] == "dislike") { ?>
                                                            <i class="bi bi-hand-thumbs-down-fill me-2" style="color:red"></i>
                                                        <?php } else { ?>
                                                            <i class="bi bi-hand-thumbs-down me-2"></i>

                                                        <?php   } ?>
                                                    </a>
                                                    <span><?php echo $comment['dislikes'] ?> Dislikes</span>
                                                </div>
                                            </div>
                                        </div>

                        <?php
                                    }
                                }
                            }
                        }

                        ?>
                            </div>
                        </div>
                    </div>
        </div>
    </div>