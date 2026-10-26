<?php
declare(strict_types=1);

class CommentCategorizer {
    public const CANDY     = 'Comments about candy';
    public const CALL      = 'Comments about call me / don\'t call me';
    public const REFERRAL  = 'Comments about who referred me';
    public const SIGNATURE = 'Comments about signature requirements upon delivery';
    public const MISC      = 'Miscellaneous comments (everything else)';

    public function categorize(array $orders): array {
        $buckets = [
            self::CANDY     => [],
            self::CALL      => [],
            self::REFERRAL  => [],
            self::SIGNATURE => [],
            self::MISC      => [],
        ];

        // loop though each order
        foreach ($orders as $order) {
            $comment = $order['comments'];
            // place the comment into the appropriate bucket based on regex matching 
            if ($this->matchesCandy($comment)) {
                $buckets[self::CANDY][] = $order;
            } elseif ($this->matchesCallPreference($comment)) {
                $buckets[self::CALL][] = $order;
            } elseif ($this->matchesReferral($comment)) {
                $buckets[self::REFERRAL][] = $order;
            } elseif ($this->matchesSignatureRequirement($comment)) {
                $buckets[self::SIGNATURE][] = $order;
            } else {
                $buckets[self::MISC][] = $order;
            }
        }

        return $buckets;
    }

    // regex to match related kewords for each bucket
    private function matchesCandy(string $text): bool {
        return (bool) preg_match('/\b(candy|candies|smarties|taffy|tootsie|bit[- ]o[- ]honey|sweets)\b/i', $text);
    }

    private function matchesCallPreference(string $text): bool {
        return (bool) preg_match('/\b(call me|don\'?t call|do not call|no calls|phone call|reach me by phone|call when|ll[aá]mame)\b/i', $text);
    }

    private function matchesReferral(string $text): bool {
        return (bool) preg_match('/\b(referred|referral|heard about you|told me about|recommendation of)\b/i', $text);
    }

    private function matchesSignatureRequirement(string $text): bool {
        return (bool) preg_match('/\b(signature|sign for|waive signature|no signature)\b/i', $text);
    }
}