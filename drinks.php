<?include('includes/dbconnect.php');?>
<?php
	$page_title="Drinks | Monte's Grill &amp; Pub";
?>
<?PHP require_once('includes/header.php'); ?>
<body>
<div id="drinks" data-role="page" data-add-back-btn="true" data-theme="a">
<?include 'includes/navigation.php';?>
	<div id="content" data-role="content">
		<h1>Drinks</h1>
		<!--
		<div id="menu-nav-specials" rel="#specials" data-role="collapsible" data-collapsed="true">
			<h3>Happy Hour/Specials</h3>
			<div id="specials" class="food-menu">
				<h2>Happy Hour/Specials</h2>
				<h4>Happy Hour (2-6pm Monday - Friday)</h4>
				<h4>Late Night Happy Hour (10pm-Midnight Everyday)</h4>
				<h4>Bartender Specials</h4>
				<h4>Beer Specials</h4>
			</div>
		</div>
		-->
		<div id="menu-nav-bottled-beer" rel="#bottled-beer" data-role="collapsible" data-collapsed="true" data-theme="c" data-content-theme="c">
			<h3>Bottled Beer</h3>
			<p>*Denotes rotational (seasonal) beer</p>
			<div id="bottled-beer-craft" class="food-menu" rel="#bottled-beer-craft" data-role="collapsible" data-collapsed="true">
				<h3>Microbrew Craft Bottles</h3>
				<h4>Alaskan <span class="italic normal">Amber</span></h4>
				<h4>Ale Asylum <span class="italic normal">Hopalicious APA</span></h4>
				<h4>Ale Asylum <span class="italic normal">Ballistic IPA</span></h4>
				<h4>Capital <span class="italic normal">Hop Cream</span></h4>
				<h4>Central Waters <span class="italic normal">Satin Solstice Imperial Stout</span></h4>
				<h4>Central Waters <span class="italic normal">Mudpuppy Porter</span></h4>
				<h4>Furthermore <span class="italic normal">Knot Stock APA</span></h4>				
				<h4>Lake Louie <span class="italic normal">Kiss the Lips IPA</span></h4>
				<h4>Lake Louie <span class="italic normal">Tommy Porter</span></h4>
				<h4>Lakefront <span class="italic normal">Fixed Gear</span><span class="normal">(American Red Ale)</span></h4>
				<h4>Lakefront <span class="italic normal">New Grist Gluten Free Pilsner</span></h4>
				<h4>O'so <span class="italic normal">Night Train Porter</span></h4>
				<h4>O'so <span class="italic normal">Rusty Red Ale</span></h4>
				<h4>*O'so <span class="italic normal">Dark Imperial Red</span></h4>
				<h4>*Potosi <span class="italic normal">Gandy Dancer Porter</span></h4>
				<h4>Rush River <span class="italic normal">Winter Warmer Scotch Ale</span></h4>
				<h4>*Rush River <span class="italic normal">Lyndale Brown Ale</span></h4>
				<h4>*Tyranena <span class="italic normal">Stone Tepee APA</span></h4>
				<h4>Guinness <span class="italic normal">Irish Dry Stout</span></h4>
				<h4>Sprecher <span class="italic normal">Bourbon-barreled Hard Root Beer</span></h4>
				<h4>*Ciderboys <span class="italic normal">Mad Bark Apple Cinnamon Hard Cider</span></h4>
			</div>
			<!--
			<div id="bottled-beer-domestic" rel="#bottled-beer-domestic" data-role="collapsible" data-collapsed="true">
				<h3>Domestics</h3>
				<h4>Miller Lite</h4>
				<h4>Miller 64</h4>
				<h4>Miller High Life</h4>
				<h4>Miller High Life Light</h4>
				<h4>Coors Light</h4>
				<h4>Budweiser</h4>
				<h4>Bud Light</h4>
				<h4>Bud Select 55</h4>
				<h4>Michelob Ultra</h4>
				<h4>Busch Light</h4>
				<h4>Berghoff</h4>
				<h4>Schlitz</h4>
				<h4>Pabst</h4>
				<h4>Chick</h4>
			</div>
			<div id="bottled-beer-import" rel="#bottled-beer-import" data-role="collapsible" data-collapsed="true">
				<h3>Imports</h3>
				<h4>Heineken</h4>
				<h4>Corona Light</h4>
				<h4>Guinness</h4>
				<h4>Murphy's</h4>
			</div>
			-->
		</div>
		
		<div id="menu-nav-tap-beer" rel="#tap-beer" data-role="collapsible" data-collapsed="true">
			<h3>Tap Beer</h3>
			<div id="tap-beer" class="food-menu">
				<h4>*Capital Maibock <span class="italic normal">Maibock/Helles Bock (Lager)</span> <span class="italic bold">Middleton, WI</span> <span class="normal">ABV:5.4%</span></h4>
				<h4>New Glarus Spotted Cow <span class="italic normal">Creamy Farmhouse Ale</span> <span class="italic bold">New Glarus, WI</span> <span class="normal">ABV:4.8%</span></h4>
				<h4>*New Glarus Cabin Fever <span class="italic normal">Honey Maibock</span> <span class="italic bold">New Glarus, WI</span> <span class="normal">ABV:6.0%</span></h4>
				<h4>Furthermore Proper <span class="italic normal">English Pale Ale</span> <span class="italic bold">Spring Green, WI</span> <span class="normal">ABV:4.5%</span></h4>
				<h4>Rush River Double Bubble <span class="italic normal">Imperial IPA</span> <span class="italic bold">River Falls, WI</span> <span class="normal">ABV:7.9%</span></h4>
				<h4>Lake Louie Warped Speed <span class="italic normal">Scotch Ale</span> <span class="italic bold">Arena, WI</span> <span class="normal">ABV:7.3%</span></h4>
				<h4>Ciderboys <span class="italic normal">First Press Hard Cider</span> <span class="italic bold">Stevens Point, WI</span> <span class="normal">ABV:5.0%</span></h4>
			</div>
		</div>
		<div id="menu-nav-alcoholic" rel="#alcoholic" data-role="collapsible" data-collapsed="true">
			<div id="alcoholic" class="food-menu">
				<h3>Alcoholic</h3>
				<h2>Alcoholic</h2>
				<h4>Old Fashioned</h4>
			</div>
		</div>
		<div id="menu-nav-wine" rel="#wine" data-role="collapsible" data-collapsed="true" data-theme="c" data-content-theme="c">
			<h3>Wine</h3>
			<div id="wine" class="food-menu">
				<h2>Wine</h2>
				<p>Offered by the glass</p>
				<div id="wine-house" rel="#wine-house" data-role="collapsible" data-collapsed="true">
					<h3>House</h3>
					<p>Sea Ridge Chardonnay</p>
					<p>Sea Ridge Merlot</p>
					<p>Beringer White Zinfandel</p>
				</div>
				<div id="wine-red" rel="#wine-red" data-role="collapsible" data-collapsed="true">
					<h3>Red</h3>
					<p>Botham Uplands Reserve</p>
					<p>Montpellier Pinot Noir</p>
					<p>Red Rock Merlot</p>
					<p>Rosemount Shiraz</p>
					<p>Medrano Malbec</p>
					<p>Montpellier Cabernet Sauvignon</p>
				</div>
				<div id="wine-white" rel="#wine-white" data-role="collapsible" data-collapsed="true">
					<h3>White</h3>
					<p>Barefoot Moscato</p>
					<p>Botham Riesling</p>
					<p>Wollersheim Prairie Fume</p>
					<p>Albertoni Pinot Grigio</p>
					<p>Guenoc Sauvignon Blanc</p>
					<p>Yellowtail Chardonnay</p>
				</div>
			</div>
		</div>

		<div data-role="collapsible" data-collapsed="true">
			<h3>Non-Alcoholic</h3>
			<div class="food-menu">
				<h2>Non-Alcoholic</h2>
				<h4>Regular Coffee - 1.29</h4>
				<h4>Decaf Coffee - 1.39</h4>
				<h4>Hot Tea - Small: 1.29 Large: 1.79</h4>
				<h4>Juice - Small: 1.59 Large: 1.99</h4>
				<p>Orange, Cranberry, Grapefruit or Tomato</p>
				<h4>Milk - Small: 1.59 Large: 1.99</h4>
				<h4>Hot Chocolate - 1.99</h4>
				<h4>Soda - 1.99</h4>
				<p>Pepsi, Diet Pepsi, Mountain Dew, 7-UP, Diet 7-UP, Iced Tea, Lemonade</p>
				<h4>Root Beer - 2.25
			</div>
		</div>
		<p>&nbsp;</p>
		<p>Please drink responsibly! Ask your bartender about a Safe Ride if you need a lift home!</p>
	</div>
	<?include 'includes/right_bar.php';?>
	<?include 'includes/footer.php';?>
</div>
</body>
</html>
