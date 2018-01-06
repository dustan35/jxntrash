<?php
	require_once('utils.php');
	$streets = require_once('data/streets.php');
	$recycling_schedules = require_once('data/recycling.php');

	$input = new UserInput();

	$message = 'New in town? Just moved in? Housesitting? Just can\'t remember when the city picks up garbage and recycling? Search for your street and we\'ll do our best to tell you when to take your bins to the curb.';

	if ($input->post('street'))
	{
		$pattern = '/\s\(([a-zA-Z\s]+)\sto\s([a-zA-Z\s]+)\)/';

		//	Check to see if this street has boundaries on it
		if (preg_match($pattern, $input->post('street'), $boundaries))
		{
			//	it has boundaries! so sort things out into street name and boundaries
			$user_street = strtolower(trim(preg_replace($pattern, '', $input->post('street'))));
			$boundaries = strtolower($boundaries[1].','.$boundaries[2]);
		}
		else
		{
			//	no boundaries, so carry on
			$user_street = strtolower(trim($input->post('street')));
			$boundaries = '';
		}

		foreach($streets as $data_street)
		{
			//	loop through dataset and find the perfect match based on both street name AND boundaries
			if ($data_street[0] === $user_street AND $data_street[3] === $boundaries)
			{
				$message = null;
				//	create a user_pickup to output an HTML component
				$user_pickup = $data_street;
				$user_pickup[2] = str_replace('&', ' and ', $user_pickup[2]);
				foreach ($recycling_schedules[$data_street[1]] as $date)
				{
					if (time() < strtotime($date))
					{
						$user_pickup['recycling'][] = strtotime($date);
					}
				}
			}
		}

		if (!isset($user_pickup))
		{
			$message = 'Uh oh. We didn\'t find an entry for your street. Maybe try again, or call <a href="tel://601-960-0000">601-960-0000</a> to speak with the City of Jackson Solid Waste Division';
		}
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="./styles.min.css" />
	<title>City of Jackson Garbage and Recycling Pickup Schedule</title>

	<meta property="og:type" content="website" />
	<meta property="og:title" content="City of Jackson Garbage and Recycling Collection Schedule Finder" />
	<meta property="og:url" content="https://vincefalconi.com/jxntrash" />
	<meta property="og:image" content="https://www.vincefalconi.com/assets/img/jxntrash.jpg" />
	<meta property="og:image:width" content="801" />
	<meta property="og:image:height" content="801" />
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

		<?php if (isset($user_pickup)): ?>
			<article class="pickup-information">
				<h1 class="pickup-information-heading">Schedule for <?=ucwords($user_pickup[0]);?></h1>
				<?php if ($user_pickup[3] !== ''):
					$bounds = explode(',', $user_pickup[3]);
					$bounds = array_map(function($val){
						return ucwords($val);
					}, $bounds);
					$bounds = join(' to ', $bounds);
				?>
					<em>This pick up schedule applies to <?=ucwords($user_pickup[0]);?> between <?=$bounds;?></em>
				<?php endif ?>
				<dl class="info-list">
					<dt class="info-list__heading">Regular Garbage Pickup Days</dt>
					<dd class="info-list__item"><?=$user_pickup[2];?></dd>
					<dt class="info-list__heading">Upcoming Recycling Collections (<?=$user_pickup[1];?> Route)</dt>
					<dd class="info-list__item">
						<ol class="recycling-pickup-schedule">
							<?php
								$count = 0;
								while($count < 3):
							?>
								<li><time><?=date('F j, Y', $user_pickup['recycling'][$count]);?></time></li>
							<?php
								$count++;
								endwhile;
							?>
						</ol>
					</dd>
				</dl>
				<a href="./see-all">See all pickup schedules</a>
			</article>
		<?php endif ?>

		<?php if (isset($message)): ?>
			<p><?=$message;?></p>
		<?php endif ?>

		<div class="form-wrapper">
			<form action="" method="post">
				<div class="control">
					<input type="text" class="input input--text" list="streets" name="street" placeholder="123 Main St" aria-label="Search for your street name" />
					<datalist id="streets">
						<?php foreach($streets as $key => $street):
							$bounds = explode(',', $street[3]);
							$bounds = array_map(function($val){
								return ucwords($val);
							}, $bounds);
							$bounds = join(' to ', $bounds);
						?>
							<option value="<?=ucwords($street[0])?> <?php if ($street[3] !== ''): ?>(<?=$bounds?>)<?php endif; ?>" />
						<?php endforeach; ?>
					</datalist>
				</div>
				<div class="control">
					<button class="input input--button">Search</button>
				</div>
			</form>
		</div>

	</main>

	<footer class="container">
		<p>Made by <a href="https://vincefalconi.com">Vince Falconi</a></p>
		<p><a href="https://github.com/vfalconi/jxntrash">About</a></p>
	</footer>

</body>
</html>
