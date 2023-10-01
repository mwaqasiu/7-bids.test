<?php

namespace App\Http\Livewire\Auction;

use App\Models\Auction;
use App\Models\Auctionwishlist;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class AuctionList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    private $auctions;

    public $searchByCategories = [];

    public $searchByFeature = [];

    public $sortByDate = '';

    public $sortByPrice = '';

    public $searchByNewArrivals = [];

    public $searchBySold = [];

    public $minPrice;
    public $maxPrice;

    public function updatedSearchByCategories($value): void
    {
        $this->searchByCategories = $value;
    }

    public function updatedSearchByFeature($value): void
    {
        $this->searchByFeature = $value;
    }

    public function updatedSearchByNewArrivals($value): void
    {
        $this->searchByNewArrivals = $value;
    }

    public function updatedSearchBySold($value): void
    {
        $this->searchBySold = $value;
    }

    public function updatedSortByDate($value)
    {
        $this->sortByDate = $value;
        $this->sortByPrice = '';
    }

    public function updatedSortByPrice($value)
    {
        $this->sortByPrice = $value;
        $this->sortByDate = '';
    }

    public function addToWishList(Auction $auction)
    {
        $wishlist = new Auctionwishlist();
        $wishlist->user_id = auth()->user() ? auth()->user()->id : null;
        $wishlist->auction_id = $auction->id;
        $wishlist->ip_address = getenv('REMOTE_ADDR');
        $wishlist->save();
    }

    public function removeFromWishList(Auctionwishlist $wishlist)
    {
        $wishlist->delete();
    }

    public function render()
    {
        $emptyMessage = "No Auction Found";

        $auctions = Auction::with('auctionwinner.auctionbid')
        ->with('auctionWishlists', function ($wishLists) {
            $user = auth()->user();
            $ipAddress = getenv('REMOTE_ADDR');
            $wishLists->where(function ($query) use ($user, $ipAddress) {
                $query->when(isset($user), function ($query) use ($user) {
                    $query->orWhere('user_id', $user->id);
                })->orWhere('ip_address', $ipAddress);
            });
        })
        ->when(count($this->searchByCategories) > 0, function ($query) {
            $query->whereIn('category_id', $this->searchByCategories);
        })
        ->when(count($this->searchByFeature) > 0, function ($query) {
            foreach ($this->searchByFeature as $value) {
                $query->where('specification', 'not like', '%"name":"' . $value . '","value":null%');
            }
        })
        ->when(count($this->searchByNewArrivals) > 0 && in_array('newArrivals',$this->searchByNewArrivals), function($query){
            $query->whereDate('created_at', '>=', Carbon::now()->subDays(2));
        })
        ->when(count($this->searchBySold) > 0 && in_array('sold',$this->searchBySold), function($query){
            $query->whereHas('auctionwinner');
        })
        ->when(count($this->searchBySold) === 0, function($query){
            $query->live()->doesnthave('auctionwinner');
        })
        ->when(!empty($this->sortByDate) && $this->sortByDate != '', function ($query) {
            if ($this->sortByDate == 'created_at_asc') {
                $query->orderBy('expired_at', 'ASC');
            } else {
                $query->orderBy('expired_at', 'DESC');
            }
        })
        ->when(!empty($this->sortByPrice) && $this->sortByPrice != '', function ($query) {
            if ($this->sortByPrice === 'price_asc') {
                $query->orderBy('price', 'ASC');
            } else {
                $query->orderBy('price', 'DESC');
            }
        })
        ->when(empty($this->sortByDate), function ($query) {
            $query->oldest('expired_at');
        })
        ->paginate(18);


        $categories = Category::withCount(['auctions' => function ($query) {
            $query->with('auctionwinner.auctionbid')
                ->with('auctionWishlists', function ($wishLists) {
                    $user = auth()->user();
                    $ipAddress = getenv('REMOTE_ADDR');
                    $wishLists->where(function ($query) use ($user, $ipAddress) {
                        $query->when(isset($user), function ($query) use ($user) {
                            $query->orWhere('user_id', $user->id);
                        })->orWhere('ip_address', $ipAddress);
                    });
                })
                ->when(count($this->searchByCategories) > 0, function ($query) {
                    $query->whereIn('category_id', $this->searchByCategories);
                })
                ->when(count($this->searchByFeature) > 0, function ($query) {
                    foreach ($this->searchByFeature as $value) {
                        $query->where('specification', 'not like', '%"name":"' . $value . '","value":null%');
                    }
                })
                ->when(count($this->searchByNewArrivals) > 0 && in_array('newArrivals',$this->searchByNewArrivals), function($query){
                    $query->whereDate('started_at', '>', Carbon::now());
                })
                ->when(count($this->searchByNewArrivals) === 0, function($query){
                    $query->whereDate('started_at', '<', Carbon::now());
                })
                ->when(count($this->searchBySold) > 0 && in_array('sold',$this->searchBySold), function($query){
                    $query->whereHas('auctionwinner');
                })
                ->when(!empty($this->sortByDate) && $this->sortByDate != '', function ($query) {
                    if ($this->sortByDate === 'created_at_asc') {
                        $query->orderBy('expired_at', 'ASC');
                    } else {
                        $query->orderBy('expired_at', 'DESC');
                    }
                })
                ->when(!empty($this->sortByPrice) && $this->sortByPrice != '', function ($query) {
                    if ($this->sortByPrice === 'price_asc') {
                        $query->orderBy('price', 'ASC');
                    } else {
                        $query->orderBy('price', 'DESC');
                    }
                })
                ->when(count($this->searchBySold) === 0, function($query){
                    $query->live()->doesnthave('auctionwinner');
                });
        }])->whereStatus(true)->get();
        
        $wishlists = Auctionwishlist::all();

        return view('livewire.auction.auction-list')
            ->with('auctions', $auctions)
            ->with('wishlists', $wishlists)
            ->with('categories', $categories)
            ->with('emptyMessage', $emptyMessage);
    }
}