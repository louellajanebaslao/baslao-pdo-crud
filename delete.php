<?php
require_once "config.php";

// Process delete operation after confirmation
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["id"]) && !empty($_POST["id"])) {
        $id = trim($_POST["id"]);

        $sql = "DELETE FROM products WHERE product_id = :id";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    } else {
        header("Location: error.php");
        exit();
    }
}

// Check existence of id parameter
if (empty($_GET["id"])) {
    // URL doesn't contain id parameter. Redirect to error page
    header("Location: error.php");
    exit();
}

$id = trim($_GET["id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
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
                    <h2 class="mt-5 mb-3">Delete Record</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                            <p>Are you sure you want to delete this product record?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="index.php" class="btn btn-secondary ml-2">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>