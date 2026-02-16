<?php
/**
 * @var $currentPage
 */

// Получаем все изображения из директории img/$currentPage/
$imageDir = WWW . "/img/$currentPage/";
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
        $imagePath = "/img/$currentPage/" . $imageFile;
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
    endforeach;
else:
    // Если изображений нет, показываем заглушки
    $placeholders = [
            '/img/stub.jpg',
            '/img/stub.jpg',
            '/img/stub.jpg',
            '/img/stub.jpg',
            '/img/stub.jpg'
    ];
    foreach ($placeholders as $placeholder):
        ?>
        <figure>
            <img src="<?php echo urlencode($placeholder); ?>"
                 alt="<?php echo htmlspecialchars($placeholder); ?>"/>
        </figure>
    <?php
    endforeach;
endif;