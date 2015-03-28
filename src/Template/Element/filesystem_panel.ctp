<?php

use \WyriHaximus\FlyPie\Panel\FilesystemPanel;

?>

<?php if (!empty($filesystems)): ?>
    <h4>Configured filesystems</h4>
    <p class="warning">The following list used <strong><?= FilesystemPanel::CONFIGURE_KEY ?></strong> as filesystem list:</p>
    <ul class="neat-array depth-0">
        <?php foreach ($filesystems as $filesystem): ?>
            <li class="expandable collapsed filesystemtree" data-alias="<?= $filesystem; ?>">
                <strong><?= h($filesystem) ?></strong>
                (<?= h(get_class($instances[$filesystem]->getAdapter())) ?>)
                <ul class="neat-array depth-1 loading" style="display: none;">
                    <li>Loading...</li>
                </ul>
            </li>
            <?php $noOutput = false; ?>
        <?php endforeach ?>
    </ul>
    <script>
        $('.filesystemtree').each(function () {
            var $this = $(this);
        });
    </script>
    <hr />
<?php else: ?>
    <div class="warning"><?= __d('fly_pie', 'No configured filesystem found') ?></div>
<?php endif ?>
