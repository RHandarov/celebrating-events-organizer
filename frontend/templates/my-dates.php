<main id="my-dates">
    <header>
        <h2>Моите дати</h2>
        <form method="GET" action="/my-dates/add/">
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
                echo "<a href=\"#\">Редактирай</a> |";
                echo "<a href=\"#\">Изтрий</a>";
                echo "</td>";
                echo "</tr>";
            }
        ?>
    </table>
</main>