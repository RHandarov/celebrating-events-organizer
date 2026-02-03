<main id="home-page">
    <div id="top-20-events">
        <h2>20-те най-скорошни събития</h2>
        <table class="table">
            <tr>
                <th scope="col">Заглавие</th>
                <th scope="col">Дата</th>
            </tr>
            <?php
                foreach ($top_20_events as $event) {
                    echo "<tr>";
                    echo "<td><a href=\"" . Router::get_url() . "?action=event&id=" . $event->get_id() . "\">" . $event->get_title() . "</a></td>";
                    echo "<td>" . $event->get_celebrating_date() . "</td>";
                    echo "</tr>";
                }
            ?>
        </table>
        <a class="green-button" href="<?php echo Router::get_url() . "?action=all-events"; ?>">Виж всички събития</a>
    </div>
    <div id="buying-gifts">
        <h2>Моите подаръци</h2>
        <table class="table">
            <tr>
                <th scope="col">Описание</th>
                <th scope="col">Събитие</th>
                <th scope="col">Действия</th>
            </tr>
            <?php
                foreach ($user_gifts as $gift) {
                    echo "<tr>";
                    echo "<td>" . $gift->get_description() . "</td>";
                    echo "<td><a href=\"" . Router::get_url() . "?action=event&id=" . $gift->get_event()->get_id() . "\">" . $gift->get_event()->get_title() . "</a></td>";
                    $actions = "<a href=\"" . Router::get_url() . "?action=gift&a=edit&id=" . $gift->get_id() ."\">Редактирай</a> | <a href=\"" . Router::get_url() . "?action=gift&a=delete&id=" . $gift->get_id() . "&back=" . $event->get_id() . "\">Изтрий</a>";
                    echo "<td>" . $actions . "</td>";
                    echo "</tr>";
                }
            ?>
        </table>
    </div>
</main>