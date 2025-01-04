<?= renderTemplate(__DIR__ . '/Components/header.php'); ?>

<h3>Product #<?= $product->getId() ?> management</h3>

<form action="/web/product/update" method="post">
    <input type="hidden" name="id" value="<?= $product->getId() ?>">
    <input type="hidden" name="csrf" value="<?= $accessToken ?>">
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Product name" value="<?= $product->getName() ?>">
    </div>
    <br/>

    <div>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" placeholder="Quantity" value="<?= $product->getQuantity() ?>">
    </div>
    <br/>

    <div>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" placeholder="Price" value="<?= $product->getPrice() ?>">
    </div>
    <br/>

    <input type="submit" value="Save">
</form>
<hr/>
<a href="/web/products">Back to products</a>