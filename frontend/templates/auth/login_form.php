<main id="login-form">
    <?php
        if (isset($errors)) {
            foreach ($errors as $error) {
                echo "<h2 class=\"error\">" . $error . "</h2>";
            }
        }
    ?>

    <h2>Вход в системата</h2>
    <form method="post">
        <label for="username">Потребителско име:</label>
        <input id="username" name="username" type="text" maxlength="30" required>

        <label for="passowrd">Парола:</label>
        <input id="password" name="password" type="password" required>

        <button type="submit">Влез</button>
    </form>
</main>