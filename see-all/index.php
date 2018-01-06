<?php
	$streets = require_once('../data/streets.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="../styles.min.css" />
	<title>City of Jackson Garbage and Recycling Pickup Schedule</title>

	<meta property="og:type" content="website" />
	<meta property="og:title" content="City of Jackson Garbage and Recycling Collection Schedule Finder" />
	<meta property="og:url" content="https://vincefalconi.com/jxntrash" />
	<meta property="og:image" content="https://www.vincefalconi.com/assets/img/jxntrash.jpg" />
	<meta property="og:site_name" content="Jackson Garbage and Recycling Schedule Finder" />
	<meta property="og:description" content="Find your street's garbage pickup days and recycling schedule" />
	<meta property="og:locale" content="en_US" />

	<meta name="twitter:card" content="summary" />
	<meta name="twitter:site" content="@vincefalconi" />
	<meta name="twitter:creator" content="@vincefalconi" />
	<meta name="twitter:title" content="City of Jackson Garbage and Recycling Collection Schedule Finder" />
	<meta name="twitter:description" content="Jackson Garbage and Recycling Schedule Finder" />
	<meta name="twitter:image" content="https://www.vincefalconi.com/assets/img/jxntrash.jpg" />
</head>
<body>

	<main class="container">

		<h1 class="page-heading">Jackson Garbage Collection Schedule</h1>

		<table class="data-table">
			<thead>
				<tr>
					<th class="header-cell">Street</th>
					<th class="header-cell">Garbage Pickup Days</th>
					<th class="header-cell">Recycling Route</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($streets as $street):
					if ($street[3] !== '')
					{
						$bounds = explode(',', $street[3]);
						$bounds = array_map(function($val){
							return ucwords($val);
						}, $bounds);
						$bounds = ' <span class="location-bounds">('.join(' to ', $bounds).')</span>';
					}
					else
					{
						$bounds = '';
					}
				?>
					<tr class="data-row">
						<td class="data-cell"><?=ucwords($street[0]).$bounds;?></td>
						<td class="data-cell"><?=str_replace('&', ' and ', $street[2]);?></td>
						<td class="data-cell"><?=$street[1];?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</main>

	<footer class="container">
		<p>Made by <a href="https://vincefalconi.com">Vince Falconi</a></p>
		<p><a href="https://github.com/vfalconi/jxntrash">About</a></p>
	</footer>

</body>
</html>
