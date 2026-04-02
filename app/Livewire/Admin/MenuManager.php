<?php

namespace App\Livewire\Admin;

use App\Models\AdminLog;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class MenuManager extends Component
{
    use WithFileUploads;

    // Category form
    public bool $showCategoryForm = false;
    public ?int $editingCategoryId = null;
    public string $categoryName = '';

    // Item form
    public bool $showItemForm = false;
    public ?int $editingItemId = null;
    public ?int $itemCategoryId = null;
    public string $itemName = '';
    public string $itemDescription = '';
    public string $itemPrice = '';
    public $itemImage;
    public bool $itemAvailable = true;

    // Active category tab
    public ?int $activeCategory = null;

    // Confirmation
    public ?int $confirmDeleteCategory = null;
    public ?int $confirmDeleteItem = null;

    public function mount(): void
    {
        $first = MenuCategory::orderBy('sort_order')->first();
        $this->activeCategory = $first?->id;
    }

    /* ----------------------------------------------------------------
     |  Category CRUD
     | ---------------------------------------------------------------- */

    public function openCategoryForm(?int $id = null): void
    {
        $this->resetCategoryForm();
        if ($id) {
            $cat = MenuCategory::findOrFail($id);
            $this->editingCategoryId = $cat->id;
            $this->categoryName = $cat->name;
        }
        $this->showCategoryForm = true;
    }

    public function saveCategory(): void
    {
        $this->validate(['categoryName' => 'required|string|max:255']);

        if ($this->editingCategoryId) {
            $cat = MenuCategory::findOrFail($this->editingCategoryId);
            $cat->update(['name' => strip_tags($this->categoryName)]);
            AdminLog::record('update_category', 'MenuCategory', $cat->id, "Renamed to: {$this->categoryName}");
        } else {
            $maxSort = MenuCategory::max('sort_order') ?? 0;
            $cat = MenuCategory::create([
                'name'       => strip_tags($this->categoryName),
                'slug'       => Str::slug($this->categoryName),
                'sort_order' => $maxSort + 1,
            ]);
            AdminLog::record('create_category', 'MenuCategory', $cat->id, "Created: {$this->categoryName}");
        }

        $this->resetCategoryForm();
        $this->activeCategory ??= MenuCategory::orderBy('sort_order')->first()?->id;
    }

    public function deleteCategory(int $id): void
    {
        $cat = MenuCategory::findOrFail($id);
        AdminLog::record('delete_category', 'MenuCategory', $id, "Deleted: {$cat->name}");
        $cat->delete();
        $this->confirmDeleteCategory = null;
        if ($this->activeCategory === $id) {
            $this->activeCategory = MenuCategory::orderBy('sort_order')->first()?->id;
        }
    }

    public function moveCategoryUp(int $id): void
    {
        $cat = MenuCategory::findOrFail($id);
        $prev = MenuCategory::where('sort_order', '<', $cat->sort_order)
            ->orderByDesc('sort_order')->first();
        if ($prev) {
            [$cat->sort_order, $prev->sort_order] = [$prev->sort_order, $cat->sort_order];
            $cat->save();
            $prev->save();
        }
    }

    public function moveCategoryDown(int $id): void
    {
        $cat = MenuCategory::findOrFail($id);
        $next = MenuCategory::where('sort_order', '>', $cat->sort_order)
            ->orderBy('sort_order')->first();
        if ($next) {
            [$cat->sort_order, $next->sort_order] = [$next->sort_order, $cat->sort_order];
            $cat->save();
            $next->save();
        }
    }

    private function resetCategoryForm(): void
    {
        $this->showCategoryForm = false;
        $this->editingCategoryId = null;
        $this->categoryName = '';
    }

    /* ----------------------------------------------------------------
     |  Item CRUD
     | ---------------------------------------------------------------- */

    public function openItemForm(?int $categoryId = null, ?int $itemId = null): void
    {
        $this->resetItemForm();
        $this->itemCategoryId = $categoryId;

        if ($itemId) {
            $item = MenuItem::findOrFail($itemId);
            $this->editingItemId = $item->id;
            $this->itemCategoryId = $item->menu_category_id;
            $this->itemName = $item->name;
            $this->itemDescription = $item->description ?? '';
            $this->itemPrice = (string) $item->price;
            $this->itemAvailable = $item->is_available;
        }
        $this->showItemForm = true;
    }

    public function saveItem(): void
    {
        $this->validate([
            'itemCategoryId'  => 'required|exists:menu_categories,id',
            'itemName'        => 'required|string|max:255',
            'itemDescription' => 'nullable|string|max:1000',
            'itemPrice'       => 'required|numeric|min:0',
            'itemImage'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'menu_category_id' => $this->itemCategoryId,
            'name'             => strip_tags($this->itemName),
            'description'      => strip_tags($this->itemDescription),
            'price'            => $this->itemPrice,
            'is_available'     => $this->itemAvailable,
        ];

        if ($this->itemImage) {
            $realMime = $this->itemImage->getMimeType();
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($realMime, $allowedMimes)) {
                $this->addError('itemImage', 'Invalid file type. Only JPG, PNG, and WebP allowed.');
                return;
            }

            $ext = match ($realMime) {
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
                default      => 'jpg',
            };
            $filename = bin2hex(random_bytes(16)) . '.' . $ext;
            $this->itemImage->storeAs('menu', $filename, 'public');
            $data['image'] = 'menu/' . $filename;
        }

        if ($this->editingItemId) {
            MenuItem::findOrFail($this->editingItemId)->update($data);
            AdminLog::record('update_item', 'MenuItem', $this->editingItemId, "Updated: {$this->itemName}");
        } else {
            $maxSort = MenuItem::where('menu_category_id', $this->itemCategoryId)->max('sort_order') ?? 0;
            $data['sort_order'] = $maxSort + 1;
            $item = MenuItem::create($data);
            AdminLog::record('create_item', 'MenuItem', $item->id, "Created: {$this->itemName}");
        }

        $this->resetItemForm();
    }

    public function toggleAvailability(int $id): void
    {
        $item = MenuItem::findOrFail($id);
        $item->update(['is_available' => !$item->is_available]);
    }

    public function deleteItem(int $id): void
    {
        $item = MenuItem::findOrFail($id);
        AdminLog::record('delete_item', 'MenuItem', $id, "Deleted: {$item->name}");
        $item->delete();
        $this->confirmDeleteItem = null;
    }

    private function resetItemForm(): void
    {
        $this->showItemForm = false;
        $this->editingItemId = null;
        $this->itemCategoryId = null;
        $this->itemName = '';
        $this->itemDescription = '';
        $this->itemPrice = '';
        $this->itemImage = null;
        $this->itemAvailable = true;
    }

    /* ----------------------------------------------------------------
     |  Render
     | ---------------------------------------------------------------- */

    public function render()
    {
        $categories = MenuCategory::orderBy('sort_order')->get();
        $activeCat = $this->activeCategory
            ? MenuCategory::with('items')->find($this->activeCategory)
            : null;

        return view('livewire.admin.menu-manager', [
            'categories' => $categories,
            'activeCat'  => $activeCat,
        ]);
    }
}
