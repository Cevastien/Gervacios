<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'Starters',
                'items' => [
                    ['name' => 'Bruschetta Trio', 'price' => 8.50, 'description' => 'Toasted ciabatta topped with tomato basil, mushroom truffle, and roasted pepper spreads.'],
                    ['name' => 'Soup of the Day', 'price' => 6.00, 'description' => 'Freshly prepared seasonal soup served with artisan bread.'],
                    ['name' => 'Caesar Salad', 'price' => 9.00, 'description' => 'Crisp romaine, parmesan shavings, croutons, and house-made Caesar dressing.'],
                    ['name' => 'Garlic Prawns', 'price' => 12.00, 'description' => 'Sautéed tiger prawns in garlic butter with chili flakes and fresh parsley.'],
                ],
            ],
            [
                'name' => 'Pasta',
                'items' => [
                    ['name' => 'Spaghetti Carbonara', 'price' => 14.00, 'description' => 'Classic Roman pasta with pancetta, egg yolk, pecorino, and black pepper.'],
                    ['name' => 'Penne Arrabbiata', 'price' => 12.00, 'description' => 'Penne in a spicy tomato sauce with garlic, chili, and fresh basil.'],
                    ['name' => 'Fettuccine Alfredo', 'price' => 13.50, 'description' => 'Ribbon pasta in a rich parmesan cream sauce with a hint of nutmeg.'],
                    ['name' => 'Truffle Mushroom Risotto', 'price' => 16.00, 'description' => 'Arborio rice slow-cooked with wild mushrooms, finished with truffle oil and parmesan.'],
                ],
            ],
            [
                'name' => 'Grills',
                'items' => [
                    ['name' => 'Ribeye Steak 300g', 'price' => 28.00, 'description' => 'Prime grass-fed ribeye grilled to your preference, served with roasted vegetables.'],
                    ['name' => 'Grilled Chicken Supreme', 'price' => 18.00, 'description' => 'Herb-marinated chicken breast with lemon butter sauce and seasonal greens.'],
                    ['name' => 'Lamb Chops', 'price' => 26.00, 'description' => 'New Zealand lamb chops with rosemary jus, mint pesto, and potato gratin.'],
                    ['name' => 'Pork Belly', 'price' => 22.00, 'description' => 'Slow-roasted pork belly with apple compote, crackling, and mustard mash.'],
                ],
            ],
            [
                'name' => 'Seafood',
                'items' => [
                    ['name' => 'Pan-Seared Salmon', 'price' => 22.00, 'description' => 'Atlantic salmon fillet with dill cream sauce, asparagus, and crushed new potatoes.'],
                    ['name' => 'Fish & Chips', 'price' => 16.00, 'description' => 'Beer-battered cod with thick-cut fries, mushy peas, and tartare sauce.'],
                    ['name' => 'Seafood Platter', 'price' => 32.00, 'description' => 'A selection of grilled prawns, calamari, mussels, and catch of the day.'],
                    ['name' => 'Grilled Sea Bass', 'price' => 24.00, 'description' => 'Whole sea bass with Mediterranean vegetables, capers, and lemon olive oil.'],
                ],
            ],
            [
                'name' => 'Burgers',
                'items' => [
                    ['name' => 'Classic Beef Burger', 'price' => 15.00, 'description' => 'House-ground beef patty with lettuce, tomato, pickles, and special sauce on a brioche bun.'],
                    ['name' => 'Smoked BBQ Burger', 'price' => 17.00, 'description' => 'Beef patty with smoked cheddar, crispy bacon, onion rings, and BBQ sauce.'],
                    ['name' => 'Chicken Avocado Burger', 'price' => 16.00, 'description' => 'Grilled chicken breast with fresh avocado, arugula, and garlic aioli.'],
                    ['name' => 'Veggie Beyond Burger', 'price' => 15.00, 'description' => 'Plant-based patty with roasted peppers, vegan cheese, and basil pesto.'],
                ],
            ],
            [
                'name' => 'Beverages',
                'items' => [
                    ['name' => 'Fresh Lemonade', 'price' => 4.50, 'description' => 'House-made lemonade with mint and a touch of honey.'],
                    ['name' => 'Iced Coffee', 'price' => 5.00, 'description' => 'Cold brew coffee served over ice with your choice of milk.'],
                    ['name' => 'Mango Smoothie', 'price' => 6.00, 'description' => 'Blended fresh mango, banana, yogurt, and a drizzle of honey.'],
                    ['name' => 'Sparkling Water', 'price' => 3.00, 'description' => 'San Pellegrino 500ml.'],
                    ['name' => 'Hot Chocolate', 'price' => 5.50, 'description' => 'Rich Belgian chocolate with steamed milk and whipped cream.'],
                ],
            ],
            [
                'name' => 'Desserts',
                'items' => [
                    ['name' => 'Tiramisu', 'price' => 9.00, 'description' => 'Classic Italian dessert with mascarpone, espresso-soaked ladyfingers, and cocoa.'],
                    ['name' => 'Crème Brûlée', 'price' => 8.50, 'description' => 'Vanilla bean custard with a caramelized sugar crust.'],
                    ['name' => 'Chocolate Lava Cake', 'price' => 10.00, 'description' => 'Warm chocolate fondant with a molten center, served with vanilla ice cream.'],
                    ['name' => 'Cheesecake', 'price' => 9.00, 'description' => 'New York-style baked cheesecake with seasonal berry compote.'],
                ],
            ],
            [
                'name' => 'Set Meals',
                'items' => [
                    ['name' => 'Lunch Set A', 'price' => 18.00, 'description' => 'Soup of the day, choice of pasta or burger, and a soft drink.'],
                    ['name' => 'Lunch Set B', 'price' => 22.00, 'description' => 'Caesar salad, grilled chicken supreme, dessert of the day, and coffee.'],
                    ['name' => 'Dinner Set', 'price' => 35.00, 'description' => 'Three-course meal: starter, choice of grill or seafood main, and dessert.'],
                    ['name' => 'Family Platter', 'price' => 65.00, 'description' => 'Shared starter platter, two mains, two sides, and a dessert sampler for 4.'],
                ],
            ],
        ];

        foreach ($data as $sortOrder => $categoryData) {
            $category = MenuCategory::create([
                'name'       => $categoryData['name'],
                'sort_order' => $sortOrder + 1,
            ]);

            foreach ($categoryData['items'] as $itemSort => $itemData) {
                MenuItem::create([
                    'menu_category_id' => $category->id,
                    'name'             => $itemData['name'],
                    'description'      => $itemData['description'],
                    'price'            => $itemData['price'],
                    'is_available'     => true,
                    'sort_order'       => $itemSort + 1,
                ]);
            }
        }
    }
}
