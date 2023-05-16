<?php

namespace App\Console\Commands;

use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Offer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:offer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make price offer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
    $now=Carbon::now();
    $products=Product::all();
    foreach ($products as $product)
    {
       $price= $product['price'];
       $end = Carbon::parse($product['Ex']);
       $day = $end->diffInDays($now)+1;
       if ($day==31)
       {
           $price_of = $price - ($price * 30)/100 ;
           $product->price_offer= $price_of ;
           $product->save();
       }
       elseif ( $day<31 && $day>=15 )
       {
           $price_of = $price - ($price * 50)/100 ;
           $product->price_offer= $price_of ;
           $product->save();

       }

       elseif ( $day<15 && $day>=1)
       {
           $price_of = $price - ($price * 70)/100 ;
           $product->price_offer= $price_of ;
           $product->save();
       }

       else
       {
           $product->price_offer= 0 ;
           $product->save();

       }
    }
    }
}
