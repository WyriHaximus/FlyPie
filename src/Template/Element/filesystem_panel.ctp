<?php

use \WyriHaximus\FlyPie\Panel\FilesystemPanel;

$noOutput = true;
?>

<?php if (!empty($filesystems)): ?>
    <h4>Configured filesystems</h4>
    <p class="warning">The following list used <code><?= FilesystemPanel::CONFIGURE_KEY ?></code> as filesystem list:</p>
    <ul class="neat-array depth-0">
        <?php foreach ($filesystems as $filesystem): ?>
            <li><?= h($filesystem) ?></li>
            <?php $noOutput = false; ?>
        <?php endforeach ?>
    </ul>
    <hr />
<?php endif; ?>

<?php if ($noOutput): ?>
    <div class="warning"><?= __d('fly_pie', 'No configured filesystem found') ?></div>
<?php endif ?>
