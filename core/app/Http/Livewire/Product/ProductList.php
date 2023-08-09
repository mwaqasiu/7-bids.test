<?php

namespace App\Http\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Carbon;
use Livewire\Component;

class ProductList extends Component
{
    private $products;

    public $searchByCategories = [];

    public $searchByFeature = [];

    public $sortByDate = '';

    public $sortByPrice = '';

    public $searchByNewArrivals = [];

    public $searchBySold = [];

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

    public function addToWishList(Product $product)
    {
        $wishlist = new Wishlist();
        $wishlist->user_id = auth()->user() ? auth()->user()->id : null;
        $wishlist->product_id = $product->id;
        $wishlist->ip_address = getenv('REMOTE_ADDR');
        $wishlist->save();
    }

    public function removeFromWishList(Wishlist $wishlist)
    {
        $wishlist->delete();
    }

    public function render()
    {
        $emptyMessage = "No Product Found";

        $products = Product::with('winner.bid')
            ->with('wishlists', function ($wishLists) {
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
                $query->whereHas('winner');
            })
            ->when(isset($this->minPrice) && $this->minPrice !=0 && isset($this->maxPrice) && $this->maxPrice != 0, function($query){
                $query->whereBetween('price', [$this->minPrice, $this->maxPrice]);
            })
            ->when(!empty($this->sortByDate), function ($query) {
                if ($this->sortByDate === 'created_at_asc') {
                    $query->orderBy('created_at', 'ASC');
                } else {
                    $query->orderBy('created_at', 'DESC');
                }
            })
            ->when(!empty($this->sortByPrice), function ($query) {
                if ($this->sortByPrice === 'price_asc') {
                    $query->orderBy('price', 'ASC');
                } else {
                    $query->orderBy('price', 'DESC');
                }
            })
            ->when(count($this->searchBySold) === 0, function($query){
                $query->live()->doesnthave('winner');
            })
            ->latest('created_at')
            ->paginate(18);

        $categories = Category::withCount(['products' => function ($query) {
            $query->with('winner.bid')
                ->with('wishlists', function ($wishLists) {
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
                    $query->whereHas('winner');
                })
                ->when(isset($this->minPrice) && $this->minPrice !=0 && isset($this->maxPrice) && $this->maxPrice != 0, function($query){
                    $query->whereBetween('price', [$this->minPrice, $this->maxPrice]);
                })
                ->when(!empty($this->sortByDate), function ($query) {
                    if ($this->sortByDate === 'created_at_asc') {
                        $query->orderBy('created_at', 'ASC');
                    } else {
                        $query->orderBy('created_at', 'DESC');
                    }
                })
                ->when(!empty($this->sortByPrice), function ($query) {
                    if ($this->sortByPrice === 'price_asc') {
                        $query->orderBy('price', 'ASC');
                    } else {
                        $query->orderBy('price', 'DESC');
                    }
                })
                ->when(count($this->searchBySold) === 0, function($query){
                    $query->live()->doesnthave('winner');
                });
        }])->whereStatus(true)->get();

        return view('livewire.product.product-list')
            ->with('products', $products)
            ->with('categories', $categories)
            ->with('emptyMessage', $emptyMessage);
    }
}
