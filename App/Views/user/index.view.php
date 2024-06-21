
    <div class="container mt-5">
        <h2 class="text-center">Usuaris</h2>
        <div class="col-9 mx-auto">
            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th class="text-center">Nom</th>
                        <th class="text-center">Contrasenya</th>
                        <th class="text-center">Eliminar</th>
                        <th class="text-center">Cambiar Estat Admin</th>
                        <th class="text-center">Admin</th>


                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($llista as $user) {
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $user['username'] ?></td>
                            <!-- <td class="text-center"><?php echo $user['pass'] ?></td> -->
                            <td class="text-center">
                                <?php
                                if ($user['admin'] == false) {
                                ?>
                                    <a name="user_id" class="btn btn-danger" href="/user/userDelete/?user=<?php echo $user['id'] ?>">Eliminar</a>

                                <?php
                                } ?>
                            </td>
                            <td class="text-center">
                                <?php
                                if ($user['username'] != "admin") {
                                ?>

                                    <a name="user_id" class="btn btn-warning" href="/user/cambiarEstatAdmin/?user=<?php echo $user['id'] ?>">Cambiar Estat Admin</a>

                                <?php

                                } ?>
                            </td>
                            <td class="text-center">

                                <?php
                                if ($user['admin'] == true) {
                                ?>
                                    <i class="bi bi-check2"></i>
                                <?php

                                } else {
                                ?>
                                    <i class="bi bi-ban"></i>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php

                    }
                    ?>
                </tbody>
                
            </table>
        </div>
    </div>


