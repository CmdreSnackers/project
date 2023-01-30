<?php

$product = Products::productById($_GET['id']);

require dirname(__DIR__) . '/parts/header.php';
?>


    <div class="container mx-auto my-5" style="max-width: 500px;">
      <div class="col">
      </div>
      <h1 class="h1 mb-4 text-center"><?php echo $product['name']; ?></h1>
      <?php

      echo nl2br($product['content']);

      ?>
      <div class="text-center mt-5">
        <?php echo $product['price']; ?>
      </div>
      <div class="text-center mt-5">
        <form action="/cart" method="POST">
          <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
          <button class="btn btn-primary">Add to cart</button>
        </form>
      </div>
      <div class="text-center mt-3">
        <a href="/" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back</a
        >
      </div>
    </div>


<?php
require dirname(__DIR__) . '/parts/footer.php';
?>