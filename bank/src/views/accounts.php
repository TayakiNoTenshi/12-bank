<?php

$title = 'Accounts';

require __DIR__ . '/top.php';
require __DIR__ . '/accountBar.php';
require __DIR__ . '/messages.php'
?>


<div class="contentContainer">

<?php require __DIR__ . '/navBar.php'; ?>

<main  class="mainContetBlock contentBox">
    <?php if (!isset($_SESSION['user'])) : ?>
        Log in to see your account.
    <?php endif ?>
    <?php if (isset($_SESSION['user'])) : ?>
        <div class="contentBox fundsButtonBox left">
            <?php echo ($_SESSION['user']['lname'].' '.$_SESSION['user']['fname'].'<br><hr>') ?>
            <?php echo ($_SESSION['user']['email'].'<br>') ?>
            <?php echo ($_SESSION['user']['pnumber'].'<br>') ?>
            <?php echo ($_SESSION['user']['anumber'].'<br>') ?>
            <?php echo ($_SESSION['user']['funds'].' €<br><br>') ?>
            <div class="contentBox fundsButtonBox left">
                <a href="addFunds" class="navLink">Deposit</a>
            </div>
            <div class="contentBox fundsButtonBox left">
                <a href='withdrawFunds' class='navLink'>Withdraw</a>
            </div>
        </div>
    <?php endif ?>
</main>

</div>

<?php
require __DIR__ . '/bottom.php';


/*<ul>
<?php foreach($list as $value) : ?>

    <li><?= $value ?></li>

<?php endforeach ?>
</ul>*/