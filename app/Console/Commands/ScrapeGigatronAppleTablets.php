<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ScrapeGigatronAppleTablets extends Command
{
    protected $signature = 'scrape:gigatron-apple-tablets';
    protected $description = 'Scrape Apple tablets from Gigatron and store in database';

    public function handle()
    {
        $apiUrl = 'https://search.gigatron.rs/v1/catalog/get/prenosni-racunari/tablet-racunari?brand=Apple&limit=10';

        $client = new Client();

        try {
            // Preuzimanje podataka iz API-ja
            $response = $client->request('GET', $apiUrl);
            $data = json_decode((string)$response->getBody(), true);

            if (isset($data['hits']['hits']) && count($data['hits']['hits']) > 0) {
                $count = 0;
                foreach ($data['hits']['hits'] as $product) {
                    if ($count >= 10) {
                        break;
                    }
                    
                    $productData = $product['_source']['search_result_data'];
                    $productName = $productData['name'];
                    $productURL = 'https://gigatron.rs' . $productData['url'];
                    $productPrice = $productData['price'];
                    $productBrand = $productData['brand'];
                    $productImage = $productData['image'];

                    // Proveri da li proizvod već postoji u bazi
                    $existingProduct = Product::where('name', $productName)->orWhere('url', $productURL)->first();
                    if ($existingProduct) {
                        $this->info("Proizvod '{$productName}' već postoji u bazi. Preskačem...");
                        continue;
                    }

                    // Scraping dodatnih informacija (specifikacije)
                    $htmlResponse = $client->request('GET', $productURL);
                    $htmlContent = (string)$htmlResponse->getBody();
                    $specificationsText = '';

                    // Parsiranje HTML-a
                    $doc = new \DOMDocument();
                    @$doc->loadHTML($htmlContent);
                    $xpath = new \DOMXPath($doc);

                    $nodes = $xpath->query('//ul[contains(@class, "summary")]//li');
                    foreach ($nodes as $node) {
                        $specificationsText .= trim($node->nodeValue) . "\n";
                    }

                    // Čuvanje proizvoda u bazu
                    Product::create([
                        'name' => $productName,
                        'price' => $productPrice,
                        'image' => $productImage,
                        'description' => $specificationsText,
                        'brand' => $productBrand,
                        'url' => $productURL,
                        'specifications' => $specificationsText,
                    ]);

                    $this->info("Proizvod '{$productName}' uspešno dodat u bazu.");
                    $count++;
                }
            } else {
                $this->error('Nema pronađenih proizvoda!');
            }
        } catch (\Exception $e) {
            $this->error('Greška prilikom poziva API-ja: ' . $e->getMessage());
        }
    }
}
