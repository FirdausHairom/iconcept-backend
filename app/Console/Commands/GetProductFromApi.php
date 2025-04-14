<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Product;

class GetProductFromApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://dummyjson.com/products';

        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json();
            $this->info('Data fetched successfully!');
            $this->saveProductsIntoDb($data);

        } else {
            $this->error('Failed to fetch data from API. Status: ' . $response->status());
        }
    }

    public function saveProductsIntoDb($data) {
        foreach ($data['products'] as $product) {        
            Product::updateOrCreate(
                ['sku' => $product['sku']],
                [
                    'title' => $product['title'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'thumbnail' => $product['thumbnail'],
                ]
            );
        }
    }
}
