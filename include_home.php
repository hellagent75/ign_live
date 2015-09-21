<?php include("sc_featuredcont.php"); ?>
<div id="home_container" style="top:100px;">
    
    <div class="headlines_area">
    	<h3 class="icon_news">Latest Headlines</h3><div style="clear:left;"></div>
		<?php include("sm_headlines_box.php"); ?>   
    </div>
    <div style="clear:left;"></div>
    	
     <div class="mid_bg">
        	<div class="full_width">
		
				<div id="topmatch_home">
                    <h3 class="icon_tm">Upcoming Match</h3>
                    <div style="clear:both;"></div>
                    <?php include("sm_topmatch.php"); ?>
                </div>
                
                <div id="results_home">
                    <h3 class="icon_results">Latest Results</h3>
                    <div style="clear:both;"></div>
                    
                    
                    <ul class="idTabs">
                        <li><a class="current" href="#tab1">ALL</a></li>
                        <li><a href="#tab2">CSGO</a></li>
                        <li><a href="#tab3">SC2</a></li>
                        <li><a href="#tab4">LOL</a></li>
                        <li><a href="#tab5">D2</a></li>
                    </ul>
                    <div style="clear:both;"></div>
                     
                    <div id="tab1"><?php include("sm_results_all.php"); ?></div>
                    <div id="tab2"><?php $game=csgo; include("sm_results_game.php"); ?></div>
                    <div id="tab3"><?php $game=sc2; include("sm_results_game.php"); ?></div>
                    <div id="tab4"><?php $game=lol; include("sm_results_game.php"); ?></div>
                    <div id="tab5"><?php $game=d2; include("sm_results_game.php"); ?></div>
                    
                </div>
                
        	
            </div>
     	</div>

            
      <div class="grey_bg">
      	<div class="full_width">
		
				<div id="streams">
                    <h3 class="icon_live">Latest Media</h3>
                    
                    
                    <ul class="idTabs">
                        <li><a href="#tab7">VIDEOS</a></li>
                        <li><a href="#tab8">GALLERY</a></li>
                    </ul>
                    <div style="clear:left;"></div>
                    <div id="tab7"><?php include("sm_videos.php"); ?></div>
                    <div id="tab8"><?php include("sm_gallery.php"); ?></div>                   
                    
                </div>
                              
        	
            </div>
     	</div>
         
	<?php include("sm_footer.php"); ?> 
    
</div>