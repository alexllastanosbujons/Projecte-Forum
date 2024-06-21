
<div class="col-11 col-md-8 col-lg-6 col-xl-5 mx-auto p-4 border bg-light mt-5">
    <h2><?php echo isset($llista['post']) ? "Editar Post" : "Crear Post" ?></h2>
        <form action=<?php echo isset($llista['post']) ? '/post/update/?idPost=' . $llista['post']['id'] : "/post/store" ?> method="post" enctype="multipart/form-data" method="post">
            <div class="form-group mt-2">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value='<?php echo $llista['post']['title'] ?? $_POST['title'] ?? $_GET['title'] ?? null ?>' >
            </div>
            <div class="form-group mt-2">
                <label for="subtitle">Subtitle:</label>
                <input type="text" class="form-control" id="subtitle" name="subtitle" value='<?php echo $llista['post']['subtitle'] ?? $_POST['subtitle'] ?? $_GET['subtitle'] ?? null ?>' >
            </div>
            <div class="form-group mt-2">
                <label for="description">Description:</label>
                <textarea type="text-area" class="form-control" id="description" name="description" ><?php echo $llista['post']['description'] ?? $_POST['description'] ?? $_GET['description'] ?? null ?></textarea>
            </div>

          
            <?php 
            if (isset($llista['createPost'])) {
                foreach ($llista['createPost'] as $error) {
                    if ($error != null) {

                    ?>
                     <small class="text-danger"> <?php echo $error ?></small>
                </br>
                
                <?php
                
             }
            }
            } elseif (isset($llista['updatePost'])) {
                foreach ($llista['updatePost'] as $comprovacio) {
                    if ($comprovacio != null) {

                    ?>
                     <small class="text-success"> <?php echo $comprovacio ?></small>
                </br>
                
                <?php
                
             }
            }
            unset($_SESSION['updatePost']);

        }

            ?>
            <button type="submit" class="btn btn-primary mt-2"><?php echo isset($llista['post']) ? "Editar Post" : "Crear Post" ?></button>
        </form>
        </div>