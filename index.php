<?php
	include_once('translations.php');

	session_start();

	if (isset($_POST['clear_history'])) {
    unset($_SESSION['history']);
	}

	if(!isset($_SESSION['history'])) {
		$_SESSION['history'] = [];
	}

 	if(isset($_GET['lang'])) {
		setLanguage($_GET['lang']);
	} 

	$result = null;
	$error = null;

	$a = 0;
	$b = 0;
	$op = '';

	if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['a'], $_GET['b'], $_GET['op'])) {
		$a = floatval($_GET['a']);
		$b = floatval($_GET['b']);
		$op = $_GET['op'];

		if (!is_numeric($a) || !is_numeric($b)) {
			$error = _lang("invalid_input");
		} else {
			switch ($op) {
				case '+': $result = $a + $b; break;
				case '-': $result = $a - $b; break;
				case '*': $result = $a * $b; break;
				case '/':
					if ($b == 0) {
						$error = _lang("division_by_zero");
					} else {
						$result = $a / $b;
					}
					break;
				default:
					$error = _lang("operation_error");
					break;
			}

			if(is_null($error)) {
				$entry = [
					'time' => date('Y-m-d - H:i:s'),
					'result' => htmlspecialchars($a) . htmlspecialchars(" $op ") . htmlspecialchars($b) . " = " . $result
				];

				array_unshift($_SESSION['history'], $entry);
				
				if(sizeof($_SESSION['history']) > 10) {
					$_SESSION['history'] = array_slice($_SESSION['history'], 0, 10);
				}
			}
		} 
	}
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
<head>
	<meta charset="UTF-8">  
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= _lang("title")?></title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="wrapper">
	  <div class="language-panel">
			
      <a href="?lang=en" title="Choose English language" class="language-option">
				<img src="./images/en.svg" alt="English flag">
			</a>
      <a href="?lang=pt" title="Choose Portuguese language" class="language-option">
				<img src="./images/pt.svg" alt="Portuguese flag">
			</a>
      <a href="?lang=ua	" title="Choose Ukrainian language" class="language-option">
				<img src="./images/ua.svg" alt="Ukrainian language">
			</a>
      <a href="?lang=de" title="Choose Germany language" class="language-option">
				<img src="./images/de.svg" alt="Germany language">
			</a>
    </div>
		<h1 class='title'><?= _lang('welcome_message') ?></h1>
		<div class="history">
			<h3 class="history__title"><?= _lang('history_title') ?></h3>

			<ul class="history__list">

				<?php 
					$history = $_SESSION['history'];
					
					if(empty($history)) {
						echo "<p>" . _lang('empty_history') . "</p>";
					} else {
						foreach ($history as $key => $value) {
							$history_time = $value['time'];
							$history_result = $value['result'];
							$repeat_translation = _lang("history_repeat");
							
							echo "
								<li class='history__list-el'>
									<div class='history__list-info'>
										<span class='history__list-time'>$history_time</span>
										<span class='history__list-result'>$history_result</span>
									</div>
									<button class='history__list-copy btn'>$repeat_translation</button>
								</li>
							";
						}
					}
				?>

			</ul>

			<?php if(!empty($_SESSION['history'])): ?>
				<form method="POST">
					<button class="history__clear-btn btn" type="submit" name="clear_history"><?= _lang("clear_history") ?></button>
				</form>
			<?php endif; ?>
		</div>
		<form class='form' action="./" method="GET">
			<label for="first-number"><?= _lang('first_number_label') ?></label>
			<input id="first-number" required type="number" step="0.01" name="a">
			<label for="second-number"><?= _lang('second_number_label') ?></label>
			<input id="second-number" required type="number" step="0.01" name="b">
			<label for="op"><?= _lang('operator_label') ?></label>
			<select id="op" name="op" >
				<option value="+"><?= _lang('plus') ?></option>
				<option value="-"><?= _lang('minus') ?></option>
				<option value="/"><?= _lang('divide') ?></option>
				<option value="*"><?= _lang('multiply') ?></option>
			</select>
			<input class='btn' type="submit" value="<?= _lang('submit_button') ?>">
		</form>
		<?php if (!is_null($error)): ?>
			<p id="error"><?= $error ?>
		<?php elseif (!is_null($result)): ?>
			<p id="result"><?= _lang('result') ?>: <?= htmlspecialchars($a) . " $op " . htmlspecialchars($b) . " = " . $result ?></p>
		<?php endif; ?>
	</div>
</body>

<script src="repeat.js"> </script>
</html>