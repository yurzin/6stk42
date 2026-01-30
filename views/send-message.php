<?php
$pageTitle = 'Первый этаж – Аренда офисов в центре Кемерова';
$metaDescription = 'Изображения офисов на первом этаже. Офисное здание г. Кемерово, ул. Кузбасская, 33А';
$currentPage = $page;

include VIEWS . '/incs/header.php';

// Подключаем обработку формы
include CONFIG . '/process-form.php';
?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8 l-4">
                <?php include VIEWS . '/incs/content.php' ?>
            </div>
            <div class="col-lg-4 r-4">
                <div class="sidebar-title">
                    <h3>Форма обратной связи</h3>
                </div>

                <div class="form-wrap">
                    <form method="POST" action="">

                        <?php if ($success): ?>
                            <div class="response-output success" role="alert">
                                Спасибо за ваше сообщение. Оно было успешно отправлено.
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($errors['general'])): ?>
                            <div class="response-output error" role="alert">
                                <?php echo htmlspecialchars($errors['general']); ?>
                            </div>
                        <?php endif; ?>

                        <p>
                            <label>
                                Ваше имя<br/>
                                <span class="form-control-wrap" data-name="your-name">
                    <input
                            size="40"
                            maxlength="400"
                            class="form-control text validates-as-required"
                            autocomplete="name"
                            aria-required="true"
                            aria-invalid="<?php echo isset($errors['name']) ? 'true' : 'false'; ?>"
                            value="<?php echo htmlspecialchars($name); ?>"
                            type="text"
                            name="your-name"
                    />
                </span>
                                <?php if (isset($errors['name'])): ?>
                                    <span class="error"><?php echo $errors['name']; ?></span>
                                <?php endif; ?>
                            </label>
                        </p>

                        <p>
                            <label>
                                Ваш телефон<br/>
                                <span class="form-control-wrap your-phone">
                    <input
                            type="text"
                            value="<?php echo htmlspecialchars($phone); ?>"
                            name="your-phone"
                            class="form-control mask validates-as-required wpcf7mf-mask"
                            size="40"
                            aria-required="true"
                            aria-invalid="<?php echo isset($errors['phone']) ? 'true' : 'false'; ?>"
                            placeholder="+7-___-___-____"
                            data-mask="+7-___-___-____"
                    />
                </span>
                                <?php if (isset($errors['phone'])): ?>
                                    <span class="error"><?php echo $errors['phone']; ?></span>
                                <?php endif; ?>
                            </label>
                        </p>

                        <p>
                            <label>
                                Ваш e-mail<br/>
                                <span class="form-control-wrap" data-name="your-email">
                    <input
                            size="40"
                            maxlength="400"
                            class="form-control email validates-as-required text validates-as-email"
                            autocomplete="email"
                            aria-required="true"
                            aria-invalid="<?php echo isset($errors['email']) ? 'true' : 'false'; ?>"
                            value="<?php echo htmlspecialchars($email); ?>"
                            type="email"
                            name="your-email"
                    />
                </span>
                                <?php if (isset($errors['email'])): ?>
                                    <span class="error"><?php echo $errors['email']; ?></span>
                                <?php endif; ?>
                            </label>
                        </p>

                        <p>
                            <label>
                                Ваше сообщение<br/>
                                <span class="form-control-wrap" data-name="your-message">
                    <textarea
                            cols="40"
                            rows="10"
                            maxlength="2000"
                            class="form-control textarea validates-as-required"
                            aria-required="true"
                            aria-invalid="<?php echo isset($errors['message']) ? 'true' : 'false'; ?>"
                            name="your-message"
                    ><?php echo htmlspecialchars($message); ?></textarea>
                </span>
                                <?php if (isset($errors['message'])): ?>
                                    <span class="error"><?php echo $errors['message']; ?></span>
                                <?php endif; ?>
                            </label>
                        </p>

                        <p>
            <span class="form-control-wrap" data-name="accept-this-1">
                <span class="form-control acceptance">
                    <span class="list-item">
                        <label>
                            <input
                                    type="checkbox"
                                    name="accept-this-1"
                                    value="1"
                                    aria-invalid="<?php echo isset($errors['accept']) ? 'true' : 'false'; ?>"
                            />
                            <span class="list-item-label">
                                <a href="/?page=privacy-policy">Нажимая на кнопку, я даю согласие на обработку персональных данных и подтверждаю ознакомление с политикой конфиденциальности.</a>
                            </span>
                        </label>
                    </span>
                </span>
            </span>
                            <?php if (isset($errors['accept'])): ?>
                                <span class="error"><?php echo $errors['accept']; ?></span>
                            <?php endif; ?>
                        </p>

                        <p>
                            <input
                                    class="form-control submit has-spinner"
                                    type="submit"
                                    value="Отправить"
                            />
                        </p>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Маска для телефона
        document.addEventListener('DOMContentLoaded', function () {
            const phoneInput = document.querySelector('input[name="your-phone"]');
            if (phoneInput) {
                phoneInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.startsWith('7')) value = value.slice(1);
                    if (value.length > 10) value = value.slice(0, 10);

                    let formatted = '+7';
                    if (value.length > 0) formatted += '-' + value.slice(0, 3);
                    if (value.length > 3) formatted += '-' + value.slice(3, 6);
                    if (value.length > 6) formatted += '-' + value.slice(6, 10);

                    e.target.value = formatted;
                });
            }
        });
    </script>

<?php include __DIR__ . '/incs/footer.php'; ?>