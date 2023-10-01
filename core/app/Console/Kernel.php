<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use App\Models\Auction;
use App\Models\Auctionbid;
use App\Models\Auctionwinner;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\Transaction;
use App\Models\Auctionwishlist;
use App\Models\EmailTemplate;
use App\Models\GeneralSetting;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function() {
            $auctions = Auction::expired()->get();
            foreach($auctions as $auction) {
                $bids = Auctionbid::where('auction_id', $auction->id)->exists();
                if($bids) {
                    $auctionbid = Auctionbid::where('auction_id', $auction->id)->orderBy('amount', 'DESC')->take(1)->get();
                    $winner = Auctionwinner::where('auction_id', $auctionbid[0]->auction_id)->exists();
                    if($winner) {
                    } else {
                        $winners = new Auctionwinner();
                        $winners->user_id = $auctionbid[0]->user_id;
                        $winners->auction_id = $auctionbid[0]->auction_id;
                        $winners->auctionbid_id = $auctionbid[0]->id;
                        $winners->save();
                        
                        $userss = User::findorFail($auctionbid[0]->user_id);
                        $userss->balance -= $auctionbid[0]->amount;
                        $userss->save();
                        
                        $trx = getTrx();
                        
                        $userNotification = new UserNotification();
                        $userNotification->user_id = $auctionbid[0]->user_id;
                        $userNotification->n_user_id = $auctionbid[0]->user_id;
                        $userNotification->auction_id = $auctionbid[0]->auction_id;
                        $userNotification->title = "We need your instruction to continue.";
                        $userNotification->click_url = urlPath('user.notification.bids', $auctionbid[0]->auction_id);
                        $userNotification->save();
                        
                        $transaction = new Transaction();
                        $transaction->user_id = $auctionbid[0]->user_id;
                        $transaction->amount = $auctionbid[0]->amount;
                        $transaction->post_balance = $userss->balance;
                        $transaction->trx_type = '-';
                        $transaction->details = "Winning Bid ".$auction->name;
                        $transaction->trx = $trx;
                        $transaction->save();
                        
                        $trx1 = getTrx();
        
                        $transaction1 = new Transaction();
                        $transaction1->user_id = $auctionbid[0]->user_id;
                        $transaction1->amount = 100;
                        $transaction1->post_balance = User::findOrFail($auctionbid[0]->user_id)->bonuspoint + 100;
                        $transaction1->charge = 0;
                        $transaction1->trx_type = 'bonus_plus';
                        $transaction1->details = 'Winning Bid';
                        $transaction1->trx =  $trx1;
                        $transaction1->save();
                        
                        $users = User::findOrFail($auctionbid[0]->user_id);
                        $users->bonuspoint += 100;
                        $users->save();
                        
                        $general = GeneralSetting::first();
                        $notifyuser = User::findOrFail($auctionbid[0]->user_id);
                        
                        $wishlists = Auctionwishlist::where('ip_address', getenv('REMOTE_ADDR'))->where('auction_id', $auctionbid[0]->auction_id)->get();
                        if(count($wishlists) > 0)
                        {
                            foreach($wishlists as $wishlist) {
                                $wishs = Auctionwishlist::findOrFail($wishlist->id);
                                $wishs->delete();
                            }
                        }
                        
                        notify($notifyuser, 'BID_WINNER', [
                            'product' => $product->name,
                            'product_price' => showAmount($auction->price, 0),
                            'currency' => $general->cur_text,
                            'amount' => showAmount($auctionbid[0]->amount, 0),
                        ]);
                    }
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
