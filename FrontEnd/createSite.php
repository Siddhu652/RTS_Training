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

<body class="bg-info">
<div class="bg-info-subtle mt-5 container w-75 h-75 d-flex justify-content-center align-items-center">


<form method="post">
  
<h4 class="text-center m-4">ENTER SITE DETAILS</h4>
<div class="mb-3">
    <label for="username" class="form-label">Username</label>
    <input type="text" name="username" class="form-control" id="username">
  </div>
  

  <div class="mb-3">
    <label for="sitename" class="form-label">Site Name</label>
    <input type="text" name="sitename" class="form-control" id="sitename">
  </div>


  <div class="mb-3">
    <label for="awsconcept" class="form-label">AWS CONCEPT</label>
    <select  class="form-control" name="awstype" id="awsconcept" aria-label="Default select example">
  <option selected>Select AWS Concept</option>
  <option value="AWS 1">AWS 1</option>
  <option value="AWS 2">AWS 2</option>
</select>
  </div>

  <div class="mb-3">
    <label for="awstype" class="form-label">AWS TYPE</label>
    <select  class="form-control" name="awstype" id="awstype" aria-label="Default select example">
  <option selected>Select AWS Type</option>
  <option value="IN">IN</option>
  <option value="OUT">OUT</option>
</select>
  </div>


  <button type="button" class="m-4 btn btn-success" onclick="create_Site()">Register</button>

</form>
<script>
    function create_Site() {
        let username = document.getElementById("username").value.trim();
        let sitename = document.getElementById("sitename").value.trim();
        let awstype = document.getElementById("awstype").value;
        let awsconcept = document.getElementById("awsconcept").value;
console.log(username);


        if(username=="" || sitename=="" || awstype=="" || awsconcept==""){
         alert('Enter all the details to proceed');
         return;
        }

        $.ajax({
    url: "../Server/siteFunction.php",
    type: "POST",   
    dataType: "json",
    data: {
        "username": username,
        "sitename": sitename,
        "awstype": awstype,
        "awsconcept": awsconcept
    },
    success: function(response) {
      console.log("Server Response:", response); 
      alert("Site registered successfully!");
if(response.status == 'success'){
  window.location.href="SiteDetails.php";
}
        
    },
    error: function(xhr, status, error) {
        console.error("Error:",error);
        alert("Failed to register site. Please try again.");
    }
});

}
</script>

</body>
</html>
