<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Default Title'); ?></title>
    <link rel="stylesheet" href="./css/<?php echo htmlspecialchars($css ?? 'default.css'); ?>">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
</head>
<body>
    <?php include('./layout/header.php'); ?>

    <main>
        <?php
        if (isset($childView) && !empty($childView) && file_exists($childView)) {
            include($childView);
        } else {
            echo '<p>Error: Content not found.</p>';
        }
        ?>
    </main>

    <?php include('./layout/footer.php'); ?>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script src="./js/<?php echo htmlspecialchars($js ?? 'default.js'); ?>"></script>
</body>
</html>