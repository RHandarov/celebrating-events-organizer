<main id="find-users">
    <header>
        <h2>Намери приятели</h2>
    </header>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Потребител</th>
                <th scope="col">Имейл</th>
                <th scope="col">Действие</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($all_users as $user): ?>
            <tr>
                <td>
                    <a href="/user/<?php echo $user->get_id(); ?>"><strong><?php echo $user->get_username(); ?></strong></a>
                </td>
                <td>
                    <?php echo $user->get_email(); ?>
                </td>
                <td>
                    <?php 
                    if (in_array($user->get_id(), $following_ids)): 
                    ?>
                        <form method="POST" action="/user/unfollow">
                            <input type="hidden" name="followed_id" value="<?php echo $user->get_id(); ?>">
                            <button type="submit">
                                Отследвай
                            </button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="/user/follow">
                            <input type="hidden" name="followed_id" value="<?php echo $user->get_id(); ?>">
                            <button type="submit">
                                + Последвай
                            </button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
