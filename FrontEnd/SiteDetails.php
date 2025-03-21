<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-color: #f8f9fa;
        }

        .table-container {
            margin: 50px auto;
            max-width: 900px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>



    function ShowSiteData() {
        $.ajax({
            url: "../Server/siteFunction.php",
            type: "GET",
            dataType: "json",
            success: function (response) {
                console.log("Fetched Data:", response);
                let tableBody = $("tbody");
                tableBody.empty();
                if (response.status === "success") {
                    response.data.forEach((site, index) => {
                        let actionButtons = "";

                if (response.user_role === "SuperAdmin") {
                    actionButtons = `<td><i class='fa-solid fa-pen-to-square edit-btn text-primary' 
                        style='cursor:pointer;' onclick='edit_Site(${site.id})'></i></td>
                     <td><i class='fa-solid fa-trash delete-btn text-danger' 
                        style='cursor:pointer;' onclick='delete_Site(${site.id})'></i></td>`;
                        }
                else if (response.user_role === "Admin" && site.username === response.session_username) {
                        actionButtons = `<td><i class='fa-solid fa-pen-to-square edit-btn text-primary' 
                        style='cursor:pointer;' onclick='edit_Site(${site.id})'></i></td>
                     <td><i class='fa-solid fa-trash delete-btn text-danger' 
                        style='cursor:pointer;' onclick='delete_Site(${site.id})'></i></td>`;
                            }
                else {
                         actionButtons = `<td></td><td></td>`;
                            }


                        let row = "";
                        row += `<tr>
                    <td>${index + 1}</td>
                    <td>${site.username}</td>
                    <td>${site.sitename}</td>
                    <td>${site.awsconcept}</td>
                    <td>${site.awstype}</td>
                    ${actionButtons}
                </tr>`;
                        // $("#table-id").html(row);
                        tableBody.append(row); 
                    });
                }
                else {
                    alert("resposne failed");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error Fetching Data:", error);
                console.error("Full Server Response:", xhr.responseText);
                alert("Failed to load site details.");
            }
        });
    }

    ShowSiteData();








    function edit_Site(id) {
        console.log("Editing Site ID:", id);

        $.ajax({
            url: "../Server/Get_SiteRow.php",
            type: "GET",
            dataType: "json",
            data: { id: id },

            success: function (response) {
                console.log("Edit Data:", response);
                console.log("Name: ", response.id);
                if (response.status === "success" && response.data.length > 0) {
                    let siteData = response.data[0];

                    $("#editSiteId").val(siteData.id);
                    $("#editUsername").val(siteData.username);
                    $("#editSitename").val(siteData.sitename);
                    $("#editAwstype").val(siteData.awstype);
                    $("#editAwsconcept").val(siteData.awsconcept);

                    $("#editSiteModal").modal("show");

                } else {
                    alert("No data found for this ID.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error Fetching Data:", error);
                alert("Failed to load site details.");
            }
        });
    }





    function save_Edit(id) {
        console.log(id);

        $.ajax({
            url: "../Server/siteFunction.php",
            type: "PUT",
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify({
                "id": id,
                "username": $("#editUsername").val(),
                "sitename": $("#editSitename").val(),
                "awsconcept": $("#editAwsconcept").val(),
                "awstype": $("#editAwstype").val()
            }),
            success: function (response) {
                console.log("Update Success:", response);
                alert("Data Updated Successfully!");
                $("#editSiteModal").modal("hide");
                ShowSiteData();
                // location.reload();  
            },
            error: function (xhr, status, error) {
                console.error("Error Updating Data:", error);
                console.error("Full Server Response:", xhr.responseText);
                alert("Failed to update site details.");
            }
        });
    }






    function delete_Site(id) {

        $.ajax({
            url: "../Server/siteFunction.php",
            type: "DELETE",
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify({
                id: id
            }),
            success: function (response) {

                if (response.status === "success") {
                    alert("Data Deleted Successfully!");
                    ShowSiteData();
                } else {
                    alert("Failed to delete: " + response.message);
                }

            },
            error: function (xhr, status, error) {
                console.error("Error Updating Data:", error);
                console.error("Full Server Response:", xhr.responseText);
                alert("Failed to Delete site details.");
            }
        });
    }

</script>

<body>
<h3 class="text-center m-4">Add Site Detials</h3>
    <header class="mt-3 d-flex flex-row align-items-center justify-content-between">
        
        <a href="createSite.php">
            <button class="btn btn-success m-4">ADD</button>
        </a>
    <a href="logout.php">
        <button class="btn btn-danger m-4">LOGOUT</button>
        </a></div>
        
    </header>
    
    <div class="container table-container">     
        <h3 class="text-center mb-4">Site Details</h3>
        <table id="table-id" class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col">S.NO</th>
                    <th scope="col">User Name</th>
                    <th scope="col">Site Name</th>
                    <th scope="col">AWS Concept</th>
                    <th scope="col">AWS Type</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>



            </tbody>
        </table>
    </div>

    <div class="modal fade" id="editSiteModal" tabindex="-1" aria-labelledby="editSiteModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSiteModalLabel">Edit Site Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editSiteForm">
                        <input type="hidden" id="editSiteId">

                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUsername" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="editSitename" class="form-label">Site Name</label>
                            <input type="text" class="form-control" id="editSitename">
                        </div>

                        <div class="mb-3">
                            <label for="editAwsconcept" class="form-label">AWS Concept</label>
                            <select class="form-control" id="editAwsconcept">
                                <option value="AWS 1">AWS 1</option>
                                <option value="AWS 2">AWS 2</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editAwstype" class="form-label">AWS Type</label>
                            <select class="form-control" id="editAwstype">
                                <option value="IN">IN</option>
                                <option value="OUT">OUT</option>
                            </select>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveChanges"
                        onclick="save_Edit($('#editSiteId').val())">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>