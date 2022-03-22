<?php

// Trim the leading slashes & split the path on slashes
$path = ltrim($_SERVER['REQUEST_URI'], '/');
$elements = explode('/', $path);

// Show home if there are no parameters or paths
if (empty($elements[0])) {
    gallery();
} // Else, grab the first item in the URL so we can identify which function to use
else {
    switch (array_shift($elements)) {
        case 'search':
            search($elements);
            break;
        case 'page':
            gallery($elements);
            break;
        case 'robots.txt':
            robots();
            break;
        default:
            gallery();
    };
}

function not_blank($value): bool
{
    return isset($value) && !empty($value) && $value !== '';
}

function authentication(): \Tumblr\API\Client
{
    // Grab the Tumblr API library
    require __DIR__ . '/vendor/autoload.php';
    // Start a secure session
    session_start();
    // Set up defaults
    $consumer_key = getenv('CONSUMER_KEY');
    $consumer_secret = getenv('CONSUMER_SECRET');
    $client = new Tumblr\API\Client($consumer_key, $consumer_secret);
    $requestHandler = $client->getRequestHandler();
    $requestHandler->setBaseUrl('https://www.tumblr.com/');
    // Check if the user has already authenticated
    if (not_blank($_SESSION['perm_token']) && not_blank($_SESSION['perm_secret'])) {
        $token = $_SESSION['perm_token'];
        $token_secret = $_SESSION['perm_secret'];
    } // Check if the user was here earlier by checking cookies
    else if (not_blank($_COOKIE['perm_token']) && not_blank($_COOKIE['perm_secret'])) {
        $token = $_COOKIE['perm_token'];
        $token_secret = $_COOKIE['perm_secret'];
    } // Check if this is the user's first visit
    else if (!isset($_GET['oauth_verifier'])) {
        // Grab the oauth token
        $resp = $requestHandler->request('POST', 'oauth/request_token', array());
        $out = $result = $resp->body;
        $data = array();
        parse_str($out, $data);
        // Save temporary tokens to session
        $_SESSION['tmp_token'] = $data['oauth_token'];
        $_SESSION['tmp_secret'] = $data['oauth_token_secret'];
        // Redirect user to Tumblr auth page
        session_regenerate_id(true);
        $header_url = 'https://www.tumblr.com/oauth/authorize?oauth_token=' . $data['oauth_token'];
        header('Location: ' . $header_url);
        die();
    } // Check if the user was just sent back from the Tumblr authentication site
    else {
        $verifier = $_GET['oauth_verifier'];
        // Use the stored temporary tokens
        $client->setToken($_SESSION['tmp_token'], $_SESSION['tmp_secret']);
        // Access the permanent tokens
        $resp = $requestHandler->request('POST', 'oauth/access_token', array('oauth_verifier' => $verifier));
        $out = $result = $resp->body;
        $data = array();
        parse_str($out, $data);
        // Set permanent tokens
        $token = $data['oauth_token'];
        $token_secret = $data['oauth_token_secret'];;
        $_SESSION['perm_token'] = $data['oauth_token'];
        $_SESSION['perm_secret'] = $data['oauth_token_secret'];
        // Set cookies in case the user comes back later
        setcookie("perm_token", $_SESSION['perm_token']);
        setcookie("perm_secret", $_SESSION['perm_secret']);
        // Redirect user to homepage for a clean URL
        session_regenerate_id(true);
        $header_url = 'https://michelangelo.example.com';
        header('Location: ' . $header_url);
        die();
    }
    // Authenticate via OAuth
    // Send the client back to the function
    return new Tumblr\API\Client(
        $consumer_key,
        $consumer_secret,
        $token,
        $token_secret
    );
}

function get_dashboard_posts($client, $post_start, $limit, $post_type = 'photo'): string
{
    // Call the function for dashboard posts
    $dashboard_posts = $client->getDashboardPosts(array('limit' => $limit, 'offset' => $post_start, 'type' => $post_type));
    // Open the row that will hold all photos/videos
    $post_row = '<div class="row">';
    // For each post returned by Tumblr, create the HTML content
    foreach ($dashboard_posts->posts as $post) {
        if ($post->type == 'photo') {
            // Get post variables
            $caption = $post->caption;
            $id = $post->id_string;
            $type = $post->type;
            $url = $post->photos[0]->original_size->url;
            // Set up post HTML
            $post_row .= <<<EOD
                <div class="col-sm-6 col-lg-4 mb-4">
                    <div class="card" data-type="$type" data-id="$id">
                        <a data-fancybox="gallery" aria-label="Open gallery viewer" href="$url">
                            <img class="card-img h-100 w-100" src="$url" loading="lazy">
                        </a>
                    </div>
                </div>
            EOD;
        } else if ($post->type == 'video') {
            // Get post variables
            $caption = $post->caption;
            $id = $post->id_string;
            $thumbnail = $post->thumbnail_url;
            $type = $post->type;
            $url = $post->video_url;
            // Set up post HTML
            $post_row .= <<<EOD
                <div class="col-sm-6 col-lg-4 mb-4">
                    <div class="card" data-type="$type" data-id="$id">
                        <a data-fancybox="gallery" aria-label="Open gallery viewer" href="$url">
                            <video class="card-img h-100 w-100" controls autoplay poster="$thumbnail"><source src="$url"></source></video>
                        </a>
                    </div>
                </div>
            EOD;
        }
    }
    $post_row .= '</div>';
    return $post_row;
}

function get_tagged_posts($client, $tag): string
{
    // Call the function for posts that match the search query
    $tagged_posts = $client->getTaggedPosts($tag);
    // Open the row that will hold all photos/videos
    $post_row = '<div class="row">';
    // For each post returned by Tumblr, create the HTML content
    foreach ($tagged_posts as $post) {
        if ($post->type == 'photo') {
            // Get post variables
            $caption = $post->caption;
            $id = $post->id_string;
            $type = $post->type;
            $url = $post->photos[0]->original_size->url;
            // Set up post HTML
            $post_row .= <<<EOD
                <div class="col-sm-6 col-lg-4 mb-4">
                    <div class="card" data-type="$type" data-id="$id">
                        <a data-fancybox="gallery" aria-label="Open gallery viewer" href="$url">
                            <img class="card-img h-100 w-100" src="$url" loading="lazy">
                        </a>
                    </div>
                </div>
            EOD;
        } else if ($post->type == 'video') {
            // Get post variables
            $caption = $post->caption;
            $id = $post->id_string;
            $thumbnail = $post->thumbnail_url;
            $type = $post->type;
            $url = $post->permalink_url;
            // Set up post HTML
            $post_row .= <<<EOD
                <div class="col-sm-6 col-lg-4 mb-4">
                    <div class="card" data-type="$type" data-id="$id">
                        <a data-fancybox="gallery" aria-label="Open gallery viewer" href="$url">
                            <video class="card-img h-100 w-100" controls autoplay poster="$thumbnail"><source src="$url"></source></video>
                        </a>
                    </div>
                </div>
            EOD;
        }
    }
    $post_row .= '</div>';
    return $post_row;
}

function gallery($params = null)
{
    // Authenticate the current user
    $client = authentication();

    foreach ($client->getUserInfo()->user->blogs as $blog) {
        $blog_name = $blog->name;
    }

    // Get current page number & calculate posts to request
    $page_num = (int)substr($params[0], 0, 2);
    if (!is_null($params) && is_numeric($page_num)) {
        $page = $page_num;
    } else {
        $page = 1;
    }
    $post_start = (($page - 1) * 20) + 1;
    $limit = 20;
    // Check for specific post type in the URL
    if (not_blank($_GET['post_type'])) {
        $post_type = $_GET['post_type'];
    } else {
        $post_type = 'photo';
    }
    // Call the get_dashboard_posts function and load some posts!
    $posts = get_dashboard_posts($client, $post_start, $limit, $post_type);
    // Set page variables
    $page_title = 'Michelangelo';
    $header = $blog_name;
    // Create page navigation
    $page_next = $page + 1;
    $page_prev = $page - 1;
    $disabled = '';
    if ($page <= 1) {
        $disabled = 'disabled';
    } else {
        $page_item = <<<EOD
            <li class="page-item">
                <a class="page-link" href="/page/$page_prev?post_type=$post_type">$page_prev</a>
            </li>
        EOD;
    }
    $page_nav = <<<EOD
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item $disabled">
                    <a class="page-link" href="/page/$page_prev?post_type=$post_type" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                $page_item
                <li class="page-item active">
                  <a class="page-link" href="#">$page</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="/page/$page_next?post_type=$post_type">$page_next</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="/page/$page_next?post_type=$post_type" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    EOD;
    // Feed all these variables to the template
    require 'template.php';
}

function search($params)
{
    // Authenticate the current user
    $client = authentication();
    // Get search query
    $query = $params[0];
    // Call the get_dashboard_posts function and load some posts!
    $posts = get_tagged_posts($client, $query);
    // Set page variables
    $page_title = 'Search | Michelangelo';
    $header = 'Search Results for "' . $query . '"';
    // Feed all these variables to the template
    require 'template.php';
}

function robots()
{
    header('Content-type: text/plain');
    echo 'User-agent: *';
    echo 'Disallow: /search/';
    echo 'Disallow: /page/';
    die();
}
