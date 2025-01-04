<?= renderTemplate(__DIR__ . '/Components/header.php'); ?>

<h3>Created new products with id #<?= $product->getId() ?></h3>

<div style="background-color: #9c9898; border-radius: 5px; width: 300px; padding: 10px 10px 10px 10px">
    <?= 'Name: ' . $product->getName() ?>
    <br/>
    <?= 'Quantity: ' . $product->getQuantity() ?>
    <br/>
    <?= 'Price: $' . $product->getPrice() ?>
</div>

<hr/>

<a href="/web/products">Back to products</a>