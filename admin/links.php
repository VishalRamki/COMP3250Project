    <div id="cssmenu">
        <ul>
            <li><a href="index.php">Admin Panel Home</a></li>
            <li><a href="">Orders</a></li>
            <li class="<?php if ($manage == 0) echo "active "; ?>has-sub"><a href="#">Manage</a>
                <ul>
                    <li><a href="dish.php">Add Dish</a></li>
                    <li><a href="ingredient.php">Add Ingredient</a></li>
                </ul>
             </li>
             <li class="<?php if ($manage == 1) echo "active "; ?>has-sub"><a href="">View/Edit</a>
                <ul>
                    <li><a href="dish.php?view=true">View/Edit Dishes</a></li>
                    <li><a href="ingredient.php?view=true">View/Edit Ingredients</a></li>
                </ul>
             </li>
             <li><a href="">Reports</a></li>
        </ul>
    </div>
