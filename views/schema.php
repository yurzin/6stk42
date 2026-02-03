<?php
$pageTitle = 'Схема расположения.';
$metaDescription = 'Схема расположения офисов.';

include VIEWS . '/incs/header.php';

?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8 l-4">
                <?php include VIEWS . '/incs/content.php' ?>
            </div>
            <div class="col-lg-4 r-4">
                <div class="sidebar-title">
                    <h3>Схема расположения кабинетов</h3>
                <div class="block-gallery">
                    <?php include VIEWS . '/incs/sidebar.php' ?>
                </div>
            </div>
        </div>
    </div>

<?php include VIEWS . '/incs/footer.php'; ?>