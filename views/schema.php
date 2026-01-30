<?php
$pageTitle = 'Первый этаж – Аренда офисов в центре Кемерова';
$metaDescription = 'Изображения офисов на первом этаже. Офисное здание г. Кемерово, ул. Кузбасская, 33А';
$currentPage = $page;

include __DIR__ . '/incs/header.php';

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
                    <?php include __DIR__ . '/incs/sidebar.php' ?>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/incs/footer.php'; ?>