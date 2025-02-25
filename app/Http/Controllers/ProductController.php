<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use League\Csv\Reader;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function scrapeAndStore()
    {
        $url = 'https://search.gigatron.rs/v1/catalog/get/prenosni-racunari/tablet-racunari?brand=Apple&limit=10';
        $client = new Client();

        try {
            $response = $client->get($url);
            $body = json_decode($response->getBody(), true);

            if (!isset($body['hits']['hits']) || empty($body['hits']['hits'])) {
                Log::error('Nema podataka iz Gigatron API-ja.');
                return redirect()->route('products.index')->with('error', 'Nema pronađenih proizvoda!');
            }

            foreach ($body['hits']['hits'] as $item) {
                $productData = $item['_source']['search_result_data'];

                $name = $productData['name'];
                $image = $productData['image'] ?? '';
                $price = (int) str_replace(['.', ',', ' RSD'], '', $productData['price']);
                $url = 'https://gigatron.rs' . $productData['url'];
                $description = '';

                // Scraping dodatnih informacija
                $htmlResponse = $client->get($url);
                $htmlContent = (string) $htmlResponse->getBody();
                $crawler = new Crawler($htmlContent);

                // Dohvatanje opisa
                $crawler->filter('.summary li')->each(function ($node) use (&$description) {
                    $description .= $node->text() . '; ';
                });

                Product::updateOrCreate(
                    ['name' => $name],
                    [
                        'image' => $image,
                        'price' => $price,
                        'description' => trim($description, '; '),
                        'url' => $url
                    ]
                );
            }

            return redirect()->route('products.index')->with('success', 'Podaci su uspešno sačuvani!');
        } catch (\Exception $e) {
            Log::error('Greška pri pozivanju API-ja: ' . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'Greška pri preuzimanju podataka.');
        }
    }

    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function importProductsFromCSV(Request $request)
    {
        if (!$request->hasFile('csv_file')) {
            return redirect()->route('products.index')->with('error', 'Fajl nije pronađen!');
        }

        $path = $request->file('csv_file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            Product::updateOrCreate(
                ['name' => $row['name']],
                [
                    'price' => $row['price'],
                    'image' => $row['image'],
                    'description' => $row['description'],
                ]
            );
        }

        return redirect()->route('products.index')->with('success', 'Proizvodi su uspešno uvezeni!');
    }
}
