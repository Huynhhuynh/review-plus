
<?php
  get_header();
  // update_user_meta(1,'data_score_cat_form',Null);
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
            <span>Total Travel Point: <?php echo $total_point_travel?></span>
            <span>Total Travel Authority Point: <?php echo $total_point_session?></span>
            <span>Total Travel Category Score: <?php echo $total_point_category?></span>
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
                        <h1>Travel Styles</h1>
                        <div class="item_category_list">
                      <?php
                      while($the_query_form->have_posts()){
                        $the_query_form->the_post();
                        $id_post = get_the_ID();
                        $cat_forms = carbon_get_post_meta($id_post,'categories_fields');
                        ?>
                          <div class="form-cat-point">
                            <h3><?php echo get_the_title($id_post)?></h3>
                            <div class="item-cat-point">
                              
                                <?php
                                  foreach(array_slice($cat_forms,0,5) as $key=>$cat_form){
                                    ?>
                                    <div class="content-cat">  
                                      <?php 
                                        if(is_user_logged_in()){
                                          ?>
                                            <input 
                                              data-index="<?php echo($key+1)?>"
                                              type="number" 
                                              class="score-profile-cat" 
                                              data-design="<?php echo $id_post ?>"
                                              data-namecat="<?php echo $cat_form['name']?>"
                                            />
                                          <?php
                                        }else{
                                          ?>
                                            <input 
                                              type="number" 
                                              class="score-profile-cat"
                                              value=""
                                              disabled
                                            />
                                          <?php
                                        }
                                      ?>
                                      <span class="notice-change-input">
                                        asdasdss
                                      </span>
                                      <span>
                                        <?php 
                                          echo $cat_form['name'];
                                        ?>
                                      </span>    
                                    </div>    
                                    <?php
                                  }
                                ?>
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
                    <h1>Travel Styles</h1>
                    <div class="item_category_list">
                    <?php
                    foreach($data_id_forms as $key_id_form =>$id_form){
                      ?>
                        <div class="form-cat-point">
                          <h3><?php echo get_the_title($id_form)?></h3>
                          <div class="item-cat-point">
                          <?php 
                            foreach($data_cat_user as $key=>$cat_user){
                              if($id_form==$cat_user['idForm']){
                                ?>
                                  <div class="content-cat">  
                                      <?php 
                                        if(is_user_logged_in()){
                                          ?>
                                            <input 
                                              type="number" 
                                              data-index="<?php echo($key+1)?>"
                                              class="score-profile-cat" 
                                              value="<?php echo $cat_user['score']?>"
                                              data-design="<?php echo $id_form ?>"
                                              data-namecat="<?php echo $cat_user['nameCat']?>"
                                            />
                                          <?php
                                        }else{
                                          ?>
                                            <input 
                                              type="number" 
                                              class="score-profile-cat"
                                              value="<?php echo $cat_user['score']?>"
                                              disabled
                                            />
                                          <?php
                                        }
                                      ?>
                                      <span class="notice-change-input">
                                        
                                      </span>
                                      <span>
                                        <?php 
                                          echo $cat_user['nameCat'];
                                        ?>
                                      </span>    
                                    </div> 
                                <?php
                              }
                            }
                          ?>
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
                        <th>Categorys</th>
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
                                          if($count_cat=$key_cat_show){
                                            $string_end = '';
                                          }else{
                                            $string_end = ',';
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
                        $data_cat_item_review = carbon_get_post_meta($id_post,'categories');
                        $data_string_name_cat = [];
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
                
                if(!empty($data_cat_entrie_review) && !empty($data_score_cat_form)){
              ?>

                 
                  <h1>Personalized scoring</h1>
                  <div class="wrapper-form-personalized">
                  <?php 
                    
                    $data_item_point = [];
                    foreach($data_cat_entrie_review as $key_entrie=>$item_entrie_review){
                      $item_point=[]; 
                      foreach($data_score_cat_form as $key_score=>$item_score_cat){
                        if($item_entrie_review['idDesign']==$item_score_cat['idForm']){
                          if(in_array($item_score_cat['nameCat'],$item_entrie_review['data'])){
                            array_push($item_point,$item_score_cat['score']);
                            $data_item_point[$key_entrie]=$item_point;
                          }
                        }
                      }
                    }
                    foreach($data_item_point as $key_average=>$item_point_average){
                      $average = round(array_sum($item_point_average)/count($item_point_average),2); 
                      $data_cat_entrie_review[$key_average]['score']=$average;
                    }
                    foreach($id_form_post as $idform){
                    ?>
                        <div class="content-data-personalized">
                          <h2><?php echo get_the_title($idform)?></h2>
                          <div class="content-post-point">
                            <?php 
                              foreach($data_cat_entrie_review as $item_cat_entrie_show){
                                if($idform==$item_cat_entrie_show['idDesign']){
                                  ?>
                                    <div class="content-show-point-average">
                                      <h4><?php echo get_the_title($item_cat_entrie_show['idPost'])?></h4>
                                    <p>Personalized scoring:<?php echo $item_cat_entrie_show['score']?></p>

                                    </div>
                                  <?php
                                }
                              }
                            ?>
                          </div>
                        </div>
                    <?php
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

    function data_list_score_init () {
      $('.item_category_list .form-cat-point .item-cat-point').each(function(e,i){
        $(this).find('.content-cat').each(function(item,childIdex){
            let data_item_cat_score = {
              'idForm':'',
              'nameCat':'', 
              'score':''
            }
            data_item_cat_score.idForm  = $(this).find('.score-profile-cat').data('design');
            data_item_cat_score.nameCat = $(this).find('.score-profile-cat').data('namecat');
            data_item_cat_score.score   = $(this).find('.score-profile-cat').val();
            data_cat_score_by_form.push(data_item_cat_score);
        })
      })
    }

    data_list_score_init();
    


    $('.score-profile-cat').on('change',function(e){
      let point_data = $(this).val();
      let seft = $(this);
      let count = 0;
      let data_point_init = [1,2,3,4,5];
      

      let data_index_ip = Number($(this).data('index'));

      data_point_init[data_index_ip-1]=Number(point_data);
      for(var i in data_point_init) {
        if(data_point_init[i]==point_data){
          count++;
        }
      }
      seft.parents('.item-cat-point').find('.content-cat').each(function(i,e){
        
        if((i+1)!=data_index_ip){
          let seft_itme = $(this).parent();
          if(point_data==$(this).find('.score-profile-cat').val()){
            seft_itme.find('.content-cat:nth-child('+data_index_ip+')').find('.notice-change-input').addClass('active-tooltip');
            seft_itme.find('.content-cat:nth-child('+data_index_ip+')').find('.notice-change-input').html('This point already exists')
          }
          if(count<2){
            seft_itme.find('.content-cat:nth-child('+data_index_ip+')').find('.notice-change-input').removeClass('active-tooltip');
          }
        }
        
      })
      if(count<2){
        let data_item_cat_score = {
          'idForm':'',
          'nameCat':'', 
          'score':''
        }
        data_item_cat_score.idForm=$(this).data('design');
        data_item_cat_score.nameCat=$(this).data('namecat');
        data_item_cat_score.score=$(this).val();
        let data_index;
        let push_data = true;
        for(var i in data_cat_score_by_form) {
          if(data_cat_score_by_form[i].idForm == $(this).data('design')
            && data_cat_score_by_form[i].nameCat == $(this).data('namecat')
          ){
            push_data = false;
            data_index= i;
          }
        }
        if(!push_data && data_index) {
          data_cat_score_by_form[data_index]=data_item_cat_score;
        }
        if(push_data){
          data_cat_score_by_form.push(data_item_cat_score);
        }
      }
    })

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
