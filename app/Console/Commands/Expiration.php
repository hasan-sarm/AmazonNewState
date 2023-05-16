<?php

namespace App\Console\Commands;

use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Expiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:exp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete product when it is expire';

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
        $today = Carbon::today();
        $products = Product::where('Ex',$today)->get();
        foreach ($products as $product)
        {
            $product->delete();
        }
    }
}
