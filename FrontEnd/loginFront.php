<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" 
  integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<body class="bg-warning">
    
<div class="bg-info-subtle mt-5 container w-75 h-75 d-flex justify-content-center align-items-center">


<form method="post">
  <h4 class="text-center mb-4">LOGIN FORM</h4>
<div class="mb-3">
    <label for="username" class="form-label">Username</label>
    <input type="text" name="username" class="form-control" id="Login_Input">
  </div>
  

  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" name="password" class="form-control" id="Login_Password">
  </div>

  <button type="button" class=" m-3 btn btn-primary" onclick="fetchRouteData()">Login</button>
  <button type="button" class="btn btn-primary ms-3" onclick="window.location.href='index.html'">
    Back to Home
</button>


  <p class="mt-3 ">If you are a new user!<a href="../Server/user_login/sign_Up.php">Click Here to Signup</a></p>

</form>


<script>
    function fetchRouteData() {
    let username = document.getElementById("Login_Input").value;
    let password = document.getElementById("Login_Password").value;

    console.log(password);
    
    
    if (username === "" || password === "") {
        alert("Enter your username and password");
        return;
    }

    $.ajax({
        url: "../Server/user_login/login.php",
        type: "POST",
        dataType: "json",
        data: { username: username, password: password },
        success: function(response) {          
            if (response.status === "success") {
              alert("login successful");
                window.location.href = "SiteDetails.php";
            } else {
                console.log("failed");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching route data:", error);
        }
    });
}

</script>
</body>
</html>

