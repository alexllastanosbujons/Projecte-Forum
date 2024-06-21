<?php
include_once(__DIR__ . "/../../Helpers/helper.php");


?>
<div class="container text-center mt-5">
    <img class="mx-auto rounded-circle w-50 h-50 me-3" style="max-width: 100px;" src='/Public/Assets/img/profiles/<?php echo $llista['user']['profile'] ?>' alt="">
    <h1 class="mt-3"><?php echo $llista['user']['username'] ?></h1>
</div>
<div class="container">
    <div class="row">


        <div class="col-lg-9 mx-auto">
            <h1 class="mt-5">Posts</h1>
            <?php
            foreach ($llista['posts'] as $post) {
                if ($post['idUser'] == $llista['user']['id']) {
            ?>
                    <div class="row">
                        <div class="col-lg-12 mx-auto">
                            <div class="card row-hover pos-relative py-3 px-3 mb-3 border-warning border-top-0 border-right-0 border-bottom-0 rounded-0">
                                <div class="container mt-2">
                                    <h3 style="position: relative;">
                                        <a href='/post/postView/?idPost=<?php echo $post['id'] ?>' class="text-decoration-none"><?php echo  $post['title'] ?></a>
                                        <div class="dropdown" style="position: absolute; top: 0; right: 0;">
                                            <?php
                                            if ($llista['userLogged']['id'] == $post['idUser'] || $llista['userLogged']['admin'] == true) {
                                            ?>
                                                <button class="btn btn-secondary btn-no-arrow bg-transparent border-0 float-end" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="bi bi-three-dots text-dark"></i>
                                                </button>

                                                <div class="dropdown-menu bg-transparent dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="/post/updateView/?idPost=<?php echo $post['id'] ?>"><i class="fas fa-pencil-alt"></i> Modificar</a>
                                                    <a class="dropdown-item" href="/post/delete/?idPost=<?php echo $post['id'] ?>"></i> Eliminar</a>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </h3>
                                    <div class="text-sm op-5">
                                        <p class="text-black mr-2"></p><?php echo $post['subtitle'] ?></p>
                                    </div>
                                    <p class="text-sm">
                                    <p class="text-black" href="#">Penjat fa <?php echo compararDates($post['date'])  ?></p>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php


                }
            }
            ?>
        </div>