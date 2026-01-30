<?php

// Получаем все изображения из директории img/floor1/
$imageDir = WWW . "/img/$page/";
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
        $imagePath = "/img/$page/" . $imageFile;
        $altText = pathinfo($imageFile, PATHINFO_FILENAME);
        $altText = str_replace(['_', '-'], ' ', $altText);
        $altText = ucfirst($altText);
        ?>
        <figure class="home-image-gallery">
            <img src="<?php echo htmlspecialchars($imagePath); ?>"
                 alt="<?php echo htmlspecialchars($altText); ?>"
                 loading="lazy"/>
        </figure>
    <?php
    endforeach;
else:
    // Если изображений нет, показываем заглушки
    $placeholders = [
            'Помещение 1',
            'Помещение 2',
            'Помещение 3',
            'Помещение 4',
            'Помещение 5',
            'Коридор',
            'Входная зона',
            'Санузел'
    ];
    foreach ($placeholders as $placeholder):
        ?>
        <figure>
            <img src="https://via.placeholder.com/400x300/34495e/ffffff?text=<?php echo urlencode($placeholder); ?>"
                 alt="<?php echo htmlspecialchars($placeholder); ?>"/>
        </figure>
    <?php
    endforeach;
endif;