<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Inventario</title>
</head>
<body>
    <h2>Sistema de Gestión de Inventario</h2>
    <?php
    
    function validatePrice($price) {
        return is_numeric($price) && $price > 0 && $price <= 1000000;
    }
    
    function validateStock($stock) {
        return is_numeric($stock) && $stock >= 0 && $stock <= 100000;
    }
    function sanitizar_entrada($dato) {
    return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}
    
    
    $products = [
        ['id' => 1, 'name' => 'Globos azules', 'price' => 2, 'stock' => 4, 'minStock' => 10],
        ['id' => 2, 'name' => 'Vasos desechables', 'price' => 6, 'stock' => 15, 'minStock' => 10],
        ['id' => 3, 'name' => 'Globos Con dibujo', 'price' => 3, 'stock' => 0, 'minStock' => 5],
        ['id' => 4, 'name' => 'Flores eternas', 'price' => 11, 'stock' => 20, 'minStock' => 20]
    ];
    
    //  actualización de stock
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateStock'])) {
        $productId = intval($_POST['productId']);
        $newStock = intval($_POST['newStock']);
        
        if (validateStock($newStock)) {
            // Actualizar stock
            foreach ($products as &$product) {
                if ($product['id'] === $productId) {
                    $product['stock'] = $newStock;
                    echo "<p style='color: green;'>Stock actualizado para " . $product['name'] . "</p>";
                    break;
                }
            }
        } else {
            echo "<p style='color: red;'>Stock inválido</p>";
        }
    }
    
    // Generar alertas
    $alerts = [];
    $lowStockProducts = [];
    $outOfStockProducts = [];
    
    foreach ($products as $product) {
        if ($product['stock'] == 0) {
            $outOfStockProducts[] = $product;
            $alerts[] = "CRÍTICO: " . $product['name'] . " - STOCK AGOTADO";
        } elseif ($product['stock'] < $product['minStock']) {
            $lowStockProducts[] = $product;
            $alerts[] = "ADVERTENCIA: " . $product['name'] . " - Stock bajo (" . $product['stock'] . ")";
        }
    }
    
    // Calcular valor total del inventario
    $totalValue = 0;
    foreach ($products as $product) {
        $totalValue += $product['price'] * $product['stock'];
    }
    ?>
    
    
    <?php if (!empty($alerts)): ?>
        <div style="border: 2px solid #ff0000; padding: 10px; margin: 10px 0;">
            <h3>⚠️ Alertas de Inventario</h3>
            <ul>
                <?php foreach ($alerts as $alert): ?>
                    <li style="color: red;"><?= $alert ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <!-- Resumen del Inventario -->
    <div style="background-color: #f0f0f0; padding: 10px; margin: 10px 0;">
        <h3>Resumen del Inventario</h3>
        <p>Total productos: <?= count($products) ?></p>
        <p>Productos con Stock Bajo: <?= count($lowStockProducts) ?></p>
        <p>Productos Agotados: <?= count($outOfStockProducts) ?></p>
        <p>Valor Total del Inventario: $<?= number_format($totalValue, 2) ?></p>
    </div>
    
    <!-- Tabla de Inventario -->
    <h3>Inventario Actual</h3>
    <table border="1" style="width: 100%;">
        <tr>
            
            <th>Nombre</th>
            <th>Precio</th>
            <th>Stock Actual</th>
            <th>Stock Mínimo</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($products as $product): 
            $status = '';
            $rowColor = '';
            
            if ($product['stock'] == 0) {
                $status = 'AGOTADO';
                $rowColor = '#ffcccc';
            } elseif ($product['stock'] < $product['minStock']) {
                $status = 'STOCK BAJO';
                $rowColor = '#ffffcc';
            } else {
                $status = 'NORMAL';
                $rowColor = '#ccffcc';
            }
        ?>
            <tr style="background-color: <?= $rowColor ?>;">
                
                <td><?= $product['name'] ?></td>
                <td>$<?= number_format($product['price'], 2) ?></td>
                <td><?= $product['stock'] ?></td>
                <td><?= $product['minStock'] ?></td>
                <td><strong><?= $status ?></strong></td>
                <td>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="productId" value="<?= $product['id'] ?>">
                        <input type="number" name="newStock" min="0" max="100000" 
                               value="<?= $product['stock'] ?>" style="width: 80px;">
                        <button type="submit" name="updateStock">Actualizar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    
    
    <?php if (!empty($lowStockProducts) || !empty($outOfStockProducts)): ?>
        <h3>Productos para Reordenar</h3>
        <table border="1">
            <tr>
                <th>Producto</th>
                <th>Stock Actual</th>
                <th>Stock Mínimo</th>
                <th>Cantidad a Ordenar</th>
            </tr>
            <?php 
            $productsToReorder = array_merge($outOfStockProducts, $lowStockProducts);
            foreach ($productsToReorder as $product): 
            $orderQuantity = max($product['minStock'] * 2 - $product['stock'], 10);
            ?>
            
                <tr>
                    <td><?= $product['name'] ?></td>
                    <td><?= $product['stock'] ?></td>
                    <td><?= $product['minStock'] ?></td>
                    <td><strong><?= $orderQuantity ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>