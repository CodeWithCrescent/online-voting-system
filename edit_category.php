<?php
session_start();
include 'config/dbconnection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM categories WHERE id = $id");

    if ($result) {
        $row = $result->fetch_assoc();
        if ($row) { ?>
            <form id="update-category-form">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="category" class="form-label">Category Name</label>
                        <input type="hidden" name="category_id" value="<?php echo $row['id']; ?>">
                        <input type="text" name="category" value="<?php echo $row['name'] ?>" class="form-control" id="category" data-error-message="Category is required">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" data-bs-dismiss="modal" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
<?php    }
    } else {
        echo "Error executing the query.";
    }

    $conn->close();
} else {

    echo "404 Error! No category found.";
}
?>