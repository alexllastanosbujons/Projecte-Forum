<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<nav class="d-flex justify-content-between p-3 bg-success text-white">
    <div class="left">
        <a class="text-decoration-none text-white" href="/main/home"><i class="bi bi-alphabet">  Foro Fake</i>
    </div></a>
    <div class="right">
        <?php

        if (isset($userLogged)) {
            if ($userLogged['username'] != 'admin') {
                echo '<a class="text-decoration-none p-3 text-white" href="/user/create">' . $userLogged['username'] . '</a>';
            }
            if ($userLogged['admin']) {
                echo '<a class="p-3 text-decoration-none text-white" href="/user/index">Usuaris</a>';
            }
            echo '<a class="text-decoration-none p-3 text-white"  href="/user/signOut">Sign Out</a>';
        } else {
            echo '<a class="text-decoration-none p-3 text-white" href="/user/create">Sign Up</a>';
            echo '<a class="text-decoration-none text-white" href="/user/login">Sign In</a> ';
        }
        echo '<a class="text-decoration-none text-white p-3" href="/reset/run"> Reset </a> ';


        ?>


    </div>
</nav>