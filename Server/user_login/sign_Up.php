<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" 
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="bg-warning">
<div class="bg-info-subtle mt-5 container w-75 h-75 d-flex justify-content-center align-items-center">


<form method="post" action="">
  
<h4 class="text-center m-4">REGISTRATION FORM</h4>
<div class="mb-3">
    <label for="exampleInputUsername" class="form-label">Username</label>
    <input type="text" name="username" class="form-control" id="exampleInputUsername">
  </div>
  

  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" name="password" class="form-control" id="exampleInputPassword1">
  </div>
  

  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email Address</label>
    <input type="email" name="email" class="form-control" id="exampleInputEmail1">
  </div>

  <div class="mb-3">
    <label for="PhoneNo" class="form-label">Phone Number</label>
    <input type="number" name="phoneno" class="form-control" id="phoneno" maxlength=10 >
  </div>

  <div class="mb-3">
    <label for="Role" class="form-label">Role</label>
    <select  class="form-control" name="role" aria-label="Default select example">
  <option selected>Select your role</option>
  <option value="SuperAdmin">SuperAdmin</option>
  <option value="Admin">Admin</option>
  <option value="User">User</option>
</select>
  </div>


  <button type="submit" class="m-3 btn btn-primary">Register</button>
<a href="../../FrontEnd/loginFront.php" class="btn btn-primary">Go Back to Login</a>



  
</form>

</body>
</html>


<?php
include("../../db_connection/connection.php");

?>

<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $mobileno = trim($_POST['phoneno']);
    $role = trim($_POST['role']);


    
    if (empty($username) || empty($password) || empty($email) || empty($mobileno) || empty($role)) {
        echo "<script>alert('Enter all the details');</script>";  
    } 
    
    else {
        $hashed_pass = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("INSERT INTO user_details (Username, password, email, mobileno, role) VALUES (:username, :password, :email, :mobileno, :role)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_pass);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mobileno', $mobileno);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location.href='../../FrontEnd/loginFront.php';</script>";  
        } else {
            echo "<script>alert('There is an error in registration');</script>";
        }
    }
}
?>


