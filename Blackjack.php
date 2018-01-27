<?php

class Blackjack
{

    /**
     * function to create the deck array
     * @return array $deck
     */
    private function getCardDeck()
    {
        $deck = [];

        //create a new
        $suit = [1 => 'A',2,3,4,5,6,7,8,9,10,'J','Q','K'];

        // using the deck structure
        $deck['H'] = $suit;
        $deck['C'] = $suit;
        $deck['D'] = $suit;
        $deck['S'] = $suit;

        return $deck;
    }

    /**
     * function to pick a random card from the deck
     * @param array $deck
     * @return array
     */
    public static function drawCard(array $deck)
    {
        $randSuit = array_rand($deck);

        $randCard = array_rand($deck[$randSuit]);

        $cardValue = ($randCard <= 10) ? $randCard : 10;

        $card  = $randSuit;
        $card .= ($cardValue <10) ? '0' : '';
        $card .= $randCard . '.jpg';

        unset($deck[$randSuit][$randCard]);

        return ['display' => $card, 'value' => $cardValue];
    }

    /**
     * @param array $cards
     * @return int hand value
     */
    public static function calculateHandValue(array $cards)
    {
        $ace = 0;
        $handValue = 0;

        foreach ($cards as $card) {
            $value = $card['value'];
            $handValue += $value;

            if ($value == 1) {
                $ace = 10;
            }
        }

        if (($handValue + $ace) <= 21) {
            $handValue += $ace;
        }

        return $handValue;
    }

    /**
     * @param array $cards
     * @param bool $hideLast
     * @return string hand value
     */
    private function displayCards(array $cards, $hideLast = false)
    {
        $lastKey = end(array_keys($cards));
        $cardImages = '<p>';

        foreach ($cards as $key => $value) {
            if ($hideLast && $key === $lastKey) {
                $cardImages .= '<img class="image" src="assets/images/back.jpg"/>';
            } else {
                $cardImages .= '<img src="assets/images/' . $value['display'] . '"/>';
            }
        }
        $cardImages .= '</p>';

        return $cardImages;
    }

    /**
     * @return array
     */
    public function getNewHand()
    {
        $deck = $this->getCardDeck();

        $player = [];
        $dealer = [];

        $dealer[] = $this->drawCard($deck);
        $dealer[] = $this->drawCard($deck);

        $player[] = $this->drawCard($deck);
        $player[] = $this->drawCard($deck);

        return [$deck, $dealer, $player];
    }

    /**
     * @param bool $endGame
     * @param string $winner
     * @param string $winningHand
     * @return string html
     */
    public static function displayForm($endGame, $winner, $winningHand)
    {
        $html = '<center><form action="' . $_SERVER['PHP_SELF'] .'" method="POST">';

        if ($endGame == false) {
            $html .= '<center>1 &emsp; 2 &emsp; &emsp; 3</center>
            <input type="submit" name="command" value="Hit" accessKey="1" />
            <input type="submit" name="command" value="Stand" accessKey="2" />';
        } elseif ($winner != null) {
            $html .= $winner . ' Wins with ' . $winningHand . '!<br />';
        }

        $html .= '<input type="submit" name="command" value="New" accessKey="3" /></form></center>';

        return $html;
    }

    /**
     * @param bool $endGame
     * @param string $dealer
     * @param string $dealerHand
     * @return array dealer information
     */
    public function displayDealerCards($endGame, $dealer, $dealerHand)
    {
        if ($endGame == true) {
            return [
                'cards' => $this->displayCards($dealer, false),
                'information' => '<br /><b>Dealer has ' . $dealerHand . '</b><br />',
            ];
        } else {
            return [
                'cards' => $this->displayCards($dealer, true),
                'information' => '<br /><b>Dealer has ???</b><br />',
            ];
        }
    }

    /**
     * @param string $player
     * @param string $playerHand
     * @return array player information
     */
    public function displayPlayerCards($player, $playerHand)
    {
        return [
            'cards' => $this->displayCards($player),
            'information' => '<p>Player has ' . $playerHand . '</p>',
        ];
    }
}
