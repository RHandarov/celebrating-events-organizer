<main id="gifts-form">
    <?php
        if (isset($errors)) {
            foreach ($errors as $error) {
                echo "<h2 class=\"error\">" . $error . "</h2>";
            }
        }
    ?>

    <h2>
        <?php
            if ($edit_mode) {
                echo "Редактиране";
                $action_url = Router::get_url() . "?action=gift&a=edit&id=" . $gift_id;
            } else {
                echo "Добавяне";
                $action_url = Router::get_url() . "?action=gift&a=add&event_id=" . $event_id;
            }
        ?>
        на подарък
    </h2>

    <form method="POST" action="<?php echo $action_url; ?>">
        <label for="description">Описание на подаръка</label>
        <input id="description"
            name="description"
            type="text"
            required
            maxlength="512"
            value="<?php
                if ($edit_mode) {
                    echo $gift->get_description();
                } else {
                    echo "";
                }
            ?>">

        <button type="submit">
            <?php
                if ($edit_mode) {
                    echo "Редактирай";
                } else {
                    echo "Добави";
                }
            ?>
        </button>
    </form>
</main>
