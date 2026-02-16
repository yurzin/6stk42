<?php

include VIEWS . '/incs/header.php';

// Подключаем обработку формы
include CONFIG . '/process-form.php';
?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8 col-md-12 col-sm-12 l-4">
                <?php include VIEWS . '/incs/content.php' ?>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 r-4">
                <div class="sidebar-title">
                    <h3>Форма обратной связи</h3>
                </div>
                <div class="form-wrap">
                    <form method="POST" action="">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

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

                        <div class="form-control-input">
                            <label for="your-name">Ваше имя</label>
                                <input
                                        id="your-name"
                                        size="40"
                                        class="form-control"
                                        autocomplete="name"
                                        aria-required="true"
                                        aria-invalid="<?php echo isset($errors['name']) ? 'true' : 'false'; ?>"
                                        value="<?php echo htmlspecialchars($name); ?>"
                                        type="text"
                                        name="your-name"
                                />

                            <div><?php if (isset($errors['name'])): ?>
                                    <span class="error"><?php echo $errors['name']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-control-input">
                            <label for="your-phone">Ваш телефон</label>
                            <input
                                        id="your-phone"
                                        type="text"
                                        value="<?php echo htmlspecialchars($phone); ?>"
                                        name="your-phone"
                                        class="form-control"
                                        size="40"
                                        aria-required="true"
                                        aria-invalid="<?php echo isset($errors['phone']) ? 'true' : 'false'; ?>"
                                        placeholder="+7-___-___-____"
                                        data-mask="+7-___-___-____"
                                />
                            <div><?php if (isset($errors['phone'])): ?>
                                    <span class="error"><?php echo $errors['phone']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-control-input">
                            <label for="your-email">Ваш e-mail</label>
                            <input
                                        id="your-email"
                                        size="40"
                                        class="form-control email"
                                        autocomplete="email"
                                        aria-required="true"
                                        aria-invalid="<?php echo isset($errors['email']) ? 'true' : 'false'; ?>"
                                        value="<?php echo htmlspecialchars($email); ?>"
                                        type="email"
                                        name="your-email"
                                />
                            <div><?php if (isset($errors['email'])): ?>
                                    <span class="error"><?php echo $errors['email']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-control-input">
                            <label for="your-message">Ваше сообщение</label>
                            <textarea
                                        id="your-message"
                                        cols="50"
                                        rows="10"
                                        class="form-control"
                                        aria-required="true"
                                        aria-invalid="<?php echo isset($errors['message']) ? 'true' : 'false'; ?>"
                                        name="your-message"
                                ><?php echo htmlspecialchars($message); ?></textarea>
                            <div><?php if (isset($errors['message'])): ?>
                                    <span class="error"><?php echo $errors['message']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-control-input">
                        <label for="your-accept"></label>
                            <input
                                    id="your-accept"
                                    type="checkbox"
                                    name="your-accept"
                                    value="1"
                                    aria-invalid="<?php echo isset($errors['accept']) ? 'true' : 'false'; ?>"
                            />
                            <span class="your-accept-label">
                                <a href="/privacy-policy">Нажимая на кнопку, я даю согласие на обработку персональных данных и подтверждаю ознакомление с политикой конфиденциальности.</a>
                            </span>
                        </div>
                        <div><?php if (isset($errors['accept'])): ?>
                                <span class="error"><?php echo $errors['accept']; ?></span>
                            <?php endif; ?></div>
                        <div>
                        <!-- Скрытое Honeypot поле для ботов -->
                            <label>
                                <input
                                        type="text"
                                        name="website"
                                        value=""
                                        style="position: absolute; left: -9999px; width: 1px; height: 1px;"
                                        tabindex="-1"
                                        autocomplete="off"
                                        aria-hidden="true"
                                />
                            </label>
                        </div>
                        <div class="form-control-button">
                        <input
                                    class="btn btn-primary"
                                    type="submit"
                                    value="Отправить"
                            />
                        </div>
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

<?php include VIEWS . '/incs/footer.php'; ?>