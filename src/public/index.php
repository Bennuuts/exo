<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App(['settings' => $config]);

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;
$config['db']['host']   = 'localhost';
$config['db']['user']   = 'root';
$config['db']['pass']   = '';
$config['db']['dbname'] = 'slimproject';

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};
// fonction pour justifier récuperée https://gist.github.com/galen/2939068
function justify( $str_in, $desired_length=80 ) {
    // Cut off long lines
    if ( strlen( $str_in ) > $desired_length ) {
        $str_in = current( explode( "\n", wordwrap( $str_in, $desired_length ) ) );
    }
    // String length
    $string_length = strlen( $str_in );
    // Number of spaces
    $spaces_count = substr_count( $str_in, ' ' );
    // Number of total spaces needed
    $needed_spaces_count = $desired_length - $string_length + $spaces_count;
    // One word, so split spaces in half, ceil it add it to eaither side of the word
    // Then take the first 48 chars
    if ( $spaces_count === 0 ) {
        return str_pad( $str_in, $desired_length, ' ', STR_PAD_BOTH );
    }
    // How many spaces the string needs per space to have atleast 48 characters
    $spaces_per_space = ceil( $needed_spaces_count / $spaces_count );
    // Replace all spaces with the neccessary spaces per space
    // This string will sometimes be too long
    $spaced_string = preg_replace( '~\s+~', str_repeat( ' ', $spaces_per_space ), $str_in );

    for ( $j=0; $j <$justifyL_length; $j++ )

{

if ( $justifyL_length <$L_length &&$justifyline[$j] == " " )

{

$justifyline = substr_replace( $justifyline, " ", $j, 0 );

$justifyL_length++;

$j++;

}

}

}

$txtJustify .= "$justifyline\n";

$strtofline += $originallength + 1;

}
    // Now, some strings will be too long so you need to replace the spaces with one less space until
    // the desired amount of characters is reached
    //
    // This is done with preg_replace callback and the $limit parameter
    // Limit replacements to the exact number we need to reach the desired length
    return preg_replace_callback(
        sprintf( '~\s{%s}~', $spaces_per_space ),
        function ( $m ) use( $spaces_per_space ) {
            return str_repeat( ' ', $spaces_per_space-1 );
        },
        $spaced_string,
        strlen( $spaced_string ) - $desired_length
    );
}

$app->post('/api/justify', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    // on protege contre les attaques (filter var) puis on justifie.
    $justified_text = justify(filter_var($data['text'], FILTER_SANITIZE_STRING));
    $response->getBody()->write($justified_text);

    return $response;
});

// get ou post 

$app->get('/bonjour/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("bonjour, $name");

    return $response;
});

$app->get('/test/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("test");

    return $response;
});

$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Hello");

    return $response;
});

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->run();

