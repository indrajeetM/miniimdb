<?php 
    include 'header.php';
?>
               
<!--Main Navigation-->
<header>

    <nav class="navbar fixed-top navbar-expand-lg navbar-dark scrolling-navbar" style="background: linear-gradient(40deg,#ff6ec4,#7873f5) !important;">
        <a class="navbar-brand" href="<?php echo Base_url; ?>"><strong>Mini IMDB</strong></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item ">
                    <a class="nav-link" href="<?php echo Base_url; ?>AddnewMovie.php">Add New Movie</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo Base_url; ?>AddnewCategory.php">Add New Category</a>
                </li>
              
            </ul>
            <ul class="navbar-nav nav-flex-icons">
                <li class="nav-item">
                    <a class="nav-link"><i class="fa fa-facebook"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"><i class="fa fa-twitter"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"><i class="fa fa-instagram"></i></a>
                </li>
            </ul>
        </div>
    </nav>

</header>
<!--Main Navigation-->
                
            