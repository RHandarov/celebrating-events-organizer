<main id="find-users">
    <script type="text/javascript">
    window.addEventListener('DOMContentLoaded', () => {
        const scrollPos = localStorage.getItem('scrollPosition');
        if (scrollPos) {
            window.scrollTo(0, parseInt(scrollPos));
            localStorage.removeItem('scrollPosition');
        }
    });

    document.addEventListener('submit', () => {
        localStorage.setItem('scrollPosition', window.scrollY);
    });
    </script>

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
                    <a href="<?php echo Router::get_url(); ?>?action=user&id=<?php echo $user->get_id(); ?>"><strong><?php echo $user->get_username(); ?></strong></a>
                </td>
                <td>
                    <?php echo $user->get_email(); ?>
                </td>
                <td>
                    <?php 
                    if (in_array($user->get_id(), $following_ids)): 
                    ?>
                        <form method="POST" action="<?php echo Router::get_url(); ?>?action=user&a=unfollow">
                            <input type="hidden" name="followed_id" value="<?php echo $user->get_id(); ?>">
                            <button type="submit" class="btn-unfollow">
                                Отследвай
                            </button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="<?php echo Router::get_url(); ?>?action=user&a=follow">
                            <input type="hidden" name="followed_id" value="<?php echo $user->get_id(); ?>">
                            <button type="submit" class="btn-follow">
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
