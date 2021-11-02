
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
    if(!empty($id_user)){
      if(get_user_meta($id_user,'review_show_profile',true)=='1'){
        $key_checked = 'checked';
      }
      if(get_user_meta($id_user,'review_show_profile',true)=='0'){
        $key_checked = '';
      }
      ?>
        <div class="show-infor-review">
          <label for="show_review_user"> Publish review page</label>
          <input type="checkbox" id="show_review_user" name="show-review-user"  data-user-id="<?php echo $id_user?>" <?php echo $key_checked?>>
        </div>
      <?php
    }else{
      $id_user_link = $_GET['userID'];
      if(!empty($id_user_link)){
        if(get_user_meta($id_user_link,'review_show_profile',true)=='1'){
          $id_user=intval($id_user_link);
        }else{
          $id_user=Null;
        }
      }
    }

    $args = array(
      'post_type'=>'point-entries',
      'posts_per_page'=>-1,
      'post_status'=>'publish',
      'meta_query'=>array(
        array(
          'key'     => 'review_user_id',
          'value'   => $id_user,
          'compare' => '=',
        )
      )
    );
    $q_svl = new \WP_Query( $args );
    $total_reviews=$q_svl->found_posts;
    $id_form_designs =[];
    $total_point = 0;
    if($q_svl->have_posts()){
      while($q_svl->have_posts()){
        $q_svl->the_post();
        $id= get_the_ID();
        $id_form_design = carbon_get_post_meta($id,'id_form_design');
        array_push($id_form_designs,$id_form_design);
        $cat_point_show_all= carbon_get_post_meta($id,'categories_fields_point');
        foreach ($cat_point_show_all as $cat_point_show_key => $cat_point_show_item) {
          $total_point = $total_point+intval($cat_point_show_item['score']);
        }
      }

    }
    $id_form_post = array_unique($id_form_designs);
      if(!empty($id_user)){
      ?>
        <div class="infor-user-review">
            <span>Total Point: <?php echo $total_point?></span>
            <span>Total Review: <?php echo $total_reviews?></span>
        </div>
      <?php
      }
      ?>
      <div class="gc gc--1-of-3">
        <div class="menu">
          <?php
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
          ?>
  		</div>
      </div>
      <div class="gc gc--2-of-3">
        <ul class="nacc">
        <?php

          foreach ($id_form_post  as $key => $value) {
            $array_cat  = carbon_get_post_meta($value,'categories_fields');
            $point_form_all =0;
            if($key==0){
              $class_active_tab = 'active';
            }else{
              $class_active_tab ='';
            }
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
                        $cat_raw_form_all =[];
                        $array_show_score=[];
                        $array_show=[];
                        $count_item_cat = count($array_cat);
                          foreach ($array_cat as $cat) {
                            array_push($cat_raw_form_all,$cat['name']);
                            array_push($array_show_score,$cat['score']);
                            ?>
                              <th>
                                <?php echo $cat['name']?>
                              </th>
                            <?php
                          }
                        ?>
                      </tr>
                      <?php
                        $args_form = array(
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
                              'key'     => 'id_form_design',
                              'value'   => $value,
                              'compare' => '=',
                            ),
                          )
                        );
                        $q_svl_new = new \WP_Query( $args_form );
                        $total_reviews_form=$q_svl_new->found_posts;
                      ?>

                        <?php
                          if($q_svl_new->have_posts()){
                            $index=0;
                            while($q_svl_new->have_posts()){

                              $q_svl_new->the_post();
                              $id = get_the_ID();
                              $index++;
                              $id_post = carbon_get_post_meta($id,'post_id');
                              ?><tr>
                                  <td>
                                    <?php echo $index?>
                                  </td>
                                  <?php
                                  $cat_in_point_rw = carbon_get_post_meta($id,'categories_fields_point');
                                  $cat_in_point_rw_name = [];
                                  $all_point_cat = 0;

                                  foreach ($cat_in_point_rw as $cat_in_point) {
                                    array_push($cat_in_point_rw_name,$cat_in_point['name']);
                                    $all_point_cat=$all_point_cat+$cat_in_point['score'];
                                  }
                                  $point_form_all = $point_form_all+$all_point_cat;
                                  $result = array_intersect( $cat_raw_form_all,$cat_in_point_rw_name);


                                  foreach ($array_cat as $_key_s => $vldf_show) {
                                    $array_show[$_key_s]='-';
                                  }
                                  foreach ($result as $key_new => $result_item) {
                                    $array_show[$key_new]=$result_item;
                                  }
                                  foreach ($array_show as $key_new_show => $item_show) {
                                    if($item_show!='-'){
                                      $array_show[$key_new_show]=$array_show_score[$key_new_show];
                                    }
                                  }
                                  ?>
                                  <td>
                                    <a href="<?php echo get_permalink($id_post)?>">
                                      <?php echo get_the_title($id_post)?>
                                    </a>
                                  </td>
                                  <td><?php echo $all_point_cat/$count_item_cat?></td>
                                  <td><?php echo get_the_date('M j, Y')?></td>
                                  <?php
                                    foreach ($array_show as $value) {
                                      ?>
                                        <td><?php echo $value?></td>
                                      <?php
                                    }
                                  ?>
                                </tr>
                              <?php
                            }
                          }
                        ?>
                    </table>
                    <div class="wrapper-review-form-detail">
                      <span>Total Point Form: <?php echo $point_form_all?></span>
                      <span>Total Review Form: <?php echo $total_reviews_form?></span>
                    </div>
                  </div>
                </li>

            <?php
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


  .content-template-history-point {
    width: 1140px;
    margin: auto;
    max-width: 95%;
  }
  .infor-user-review {
    text-align: center;
    max-width: 1140px;
    display: flex;
    margin: auto;
    justify-content: space-between;
    margin-bottom: 32px;
    flex-wrap: wrap;
  }
  .infor-user-review span {
    display: inline-block;
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
    width: 6px;
    background-color: #F5F5F5;
  }

  .nacc li div table::-webkit-scrollbar-thumb {
    background-color: #3F51B5;
  }

  .nacc li div table::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
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
     margin: 100px auto 0;
  }

  .naccs .menu div span {
    font-size: 18px;
  }
  .naccs .menu div {
     padding: 15px 20px 15px 40px;
     margin-bottom: 10px;
     color: #fff;
     background: #292e4b;
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
     background-color: #fff;
     border-radius: 100%;
     transition: 1s all cubic-bezier(0.075, 0.82, 0.165, 1);
  }

  .naccs .menu div.active span.light {
     background-color: #FBC02D;
     left: 0;
     height: 100%;
     width: 3px;
     top: 0;
     border-radius: 0;
     transform: translateY(0);
  }

  .naccs .menu div.active {
     color: #FBC02D;
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
    // $('.tab-link').click( function() {
    //     var tabID = $(this).attr('data-tab');
    //
    //     $(this).addClass('active').siblings().removeClass('active');
    //
    //     $('#tab-'+tabID).addClass('active').siblings().removeClass('active');
    // });

    let height_li_active_first = $('.nacc li.active').innerHeight();

    $('.naccs ul').height(height_li_active_first + 'px');


    $(document).on("click", ".naccs .menu div", function() {
    	var numberIndex = $(this).index();

    	if (!$(this).is("active")) {
    		$(".naccs .menu div").removeClass("active");
    		$(".naccs ul li").removeClass("active");

    		$(this).addClass("active");
    		$(".naccs ul").find("li:eq(" + numberIndex + ")").addClass("active");

    		var listItemHeight = $(".naccs ul")
    			.find("li:eq(" + numberIndex + ")")
    			.innerHeight();
    		$(".naccs ul").height(listItemHeight + "px");
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
