<?php
define('ROOT', dirname(dirname(__FILE__)));
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once ROOT.'/models/User.php';

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);


// Get All Users
$app->get('/api/users', function(Request $request, Response $response) {
    $sql = "SELECT * FROM users";

    try{
        $db = new Database();

        $user = new User($db);

        $users = $user->query($sql);

        echo json_encode($users);

    } catch(PDOException $e) {
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Get Single User
$app->get('/api/user/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM users WHERE id = $id";

    try{
        $db = new Database();

        $user = new User($db);

        $user = $user->query($sql);

        echo json_encode($user);

    } catch(PDOException $e) {
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Add User
$app->post('/api/user/add', function(Request $request, Response $response) {
    $firstname = $request->getParam('firstname');
    $lastname = $request->getParam('lastname');
    $phone = $request->getParam('phone');
    $email = $request->getParam('email');
    $address = $request->getParam('address');
    $city = $request->getParam('city');
    $state = $request->getParam('state');

    $data = array(
        'firstname' => $firstname,
        'lastname' => $lastname,
        'phone' => $phone,
        'email' => $email,
        'address' => $address,
        'city' => $city,
        'state' => $state
    );

    try{
        $db = new Database();

        $user = new User($db);

        $user = $user->insert($data);

        echo '{"notice": {"text": "Customer Added"}';

    } catch(PDOException $e) {
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Update User
$app->put('/api/user/update/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $firstname = $request->getParam('firstname');
    $lastname = $request->getParam('lastname');
    $phone = $request->getParam('phone');
    $email = $request->getParam('email');
    $address = $request->getParam('address');
    $city = $request->getParam('city');
    $state = $request->getParam('state');

    $data = array(
        'firstname' => $firstname,
        'lastname' => $lastname,
        'phone' => $phone,
        'email' => $email,
        'address' => $address,
        'city' => $city,
        'state' => $state
    );

    try{
        $db = new Database();

        $user = new User($db);

        $user = $user->update($id, $data);

        echo '{"notice": {"text": "Customer Updated"}';

    } catch(PDOException $e) {
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Delete a User
$app->delete('/api/user/delete/{id}', function (Request $request, Response $response) {
    $data['id'] = $request->getAttribute('id');

    try {
        $db = new Database();

        $user = new User($db);

        $user = $user->delete($data);

        echo '{"notice": {"text": "Customer Deleted"}';        

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});