<?php

namespace App\Actions\Property;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

class PrefillPropertyFromUrl
{
    /**
     * Best-effort extraction of listing fields from an arbitrary URL.
     *
     * Note on regex: we parse arbitrary third-party HTML/prose here, so regex over the
     * page text is appropriate — the spec's "prefer Laravel rules over regex" applies
     * to validating our own inputs, not to extracting fields out of someone else's page.
     *
     * @return array{title: ?string, price: ?float, description: ?string, beds: ?int, baths: ?int, address: ?string}
     */
    public function handle(string $url): array
    {
        $result = [
            'title' => null,
            'price' => null,
            'description' => null,
            'beds' => null,
            'baths' => null,
            'address' => null,
        ];

        try {
            $response = Http::timeout(8)
                ->withUserAgent('Mozilla/5.0 (compatible; RealEstateBot/1.0)')
                ->get($url);

            if (! $response->ok()) {
                return $result;
            }

            $html = $response->body();
            $crawler = new Crawler($html);

            $result['title'] = $this->meta($crawler, 'og:title') ?? $this->firstText($crawler, 'title');
            $result['description'] = $this->meta($crawler, 'og:description') ?? $this->meta($crawler, 'description');
            $result['address'] = $this->meta($crawler, 'og:street-address');

            $priceStr = $this->meta($crawler, 'og:price:amount') ?? $this->meta($crawler, 'product:price:amount');
            if ($priceStr !== null) {
                $cleaned = preg_replace('/[^\d.]/', '', $priceStr);
                $result['price'] = ($cleaned !== '' && $cleaned !== null) ? (float) $cleaned : null;
            }

            if (preg_match('/(\d+)\s*(bed|bedroom)/i', $html, $m)) {
                $result['beds'] = (int) $m[1];
            }
            if (preg_match('/(\d+)\s*(bath|bathroom)/i', $html, $m)) {
                $result['baths'] = (int) $m[1];
            }
        } catch (Throwable $e) {
            Log::warning("PrefillPropertyFromUrl failed for {$url}: ".$e->getMessage());
        }

        return $result;
    }

    private function meta(Crawler $crawler, string $name): ?string
    {
        try {
            $node = $crawler->filter('meta[property="'.$name.'"], meta[name="'.$name.'"]');

            return $node->count() > 0 ? ($node->attr('content') ?: null) : null;
        } catch (Throwable) {
            return null;
        }
    }

    private function firstText(Crawler $crawler, string $selector): ?string
    {
        try {
            $node = $crawler->filter($selector);
            if ($node->count() === 0) {
                return null;
            }
            $text = trim($node->text());

            return $text !== '' ? $text : null;
        } catch (Throwable) {
            return null;
        }
    }
}
