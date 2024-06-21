<?php 
//var_dump($_SESSION['users']);

?>

<form class="col-5 m-4 p-4 bg-light mx-auto" action="/user/loginCheck" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" name="email" id="" aria-describedby="helpId" placeholder="" value='<?php echo (isset($_GET['email'])) ? $_GET['email'] : null ?>'>
            </div>
            <div class="mb-3">
                <label for="pass" class="form-label">Password</label>
                <input type="password" class="form-control" name="pass" id="" aria-describedby="helpId" placeholder="">
                <small id="helpId" class="form-text text-muted"></small>
            </div>
            <button name="sub_login" type="submit" class="btn btn-primary">Submit</button>
            <p class="mb-3"></p>
            <?php
            if (!is_null($llista)) {
                foreach ($llista as $error) {
                    echo "<small class='text-danger'>" . $error . "</small>";
                }
                unset($_SESSION['flash']);
            }
            ?>

        </form>