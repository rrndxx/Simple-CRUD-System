<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location: adminlogin.php');
}
require_once('connection.php');

$newConnection->addProduct();
$newConnection->editProduct();
$newConnection->deleteProduct();
$newConnection->addCategory();
$products = [];
$users = [];
$categories = $newConnection->getCategories();

if (isset($_POST['filterProducts'])) {
    $selectedCategory = $_POST['selectedCategory'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    $products = $newConnection->filterProducts($selectedCategory, $startDate, $endDate);
} elseif (isset($_POST['searchbutton'])) {
    $products = $newConnection->searchProduct();
} elseif (isset($_POST['instock'])) {
    $products = $newConnection->inStock();
} elseif (isset($_POST['outofstock'])) {
    $products = $newConnection->outofStock();
} else {
    $connection = $newConnection->openConnection();
    $stmnt = $connection->prepare("SELECT * FROM products");
    $stmnt->execute();
    $products = $stmnt->fetchAll();
}

$connection = $newConnection->openConnection();
$stmnt = $connection->prepare("SELECT * FROM users WHERE role = 'Customer'");
$stmnt->execute();
$users = $stmnt->fetchAll();

if (isset($_POST['logout'])) {
    session_start();
    session_destroy();
    header('location: adminlogin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<style>
    body {
        margin: 0;
        padding: 20px;
        background-color: #808D7C;
        font-family: Montserrat;
        color: white;
    }

    .navbar-brand {
        font-size: 30px;
    }

    .table-responsive {
        margin-top: 20px;
        box-shadow: rgba(0, 0, 0, 0.44) 0px 3px 8px;
    }

    button,
    .tb,
    .modal {
        box-shadow: rgba(0, 0, 0, 0.44) 0px 3px 8px;
    }
</style>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <p class="navbar-brand"><?php echo "Welcome, " . $_SESSION['admin'] . "!"; ?>
                <button class="navbar-toggler bg-success" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <form class="d-flex ms-auto" method="POST">
                    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addCat">Add Category</button>
                    <button type="button" class="btn btn-success me-2" data-bs-toggle="modal"
                        data-bs-target="#addModal">
                        Add Product
                    </button>
                    <button type="button" class="btn btn-info me-2" data-bs-toggle="modal"
                        data-bs-target="#filterModal">
                        Filter
                    </button>
                    <input type="search" class="tb form-control me-2" placeholder="Input product name" name="search"
                        required>
                    <button class="btn btn-primary" type="submit" name="searchbutton">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <hr class="mb-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-start">PRODUCTS</h2>
        <div class="text-end">
            <form action="" method="POST">
                <button class="btn btn-warning me-2" type="button"
                    onclick="window.location.href='main.php'">All Products</button>
                <button class="btn btn-success me-2" type="submit" name="instock">In Stock</button>
                <button class="btn btn-danger" type="submit" name="outofstock">Out of Stock</button>
            </form>
        </div>
    </div>  

    <!-- PRODUCTS TABLE -->
    <div class="table-responsive mb-4">
        <table class="table table-hover" style="color: white;">
            <thead>
                <tr class="text-center bg-secondary">
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category ID</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Purchased Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr class="text-center">
                        <th scope="row"><?php echo $product->id; ?></th>
                        <td><?php echo $product->prod_name; ?></td>
                        <td><?php echo $product->cat_id; ?></td>
                        <td><?php echo $product->cat; ?></td>
                        <td><?php echo $product->quan; ?></td>
                        <td><?php echo $product->date; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <button type="button" class="btn btn-primary me-4 w-25" data-bs-toggle="modal"
                                    data-bs-target="#editModal<?= $product->id ?>">
                                    Edit
                                </button>
                                <button type="submit" class="btn btn-danger w-25" name="deletebutton"
                                    value="<?php echo $product->id; ?>">Delete
                                </button>
                            </form>
                        </td>
                        <?php include 'modals.php'; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <hr class="my-4">

    <div class="mt-4">
        <h2>USERS</h2>
    </div>

    <!-- USERS TABLE -->
    <div class="table-responsive mt-4">
        <table class="table table-hover" style="color: white;">
            <thead>
                <tr class="text-center bg-secondary">
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Address</th>
                    <th>Birthdate</th>
                    <th>Gender</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Date Joined</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr class="text-center">
                        <th scope="row"><?php echo $user->first_name; ?></th>
                        <td><?php echo $user->last_name; ?></td>
                        <td><?php echo $user->address; ?></td>
                        <td><?php echo $user->birthdate; ?></td>
                        <td><?php echo $user->gender; ?></td>
                        <td><?php echo $user->username; ?></td>
                        <td><?php echo $user->role; ?></td>
                        <td><?php echo $user->date_created; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <form action="" method="POST" class="mt-5">
        <div class="text-end">
            <button class="btn btn-danger" type="submit" name="logout">Logout</button>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

</body>

</html>