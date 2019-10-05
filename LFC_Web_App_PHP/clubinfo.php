<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    // Get the Data we need for this page
    $tcitems = xmlLoadAll("data/xml/carousel-club.xml", "PLCarouselImage", "Image");
    $ttabs = xmlLoadAll("data/xml/tabs-club.xml", "PLTab", "Tab");
    $tquotes = xmlLoadOne("data/xml/quotes-any.xml", "PLQuote");
    $tmanager = jsonLoadOneManager(1);
    $tcoaches = jsonLoadAllCoaching();
    $tboard = jsonLoadAllExecutive();
    $tstadium = jsonLoadOneStadium(1);

    $tkits = jsonLoadAllKit();

    // Build the UI Components
    $tcarouselhtml = renderUICarousel($tcitems, "img/carousel");
    $tmanagerhtml = renderManagerTable($tmanager);
    $tboardhtml = renderExecutiveTable($tboard);
    $tstadiumhtml = renderStadiumSummary($tstadium);
    $tcoachhtml = renderCoachingTable($tcoaches);
    $tkitshtml = renderKitTable($tkits);

    // Build UI Components with External HTML Loading
    $tquotehtml = "";
    $tquotehtml .= renderUIQuote($tquotes);

    $ttabhtml = renderUITabs($ttabs, "club-content");

    // Construct the Page
    $tcontent = <<<PAGE
    {$tcarouselhtml}
<section class="row details" id="club-quote">
    {$tquotehtml}
    </section>
    <section class="row details" id="club-overview">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Club Overview:</h3>
        </div>
        <div class="panel-body">
        {$ttabhtml}
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Manager Overview:</h3>
        </div>
        <div class="panel-body">
        {$tmanagerhtml}
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Coaching Staff</h3>
        </div>
        <div class="panel-body">
        {$tcoachhtml}
        </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Club Management</h3>
        </div>
        <div class="panel-body">
        {$tboardhtml}
        </div>
    </div>
    <div class="panel panel-warning">
      <div class="panel-heading">
         <h3 class="panel-title">Club Kits</h3>
        {$tkitshtml}
      </div>
      <div class="panel-body">

       </div>
    </div>
    <div class="panel">
      <div class="panel-heading">
         <h3 class="panel-title">Club Stadium</h3>
      </div>
      <div class="panel-body">
        {$tstadiumhtml}
       </div>
    </div>
</section>
     
PAGE;

    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

// Build up our Dynamic Content Items.
$tpagetitle = "Club Information";
$tpagelead = "";
$tpagecontent = createPage();
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