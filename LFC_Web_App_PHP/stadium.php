<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createStadiumPage($pstadid)
{
    // Get the Data we need for this page
    $tstadium = jsonLoadOneStadium($pstadid);
    if (! empty($tstadium->desc_href))
    {
        $tstadium->desc = file_get_contents("data/html/{$tstadium->desc_href}");
    }
    // Build the UI Components
    $tstadiumhtml = renderStadiumOverview($tstadium);

    // Construct the Page
    $tcontent = <<<PAGE
    <section class="row details" id="kit-details">
        <div class="well">
            {$tstadiumhtml}
        </div>
    </div>
PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

$tpagecontent = "";

$tid = $_REQUEST["id"] ?? - 1;

// Handle our Requests and Search for Players
if (is_numeric($tid) && $tid > 0)
{
    $tpagecontent = createStadiumPage($tid);
}
else
{
}

// Build up our Dynamic Content Items.
$tpagetitle = "Stadium Information";
$tpagelead = "";
$tpagefooter = "";

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tpage = new MasterPage($tpagetitle);
// Set the Three Dynamic Areas (1 and 3 have defaults)
if (! empty($tpagelead))
    $tpage->setDynamic1($tpagelead);
$tpage->setDynamic2($tpagecontent);
if (! empty($tpagefooter))
    $tpage->setDynamic3($tpagefooter);
// Return the Dynamic Page to the user.
$tpage->renderPage();
?>