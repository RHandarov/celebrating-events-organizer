<main id="change-password-form">
    <?php
        if (isset($errors)) {
            foreach ($errors as $error) {
                echo "<h2 class=\"error\">" . $error . "</h2>";
            }
        }
    ?>

    <h2>Смяна на паролата</h2>
    <form method="post">
        <label for="old-password">Стара парола:</label>
        <input id="old-password" name="old-password" type="password" required>

        <label for="new-password">Нова парола:</label>
        <input id="new-password" name="new-password" type="password" required>

        <label for="repeat-new-password">Повтори новата парола:</label>
        <input id="repeat-new-password" name="repeat-new-password" type="password" required>

        <button type="submit">Смени</button>
    </form>
</main>
