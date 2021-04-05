<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Bootstrap theme</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
            <ul class="nav navbar-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Usuários <span class="caret"></span></a>
                <ul class="dropdown-menu">
                <li><a href="/users">Lista de Usuários</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Ademir</li>
                <li><a href="/users/profile">Perfil</a></li>
                </ul>
            </li>
            </ul>
            
            <form action="/login" class="navbar-form navbar-right">
                
                <button type="submit" class="btn btn-success">Login</button>
            </form>
        </div><!--/.nav-collapse -->
    </div>
</nav>
