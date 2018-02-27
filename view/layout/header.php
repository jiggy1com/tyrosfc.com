<header class="bg-light">

    <!---->
    <div class="container-fluid ">
        <div class="row align-items-center justify-content-md-center">
            <div class="col-xs-auto mt-3 mb-3">
                <!--
                <img src="https://media.licdn.com/media/AAEAAQAAAAAAAATbAAAAJDM4NDk1NzQxLTk5NDAtNDdlYi05OGNlLWIzOTZjOTIxNmQwNQ.jpg" />
                -->
                <img src="/img/header/tyros-logo.png" width="150" class="d-block m-auto" />
            </div>
            <!--
            <div class="col">
                <h1 class="text-dark text-center mt-3">Tyros Schedule & Attendance App</h1>
            </div>
            -->
        </div>
    </div>


    <!--
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-3 mb-3 text-center">
                <img src="/img/header/tyros-logo.png" width="150" />
            </div>
            <div class="col-12 text-center bg-dark">
                <h1 class="text-light mt-3">Tyros Soccer Schedule & Attendance App</h1>
            </div>
        </div>
    </div>
    -->

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/">
            <!--Tyros-->
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/schedule">Schedule</a>
                </li>
                <?php if( !$GLOBALS['app']->isLoggedIn ): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                <?php endif; ?>

                <?php if( $GLOBALS['app']->isLoggedIn ): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/profile/edit">Edit Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Logout</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

</header>