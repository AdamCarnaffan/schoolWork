<html>
<body>
<?php
session_start();
// Setup Library
$wordsToGuess = ['tangerine','amber','peach','mango','nectarine','apricot','papaya','citron','citrus','salmon','bittersweet','cantaloupe','coral','orangish','citrange','carrot','creamy','henna','mulberry','marigold','naval','ochre','pineaple','apple','fig','kumquat','loganberry','mandarin','melon','raisin','plum','raspberry','strawberry','cacao','capulin','imbu','cherimoya','oak','plumcot','almond','ananas','bergamot','brassy','breadfruit','bronzy','bum','butternut','candleberry','canistel','casaba','checkerberry','chromatic','cinnamon','civet','cocoa','conic','coconut','coppery','crab','custard','dewberry','gum','honeydew','huckleberry','icaco','ilama','jackfruit','jaundiced','jujube','juniper','linden','loquat','mahogany','mammee','mangosteen','manzanilla','maramalde','mayapple','muscat','muscatel','muskmelon','nutmeg','ocherous','coloured','ovoid','oranges','passion','pippin','pumpkin','currant','reddish','russet','rosy','simple','spheroidal','sugar','sugarplum','sweet','tawny','ugli','watermelon','cherry','lime','olive','avocado','banana','grapefruit','lemon','ebony','gooseberry','khaki','pear','prune','quince','berry','blackberry','chestnut','cranberry','crimson','date','grape','guava','tree','orangeness','carroty','ginger','gold','hazel','ocher','red','alligator','ash'];
// Take Input
$newInput = (isset($_POST['letterInput'])) ? strtolower(trim($_POST['letterInput'])) : null;
$stopGuess = false;
// Game Outcomes
$win = false;
$loss = false;
// User Messages
$validationError = null;
$returnMessage = null;

// Check for restart
if (isset($_POST['restart'])) {
	session_unset();
	$_POST['newGame'] = true;
	$newInput = null;
}

// Generate new Word
if (!isset($_SESSION['gameWord'])) {
	$_SESSION['gameWord'] = $wordsToGuess[rand(0,count($wordsToGuess) - 1)];
	$_SESSION['guessedDisplay'] = generateBlankGuessDisplay($_SESSION['gameWord']);
	$_SESSION['wordAsArray'] = str_split($_SESSION['gameWord']);
	$_SESSION['guessedLetters'] = [];
  $_SESSION['lettersGuessed'] = 0;
  $_SESSION['strikes'] = 0;
  $_SESSION['wrongLetters'] = [];
}

// Validate User Input
try {
	if (InputIsLetter($newInput, $_SESSION['guessedLetters'])) {
		$userGuess = $newInput;
	} else {
		$validationError = ($newInput != null) ? "Invalid Input" : null;
		$userGuess = null;
	}
} catch (exception $e) {
	$validationError = $e->getMessage();
	$userGuess = null;
}

if ($userGuess != null) {
	if (in_array($userGuess, $_SESSION['wordAsArray'])) { // Correct guess
            $c = 0;
            foreach ($_SESSION['wordAsArray'] as $letter) {
                if ($letter == $userGuess) {
                    $_SESSION['guessedDisplay'][$c] = $letter;
                    $_SESSION['lettersGuessed']++;
                }
                $c++;
            }
            $returnMessage = "<font color='green'>That was correct! Keep going!</font>";
	} else { // Penalty for wrong guess
            $_SESSION['strikes']++;
            array_push($_SESSION['wrongLetters'],$userGuess);
            $returnMessage = "<font color='red'>That was wrong! Try again.</font>";
	}
        array_push($_SESSION['guessedLetters'],$userGuess);
}

//echo $_SESSION['gameWord'] . "</br>";

// Check for game win
if ($_SESSION['lettersGuessed'] == count($_SESSION['wordAsArray'])) {
    $stopGuess = true;
    $win = true;
    $returnMessage = "<font color='green'>You've won! Well Done. Click below to play again.</font";
}

// Check for game loss
if ($_SESSION['strikes'] >= 6) {
    $stopGuess = true;
    $loss = true;
    $validationError = null;
    $returnMessage = "<font color='red'>Darn, you've lost! The correct word was <u>" . $_SESSION['gameWord'] . "</u>! Click below to try again.</font>";
}

// Hangman Display Drawing
DrawHangMan($_SESSION['strikes']);

// Display current guessed
foreach ($_SESSION['guessedDisplay'] as $letter) {
	echo $letter . " ";
}
echo "</br>";

// Display Validation Errors on user input
if (!isset($_POST['newGame'])) {
	echo "<font color='red'>" . $validationError . "</font>";
}

echo "</br>";

// FUNCTIONS DEFINED BELOW

function generateBlankGuessDisplay($newWord) {
	$wordBlank = [];
	$characters = str_split($newWord);
	foreach ($characters as $char) {
		array_push($wordBlank, "_");
	}
	return $wordBlank;
}

function InputIsLetter($input, $guessed) {
	$validCharacters = range('a','z'); // Alphabetical characters as lowercase
	if (strlen($input) > 1 || $input == "") {
		throw new exception("Your input is of the wrong length");
	} elseif (!in_array($input, $validCharacters)) {
		throw new exception("Your input is not a letter of the English alphabet");
	} elseif ($input == null) {
		return false;
	} elseif (in_array($input, $guessed)) {
		throw new exception("You've already guessed this letter");
	}
	return true;
}

function DrawHangMan($strikes) {
		// Set the base drawing
		$drawing = "|-------------||----
";
		for ($c = 1; $c < 13; $c++) {
			$drawing .= "|                   
";
		}
		$drawing .= "--------------------";
		
    switch ($strikes) {
        case 0:
            echo "<pre>" . $drawing . "</pre>";
            return;
        case 6: // Arm 2 (left side)
            $drawing = substr_replace($drawing, "/", 118, 1);
            $drawing = substr_replace($drawing, "/", 138, 1);
            $drawing = substr_replace($drawing, "/", 158, 1);
        case 5: // Arm 1 (right side)
            $drawing = substr_replace($drawing, "\ ", 120, 2);
            $drawing = substr_replace($drawing, "\ ", 142, 2);
            $drawing = substr_replace($drawing, "\ ", 164, 2);
        case 4: // Leg 2 (left side)
            $drawing = substr_replace($drawing, "/", 223, 1);
            $drawing = substr_replace($drawing, "/", 243, 1);
            $drawing = substr_replace($drawing, "/", 263, 1);
        case 3: // Leg 1 (right side)
            $drawing = substr_replace($drawing, "\ ", 225, 2);
            $drawing = substr_replace($drawing, "\ ", 247, 2);
            $drawing = substr_replace($drawing, "\ ", 269, 2);
        case 2: // Torso
            $drawing = substr_replace($drawing, "|", 98, 1);
            $drawing = substr_replace($drawing, "|", 119, 1);
            $drawing = substr_replace($drawing, "|", 140, 1);
            $drawing = substr_replace($drawing, "|", 161, 1);
            $drawing = substr_replace($drawing, "|", 182, 1);
            $drawing = substr_replace($drawing, "|", 203, 1);
        case 1: // Head
            $drawing = substr_replace($drawing, "__", 35, 2);
            $drawing = substr_replace($drawing, "/", 55, 1);
            $drawing = substr_replace($drawing, "\ ", 58, 2);
            $drawing = substr_replace($drawing, "\__/", 76, 4);
            echo "<pre>" . $drawing . "</pre>";
            return;
    }
}

/*  The Plan ;)
 *  |-------------||----
    |             __    
    |           /   \   
    |           \___/   
    |            |      
    |            |      
    |           /|\     
    |          / | \    
    |         /  |  \   
    |            |      
    |           / \     
    |          /   \    
    |         /     \   
    --------------------
 * 
 * 
 * 
 * 
 */

?>
<form method='POST'>
	<input type='text' name='letterInput' placeholder='Enter a letter!' autofocus <?php if ($stopGuess) echo "disabled"; ?>>
	<input type='submit' name='submit' value='Go!'>
	<input type='submit' name='restart' value='Restart'>
</form>
    
<?php
// Display incorrect guesses
echo "<font color='blue'><b>You've incorrectly tried: </b></font>";
foreach ($_SESSION['wrongLetters'] as $letter) {
    echo $letter . " ";
}
echo "</br>";
echo "</br>";
// Move Result Display
echo "<b>" . $returnMessage . "</b>";
if ($stopGuess) {
    echo "</br><form method='POST'><input type='submit' name='restart' value='Play Again!'></form>";
}

?>
</body>
</html>
