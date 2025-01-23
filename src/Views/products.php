<?= renderTemplate(__DIR__ . '/Components/header.php'); ?>

<h2>Products</h2>
<?php foreach ($products as $product) : ?>
    <div style="background-color: #9c9898; border-radius: 5px; width: 300px; padding: 10px 10px 10px 10px">
        <?= 'Name: ' . $product->getName() ?>
        <br/>
        <?= 'Quantity: ' . $product->getQuantity() ?>
        <br/>
        <?= 'Price: $' . $product->getPrice() ?>
        <br/>
        <a href="/web/product/show?productId=<?= $product->getId() ?>">Edit</a>
        <a href="/web/product/delete?productId=<?= $product->getId() ?>">Edit</a>
    </div>
    <br/>
<?php endforeach; ?>
<hr/>
<h2>Add new product</h2>
<form action="/web/product" method="post">
    <input type="hidden" name="csrf" value="<?= $accessToken ?>">
    <label>
        <input type="text" name="name" placeholder="Product name">
    </label>
    <label>
        <input type="number" name="quantity" placeholder="Quantity">
    </label>
    <label>
        <input type="number" name="price" placeholder="Price">
    </label>
    <input type="submit" value="Create product">
</form>

