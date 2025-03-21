<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require("../db_connection/connection.php");




$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {

case 'POST':
    try {
        if (empty($_POST)) {
            echo json_encode(["status" => "error", "message" => "No POST data received"]);
            exit;
        }

        $username = trim($_POST['username']);
        $sitename = trim($_POST['sitename']);
        $awstype = $_POST['awstype'];
        $awsconcept = $_POST['awsconcept'];

        if (empty($username) || empty($sitename) || empty($awstype) || empty($awsconcept)) {
            echo json_encode(["status" => "error", "message" => "All fields are required"]);
            exit;
        }

        $stmt = $pdo->prepare('INSERT INTO site_details (username, sitename, awstype, awsconcept) 
                               VALUES (:username, :sitename, :awstype, :awsconcept)');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':sitename', $sitename, PDO::PARAM_STR);
        $stmt->bindParam(':awstype', $awstype, PDO::PARAM_STR);
        $stmt->bindParam(':awsconcept', $awsconcept, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "data" => [
                    "id" => $pdo->lastInsertId(),
                    "username" => $username,
                    "sitename" => $sitename,
                    "awstype" => $awstype,
                    "awsconcept" => $awsconcept
                ]
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to insert data"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "error_message" => $e->getMessage()]);
    }
    break;





    case 'GET':

       


        try {
            // session_start();
            session_start();

            if (!isset($_SESSION['combined_token']) || empty($_SESSION['combined_token'])) {
                echo json_encode(["status" => "error", "message" => "CSRF token validation failed!"]);
                exit;
            }
            

            if (!isset($_SESSION['role']) || !isset($_SESSION['username'])) {
                echo json_encode(["status" => "error", "message" => "Session variables missing"]);
                exit;
            }
            
            $combined_token = $_SESSION['combined_token'];

           $pieces = explode("$", $combined_token);
           $loggedInUsername = $pieces[1]; 
           $userRole= $pieces[2]; 
           
            if ($userRole == "SuperAdmin") {
                $stmt = $pdo->query("SELECT * FROM site_details");
                $sites = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else if ($userRole == "Admin") {
                $stmt = $pdo->prepare("SELECT * FROM site_details WHERE username = :username");
                $stmt->bindParam(":username", $loggedInUsername, PDO::PARAM_STR);
                $stmt->execute();
                $sites = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else{
                $stmt = $pdo->query("SELECT username, sitename, awsconcept, awstype FROM site_details");
                $sites = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            
            echo json_encode([
                "status" => "success",
                "data" => $sites,
                "user_role" => $userRole,
                "session_username" => $loggedInUsername
            ]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        exit;
        


break;




case 'PUT':
    try {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id'])) {
            echo json_encode(["status" => "error", "message" => "Missing 'id' in request"]);
            exit;
        }

        $id = $data['id'];
        $username = $data['username'] ?? null;
        $sitename = $data['sitename'] ?? null;
        $awstype = $data['awstype'] ?? null;
        $awsconcept = $data['awsconcept'] ?? null;

        if (!$sitename || !$awsconcept || !$awstype) {
            echo json_encode(["status" => "error", "message" => "All fields are required"]);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE site_details SET 
                               username = :username, 
                               sitename = :sitename,
                               awstype = :awstype, 
                               awsconcept = :awsconcept
                               WHERE id = :id");

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":sitename", $sitename);
        $stmt->bindParam(":awstype", $awstype);
        $stmt->bindParam(":awsconcept", $awsconcept);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Site details updated successfully",
                "data" => [
                    "id" => $id,
                    "username" => $username,
                    "sitename" => $sitename,
                    "awstype" => $awstype,
                    "awsconcept" => $awsconcept
                ]
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update data"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "error_message" => $e->getMessage()]);
    }
    break;



    case 'DELETE':
        try {
            

                    $data = json_decode(file_get_contents("php://input"), true);
        
                    if (!isset($data['id']) || empty($data['id'])) {
                        echo json_encode(["status" => "error", "message" => "Missing 'id' in request"]);
                        exit;
                    }
        
                    $id = intval($data['id']); 
        
                    $stmt = $pdo->prepare("DELETE FROM site_details WHERE id = ?");
                    $stmt->execute([$id]);
        
                    if ($stmt->rowCount() > 0) {
                        echo json_encode(["status" => "success", "message" => "Record deleted successfully"]);
                    } else {
                        echo json_encode(["status" => "error", "message" => "No matching record found"]);
                    }
                   break;
        
               
            
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "error_message" => $e->getMessage()]);
        }
    break;

default:
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    break;


    

    }



?>
