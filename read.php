<?php
// Include config file
require_once "config.php";

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    
    // Prepare a select statement
    $sql = "SELECT * FROM products WHERE product_id = :id";
    
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":id", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            if($stmt->rowCount() == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Retrieve individual field value
                $productName = $row["product_name"];
                $productDescription = $row["product_description"];
                $productRetailPrice = $row["product_retail_price"];
                $productDateAdded = $row["product_date_added"];
                $productUpdatedDate = $row["product_updated_date"];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    unset($stmt);
    
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">View Product</h1>
                    <div class="form-group">
                        <label>Name</label>
                        <p><b><?php echo htmlspecialchars($productName); ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <p><b><?php echo htmlspecialchars($productDescription); ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Retail Price</label>
                        <p><b><?php echo htmlspecialchars($productRetailPrice); ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Date Added</label>
                        <p><b><?php echo htmlspecialchars($productDateAdded); ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Updated Date</label>
                        <p><b><?php echo htmlspecialchars($productUpdatedDate); ?></b></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>