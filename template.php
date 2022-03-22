<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="apple-touch-icon" sizes="57x57" href="/assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/fancybox-3.5.7.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <title><?php echo $page_title; ?></title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Michelangelo</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-0 ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/?post_type=photo">Photos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/?post_type=video">Videos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main container">

        <p class="h1 text-center my-4">
            <?php echo $blog_name; ?>
        </p>

        <?php echo $posts; ?>

        <?php echo $page_nav; ?>

    </div><!-- ./main -->

    <!-- JavaScript -->
    <script defer src="/assets/js/jquery-3.5.1.min.js"></script>
    <script defer src="/assets/js/bootstrap.bundle.min.js"></script>
    <script defer src="/assets/js/masonry-4.2.2.min.js"></script>
    <script defer src="/assets/js/imagesloaded-4.1.4.min.js"></script>
    <script defer src="/assets/js/fancybox-3.5.7.min.js"></script>
    <script defer src="/assets/js/app.min.js"></script>
</body>

</html>
