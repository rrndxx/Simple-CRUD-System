<?php
require_once('connection.php');

$newConnection->addProduct();
$newConnection->editProduct();
$newConnection->deleteProduct();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<style>
    body {
        margin: 0;
        padding: 20px;
        background-color: #f8f9fa;
    }

    .navbar-brand {
        font-size: 30px;
    }

    .modal-header {
        background-color: #28a745;
        color: white;
    }

    .table-responsive {
        margin-top: 20px;
    }
</style>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Gaisano Bogo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <form class="d-flex ms-auto" method="POST">
                    <button type="button" class="btn btn-success me-2" data-bs-toggle="modal"
                        data-bs-target="#addModal">
                        Add
                    </button>
                    <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal"
                        data-bs-target="#filterModal">
                        Filter
                    </button>
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <!-- TABLE -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Purchased Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $connection = $newConnection->openConnection();
                $stmnt = $connection->prepare("SELECT * FROM products");
                $stmnt->execute();
                $result = $stmnt->fetchAll();
                if ($result) {
                    foreach ($result as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row->id; ?></td>
                            <td><?php echo $row->prod_name; ?></td>
                            <td><?php echo $row->cat; ?></td>
                            <td><?php echo $row->quan; ?></td>
                            <td><?php echo $row->date; ?></td>
                            <td>
                                <button type="button" class="btn btn-outline-primary me-4 w-25" data-bs-toggle="modal"
                                    data-bs-target="#editModal<?= $row->id ?>">
                                    Edit
                                </button>
                                <button type="submit" class="btn btn-outline-danger w-25" name="deletebutton"
                                    value="<?php echo $row->id; ?>">Delete
                                </button>
                            </td>
                            <?php include('modal.php'); ?>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- ADD MODAL -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="exampleModalLabel">ADD PRODUCT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form method="POST" action="">
                            <div class="row g-3 mb-3">
                                <div class="col">
                                    <label for="inputproductname" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="inputproductname" name="productname"
                                        required>
                                </div>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col">
                                    <label for="inputState" class="form-label">Category</label>
                                    <select id="inputState" class="form-select" name="category" required>
                                        <option selected disabled>Choose...</option>
                                        <option>Vegetable</option>
                                        <option>Fruit</option>
                                        <option>Drink</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="inputquantity" class="form-label">Quantity</label>
                                    <input type="text" class="form-control" id="inputquantity" name="quantity" required>
                                </div>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col">
                                    <label for="inputpurchasedate" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="inputpurchasedate" name="purchasedate"
                                        required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success" name="addproduct">Add Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- FILTER MODAL -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Select Filter Type</label>
                        <select id="filterType" class="form-select" name="filterType" required>
                            <option value="">Choose...</option>
                            <option value="category">By Category</option>
                            <option value="availability">By Availability</option>
                            <option value="date">By Date Range</option>
                        </select>
                    </div>

                    <div id="categoryFilter" class="filter-option mb-3 d-none">
                        <label for="filterCategory" class="form-label">Category</label>
                        <select id="filterCategory" class="form-select" name="filterCategory">
                            <option value="">All Categories</option>
                            <option value="Vegetable">Vegetable</option>
                            <option value="Fruit">Fruit</option>
                            <option value="Drink">Drink</option>
                        </select>
                    </div>

                    <div id="availabilityFilter" class="filter-option mb-3 d-none">
                        <label for="filterAvailability" class="form-label">Availability</label>
                        <select id="filterAvailability" class="form-select" name="filterAvailability">
                            <option value="">All</option>
                            <option value="in_stock">In Stock</option>
                            <option value="out_of_stock">Out of Stock</option>
                        </select>
                    </div>

                    <div id="dateFilter" class="filter-option mb-3 d-none">
                        <label class="form-label">Date Range</label>
                        <div class="row">
                            <div class="col">
                                <input type="date" class="form-control" id="filterDateFrom" name="filterDateFrom">
                            </div>
                            <div class="col">
                                <input type="date" class="form-control" id="filterDateTo" name="filterDateTo">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" name="applyFilters">Apply Filters</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>

</html>
