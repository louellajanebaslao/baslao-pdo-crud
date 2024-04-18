<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$productName = $productDescription = $productRetailPrice = "";
$productName_err = $productDescription_err = $productRetailPrice_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate product name
    $input_productName = trim($_POST["name"]);
    if (empty($input_productName)) {
        $productName_err = "Please enter a product name.";
    } else {
        $productName = $input_productName;
    }

    // Validate product description
    $input_productDescription = trim($_POST["description"]);
    if (empty($input_productDescription)) {
        $productDescription_err = "Please enter a product description.";
    } else {
        $productDescription = $input_productDescription;
    }

    // Validate product retail price
    $input_productRetailPrice = trim($_POST["retail_price"]);
    if (empty($input_productRetailPrice)) {
        $productRetailPrice_err = "Please enter the product retail price.";
    } elseif (!ctype_digit($input_productRetailPrice)) {
        $productRetailPrice_err = "Please enter a positive integer value.";
    } else {
        $productRetailPrice = $input_productRetailPrice;
    }

    // Check input errors before inserting into database
    if (empty($productName_err) && empty($productDescription_err) && empty($productRetailPrice_err)) {
        // Prepare an update statement
        $sql = "UPDATE products SET product_name = :productName, product_description = :productDescription, product_retail_price = :productRetailPrice WHERE product_id = :id";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":productName", $productName);
            $stmt->bindParam(":productDescription", $productDescription);
            $stmt->bindParam(":productRetailPrice", $productRetailPrice);
            $stmt->bindParam(":id", $param_id);

            // Set parameters
            $param_id = $_POST["id"];

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        unset($stmt);
    }

    // Close connection
    unset($pdo);
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id = trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM products WHERE product_id = :id";
        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":id", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Retrieve individual field value
                    $productName = $row["product_name"];
                    $productDescription = $row["product_description"];
                    $productRetailPrice = $row["product_retail_price"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        unset($stmt);

        // Close connection
        unset($pdo);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Product</title>
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
                <h2 class="mt-5">Update Product</h2>
                <p>Please edit the input values and submit to update the product record.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($productName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $productName; ?>">
                        <span class="invalid-feedback"><?php echo $productName_err;?></span>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control <?php echo (!empty($productDescription_err)) ? 'is-invalid' : ''; ?>"><?php echo $productDescription; ?></textarea>
                        <span class="invalid-feedback"><?php echo $productDescription_err;?></span>
                    </div>
                    <div class="form-group">
                        <label>Retail Price</label>
                        <input type="text" name="retail_price" class="form-control <?php echo (!empty($productRetailPrice_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $productRetailPrice; ?>">
                        <span class="invalid-feedback"><?php echo $productRetailPrice_err;?></span>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>