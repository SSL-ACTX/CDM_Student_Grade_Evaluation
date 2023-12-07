<?php
function getScore($label) {
    return isset($_POST[$label]) ? intval($_POST[$label]) : 0;
}

function calculateGrade($category, $weights) {
    $scores = [];
    foreach ($weights as $label => $weight) {
        $scores[$label] = getScore($label);
    }
    return array_sum($scores) / count($scores) * $weights['total'];
}

// Perform the calculations based on the submitted form data
$MTQ = calculateGrade("quiz_scores", ["Q1" => 1, "Q2" => 1, "Q3" => 1, "Q4" => 1, "Q5" => 1, "total" => 0.35]);
$MTCS = calculateGrade("class_standing_scores", ["Assignment" => 1, "BEH" => 1, "Attendance" => 1, "Recitation" => 1, "total" => 0.25]);
$MTE = getScore("midterm_lecture_exam") * 0.40;
$Midlecgrade = $MTQ + $MTCS + $MTE;

$MTMP = calculateGrade("machine_problem_scores", ["MP1" => 1, "MP2" => 1, "MP3" => 1, "total" => 0.45]);
$MTCS1 = calculateGrade("class_standing_scores_midterm", ["Activity" => 1, "Seatwork" => 1, "BEH" => 1, "total" => 0.20]);
$MTE1 = getScore("midterm_lab_exam") * 0.35;
$MidlabGrade = $MTMP + $MTCS1 + $MTE1;
$MidtermGrade = ($Midlecgrade * 0.60) + ($MidlabGrade * 0.40);

// Perform the calculations based on the submitted form data for Final Grade
$FTQ = calculateGrade("quiz_scores_final", ["Q1" => 1, "Q2" => 1, "Q3" => 1, "Q4" => 1, "Q5" => 1, "total" => 0.35]);
$FTCS = calculateGrade("class_standing_scores_final", ["Assignment" => 1, "BEH" => 1, "Attendance" => 1, "Recitation" => 1, "total" => 0.25]);
$FTE = getScore("final_lecture_exam") * 0.40;
$Finallecgrade = $FTQ + $FTCS + $FTE;

$FTMP = calculateGrade("machine_problem_scores_final", ["MP1" => 1, "MP2" => 1, "MP3" => 1, "total" => 0.45]);
$FTCS1 = calculateGrade("class_standing_scores_final2", ["Activity" => 1, "Seatwork" => 1, "BEH" => 1, "total" => 0.20]);
$FTE1 = getScore("final_lab_exam") * 0.35;
$FinallabGrade = $FTMP + $FTCS1 + $FTE1;
$FinalGrade = ($Finallecgrade * 0.60) + ($FinallabGrade * 0.40);


// Display the results
echo "Midterm Grade: " . $MidtermGrade . "<br><br>";
echo "Final Grade: " . $FinalGrade . "<br><br>";

// Display the final total and grade result
$TFG = ($MidtermGrade * 0.40) + ($FinalGrade * 0.60);
echo "Your total Final Grade: " . $TFG . "<br>";

// Display the corresponding grade result
if ($TFG >= 97 && $TFG <= 100) {
    echo "1.00<br>";
} elseif ($TFG >= 94 && $TFG <= 96) {
    echo "1.25<br>";
} elseif ($TFG >= 91 && $TFG <= 93) {
    echo "1.50<br>";
} elseif ($TFG >= 88 && $TFG <= 90) {
    echo "1.75<br>";
} elseif ($TFG >= 85 && $TFG <= 87) {
    echo "2.00<br>";
} elseif ($TFG >= 82 && $TFG <= 84) {
    echo "2.25<br>";
} elseif ($TFG >= 79 && $TFG <= 81) {
    echo "2.50<br>";
} elseif ($TFG >= 76 && $TFG <= 78) {
    echo "2.75<br>";
} elseif ($TFG >= 75 && $TFG < 76) {
    echo "3.00<br>";
} elseif ($TFG >= 0 && $TFG < 75) {
    echo "5.00<br>";
} else {
    echo "Invalid Grade<br>";
}
?>
