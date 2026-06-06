
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/Novice/includes/helpers.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Product Catalog</title>
        <style>
            table { border-collapse: collapse; }
            td, th { border: 1px solid black; }
        </style>
    </head>

    <body>
        <!-- This section is diagnostic information -->
        <h1>Controller Provided Information</h1>
        <div>
            <?php echo '$items:    ' . count($items); ?>
        </div>
        <div>
            <?php echo '$items[0]:    ' . $items[0]['id'] . ' ' . $items[0]['desc'] . ' ' . $items[0]['price']; ?>
        </div>
        <hr/>
        <!-- End of diagnostic information section -->

        <p>Your shopping cart contains <?php echo count($_SESSION['cart']); ?> items.</p>
        <p><a href="?cart">View your cart</a></p>
        <table border="1">
            <thead>
            <tr>
            <th>Item Description</th>
            <th>Price</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                <td><?php htmlout($item['desc']); ?></td>
                <td>
                $<?php echo number_format($item['price'], 2); ?>
                </td>
                <td>
                <form action="" method="post">
                    <div>
                        <input type="hidden" name="id" value="<?php htmlout($item['id']); ?>">
                        <input type="submit" name="action" value="Buy">
                    </div>
                </form>
                </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
        <p>All prices are in imaginary dollars.</p>
    </body>
</html>

