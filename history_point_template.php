
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
            <span>Profile review is not publish. </span>
            <a href="<?php echo get_home_url()?>">Back to home</a>
          </div>
        <?php
      }
    }else{
      if(empty($id_user)){
        ?>
          <div class="notice-message-profile">
            <span>Profile review is not publish. </span>
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
          <label for="show_review_user"> Publish review page</label>
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
                    <div class="show-form-point">

                      <div class="ss-score-total">
                        <span>Travel Session Score</span>
                        <p><?php echo get_all_point_travel($id_form_item,$id_user)[1]?></p>
                        <div class="raw-score">
                          <span>Raw Score</span>
                          <div class="icon-start">
                            <img src="https://travelingsession.com/wp-content/uploads/2021/11/star.png" >
                            <img src="https://travelingsession.com/wp-content/uploads/2021/11/star.png" >
                            <img src="https://travelingsession.com/wp-content/uploads/2021/11/star.png" >
                            <img src="https://travelingsession.com/wp-content/uploads/2021/11/star.png" >
                            <img src="https://travelingsession.com/wp-content/uploads/2021/11/star.png" >
                          </div>
                        </div>
                      </div>
                      <div class="cat-score-total ss-score-total">
                        <span>Travel Category Score</span>
                        <?php
                          if(!empty(get_current_user_id())){
                            if(!empty($id_user_link)){
                              if(intval($id_user_link)==get_current_user_id()){
                                ?>
                                  <p><?php echo get_score_category($id_user,$id_form_item)?></p>
                                <?php
                              }else{
                                ?>
                                  <a href="#">Login or Register To See Personal Travel Score</a>
                                <?php
                              }
                            }else{
                              ?>
                                <p><?php echo get_score_category($id_user,$id_form_item)?></p>
                              <?php
                            }

                          }else{
                            ?>
                              <a href="#">Login or Register To See Personal Travel Score</a>
                            <?php
                          }
                        ?>

                        <div class="raw-score">
                          <span>Raw Score</span>
                          <div class="icon-start">
                            <img src="https://travelingsession.com/wp-content/uploads/2021/11/star.png" >
                            <img src="https://travelingsession.com/wp-content/uploads/2021/11/star.png" >
                            <img src="https://travelingsession.com/wp-content/uploads/2021/11/star.png" >
                            <img src="https://travelingsession.com/wp-content/uploads/2021/11/star.png" >
                            <img src="https://travelingsession.com/wp-content/uploads/2021/11/star.png" >
                          </div>
                        </div>
                      </div>
                    </div>

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
    </div>
  </div>
</div>


<?php

  get_footer();
?>


<style>

.wrapper-template-history-point{
  margin: 0;
  background: #f5f5f5;
  font-family: 'Poppins', sans-serif;
  line-height: 2em;
}
.infor-user-review {
      text-align: center;
    max-width: 1140px;
    display: flex;
    margin: auto;
    justify-content: space-between;
    margin-bottom: 32px;
}

.wrapper {
  max-width: 800px;
  margin: auto;
  margin-top: 80px;
}


.tab-wrapper {
  text-align: center;
  display: block;
  margin: auto;
}


.tabs {
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
}


.tab-link {
  margin: 0 1%;
  list-style: none;
  padding: 10px 40px;
  color: #aaa;
  cursor: pointer;
  font-weight: 700;
  transition: all ease 0.5s;
  border-bottom: solid 3px rgba(255,255,255,0.0);
  letter-spacing: 1px;
}


.tab-link:hover {
  color: #999;
  border-color: #999;
}


.tab-link.active {
  color: #333;
  border-color: #333;
}


.tab-link.active {
  color: #EE6534;
  border-color: #EE6534;
}



.content-wrapper {
  padding: 40px 80px;
}


.tab-content {
  display: none;
  text-align: center;
  color: #888;
  font-weight: 300;
  font-size: 15px;
  opacity: 0;
  transform: translateY(15px);
  animation: fadeIn 0.5s ease 1 forwards;
}


.tab-content.active {
  display: block;
}


@keyframes fadeIn {
  100%{
  opacity: 1;
    transform: none;
}
}

.wrapper-point-star {
  display: flex;
  .review-plus-rating-field {
    margin-left: 20px;
  }
}


.content-template-history-point {
  width: 1140px;
  margin: auto;
  max-width: 95%;
  position: relative;
}
.infor-user-review {
  text-align: left;
  display: block;
  margin-bottom: 32px;
}
.infor-user-review span {
  display: block;
  margin-bottom: 5px;
}

.nacc li div table {
  display: block;
  overflow-x: auto;
  white-space: nowrap;
  text-align: center;
  font-size: 16px;
}
.nacc li div table::-webkit-scrollbar {
  height: 6px;
  background-color: #F5F5F5;
}

.nacc li div table::-webkit-scrollbar-thumb {
  background-color: #3F51B5;
}

.nacc li div table::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
  background-color: #F5F5F5;
}
.nacc li div table::-webkit-scrollbar-thumb:hover {
  background: #3F51B2;
}




.nacc li div table tbody {
  display: table;
  width: 100%;
}

.grid {
   list-style: none;
}

.gc {
   box-sizing: border-box;
   display: inline-block;
   margin-right: -.25em;
   min-height: 1;
   vertical-align: top;
}

.gc--1-of-3 {
  width: calc(100%/4);
  padding-right: 25px;
}

.gc--2-of-3 {
  width: calc(100% - calc(100%/4));
}

.naccs {
   position: relative;
   width: 1140px;
   max-width: 100%;
   margin:auto;
}

.naccs .menu div span {
  font-size: 18px;
}
.naccs .menu div {
   padding: 15px 20px 15px 40px;
   margin-bottom: 10px;
   color: #000;
   line-height: 1;
   box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
   cursor: pointer;
   position: relative;
   vertical-align: middle;
   font-weight: 700;
   transition: 1s all cubic-bezier(0.075, 0.82, 0.165, 1);
}

.naccs .menu div:hover {
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.naccs .menu div span.light {
   height: 10px;
   width: 10px;
   position: absolute;
   top:50%;
   -webkit-transform: translateY(-50%);
   transform: translateY(-50%);
   left: 15px;
   background-color: #000;
   border-radius: 100%;
   transition: 1s all cubic-bezier(0.075, 0.82, 0.165, 1);
}

.naccs .menu div.active span.light {
   background-color: #3F51B5;
   left: 0;
   height: 100%;
   width: 3px;
   top: 0;
   border-radius: 0;
   transform: translateY(0);
}

.naccs .menu div.active {
   color: #3F51B5;
   padding: 15px 20px 15px 20px;

}

ul.nacc {
   position: relative;
   height: 0px;
   list-style: none;
   margin: 0;
   padding: 0;
   transition: .5s all cubic-bezier(0.075, 0.82, 0.165, 1);
}

ul.nacc li {
   opacity: 0;
   transform: translateX(50px);
   position: absolute;
   list-style: none;
   transition: 1s all cubic-bezier(0.075, 0.82, 0.165, 1);
   max-width: 100%;
}

ul.nacc li.active {
   transition-delay: .3s;
   z-index: 2;
   opacity: 1;
   transform: translateX(0px);
}

ul.nacc li p {
  margin: 0;
}

table.styled-table tr th {
  text-align: center;
}
.styled-table {
  border-collapse: collapse;
  font-size: 0.9em;
  font-family: sans-serif;
  min-width: 400px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}
.styled-table thead tr {
  background-color: #009879;
  color: #ffffff;
  text-align: left;
}

.raw-score {
  display: flex;
  align-items: center;
}
.raw-score span {
  margin-right: 10px;
  line-height: 1;
}
.icon-start img{
  width: 20px;
  height: 20px;
}
.styled-table th,
.styled-table td {
  padding: 12px 15px;
}

.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
}

.styled-table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}

.styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #009879;
}

.show-infor-review {
  position: absolute;
  top: 40px;
  right: 0;
  max-width:1140px;
  display:flex;
  align-items:center;
  margin:auto;
  cursor:pointer;
}
#show_review_user {
  width: 25px;
  height: 24px;
  margin-left: 10px;
}

#overlay{
  position: fixed;
  top: 0;
  z-index: 100;
  width: 100%;
  height:100%;
  display: none;
  background: rgba(0,0,0,0.6);
}
.wrapper-review-form-detail {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
}
.wrapper-review-form-detail span{
  display: inline-block;
  margin-bottom: 5px;
}
.cv-spinner {
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}
.spinner {
  width: 40px;
  height: 40px;
  border: 4px #ddd solid;
  border-top: 4px #2e93e6 solid;
  border-radius: 50%;
  animation: sp-anime 0.8s infinite linear;
}
.name-user-show {
  font-size: 30px;
  margin-bottom: 30px;
  font-weight: bold;
}

.show-form-point {
  margin-bottom: 30px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
}
.ss-score-total {
  min-height: 270px;
  margin: auto;
  margin-bottom: 10px;
  width: 290px;
  max-width: 100%;
  padding: 40px 20px;
  text-align: center;
  border: 2px solid;
}
.ss-score-total p {
  font-size: 60px;
  font-weight: bold;
  color: red;
}
.ss-score-total .raw-score {
  display: flex;
  align-items: center;
  justify-content: center;
}

.show-form-point span {
  display:block;
}


@keyframes sp-anime {
  100% {
    transform: rotate(360deg);
  }
}
.is-hide{
  display:none;
}

@media(max-width:600px){

  .gc--1-of-3 {
    width: 100%;
    padding-right: 0;
  }

  .gc--2-of-3 {
    width: 100%;
  }
}
@media(max-width:450px) {
  .show-infor-review {
    position: relative;
    justify-content: flex-start;
    top: auto;
    margin-bottom: 10px;
  }
  .wrapper-review-form-detail span{
    width: 100%;
  }
  .infor-user-review span {
    width: 100%;
    text-align: left;
  }
  .infor-user-review {
    margin-bottom: 5px;
  }
}

</style>

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
      console.log('ok',data_user_update);
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
