
<?php
  get_header();

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


      }
      wp_reset_postdata();

    }

    $id_form_post = array_unique($id_form_designs);
      if(!empty($id_user)){
      ?>
        <div class="infor-user-review">
            <span>Total Travel Point: <?php echo $total_point_travel?></span>
            <span>Total Travel Authority Point: <?php echo $total_point_session?></span>
            <span>Total Travel Category Score: <?php echo $total_point_category?></span>
        </div>
      <?php
      }
      ?>
      <div class="gc gc--1-of-3">
        <div class="menu">
          <?php
          if(!empty($id_form_post)){
            foreach ($id_form_post  as $key => $value) {
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
            <div class="list-data-favorite">
              <h1>Favorite City</h1>
              <div class="wraper-content-city">
              <?php 
                if($the_query->have_posts()){
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
                } 
                wp_reset_postdata(); 
              
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
});
</script>
