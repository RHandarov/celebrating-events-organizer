<main id="all-events">
    <header>
        <h2>Всички събития</h2>
        <form method="GET" action="/my-dates/add/">
            <button type="submit">Добави събитие</button>
        </form>
    </header>
    <table class="table">
        <tr>
            <th scope="col">Заглавие</th>
            <th scope="col">Дата</th>
            <th scope="col">Място</th>
            <th scope="col">Действия</th>
        </tr>
        <?php
            foreach ($all_events_for_this_user as $event) {
                echo "<tr>";
                echo "<td>" . $event->get_title() . "</td>";
                echo "<td>" . $event->get_celebrating_date() . "</td>";
                echo "<td>" . $event->get_location() . "</td>";
                echo "<td><a href=\"#\">Детайли</a></td>";
                echo "</tr>";
            }
        ?>
    </table>
</main>
