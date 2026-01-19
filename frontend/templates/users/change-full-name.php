<main id="change-full-name-form">
    <?php
        if (isset($errors)) {
            foreach ($errors as $error) {
                echo "<h2 class=\"error\">" . $error . "</h2>";
            }
        }
    ?>

    <h2>Смяна на пълното име</h2>
    <form method="post">
        <label for="new-full-name">Ново пълно име:</label>
        <input
            id="new-full-name"
            name="new-full-name"
            type="text"
            maxlength="255"
            value="<?php echo $user->get_full_name(); ?>"
            required>

        <button type="submit">Смени</button>
    </form>
</main>
