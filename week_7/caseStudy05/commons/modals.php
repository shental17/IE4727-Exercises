
<!-- Add Product Modal -->
<div id="addProductModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addProductModal')">&times;</span>
        <h2>Add New Product</h2>
        <form id="addProductForm">
            <table>
                <tr>
                    <td><label for="productName">Product Name:</label></td>
                    <td><input type="text" id="productName" name="name" placeholder="Enter Product Name" required></td>
                </tr>
                <tr>
                    <td><label for="productDescription">Product Description:</label></td>
                    <td><textarea id="productDescription" name="description" placeholder="Enter Product Description" rows="10" cols="50" required></textarea></td>
                </tr>
            </table>
            <input type="hidden" name="action" value="addProduct">
            <button type="submit">Add</button>
            <button type="button" onclick="closeModal('addProductModal')">Cancel</button>
        </form>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editProductModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editProductModal')">&times;</span>
        <h2>Edit Product</h2>
        <form id="editProductForm">
            <table>
                <tr>
                    <td><label for="editProductName">Product Name:</label></td>
                    <td><input type="text" id="editProductName" name="name" required></td>
                </tr>
                <tr>
                    <td><label for="editProductDescription">Product Description:</label></td>
                    <td><textarea id="editProductDescription" name="description" rows="10" cols="50" required></textarea></td>
                </tr>
            </table>
            <input type="hidden" name="id">
            <input type="hidden" name="action" value="editProduct">
            <button type="submit">Edit</button>
            <button type="button" onclick="closeModal('editProductModal')">Cancel</button>
        </form>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addCategoryModal')">&times;</span>
        <h2>Add New Category</h2>
        <form id="addCategoryForm">
            <table>
                <tr>
                    <td><label for="categoryName">Category Name:</label></td>
                    <td><input type="text" id="categoryName" name="name" placeholder="Enter Category Name" required></td>
                </tr>
                <tr>
                    <td><label for="categoryPrice">Category Price:</label></td>
                    <td><input type="number" id="categoryPrice" name="price" placeholder="Enter Category Price" step="0.01" required></td>
                </tr>
                <tr>
                    <td><label for="categoryProduct">Product:</label></td>
                    <td>
                        <select id="categoryProduct" name="product_id" required>
                            <option value="">Select Product</option>
                            <?php
                            $result = $conn->query("SELECT * FROM products");
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value=\"{$row['id']}\">{$row['name']}</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="action" value="addCategory">
            <button type="submit">Add</button>
            <button type="button" onclick="closeModal('addCategoryModal')">Cancel</button>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editCategoryModal')">&times;</span>
        <h2>Edit Category</h2>
        <form id="editCategoryForm">
            <table>
                <tr>
                    <td><label for="editCategoryName">Category Name:</label></td>
                    <td><input type="text" id="editCategoryName" name="name" required></td>
                </tr>
                <tr>
                    <td><label for="editCategoryPrice">Category Price:</label></td>
                    <td><input type="number" id="editCategoryPrice" name="price" step="0.01" required></td>
                </tr>
                <tr>
                    <td><label for="editCategoryProduct">Product:</label></td>
                    <td>
                        <select id="editCategoryProduct" name="product_id"required>
                        <?php
                            // Fetch products from the database
                            $result = $conn->query("SELECT * FROM products");
                            while ($row = $result->fetch_assoc()) {
                                // Check if this product matches the product ID of the category being edited
                                // Assume $productId is the ID of the product associated with the category being edited
                                $selected = ($row['id'] == $productId) ? "selected" : ""; // Set selected if IDs match
                                echo "<option value=\"{$row['id']}\" $selected>{$row['name']}</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="id">
            <input type="hidden" name="action" value="editCategory">
            <button type="submit">Edit</button>
            <button type="button" onclick="closeModal('editCategoryModal')">Cancel</button>
        </form>
    </div>
</div>

