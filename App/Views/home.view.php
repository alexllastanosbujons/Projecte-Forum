<?php
include_once(__DIR__ . "/../Helpers/helper.php");


?>
<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
<div class="container">
    <div class="row">

        <h1 class="mt-5">Posts</h1>
        <div class="col-lg-3 mb-lg-0 px-lg-0 mt-lg-0">
            <div style="visibility: hidden; display: none; width: 285px; height: 801px; margin: 0px; float: none; position: static; inset: 85px auto auto;"></div>
            <div data-settings="{&quot;parent&quot;:&quot;#content&quot;,&quot;mind&quot;:&quot;#header&quot;,&quot;top&quot;:10,&quot;breakpoint&quot;:992}" data-toggle="sticky" class="sticky" style="top: 85px;">
                <div class="sticky-inner">
                    <a class="btn btn-lg btn-block btn-success rounded py-4 mb-3 bg-op-6 roboto-bold" href="/post/create">Add Post</a>
                    <div class="bg-white text-sm">
                        <h4 class="px-3 py-4 op-5 m-0 roboto-bold">
                            Stats
                        </h4>
                        <hr class="my-0">
                        <div class="row text-center d-flex flex-row op-7 mx-0">
                            <div class="row text-center d-flex flex-row op-7 mx-0">
                                <div class="col-sm-6 col flex-ew text-center py-3 border-bottom mx-0">
                                    <p class="d-block lead font-weight-bold" href="#"><?php echo count($llista['posts']) ?></p> Posts
                                </div>
                                <div class="col-sm-6 col flex-ew text-center py-3 border-bottom mx-0">
                                    <p class="d-block lead font-weight-bold" href="#"><?php echo count($llista['users']) ?></p> Users
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <?php
            foreach ($llista['posts'] as $post) {
                foreach ($llista['users'] as $user) {
                    if ($post['idUser'] == $user['id']) {
            ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card row-hover pos-relative py-3 px-3 mb-3 border-warning border-top-0 border-right-0 border-bottom-0 rounded-0">
                                    <div class="container mt-2">
                                        <h3 style="position: relative;">
                                            <a href='/post/postView/?idPost=<?php echo $post['id'] ?>' class="text-decoration-none"><?php echo  $post['title'] ?></a>
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
                                        </h3>
                                        <div class="text-sm op-5">
                                            <p class="text-black mr-2"></p><?php echo $post['subtitle'] ?></p>
                                        </div>
                                        <p class="text-sm">
                                        <p class="text-black" href="#">Penjat fa <?php echo compararDates($post['date'])  ?></p>
                                        <a class="text-black" href="/user/userProfile/?idUser=<?php echo $post['idUser']?>"><img class="rounded-circle w-50 h-50 me-3" style="max-width: 30px;" src='/Public/Assets/img/profiles/<?php echo $user['profile'] ?>' alt=""><?php echo  $user['username'] ?></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
            <?php

                    }
                }
            }
            ?>
        </div>
    </div>
</div>



<?php

?>