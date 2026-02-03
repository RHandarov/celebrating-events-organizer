<main id="my-dates">
    <header>
        <h2>Моите дати</h2>
        <form method="GET" action="<?php echo Router::get_url(); ?>">
            <input type="hidden" name="action" value="my-dates">
            <input type="hidden" name="a" value="add">
            <button type="submit">Добави дата</button>
        </form>
    </header>
    <table class="table">
        <tr>
            <th scope="col">Дата</th>
            <th scope="col">Повод</th>
            <th scope="col">Действия</th>
        </tr>
        <?php
            foreach ($user_dates as $user_date) {
                echo "<tr>";
                echo "<td>" . $user_date->get_date() . "</td>";
                echo "<td>" . $user_date->get_title() . "</td>";
                echo "<td>";
                echo "<a href=\"" . Router::get_url() . "?action=my-dates&a=edit&id=" . $user_date->get_id() . "\">Редактирай</a> |";
                echo "<a href=\"" . Router::get_url() . "?action=my-dates&a=delete&id=" . $user_date->get_id() . "\">Изтрий</a>";
                echo "</td>";
                echo "</tr>";
            }
        ?>
    </table>
</main>
