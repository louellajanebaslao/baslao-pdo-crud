<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$product_name = $product_description = $product_retail_price = "";
$product_name_err = $product_description_err = $product_retail_price_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate product name
    $input_product_name = trim($_POST["product_name"]);
    if(empty($input_product_name)){
        $product_name_err = "Please enter a product name.";
    } else{
        $product_name = $input_product_name;
    }
    
    // Validate product description
    $input_product_description = trim($_POST["product_description"]);
    if(empty($input_product_description)){
        $product_description_err = "Please enter a product description.";     
    } else{
        $product_description = $input_product_description;
    }
    
    // Validate product retail price
    $input_product_retail_price = trim($_POST["product_retail_price"]);
    if(empty($input_product_retail_price)){
        $product_retail_price_err = "Please enter the product retail price.";     
    } elseif(!is_numeric($input_product_retail_price)){
        $product_retail_price_err = "Please enter a numeric value for the retail price.";
    } else{
        $product_retail_price = $input_product_retail_price;
    }
    
    // Check input errors before inserting in database
    if(empty($product_name_err) && empty($product_description_err) && empty($product_retail_price_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO products (product_name, product_description, product_retail_price) VALUES (:product_name, :product_description, :product_retail_price)";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":product_name", $param_product_name);
            $stmt->bindParam(":product_description", $param_product_description);
            $stmt->bindParam(":product_retail_price", $param_product_retail_price);
            
            // Set parameters
            $param_product_name = $product_name;
            $param_product_description = $product_description;
            $param_product_retail_price = $product_retail_price;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to index.php
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Product</title>
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
                    <h2 class="mt-5">Create Product</h2>
                    <p>Please fill this form and submit to add a new product to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="product_name" class="form-control <?php echo (!empty($product_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $product_name; ?>">
                            <span class="invalid-feedback"><?php echo $product_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Product Description</label>
                            <textarea name="product_description" class="form-control <?php echo (!empty($product_description_err)) ? 'is-invalid' : ''; ?>"><?php echo $product_description; ?></textarea>
                            <span class="invalid-feedback"><?php echo $product_description_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Product Retail Price</label>
                            <input type="text" name="product_retail_price" class="form-control <?php echo (!empty($product_retail_price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $product_retail_price; ?>">
                            <span class="invalid-feedback"><?php echo $product_retail_price_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>