<?php

namespace App\Http\Livewire\Auction;

use App\Models\Auction;
use App\Models\Auctionwishlist;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Carbon;
use Livewire\Component;

class AuctionList extends Component
{
    private $auctions;

    public $searchByCategories = [];

    public $searchByFeature = [];

    public $sortByDate = '';

    public $sortByPrice = '';

    public $searchByNewArrivals = [];

    public $searchBySold = [];

    public $minPrice;
    public $maxPrice;

    protected $listeners = [
        'updatedSlideRange'
    ];

    public function mount()
    {
        $this->auctions = $this->implementQuery();
        $this->setPriceRangeValues();
    }

    public function updatedSlideRange($value)
    {
        $this->minPrice = (int)$value[0];
        $this->maxPrice = (int)$value[1];
        $this->auctions = $this->implementQuery();
        $this->setPriceRangeValues();
    }

    public function updatedSearchByCategories($value): void
    {
        $this->searchByCategories = $value;
        $this->auctions = $this->implementQuery();
        $this->setPriceRangeValues();
    }

    public function updatedSearchByFeature($value): void
    {
        $this->searchByFeature = $value;
        $this->auctions = $this->implementQuery();
        $this->setPriceRangeValues();
    }

    public function updatedSearchByNewArrivals($value): void
    {
        $this->searchByNewArrivals = $value;
        $this->auctions = $this->implementQuery();
        $this->setPriceRangeValues();
    }

    public function updatedSearchBySold($value): void
    {
        $this->searchBySold = $value;
        $this->auctions = $this->implementQuery();
        $this->setPriceRangeValues();
    }

    public function updatedSortByDate($value)
    {
        $this->sortByDate = $value;
        $this->sortByPrice = '';
        $this->auctions = $this->implementQuery();
        $this->setPriceRangeValues();
    }

    public function updatedSortByPrice($value)
    {
        $this->sortByPrice = $value;
        $this->sortByDate = '';
        $this->auctions = $this->implementQuery();
        $this->setPriceRangeValues();
    }

    public function addToWishList(Auction $auction)
    {
        $wishlist = new Auctionwishlist();
        $wishlist->user_id = auth()->user() ? auth()->user()->id : null;
        $wishlist->auction_id = $auction->id;
        $wishlist->ip_address = getenv('REMOTE_ADDR');
        $wishlist->save();
        $this->auctions = $this->implementQuery();
        $this->setPriceRangeValues();
    }

    public function removeFromWishList(Auctionwishlist $wishlist)
    {
        $wishlist->delete();
        $this->auctions = $this->implementQuery();
        $this->setPriceRangeValues();
    }

    public function implementQuery()
    {
        return Auction::live()
            ->with('auctionwinner.auctionbid')
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
                $query->doesnthave('auctionwinner');
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
    }

    public function setPriceRangeValues()
    {
        $this->minPrice = (int)$this->auctions->min('price');
        $this->maxPrice = (int)$this->auctions->max('price');
    }

    public function resetPriceFilers()
    {
        $this->minPrice = 0;
        $this->maxPrice = 0;
        $this->auctions = $this->implementQuery();
        $this->setPriceRangeValues();
    }

    public function render()
    {
        $emptyMessage = "No Auction Found";
        $categories = Category::withCount(['auctions' => function ($query) {
            $query->live()->with('auctionwinner.auctionbid')
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
                    $query->doesnthave('auctionwinner');
                });
        }])->whereStatus(true)->get();

        return view('livewire.auction.auction-list')
            ->with('auctions', $this->auctions)
            ->with('categories', $categories)
            ->with('emptyMessage', $emptyMessage)
            ->with('minPrice', $this->minPrice)
            ->with('maxPrice', $this->maxPrice);
    }
}
