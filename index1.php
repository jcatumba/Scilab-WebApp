<?php include_once "common/header.php"; ?>

<div id="main">

   <noscript>This site just doesn't work, period, without JavaScript</noscript>

   <!-- IF LOGGED IN -->
   
   <!-- Content here -->
   <ul id="list">
   <li class="colorRed">
        <span>Walk the dog</span>
        <div class="draggertab tab"></div>
        <div class="colortab tab"></div>
        <div class="deletetab tab"></div>
        <div class="donetab tab"></div>
    </li>

    <li class="colorBlue">
        <span>Pick up dry cleaning</span>
        <div class="draggertab tab"></div>
        <div class="colortab tab"></div>
        <div class="deletetab tab"></div>
        <div class="donetab tab"></div>
    </li>

    <li class="colorGreen">
        <span>Milk</span>
        <div class="draggertab tab"></div>
        <div class="colortab tab"></div>
        <div class="deletetab tab"></div>
        <div class="donetab tab"></div>
    </li>
    </ul>
    
    <form action="" id="add-new"> 

    <div>
     <input type="text" id="new-list-item-text" name="new-list-item-text" />
     <input type="submit" id="add-new-submit" value="Add" class="button" />

    </div>
    </form>
    
    <div id="share-area">
    <p>Public list URL: <a href="#">URL GOES HERE</a>
    <small>(Nobody but YOU will be able to edit this list)</small></p>
    </div>

   <!-- IF LOGGED OUT -->

          <!-- Alternate content here -->
   <ul id="list">
   <li class="colorRed">
        <span>Walk the dog</span>
    </li>

    <li class="colorBlue">
        <span>Pick up dry cleaning</span>
    </li>

    <li class="colorGreen">
        <span>Milk</span>
    </li>
    </ul>
    
    <img src="/images/newlist.jpg" alt="Your new list here!" />

</div>

<?php include_once "common/sidebar.php"; ?>

<?php include_once "common/footer.php"; ?>
