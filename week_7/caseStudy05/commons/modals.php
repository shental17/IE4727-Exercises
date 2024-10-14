
<!-- Add Product Modal -->
<div id="addProductModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">  
                <h5 class="modal-title">Add New Product</h5>
                <button type="button" class="btn-close" aria-label="Close" onclick="closeModal('addProductModal');" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <form id="addProductForm">
                <div class="modal-body">
                    <label for="productName" class="form-label">Product Name:</label></td>
                    <input type="text" id="productName" name="name" placeholder="Enter Product Name" class="form-control" required>
                    <label for="productDescription" class="form-label">Product Description:</label>
                    <textarea id="productDescription" name="description" placeholder="Enter Product Description" rows="10" class="form-label w-100" required></textarea>
                    <input type="hidden" name="action" value="addProduct">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add</button>
                    <button type="button" class="btn btn-light" onclick="closeModal('addProductModal')">Cancel</button>
                </div>    
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editProductModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">  
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" aria-label="Close" onclick="closeModal('editProductModal');" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <form id="editProductForm">
                <div class="modal-body">
                    <label for="editProductName" class="form-label">Product Name:</label></td>
                    <input type="text" id="editProductName" name="name" class="form-control"required></td>
                    <label for="editProductDescription" class="form-label">Product Description:</label></td>
                    <textarea id="editProductDescription" name="description" rows="10" class="form-label w-100" required></textarea>
                    <input type="hidden" name="id">
                    <input type="hidden" name="action" value="editProduct">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Edit</button>
                    <button type="button" class="btn btn-light" onclick="closeModal('editProductModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Add Category Modal -->
<div id="addCategoryModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal('addCategoryModal')">X</button>
            </div>
            <form id="addCategoryForm">
                <div class="modal-body">
                    <label for="categoryName" class="form-label">Category Name:</label>
                    <input type="text" id="categoryName" name="name" placeholder="Enter Category Name" class="form-control" required>
                    <label for="categoryPrice" class="form-label">Category Price:</label>
                    <input type="number" id="categoryPrice" name="price" placeholder="Enter Category Price" step="0.01" class="form-control" required>
                    <label for="categoryProduct" class="form-label">Product:</label>
                    <select id="categoryProduct" name="product_id" class="form-select" required>
                        <option value="">Select Product</option>
                        <?php
                        $result = $conn->query("SELECT * FROM products");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value=\"{$row['id']}\">{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="action" value="addCategory">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-light" onclick="closeModal('addCategoryModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="modal" >
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal('editCategoryModal')">X</button>
            </div>
            <form id="editCategoryForm">
                <div class="modal-body">
                        <label for="editCategoryName" class="form-label">Category Name:</label>
                        <input type="text" id="editCategoryName" name="name" class="form-control" required>
                        <label for="editCategoryPrice" class="form-label">Category Price:</label>
                        <input type="number" id="editCategoryPrice" name="price" step="0.01" class="form-control" required>
                        <label for="editCategoryProduct" class="form-label">Product:</label>
                        
                        <select id="editCategoryProduct" name="product_id" class="form-select" aria-label="Default select example" required>
                            <?php
                                // Fetch products from the database
                                $result = $conn->query("SELECT * FROM products");
                                while ($row = $result->fetch_assoc()) {
                                    // Assuming $productId is set correctly before this
                                    $selected = ($row['id'] == $productId) ? "selected" : ""; // Set selected if IDs match
                                    echo "<option value=\"{$row['id']}\" $selected>{$row['name']}</option>";
                                }
                            ?>
                                </select>
                </div>
                <input type="hidden" name="id">
                <input type="hidden" name="action" value="editCategory">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Edit</button>
                    <button type="button" class="btn btn-light" onclick="closeModal('editCategoryModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

