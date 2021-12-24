
<?php
  get_header();
  if(!empty($_GET['test'])){
    update_user_meta(get_current_user_id(),'data_score_cat_form',Null);
    // update_user_meta(8,'data_score_cat_form',Null);
    echo 'thanh';
  }
  
  // echo '<pre>';
  // print_r(get_user_meta(get_current_user_id(),'data_score_cat_form',true));
  // echo '</pre>';
?>
<div id="overlay">
  <div class="cv-spinner">
    <span class="spinner"></span>
  </div>
</div>
<div class="wrapper-template-history-point-new">
  <div class="naccs">
    <div class="content-template-history-point grid">
  <?php
    $id_user = get_current_user_id();
    $id_user_link = $_GET['userID'];
    if(!empty($id_user_link)){
      if(get_user_meta($id_user_link,'review_show_profile',true)=='show'){
        $id_user=intval($id_user_link);
        ?>
          <div class="name-user-show">
            <?php
              $display_name = get_display_name($id_user);
              echo $display_name;
            ?>
          </div>
        <?php
      }else{
        $id_user=Null;
        ?>
          <div class="notice-message-profile">
            <span>This profile is private. </span>
            <a href="<?php echo get_home_url()?>">Back to home</a>
          </div>
        <?php
      }
    }else{
      if(empty($id_user)){
        ?>
          <div class="notice-message-profile">
            <span>This profile is private. </span>
            <a href="<?php echo get_home_url()?>">Back to home</a>
          </div>
        <?php
      }
    }

    if(!empty($id_user) && empty($id_user_link)){
      if(get_user_meta($id_user,'review_show_profile',true)=='show'){
        $key_checked = 'checked';
      }
      if(get_user_meta($id_user,'review_show_profile',true)=='hide'){
        $key_checked = '';
      }
      ?>
        <div class="show-infor-review">
          <label for="show_review_user"> Make profile public</label>
          <input type="checkbox" id="show_review_user" name="show-review-user"  data-user-id="<?php echo $id_user?>" <?php echo $key_checked?>>
        </div>
      <?php
    }

    $args = array(
      'post_type'=>'point-entries',
      'posts_per_page'=>-1,
      'post_status'=>'publish',
      'meta_query'=>array(
        'relation' => 'AND',
        array(
          'key'     => 'review_user_id',
          'value'   => $id_user,
          'compare' => '=',
        ),
        array(
          'key'     => 'point_type_entrie',
          'value'   => ['dislikeentrie','likeentrie'],
          'compare' => 'NOT IN',
        ),
      )
    );
    $q_svl = new \WP_Query( $args );
    $total_point_travel = 0;
    $total_point_session= 0;
    $total_point_category = 0;
    $id_form_designs =[];
    if($q_svl->have_posts()){
      while($q_svl->have_posts()){
        $q_svl->the_post();
        $id= get_the_ID();
        $type_point_review = carbon_get_post_meta($id,'point_type_entrie');

        $point_number = carbon_get_post_meta($id,'point_number_entrie');
        if($type_point_review=='travelpoint'){
          $total_point_travel = $total_point_travel + $point_number;
        }
        if($type_point_review=='sessionpoint'){
          $total_point_session = $total_point_session + $point_number;
        }
        $cat_in_point_rw = carbon_get_post_meta($id,'categories_fields_point');

        foreach ($cat_in_point_rw as $cat_in_point) {
          $total_point_category=$total_point_category+$cat_in_point['score'];
        }
        $id_form_design = carbon_get_post_meta($id,'id_form_design');
        if(!empty($id_form_design)){
          array_push($id_form_designs,$id_form_design);
        }
        $cat_point_show_all= carbon_get_post_meta($id,'categories_fields_point');
        foreach ($cat_point_show_all as $cat_point_show_key => $cat_point_show_item) {
          $total_point = $total_point+intval($cat_point_show_item['score']);
        }


      }
      wp_reset_postdata();

    }

    $id_form_post = array_unique($id_form_designs);
      if(!empty($id_user)){
      ?>
        <div class="infor-user-review">
            <span>Travel Point: <?php echo $total_point_travel?></span>
            <span>Influence: <?php echo $total_point_session?></span>
            <span>Category Points: <?php echo $total_point_category?></span>
        </div>
        
        <div class="data-list-category">
          <?php
            $data_cat_user = get_user_meta($id_user,'data_score_cat_form',true);
            // echo '<pre>';
            // print_r($data_cat_user);
            // echo '</pre>';  
            if(empty($data_cat_user)){
              $args_design_review = array(
                'post_type'=>'review-design',
                'posts_per_page'=>-1,
                'post_status'=>'publish'
              );

              $the_query_form = new \WP_Query( $args_design_review );
              if($the_query_form->have_posts()){
                ?>
                  <h1>Travel Review Settings</h1>
                  <div class="item_category_list">
                <?php
                while($the_query_form->have_posts()){
                  $the_query_form->the_post();
                  $id_post = get_the_ID();
                  $rating_fields = carbon_get_post_meta($id_post,'rating_fields');
                  // echo '<pre>';
                  // print_r($rating_fields);
                  // echo '</pre>';
                  // $cat_forms = carbon_get_post_meta($id_post,'categories_fields');
                  ?>
                    <div class="form-cat-point">
                      <h3><?php echo get_the_title($id_post)?></h3>
                      <div class="item-cat-point">
                        <div class="point-default">
                          <p>Most Important <span>1</span></p>
                          <p><span>2</span></p>
                          <p><span>3</span></p>
                          <p><span>4</span></p>
                          <p>Least Important <span>5</span></p>
                        </div>
                        <div>
                          <?php
                            $index_custom = 0;
                            foreach(array_slice($rating_fields,0,5) as $key=>$rating_field){
                              $index_custom++; 
                              ?>
                              
                              <div class="content-cat container">  
                                <?php 
                                  if(is_user_logged_in()){
                                    ?>
                                      <div class="item" 
                                        draggable=true 
                                        data-score="<?php echo $index_custom?>" 
                                        data-design="<?php echo $id_post ?>">
                                        <?php echo $rating_field['name'];?>
                                      </div>
                                    <?php
                                  }
                                ?>  
                              </div>    
                              <?php
                            }
                          ?>
                        </div>  
                      </div>
                    </div>
                  <?php
                }
                ?>
                  </div>
                  <div class="wrapper-save-cat-profile">
                    <a 
                      href="#" 
                      class="save-btn-cat-profile"
                    >
                      Save          
                    </a>
                  </div>
                  
                <?php
              }

              wp_reset_postdata(); 
            }else{
              $data_id_form_raw = [];
              foreach($data_cat_user as $key_cat=>$item_cat){
                array_push($data_id_form_raw,$item_cat['idForm']);
              }
              $data_id_forms = array_unique($data_id_form_raw);
            ?>
              <h1>Travel Review Settings</h1>
              <div class="item_category_list">
              <?php
              
              foreach($data_id_forms as $key_id_form =>$id_form){
                ?>
                  <div class="form-cat-point">
                    <h3><?php echo get_the_title($id_form)?></h3>
                    <div class="item-cat-point">
                      <div class="point-default">
                          <p>Most Important <span>1</span></p>
                          <p><span>2</span></p>
                          <p><span>3</span></p>
                          <p><span>4</span></p>
                          <p>Least Important <span>5</span></p>
                      </div>
                      <div>
                    <?php 
                      $index_custom = 0;
                      foreach($data_cat_user as $key=>$cat_user){
                        
                        if($id_form==$cat_user['idForm']){
                          $index_custom++;
                          ?>
                            <div class="content-cat container">  
                                <?php 
                                  if(is_user_logged_in()){
                                    ?>
                                      <div class="item" 
                                        draggable=true 
                                        data-score="<?php echo $cat_user['score']?>" 
                                        data-design="<?php echo $id_form ?>">
                                        <?php echo $cat_user['nameCat']?>
                                      </div>
                                    <?php
                                  }
                                ?>   
                              </div> 
                          <?php
                        }
                      }
                    ?>
                    </div>
                    </div>  
                  </div> 
                <?php
              }
              ?>
              </div>
              <?php 
                if(is_user_logged_in()){
                  ?>
                    <div class="wrapper-save-cat-profile">
                      <a 
                        href="#" 
                        class="save-btn-cat-profile"
                      >
                        Save          
                      </a>
                    </div>
                  <?php
                }
            }
          ?>  
        </div>
      <?php
      }
      ?>
      <div class="gc gc--1-of-3">
        <div class="menu">
          <?php
          if(!empty($id_form_post)){
            foreach ($id_form_post  as $key => $value) {
              $array_cat  = carbon_get_post_meta($id_form_item,'categories_fields');
              $point_form_all =0;
              if($key==0){
                $class_active_link = 'active';
              }else{
                $class_active_link ='';
              }
          ?>
          <div class="<?php echo $class_active_link?>">
            <span class="light"></span>
            <span>
              <?php
                echo get_the_title($value);
              ?>
            </span>
          </div>
          <?php
            }
          }
          ?>
  		  </div>
      </div>
      <div class="gc gc--2-of-3">
        <ul class="nacc">
        <?php
        if(!empty($id_form_post)){
          foreach ($id_form_post  as $key => $id_form_item) {
            $array_cat  = carbon_get_post_meta($id_form_item,'categories_fields');
            $point_form_all =0;
            if($key==0){
              $class_active_tab = 'active';
            }else{
              $class_active_tab ='';
            }
            $args_form = array(
              'post_type'=>'review-entries',
              'posts_per_page'=>-1,
              'post_status'=>'publish',
              'meta_query'=>array(
                'relation' => 'AND',
                array(
                  'key'     => 'user_id',
                  'value'   => $id_user,
                  'compare' => '=',
                ),
                array(
                  'key'     => 'design_id',
                  'value'   => $id_form_item,
                  'compare' => '=',
                ),
                array(
                  'key'     => 'parent',
                  'value'   => '0',
                  'compare' => '='
                )
              )
            );
            $q_svl_new = new \WP_Query( $args_form );
            $total_reviews_form=$q_svl_new->found_posts;
            $rating_fields_form = carbon_get_post_meta($id_form_item,'rating_fields');
            $length_rating = count($rating_fields_form);
            ?>
                <li class="<?php echo $class_active_tab?>">
                  <div>
                    <table class="styled-table">
                      <tr>
                        <th>No</th>
                        <th>Reviews on post</th>
                        <th>Categories</th>
                        <th>Average point</th>
                        <th>Reviews Date</th>
                        <?php
                          foreach ($rating_fields_form as $key_rating_form => $data_rating_form) {
                            ?>
                              <th><?php echo $data_rating_form['name']?></th>
                            <?php
                          }

                        ?>
                      </tr>
                        <?php
                          if($q_svl_new->have_posts()){
                            $index=0;
                            while($q_svl_new->have_posts()){
                              $q_svl_new->the_post();
                              $id = get_the_ID();
                              $id_post = carbon_get_post_meta($id,'review_post_id');
                              $index++;
                              $cat_show_table = carbon_get_post_meta($id,'categories');
                              $rating = unserialize(get_post_meta( $id, '_rating_json_field', true ));
                              ?><tr>
                                  <td>
                                    <?php echo $index?>
                                  </td>
                                  <td>
                                    <a href="<?php echo get_permalink($id_post )?>" target="_blank">
                                      <?php echo get_the_title($id_post)?>
                                    </a>
                                  </td>
                                  <td>
                                    <?php 
                                      if(!empty($cat_show_table)){
                                        $count_cat = count($cat_show_table)-1;
                                        foreach ($cat_show_table as $key_cat_show => $cat_show){
                                          
                                          if($count_cat==$key_cat_show){
                                            $string_end = '';
                                          }else{
                                            $string_end = ', ';
                                          }
                                          echo $cat_show['name'].$string_end;
                                        }
                                      }
                                    ?>       
                                
                                  </td>
                                  <?php
                                    $total_rating_point = 0;
                                    foreach ($rating as $key_rating => $value_rating) {
                                      $total_rating_point=$total_rating_point+$value_rating['rate'];
                                    }
                                  ?>
                                  <td><?php echo round($total_rating_point/$length_rating,2)?></td>
                                  <td><?php echo get_the_date('M j, Y')?></td>
                                  <?php
                                    foreach ($rating as $key_rating => $value_rating) {
                                      ?>
                                        <td><?php echo $value_rating['rate']?></td>
                                      <?php
                                    }
                                  ?>

                                </tr>
                              <?php
                            }
                          }
                          wp_reset_postdata();
                        ?>
                    </table>
                  </div>
                </li>
            <?php
          }
        }

        ?>
        </ul>
      </div>
      <?php 
        if(!empty($id_user)){

          $agrs_city = array(
            'post_type'=>'favorite-city',
            'post_status'=>'publish',
            'post_per_page'=>'-1',
            'meta_query'=>array(
              'relation' => 'AND',
                array(
                  'key'     => 'user_id_custom',
                  'value'   => $id_user,
                  'compare' => '=',
                ),
            )
          );
          $the_query = new \WP_Query( $agrs_city );
          
          ?>
            <div class="data-user-infor">
              
              <div class="list-data-favorite">
              <?php 
                if($the_query->have_posts()){
              ?>
                <h1>Favorite City</h1>
                <div class="wraper-content-city">
                <?php 
                    while($the_query->have_posts()){
                      $the_query->the_post();
                      $id_post = get_the_ID();
                      $category = get_the_terms( $id_post, 'favorite-city-cat' );     
                      foreach ( $category as $cat){
                        
                        $thumbnail = get_field('icon', $cat->taxonomy . '_' . $cat->term_id);
                        
                        ?>
                          <div class="item-favorite-city">
                            <div class="cat-favorite-city">
                              <h3><?php echo $cat->name ?></h3>
                              <img src="<?php echo $thumbnail?>"/>
                            </div>
                            <span><?php echo get_field('place_name',$id_post)?></span>
                          </div>
                        <?php
                      }
                    }
                ?>
                </div>
              <?php 
                } 
                wp_reset_postdata(); 
              ?>
              </div>
              <div class="list-cat-submit">
                <h1>Travel Styles</h1>
                <div class="content-cat-submit">
                  <?php 
                    $data_travel_style_user = get_user_meta(get_current_user_id(),'data_travel_style',true);
                    $data_name_travel = [
                      'Tourist',
                      'Luxury Travel','Budget Travel',
                      'Slow Travel','Backpacker',
                      'Expat','Digital Nomad',
                      'Adventure Travel','Culture & Heritage Travel',
                      'Volunteer Travel','Faith Travel',
                      'Student Travel','Business Travel',
                      'Solo Travel','Party Travel'
                    ];
                    $data_checked_custom = [];
                    $data_slug_travel =[
                      'tourist',
                      'luxurytravel','budgettravel',
                      'slowtravel','backpacker',
                      'expat','digitalnomad',
                      'adventuretravel','cultureheritagetravel',
                      'volunteertravel','faithtravel',
                      'studenttravel','businesstravel',
                      'solotravel','partytravel'
                    ];
                    foreach($data_name_travel as $name){
                      array_push($data_checked_custom,'');
                    }
                    if(!empty($data_travel_style_user)){
                      foreach($data_name_travel as $key=>$name_travel){
                        foreach($data_travel_style_user as $key_child=>$item_name_user){
                          if($name_travel==$item_name_user){
                            $data_checked_custom[$key]='checked';
                          }
                        }
                      }
                    }
                  ?>
                  <form action="#" id="form-cat-content">
                    <?php 
                      foreach($data_name_travel as $key=>$name) {
                        ?>
                          <input 
                            type="checkbox" 
                            id="<?php echo $data_slug_travel[$key]?>" 
                            name="<?php echo $data_slug_travel[$key]?>" 
                            value="<?php echo $name?>" 
                            <?php echo $data_checked_custom[$key]?>
                          >
                          <label for="<?php echo $data_slug_travel[$key]?>"> <?php echo $name?></label><br>
                        <?php
                      }
                    ?>
                    <br>
                    <input type="submit" value="Save" class="travel-style-submit">
                  </form>
                </div>
              </div>
            </div>
            <div class="point-personalized">
              <?php 
                $data_score_cat_form = get_user_meta($id_user,'data_score_cat_form',true);
                $data_cat_entrie_review = [];
                if(!empty($id_form_post)){
                  foreach ($id_form_post  as $key => $id_form_item) {
                    $args_cat_form = array(
                      'post_type'=>'review-entries',
                      'posts_per_page'=>-1,
                      'post_status'=>'publish',
                      'meta_query'=>array(
                        'relation' => 'AND',
                        array(
                          'key'     => 'user_id',
                          'value'   => $id_user,
                          'compare' => '=',
                        ),
                        array(
                          'key'     => 'design_id',
                          'value'   => $id_form_item,
                          'compare' => '=',
                        ),
                        array(
                          'key'     => 'parent',
                          'value'   => '0',
                          'compare' => '='
                        )
                      )
                    );
                    $the_query_cat_form = new \WP_Query( $args_cat_form );
                    if($the_query_cat_form->have_posts()){
                      while($the_query_cat_form->have_posts()){
                        $the_query_cat_form->the_post();
                        $id_post = get_the_ID();
                        $id_post_submit = carbon_get_post_meta($id_post,'review_post_id');
                        //$data_cat_item_review = carbon_get_post_meta($id_post,'categories');
                        $data_cat_item_review = unserialize(get_post_meta( $id_post, '_rating_json_field', true ));
                        $data_string_name_cat = [];
                        // echo '<pre>';
                        // print_r($data_cat_item_review1);
                        // echo '</pre>';
                        foreach($data_cat_item_review as $item_cat_child) {
                          array_push($data_string_name_cat,$item_cat_child['name']);
                        }
                        $idDesign = carbon_get_post_meta($id_post,'design_id');
                        $data_item_cat_review = [
                          'idDesign'=> $idDesign,
                          'idPost'=>$id_post_submit,
                          'data'  =>$data_string_name_cat,
                          'score'=>0
                        ];
                        array_push($data_cat_entrie_review,$data_item_cat_review);
                      }
                    }
                    wp_reset_postdata();
                  }
                }
                
              ?>
                  
              </div>
            </div>
          <?php
          
        }
      ?>

    </div>
  </div>
</div>
<?php
  get_footer();
?>


<script>

jQuery( document ).ready(function($) {
    let data_cat_score_by_form = [];
    let height_li_active_first = $('.nacc li.active').innerHeight();
    $('.naccs ul').height(height_li_active_first + 'px');
    $(document).on("click", ".naccs .menu div", function() {
    	var numberIndex = $(this).index();

    	if (!$(this).is("active")) {
    		$('.naccs .menu div').removeClass('active');
    		$('.naccs ul li').removeClass('active');

    		$(this).addClass('active');
    		$('.naccs ul').find('li:eq(' + numberIndex + ')').addClass('active');

    		var listItemHeight = $('.naccs ul')
    			.find('li:eq(' + numberIndex + ')')
    			.innerHeight();
    		$('.naccs ul').height(listItemHeight + 'px');
    	}
    });



    $(document).on('click', '.save-btn-cat-profile', function(e) {
      e.preventDefault();
      $.ajax({
        cache: false,
        timeout: 8000,
        url: '<?php echo admin_url('admin-ajax.php');?>',
        type: 'POST',
        data: ({
          action: 'update_score_category_user',
          data:data_cat_score_by_form
        }),
        beforeSend: function() {
          $('#overlay').fadeIn(300);　
        },
        success: function( data, textStatus, jqXHR ){
          setTimeout(function(){
            $('#overlay').fadeOut(300);
          }, 1000);

        },
        error: function( jqXHR, textStatus, errorThrown ){
          alert( 'The following error occured: ' + textStatus, errorThrown );
          location.reload();
        },
        complete: function( jqXHR, textStatus ){

        }
      });
      
      

    })

    $('#form-cat-content input').on('change',function(e){
      // console.log('sdsds',$(this).is(':checked'));
      if($(this).siblings(':checked').length >= 3) {
          this.checked = false;
      }

    })


    function init_change_drag_score () {
      $('.content-cat').each(function(e,i){
        let data_item_cat_score = {
              'idForm':'',
              'nameCat':'', 
              'score':''
            }
        data_item_cat_score.idForm = $(this).find('.item').data('design')    
        data_item_cat_score.nameCat = $(this).find('.item').text().trim()
        data_item_cat_score.score = Number($(this).find('.item').data('score'))
        data_cat_score_by_form.push(data_item_cat_score);    
      })
      // console.log('ok2',data_cat_score_by_form);
    }
    init_change_drag_score();

    $('.travel-style-submit').on('click',function(e){
      e.preventDefault();
      var data_string_travel_style = [];
      $('#form-cat-content input').each(function(e,i){
        if($(this).is(':checked')){
          data_string_travel_style.push($(this).val());
        }
      })

      $.ajax({
         cache: false,
         timeout: 8000,
         url: '<?php echo admin_url('admin-ajax.php');?>',
         type: 'POST',
         data: ({
          action: 'update_travel_style_profile_review_user',
          data:data_string_travel_style
         }),
         beforeSend: function() {
           $('#overlay').fadeIn(300);　
         },
         success: function( data, textStatus, jqXHR ){
           setTimeout(function(){
             $('#overlay').fadeOut(300);
           }, 1000);

         },
         error: function( jqXHR, textStatus, errorThrown ){
           alert( 'The following error occured: ' + textStatus, errorThrown );
           location.reload();
         },
         complete: function( jqXHR, textStatus ){

         }
       });
    })

    $('#show_review_user').on('change',function(e){
      let data_user_update = $(this).is(':checked');
      let user_id = $(this).data('user-id');
      $.ajax({
         cache: false,
         timeout: 8000,
         url: '<?php echo admin_url('admin-ajax.php');?>',
         type: 'POST',
         data: ({
          action: 'update_show_review_user',
          review_show:data_user_update,
          user_id:user_id
         }),
         beforeSend: function() {
           $('#overlay').fadeIn(300);　
         },
         success: function( data, textStatus, jqXHR ){
           setTimeout(function(){
             $('#overlay').fadeOut(300);
           }, 1000);

         },
         error: function( jqXHR, textStatus, errorThrown ){
           alert( 'The following error occured: ' + textStatus, errorThrown );
           location.reload();
         },
         complete: function( jqXHR, textStatus ){

         }
       });
    })
    
    function handleDragStart(e) {
      this.style.opacity = "0.4";

      dragSrcEl = this;
      e.dataTransfer.effectAllowed = "move";
      //e.dataTransfer.setData("text/html", this.innerHTML);
    }

    function handleDragEnd(e) {
      this.style.opacity = "1";
    }

    function handleDragEnd(e) {
      this.style.opacity = "1";

      items.forEach(function (item) {
        item.classList.remove("over");
      });
      dragSrcEl = undefined;
    }

    function handleDragOver(e) {
      if (e.preventDefault) {
        e.preventDefault();
      }

      return false;
    }

    function handleDragEnter(e) {
      this.classList.add("over");
    }

    function handleDragLeave(e) {
      this.classList.remove("over");
    }

    function handleDrop(e) {
      e.stopPropagation();
      e.preventDefault();
      if (dragSrcEl !== this) {
        swapItems(dragSrcEl, this);

      }
      return false;
    }

    function swapItems(a, b) {
      
      const tmp = a.innerHTML;
      a.innerHTML = b.innerHTML;
      b.innerHTML = tmp;
      data_cat_score_by_form=[];
      init_change_drag_score()
    }

    function shiftItems(srcElem, destElem) {
      const items = Array.from(document.querySelectorAll(".container .item"));
      const srcIdx = items.indexOf(srcElem);
      const destIdx = items.indexOf(destElem);
      console.log(`srcIdx = ${srcIdx}, destIdx= ${destIdx}`);
      if (srcIdx < destIdx) {
        // moving down
        for (let i = srcIdx; i < destIdx - 1; i++) {
          console.log(`swapping ${i} and ${i + 1}`);
          swapItems(items[i], items[i + 1]);
        }
      } else {
        // moving up
        for (let i = srcIdx - 1; i >= destIdx; i--) {
          console.log(`swapping ${i} and ${i + 1}`);
          swapItems(items[i], items[i + 1]);
        }
      }
    }

    let dragSrcEl;
    const items = document.querySelectorAll(".container .item");
    items.forEach(function (item) {
      item.addEventListener("dragstart", handleDragStart);
      item.addEventListener("dragover", handleDragOver);
      item.addEventListener("dragenter", handleDragEnter);
      item.addEventListener("dragleave", handleDragLeave);
      item.addEventListener("dragend", handleDragEnd);
      item.addEventListener("drop", handleDrop);
    });

  // document.querySelector("#dropaction").addEventListener("change", (e) => {
  //   dropAction = e.target.value;
  // });



});
</script>
