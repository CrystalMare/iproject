<!DOCTYPE html>
<html lang="nl">
    <head>
        <title>Bootstrap Test</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/global.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="js/bootstrap.js"></script>
    </head>
    <body>
        <div class="container">
            <!-- Header -->
            <div class="row">
                <div class="col-md-12 col-xs-12 ea-login">
                    Inloggen | Registreren
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 col-xs-8 col-xs-offset-2 col-md-offset-0 ea-logo">
                    <img src="img/logo_header.png" alt="Logo Eenmaal Andermaal" class="img-responsive">
                </div>

                <div class="col-md-9 col-xs-12 ea-searchbar">
                    <div class="input-group">
                        <input type="text" class="form-control">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-default">Zoek</button>
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Categorie #1</a></li>
                                <li><a href="#">Categorie #2</a></li>
                            </ul>
                        </div>
                    </div>
            </div>

            <!-- Navbar -->
            <div class="row">
                <div class="col-md-12 col-xs-12 ea-nav">
                    <nav class="navbar navbar-default col-md-12 col-xs-12" role="navigation" >

                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                        <div class="collapse navbar-collapse" id="navbar-collapse-1">
                            <ul class="nav navbar-nav navbar-left">
                                <li class="active"><a href="#">Home</a></li>
                                <li><a href="#">Over ons</a></li>
                                <li><a href="#">Nieuws</a></li>
                                <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Webwinkel <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Producten</a></li>
                                <li><a href="#">Winkelmandje</a></li>
                                <li><a href="#">Alle producten</a></li> 
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Content -->

            <!-- Footer -->
    </body>
</html>
