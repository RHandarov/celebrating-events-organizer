<main id="registration-form">
    <?php
        if (isset($errors)) {
            foreach ($errors as $error) {
                echo "<h2 class=\"error\">" . $error . "</h2>";
            }
        }
    ?>

    <h2>Добре дошъл в нашия сайт! Попълни формата по-долу, за да се регистрираш.</h2>

    <form method="post">
        <label for="username">Потребителско име:</label>
        <input id="username" name="username" type="text" maxlength="30" required>

        <label for="email">Имейл:</label>
        <input id="email" name="email" type="email" required>

        <label for="full-name">Пълно име:</label>
        <input id="full-name" name="full-name" type="text" maxlength="255" required>

        <label for="password">Парола:</label>
        <input id="password" name="password" type="password" required>

        <button type="submit">Регистрирай се</button>
    </form>
</main>