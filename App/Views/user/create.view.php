<?php
// var_dump($llista);
?>

<div class="col-11 col-md-8 col-lg-6 col-xl-5 mx-auto p-4 border bg-light mt-5">
    <h2><?php echo isset($llista['userLogged']) ? "Editar Usuari" :  "Sign Up" ?></h2>
    <form action=<?php echo isset($llista['userLogged']) ? "/user/update" :  "/user/store" ?> method="post" enctype="multipart/form-data" method="post">
        <div class="form-group mt-2">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value='<?php echo  $llista['userLogged']['username'] ?? $_GET['username'] ?? null ?>'>
        </div>

        <?php
        if (!isset($llista['userLogged'])) {

        ?>
            <div class="form-group mt-2">
                <label for="first_name">First Name:</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value='<?php echo  $llista['userLogged']['firstname'] ?? $_GET['first_name'] ?? null ?>'>
            </div>
            <div class="form-group mt-2">
                <label for="last_name">Last Name:</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value='<?php echo  $llista['userLogged']['lastname'] ?? $_GET['last_name'] ?? null ?>'>
            </div>
            <div class="form-group mt-2">
                <label for="email">Email:</label>
                <input type="text" class="form-control" id="email" name="email" value='<?php echo  $llista['userLogged']['email'] ?? $_GET['email'] ?? null ?>'>
            </div>

            <div class="form-group mt-2">
                <label for="birthdate">Date of Birth:</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate" value='<?php echo $llista['userLogged']['birthdate'] ?? $_GET['birthdate'] ?? null ?>'>
            </div>

            <div class="form-group mt-2">
                <label for="profile_image">Profile Image:</label>
                <input type="file" class="form-control-file" id="profile_image" name="profile_image">
            </div>
        <?php
        }
        ?>
        <div class="form-group mt-2">
            <label for="password"><?php echo isset($llista['userLogged']) ? "New Password: " :  "Password: " ?></label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="form-group mt-2">
            <label for="confirm_password"><?php echo isset($llista['userLogged']) ? "Repeat the new password: " :  "Repeat the password: " ?></label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
        </div>
        <?php
        if (isset($llista['signUp'])) {
            foreach ($llista['signUp'] as $error) {
                if ($error != null) {

        ?>
                    <small class="text-danger"> <?php echo $error ?></small>
                    </br>

        <?php

                }
            }
                unset($_SESSION['signUp']);
            
        }elseif (isset($llista['updateUser'])) {
            foreach ($llista['updateUser'] as $comprovacio) {
                if ($comprovacio != null) {

        ?>
                    <small class="text-success"> <?php echo $comprovacio ?></small>
                    </br>

        <?php

                }
            }
                unset($_SESSION['updateUser']);
            
        }elseif (isset($llista['updateUserError'])) {
            foreach ($llista['updateUserError'] as $errorUpdate) {
                if ($errorUpdate != null) {
        ?>
                    <small class="text-danger"> <?php echo $errorUpdate ?></small>
                    </br>

        <?php
                }
            }
                unset($_SESSION['updateUserError']);
        }
        ?>
        <button type="submit" class="btn btn-primary mt-2"><?php echo isset($llista['userLogged']) ? "Editar Usuari" :  "Sign Up" ?></button>
    </form>
</div>