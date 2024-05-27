<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Dictionary;

use MLocati\Nexi\XPay\Dictionary\TestCard\Card;

class TestCard
{
    /**
     * @var \MLocati\Nexi\XPay\Dictionary\TestCard\Card[]
     */
    private $cards;

    public function __construct()
    {
        $this->cards = [
            new Card(true, 'VISA', '4539 9700 0000 0006', 12, 2030, ''),
            new Card(false, 'VISA', '4539 9700 0000 0014', 12, 2030, ''),
            new Card(true, 'MASTERCARD', '5255 0000 0000 0001', 12, 2030, ''),
            new Card(false, 'MASTERCARD', '5255 0000 0000 0019', 12, 2030, ''),
        ];
    }

    /**
     * Get the available circuits.
     *
     * @param bool|null $positiveOutcome set to true to list circuits for which we have cards with positive outcomes, false for negative outcomes, NULL for any outcome
     * @return string[]
     */
    public function getCircuits(?bool $positiveOutcome = null): array
    {
        $circuits = [];
        foreach ($this->cards as $card) {
            if ($positiveOutcome === null || $card->isPositiveOutcome() === $positiveOutcome) {
                $circuits[] = $card->getCircuit();
            }
        }

        $circuits = array_unique($circuits);
        sort($circuits, SORT_STRING);

        return array_values($circuits);
    }

    /**
     * Get sample cards.
     *
     * @param bool|null $positiveOutcome set to true for cards with positive outcomes, false for negative outcomes, NULL for any outcome
     * @param string $circuit set to a non empty string to retrieve cards with a specific circuit
     *
     * @return \MLocati\Nexi\XPay\Dictionary\TestCard\Card[]
     */
    public function getCards(?bool $positiveOutcome = null, string $circuit = ''): array
    {
        return array_values(array_filter(
            $this->cards,
            static function (Card $card) use ($positiveOutcome, $circuit): bool {
                return ($positiveOutcome === null || $positiveOutcome === $card->isPositiveOutcome()) && ($circuit === '' || $card->getCircuit() === $circuit);
            }
        ));
    }

    /**
     * Get a sample card.
     *
     * @param bool|null $positiveOutcome set to true for cards with positive outcomes, false for negative outcomes, NULL for any outcome
     * @param string $circuit set to a non empty string to retrieve cards with a specific circuit
     */
    public function getSampleCard(?bool $positiveOutcome = null, string $circuit = ''): ?Card
    {
        $cards = $this->getCards($positiveOutcome, $circuit);

        return array_shift($cards);
    }
}
