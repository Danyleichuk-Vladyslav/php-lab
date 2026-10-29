<?php

declare(strict_types=1);

function sumNaturalNumbers(string $input): int
{
    $parts = preg_split('/\s+/', trim($input), -1, PREG_SPLIT_NO_EMPTY);
    $sum = 0;

    foreach ($parts as $part) {
        $number = (int) $part;

        if ($number > 0) {
            $sum += $number;
        }
    }

    return $sum;
}

function wordsWithMatchingEdges(string $text): array
{
    $words = preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY);
    $found = [];

    for ($i = 0; $i < count($words) - 1; $i++) {
        $first = mb_strtolower(mb_substr($words[$i], 0, 1));
        $last = mb_strtolower(mb_substr($words[$i + 1], -1, 1));

        if ($first === $last) {
            $found[] = $words[$i].' — '.$words[$i + 1];
        }
    }

    return $found;
}

function shuffleWords(string $text): string
{
    $words = preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY);
    shuffle($words);

    return implode(' ', $words);
}
