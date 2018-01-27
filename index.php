<?php
session_start();
require_once 'Blackjack.php';

?>
<style>
div {
    background-color: lightgrey;
    width: 300px;
    border: 25px solid green;
    padding: 25px;
    margin: 25px;
}
.image {
    box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    border-radius: 2px;
    height: 165px;
}
</style>
<?php

$playerStands = false;
$endGame = false;

$blackjack = new Blackjack();

if ($_POST['command']) {
    switch (strtolower($_POST['command'])) {
        case 'hit':
            $_SESSION['player'][] = Blackjack::drawCard($_SESSION['deck']);
            break;
        case 'new':
            unset($_SESSION['deck']);
            break;
        case 'stand':
            $playerStands = true;
            break;
    }
}

if (!isset($_SESSION['deck'])) {
    list($_SESSION['deck'], $_SESSION['dealer'], $_SESSION['player']) = $blackjack->getNewHand();
}

$playerHand = Blackjack::calculateHandValue($_SESSION['player']);
$dealerHand = Blackjack::calculateHandValue($_SESSION['dealer']);

// if player gets blackjack then player automatically wins
if ($playerHand == 21) {
    $playerStands = true;
}

if ($playerStands) {
    $winner = '';
    $winningHand = '';
    while ($dealerHand < 17) {
        $_SESSION['dealer'][] = $blackjack->drawCard($_SESSION['deck']);
        $dealerHand = $blackjack->calculateHandValue($_SESSION['dealer']);
    }
    if ($dealerHand < 22 && $dealerHand > $playerHand) {
        $winner = 'Dealer';
        $winningHand = $dealerHand;
    } else {
        $winner = 'Player';
        $winningHand = $playerHand;
    }
    $endGame = true;
}
?>
<div>
    <?php
    if ($playerHand > 21) {
        $endGame = true;
        $winner = 'Dealer';
        $winningHand = $dealerHand;
        echo '<p>Player Busts (over 21)</p>';
    }

    echo Blackjack::displayForm($endGame, $winner, $winningHand);

    // display dealer hand
    $dealerHands = $blackjack->displayDealerCards($endGame, $_SESSION['dealer'], $dealerHand);
    echo $dealerHands['cards'];
    echo $dealerHands['information'];

    $playerHands = $blackjack->displayPlayerCards($_SESSION['player'], $playerHand);
    echo $playerHands['cards'];
    echo $playerHands['information'];
    ?>
</div>
