


<!DOCTYPE html>
<html lang="nl">
    <head>
        <title>EA registreren</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/global.css">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="js/bootstrap.js"></script>
        <script type="text/javascript" src="js/slider.js"></script>
    </head>
    <body>
        <div class="container">
            <!-- Header -->
            <div class="row">
                <div class="col-md-12 col-xs-12 ea-login">
                    <div class="registerbutton_home">
                        <a href="registreren.html">
                            Registeren
                        </a>
                    </div>
                    <div class="inlogbutton_home">
                        <a href="inloggen.html">
                        Inloggen
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-xs-8 col-xs-offset-2 col-md-offset-0 ea-logo">
                    <a href="index.html">
                        <img src="img/logo_header.png" alt="Logo Eenmaal Andermaal" class="img-responsive">
                    </a>
                </div>

                <div class="col-md-9 col-xs-12 ea-searchbar">
                    <div class="input-group ea-searchbar-input">
                        <input type="text" class="form-control ea-searchbar-input-twee">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-default ea-searchbar-input">
                            <img src="img/search_icon.png" alt="Zoek" class="img-responsive"></button>
                            <button type="button" class="btn btn-default dropdown-toggle ea-searchbar-input" data-toggle="dropdown">
                                Categorie
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu ea-nav-categorie" role="menu">
                                <li><a href="#">Categorie #1</a></li>
                                <li><a href="#">Categorie #2</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <!-- Navbar -->
                <div class="row">
                    <div class="col-md-12 col-xs-12 ea-nav">
                        <nav class="navbar navbar-default col-md-12 col-xs-12 ea-buttons" role="navigation" >
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <div class="collapse navbar-collapse ea-buttons" id="navbar-collapse-1">
                                <ul class="nav navbar-nav navbar-left">
                                    <li class="artikel-veilen"><a href="#">ARTIKEL VEILEN!</a></li>
                                    <li class="active"><a href="#">KLEDING</a></li>
                                    <li class="item"><a href="#">AUTO'S</a></li>
                                    <li class="item"><a href="#">BINNENHUIS</a></li>
                                    <li><a href="#">BUITENHUIS</a></li>
                                    <li><a href="#">SPEELGOED</a></li>
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

                <!-- Slider -->
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                         <div class="content-bar">
                            <p class="content-header">REGISTREREN STAP 1 </p>
                        </div>
                        <div class="content-explanation">
                        <h2 class="step1">Uitleg in stappen:</h2>
                        </div>
                    </div>

                </div>

            <!-- Content -->
            <div class="col-md-12 col-xs-12 content-home">
            <php
                session_start();
                include './hosted/inc/registerFunctions.php';
                if(isset($_POST['submit']))
                {
                    if($errors = validateRegisterForm())
                    {
                        echo "<ul>";
                        echo $errors;
                        echo "<ul>"
                    }
                    else
                    {
                        saveNewUser();

                        header("#")
                    }
                }
            ?>
                <div class="register-1">
                    <form action="#" method="POST" id="registerForm-1" name="registerFrom-1">
                        <label for="email">Voer uw emailadres in</label>
                        <input name="email" placeholder="email" type="text">  
                    <br />
                    <input type="submit" name='submit' class="buttonRegister" value="Ga verder!"></button>
                    </form>
                </div>

                <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
                <h1>HELLO WORLD!</h1>
            </div>
            <!-- Footer -->
            <div class="col-md-12 col-xs-12 ea-footer">
                <div class="col-md-3 col-xs-3">
                    <h4>Over EenmaalAndermaal</h4>
                    <div class="footer-links">
                        <a href="faq.html">FAQ</a><br />
                        <a href="voorwaarden.html">Algemene Voorwaarden</a><br />
                        <a href="contact.html">Contact Gegevens</a><br />
                        <a href="sitemap.html">Sitemap</a>
                    </div>
                </div>
                <div class="col-md-3 col-xs-3">
                    <h4>Handige links</h4>
                    <div class="footer-links">
                        <a href="registreren.html">Registreren</a><br />
                        <a href="inloggen.html">Inloggen</a><br />
                        <a href="product.html">Product Toevoegen</a><br />
                    </div>
                </div>
                <div class="col-md-3 col-xs-3">
                    <h4>Veilers</h4>
                    <div class="footer-links">
                        <a href="myAccount.html">Mijn account</a><br />
                        <a href="productVeilen.html">Product veilen</a><br />
                    </div>
                </div>
                <div class="col-md-3 col-xs-3">
                    <h4>Kopers</h4>
                    <div class="footer-links">
                        <a href="productOverzicht.html">Productoverzicht</a><br />
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>