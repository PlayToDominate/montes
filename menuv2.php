<?include('includes/dbconnect.php');?>
<?php
	$name=$_GET['title'];
	$page_title="Menu | Monte's Grill &amp; Pub";
	$page_title_header="";
	if($name){
		$page_title_header=$name;
	} else {
		$page_title_header="Menu";
	}

	$daily_specials = mysql_query("
	SELECT id,Name,Description,Price,Day,Meal,Active,Weight,dateactive
	FROM Specials
	WHERE Active = '1'
	ORDER BY dateactive ASC, Weight ASC
		")
	or die(mysql_error());
?>
<?PHP require_once('includes/header.php'); ?>
<body>
<div id="menu" data-role="page" data-add-back-btn="true" data-theme="a" data-content-theme="a">
	<?include 'includes/navigation.php';?>
	<div id="content" data-role="content">
		<h1><?=$page_title_header?></h1>
		<div id="fullMenu">
			<div id="menu-nav-specials" rel="#specials" data-role="collapsible" data-collapsed="true">
				<h3>Specials</h3>
				<div id="specials" class="food-menu">
					<?php
						$previous = '';
						$found = false;
						while($info = mysql_fetch_array( $daily_specials)) {
						$date = $info['dateactive'];
						$unix_date = strtotime ($date);
						$name_bad=$info['Name'];
						$name = str_replace("\\", "", $name_bad );
						if ($unix_date >= time() ) {
						$found = true;
					?>
					<?if ( $unix_date != $previous) {?>
					<h2><?=date("l, F jS", $unix_date)?></h2>
					<?}?>
					<h4><?=$name?><?if($info['Price'] !='N/A'){?> - <?=$info['Price']?><?}?></h4>
					<p><?=$info['Description']?></p>
					<?
					$previous = $unix_date;
					}}?>
					<?if(!$found){?>
					<p>Sorry, no posted Specials today. Please check back soon!</p>
					<?}?>
				</div>
			</div>
			<!--
			<div id="menu-under-construction">
				<div class="intro_text">
					<h2>The rest of our menu is being updated. We expect to be live soon!</h2>
				</div>
				<div class="status"> </div>
			</div>
			-->
			
			<div id="menu-nav-breakfast" rel="#breakfast" data-role="collapsible" data-collapsed="true">
				<h3>Breakfast</h3>
				<div id="breakfast" class="food-menu">
					<h2>Breakfast</h2>
					<p>Served Monday - Friday 6:00 am - 11:00 am<br />Saturday &amp; Sunday - 7:00 am - 12:00 pm</p>
					<h4>Monte's Plate - 4.99</h4>
					<p>2 eggs, 2 strips of bacon and 2 sausage links</p>
					<h4>Breakfast Sandwich - 4.99</h4>
					<p>Your choice of Ham, Bacon or Sausage with 1 egg and cheese on a croissant or English muffin<br />Add an additional egg - 79&cent;</p>
					<h3>2 Pancakes - 2.99</h3>
					<h3>2 Slices French Toast - 2.99</h3>
					<h3>Biscuits &amp; Gravy</h3>
					<h3>Hashbrowns</h3>
					<h4>Hashbrowns - 2.29</h4>
					<h4>Cheesy Browns - 3.29</h4>
					<h4>Loaded Browns</h4>
					<p>Cheddar cheese, onions, peppers and mushrooms - 3.99</p>
					<h3>Omelettes</h3>
					<p>Our two egg omelettes are served with White or Wheat toast. <br />Add an additional Egg 79&cent;<br />Egg Substitute for 79&cent; extra</p>
					<h4>German Town - 7.99</h4>
					<p>Kielbasa, Swiss cheese, onions and sauerkraut</p>
					<h4>Deluxe - 6.99</h4>
					<p>Ham, peppers, onions, mushrooms, tomatoes and American cheese</p>
					<h4>Denver - 6.49</h4>
					<p>Ham, peppers, onions and American cheese</p>
					<h4>Ham &amp; Cheese - 6.99</h4>
					<p>The classic combination</p>
					<h4>Country - 6.99</h4>
					<p>Ham, onion, American cheese and Idaho hashbrowns all inside</p>
					<h4>The Farmers' Market - 7.99</h4>
					<p>Ham, bacon, sausage, onions, tomatoes, peppers, mushrooms and shredded American cheese. Served with a side of sour cream</p>
					<h4>Garden - 5.99</h4>
					<p>Peppers, onions, mushrooms, tomatoes and American cheese</p>
					<h4>Wisconsin Cheese - 5.99</h4>
					<p>A blend of Cheddar and mozzarella</p>
				</div>
			</div>
			<div id="menu-nav-appetizers" rel="#appetizers" data-role="collapsible" data-collapsed="true">
				<h3>Appetizers</h3>
				<div id="appetizers" class="food-menu">
					<h2>Appetizers</h2>
					<p>Add half order of fries to an appetizer for 1.00.<br />Served with one diping sauce upon request:Bleu Cheese, Sweet Barbecue, Honey Mustard, Buttermilk Ranch or Sour Cream.</p>
					<h4>Wisconsin Cheese Curds - 5.99</h4>
					<p>Hennings Wisconsin natural cheddar cheese curds, hand-battered in our kitchen and fried until golden-brown.</p>
					<h4>Onion Rings - 5.99</h4>
					<p>Thin onion slices hand-battered and fried golden.</p>
					<h4>Cajun Chicken Quesadilla - 6.99</h4>
					<p>Grilled chicken dusted in our Cajun seasoning, with bell peppers, onions, tomatoes, cheddar and pepperjack cheese. Stuffed into an herbed tortilla and served with a side of homemade salsa and sour cream.</p>
					<h4>Chicken Strips - 6.99 </h4>
					<p>Fresh chicken breast sliced, hand-battered and fried crispy. Kick it up by tossing them in our homemade sauces for an extra $1!
						<ul>
							<li>Whisky Barbecue</li>
							<li>Ass Burner</li>
							<li>Teriyaki Glaze</li>
							<li>Sweet Barbecue</li>
							<li>Our house hot sauce</li>
						</ul>
					</p>
					<h4>Reuben Rools - 6.99</h4>
					<p>Hand-carved corned beef, fresh sauerkraut and Wisconsin Swiss rolled in an egg roll wrapper, deep fried and served with a side of homemade thousand island dressing.</p>
				</div>
			</div>
			<div id="menu-nav-soup-salad" rel="#soup-salad" data-role="collapsible" data-collapsed="true">
				<h3>Soups &amp; Salads</h3>
				<div id="soup-salad" class="food-menu">
					<h2>Soup &amp; Salad</h2>
					<h4>Homemade Soup</h4>
					<p>Bowl - 3.59 Cup - 2.59</p>
					<h4>Chili</h4>
					<p>Bowl - 3.99 Cup - 2.99 Add Cheese or Onion for .59 each</p>
					<h4>Taco Salad - 6.99</h4>
					<p>Tortilla shell filled with fresh greens, seasoned beef, tomatoes, black olives, jalape&ntilde;os and cheddar cheese. Served with salsa and sour cream</p>
					<h4>Quesadilla Salad - 8.95</h4>
					<p>Chopped Romaine lettuce tossed with cheddar cheese, onions and our freshmade black bean corn relish. Topped with a cheese quesadilla, and served with a side of salsa and ranch.</p>
					<h4>Oriental Salad - 8.95</h4>
					<p>Chopped Romaine lettuce blended with hand-shredded cabbage and tossed with bamboo shoots, onions, water chestnuts and crispy chow mein noodles. Drizzled with a sweet sesame chili dressing. </p>
				</div>
			</div>
			<div id="menu-nav-sandwiches" rel="#sandwiches" data-role="collapsible" data-collapsed="true">
				<h3>Sandwiches</h3>
				<div id="sandwiches" class="food-menu">
					<h2>Sandwiches</h2>
					<p>Served on a toasted roll with tavern chips, lettuce and a pickle spear.<br />Upgrade to fries for 1.00 extra or spicy waffle fries for 1.50</p>
					<h4>Monte's Chicken Sanwich - 6.99</h4>
					<p>Grilled chicken breast topped with bacon and Wisconsin mozzarella cheese, served on a toasted gourmet roll.</p>
					<h4>Hand-Battered Cod - 7.59</h4>
					<p>Lightly breaded in our kitchen, this North Atlantic cod is served on a toasted roll with a lemon wedge and tartar sauce on the side.</p>
					<h4>Chicken Tender Melt - 7.49</h4>
					<p>Our fresh chicken strips with pepperjack cheese, bacon and vine-ripened tomatoes on toasted sourdough.</p>
					<h4>Reuben - 7.99</h4>
					<p>Old fashioned corned beef brisket or shaved turkey topped with sauerkraut, homemade 1000 island dressing and Swiss cheese on grilled, thick-cut marble rye.</p>
					<h4>Steak Sandwich - 9.99</h4>
					<p>Grilled USDA choice steak sliced on the bias and topped with a pile of homemade onion rings, served on a grilled whole grain ciabatta bun with red onion and garlic aioli sauce.</p>
					<h4>Grilled Portabella - 7.99</h4>
					<p>Marinated grilled portabella mushrooms with a mix of red and yellow onions, vine-ripened tomatoes and fresh-sliced California Hass avocados. Topped with cheddar and drizzled with balsamic glaze, served on grilled sourdough.</p>
					<h4>Club Sandwich - 7.99</h4>
					<p>Thin-sliced ham and turkey with fresh lettuce, vine-ripened tomatoes and crisp bacon between two slices of toasted sourdough.</p>					
					<h4>Stuffed Grilled Cheese - 5.99</h4>
					<p>Not just for kids! Swiss and American cheese stuffed between grilled sourdough bread with bacon, vine-ripened tomatoes and red onions.</p>
					<h4>California Chicken - 8.99</h4>
					<p>Grilled chicken breast topped with fresh sliced of California Haas avocado, lettuce and vine-ripened tomatoes. Served on a grilled pretzel bun with and herbed basil aioli sauce.</p>
					<h4>Buffalo Blue Chicken - 7.49</h4>
					<p>Our fresh chicken strips smothered in our house hot sauce, homemade bleu cheese dressing and Wisconsin cheddar cheese. Served on a whole grain ciabatta bun.</p>
					<h4>B.L.T. - 5.99</h4>
					<p>Classic combo of bacon, crisp lettuce, vine-ripened tomatoes and mayo on toasted sourdough.</p>
					<h4>French Dip - 6.99</h4>
					<p>Sliced prime rib topped with Wisconsin mozzarella cheese and served on a hoagie. au jus served on the side for dipping.<br />Order it Philly Style for 7.99</p>
					<!--
					<h4>B.L.T. - 5.99</h4>
					<div class="non-mobile"><a href="images/menu/blt.jpg" class="prettyPhoto" title="Bacon, lettuce, tomato and mayo on toasted sourdough bread"><img src="images/menu/blt" width="200" alt="B.L.T." /></a></div>
					<div class="mobile"><a href="#menu-photos?photo=blt&title=B.L.T.&desc=Bacon, lettuce, tomato and mayo on toasted sourdough bread" title="Bacon, lettuce, tomato and mayo on toasted sourdough bread"><img src="images/menu/blt.jpg" width="200" alt="B.L.T." /></a></div>
					<p>Bacon, lettuce, tomato and mayo on toasted sourdough bread</p>
					-->
				</div>
			</div>
			<div id="menu-nav-dinner" rel="#dinner" data-role="collapsible" data-collapsed="true">
				<h3>Entre&eacute;</h3>
				<div id="dinner" class="food-menu">
					<h2>Entre&eacute;</h2>
					<p>Served with choice of potato and dinner roll<br />Add a dinner salad or seasonal vegetables for 1.99</p>
					<h4>Hand-Breaded Shrimp - 13.99</h4>
					<p>Jumbo butterflied, tail-on shrimp hand-breaded and served with cocktail sauce.</p>
					<h4>Lake Perch - 13.99</h4>
					<p>A stringer full of lightly battered, wild caught lake perch.</p>
					<h4>T-Bone - 21.99</h4>
					<p>16 oz USDA Choice T-Bone steak grilled to your liking and topped with a handful of our homemade onion straws.</p>
					<h4>Frenched Pork Chop - 13.99</h4>
					<p>12 oz Center cut bone-in pork chop served with a side of applesauce.</p>
					<h4>New York Strip* - 17.99</h4>
					<p>12 oz Certified Hereford New York Strip grilled to your liking and topped with grilled mushrooms and onions.</p>
					<h4>Seafood Mac &amp; Cheese - 10.95</h4>
					<p>Trottole (spinning top) pasta in creamy homemade cheddar sauce with sauteed shrimp.</p>
				</div>
			</div>
			<div id="menu-nav-wraps" rel="#wraps" data-role="collapsible" data-collapsed="true">
				<h3>Wraps</h3>
				<div id="wraps" class="food-menu">
					<h2>Wraps</h2>
					<p>Served with tavern chips and a pickle spear.<br />Upgrade to french fries for 1.00 extra, or spicy waffle fries for 1.50</p>
					<h4>Buffalo Chicken Wrap - 8.99</h4>
					<p>Grilled chicken breast tossed in our house hot sauce and buttermilk ranch, wrapped up in a herbed tortilla with vine-ripened tomatoes and crisp lettuce.</p>
					<h4>Asian Chicken Wrap - 8.99</h4>
					<p>Grilled chicken with shredded cabbage, vine-ripened tomatoes, carrots and chow mein noodles, all tossed in a sweet chili sauce and wrapped in a herbed tortilla.</p>
					<h4>Chicken Bacon Ranch- 8.99</h4>
					<p>Grilled chicken breast, bacon pieces, fresh lettuce, vine-ripened tomatoes and buttermilk ranch dressing in a herbed tortilla.</p>
				</div>
			</div>
			<div id="menu-nav-burgers" rel="#burgers" data-role="collapsible" data-collapsed="true">
				<h3>Burgers</h3>
				<div id="burgers" class="food-menu">
					<h2>Burgers</h2>
					<p>Hand-pattied from 100% Certified Hereford ground chuck<br />Served with tavern chips, lettuce and a pickle spear.<br />Upgrade to french fries for 1.00 extra, or spicy waffle fries for 1.50</p>
					<h4>Mushroom Swiss Burger* - 6.49</h4>
					<p>Topped with grilled mushrooms and Wisconsin Swiss.</p>					
					<h4>Cheeseburger* - 5.49</h4>
					<p>Your choice of Wisconsin cheese: Swiss, American, Mozzarella, Cheddar or Pepperjack cheese.</p>
					<h4>Breakfast Burger* - 8.49</h4>
					<p>Topped with thinly-sliced ham, a jumbo fried egg and American cheese.</p>
					<h4>Monte's Burger* - 6.99</h4>
					<p>Topped with fresh grilled mushrooms, thinly-sliced ham and melted Wisconsin Swiss cheese.</p>
					<h4>Bacon Cheeseburger* - 6.99</h4>
					<p>Your choice of Wisconsin cheese: Swiss, American, Mozzarella, Cheddar or Pepperjack, and two slices of crispy bacon.</p>
					<h4>Patty Melt* - 5.99</h4>
					<p>Topped with grilled onions and Wisconsin Swiss cheese, served on thick-cut marble rye.</p>
					<h4>Black Bean Burger - 6.99</h4>
					<p>This flavorful black bean and roasted corn burger is topped with pepperjack cheese and served on a toasted pretzel roll. Served with a side of sour cream and salsa.</p>
					<h4>The King* - 9.99</h4>
					<p>Sliced prime rib nestled between two burger patties, topped with mozzarella cheese and served on a toasted pretzel roll with a side of au jus.</p>					
					<h4>The Epic Burger* - 8.99</h4>
					<p>American cheeseburger topped with hame and a fried egg, served with lettuce, tomato and onion</p>
					<p>Topped with fresh mozzarella, fresh clipped basil and a drizzle of balsamic reduction glaze. SErved on a pretzel bun.</p>
				</div>
			</div>
			<div id="menu-nav-fish-fry" rel="#fish-fry" data-role="collapsible" data-collapsed="true">
				<h3>Friday Fish Fry</h3>
				<div id="fish-fry" class="food-menu">
					<h2>Friday Fish Fry</h2>
					<p>Served Fridays 5 - 10pm<br />Served with coleslaw and your choice of potato. <br />Add a trip to the salad bar for 2.49<br />Add cheesy browns for 3.29<br />Add loaded browns for 3.99</p>
					<h4>Cod Dinner - 10.99</h4>
					<p>Two pieces of delicious cod prepared your choice of deep fried or baked.</p>
					<h4>Hand-Breaded Shrimp Dinner - 13.99</h4>
					<p>Jumbo butterflied, tail-on shrimp hand-breaded and served with cocktail sauce and a side of coleslaw.</p>					
					<h4>Lake Perch Dinner - 13.99</h4>
					<p>A stringer full of lightly battered, wild caught lake perch. Served with a side of coleslaw.</p>
					<h4>Shrimp &amp; Cod Combo - 12.99</h4>
					<p>Two jumbo shrimp deep fried to a golden brown, and two pieces of our cod prepared either deep fried or baked.</p>
					<h4>Walleye Dinner - 14.99</h4>
					<p>Wild-caught Canadian walleye prepared pan-fried or depp fried.</p>
					<h4>Jail Island Salmon - 14.99</h4>
					<p>Fresh jail island salmon grilled to perfection and glazed with a grand mariner reduction.</p>
					<h4>As your server about this Friday's fresh fish feature!</h4>
					<!--
					<h4>Tilapia Parmesan</h4>
					<div class="non-mobile"><a href="images/menu/new-orleans-tilapia.jpg" class="prettyPhoto" title="A local favorite! Tilapia baked with a parmesan cheese topping"><img src="images/menu/new-orleans-tilapia.jpg" width="200" alt="Tilapia Parmesan" /></a></div>
					<div class="mobile"><a href="#menu-photos?photo=new-orleans-tilapia&title=Tilapia Parmesan&desc=A local favorite! Tilapia baked with a parmesan cheese topping" title="A local favorite! Tilapia baked with a parmesan cheese topping"><img src="images/menu/new-orleans-tilapia.jpg" width="200" alt="Tilapia Parmesan" /></a></div>
					<p>A local favorite! Tilapia baked with a parmesan cheese topping</p>
					-->
				</div>
			</div>
			<div id="menu-nav-late-night" rel="#pizza" data-role="collapsible" data-collapsed="true">
				<h3>Pizza</h3>
				<div id="pizza" class="food-menu">
					<h2>Pizza</h2>
					<h4>Pepperoni Pizza - 12" 11.99 / 16" 13.99</h4>
					<p>Pepperoni and shredded mozzarella cheese</p>
					<h4>Veggie Lovers - 12" 13.99 / 16" 17.99</h4>
					<p>Red onions, black olives, red and green peppers and diced tomatoes.</p>
					<h4>Sausage Pizza - 12" 11.99 / 16" 13.99</h4>
					<p>Seasoned sausage with shredded mozzarella cheese.</p>
					<h4>Cheese Pizza - 12" 9.99 / 16" 11.99</h4>
					<p>Good ole' Wisconsin-style pizza</p>
					<h4>Specialty Pizza</h4>
					<p>Ask your server what our Chef has come up with today!</p>
				</div>
			</div>
			<!--
			<div id="menu-nav-late-night" rel="#late-night" data-role="collapsible" data-collapsed="true">
				<h3>Late Night</h3>
				<div id="late-night" class="food-menu">
					<h2>Late Night</h2>
					<p>served 10-close</p>
					<h3>Appetizers</h3>
					<h4>Cheese Curds - 6.95</h4>
					<h4>Chicken Tenders - 7.99</h4>
					<h4>Sweet and Tangy Shrimp Basket and 1/2 order of fries - 7.99</h4>
					<h4>Basket of Waffle Fries - 3.49</h4>
					<h4>Onion Rings - 8.99</h4>
					<h3>Sandwiches</h3>
					<h4>Hot Roast Beef - 7.49</h4>
					<h4>Hot Ham &amp; Swiss - 7.49</h4>
					<h4>Chicken Tender Sandwich - 7.49</h4>
					<h4>Beer Battered Cod Sandwich - 8.59</h4>
					<h4>Pork Schnitzel Sandwich - 7.49</h4>
					<h4>Pizza</h4>
					<p>The Original Cheese 12" - 9.99<br />16" - 11.00</p>
					<p>Pepperoni Pizza<br />12" - 11.99<br />16" - 13.99</p>
					<p>Sausage Pizza<br />12" - 11.99<br />16" - 13.99</p>
					<p>Garden Veggie<br />12" - 13.99<br />16" - 17.99</p>
				</div>
			</div>
			-->
		</div>
		<p>&nbsp;</p>
		<p>* Can be cooked to order. Consuming raw or undercooked meats, poultry, seafood, shellfish or eggs may increase your risk of foodborne illness, especially if you have a medical conditition.</p>
	</div>
	<?include 'includes/right_bar.php';?>
	<?include 'includes/footer.php';?>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function(){
			$("a[class^='prettyPhoto']").prettyPhoto();
		});
	</script>
</div>
<div id="menu-photos" data-role="page">
	<?include 'includes/navigation.php';?>
  <!--<div data-role="header"><h1></h1></div>-->
  <div data-role="content" id="content"></div>
  <?include 'includes/right_bar.php';?>
  <?include 'includes/footer.php';?>
</div>
</body>
</html>
