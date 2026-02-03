<?php

require VIEWS . '/incs/header.php' ?>

<div class="container mt-lg-5 mt-md-2 mt-2">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="block-image">
                <div class="content-title">
                    <h3>Офисный центр на ул. Кузбасской, 33А</h3>
                </div>
                <figure class="home-image-gallery">
                    <img src="<?= asset('img/title_img2.jpg') ?>" alt="Трехэтажное офисное здание"/>
                </figure>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="block-list">
                <ul>
                    <li>Удобное расположение (непосредственная близость к центру города), отличная транспортная
                        доступность, хороший пешеходный трафик.
                    </li>
                    <li>Собственная парковка, свежий качественный ремонт.</li>
                    <li>Кондиционеры в каждом кабинете.</li>
                    <li>Светодиодное освещение.</li>
                    <li>Охрана и круглосуточный доступ.</li>
                    <li>Предоставляем офисную мебель при необходимости.</li>
                    <li>Возможна сдача помещений по этажам и полностью всего здания.</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="block-gallery-home">
                <?php
                // Получаем все изображения из директории img/block/
                $imageDir = WWW . '/img/block';
                $imageFiles = [];

                if (is_dir($imageDir)) {
                    $files = scandir($imageDir);
                    foreach ($files as $file) {
                        if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                            $imageFiles[] = $file;
                        }
                    }
                }

                // Если изображения найдены, показываем их
                if (!empty($imageFiles)):
                    foreach ($imageFiles as $imageFile):
                        $imagePath = '/img/block/' . $imageFile;
                        $altText = pathinfo($imageFile, PATHINFO_FILENAME);
                        $altText = str_replace(['_', '-'], ' ', $altText);
                        $altText = ucfirst($altText);
                        ?>
                        <figure class="home-image-gallery">
                            <img src="<?= asset(htmlspecialchars($imagePath)) ?>"
                                 alt="<?php echo htmlspecialchars($altText); ?>"
                                 loading="lazy"/>
                        </figure>
                    <?php
                    endforeach; endif;
                ?>
            </div>
        </div>
    </div>
</div>

<?php require VIEWS . '/incs/footer.php' ?>

