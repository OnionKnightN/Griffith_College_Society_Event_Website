<!--header that involves the navigation bar and connection to database-->
<?php require_once("header.php");?>
  <section class ="details_container">
    <section class = "details">
      <?php
        //if you have searched a society from form execute if statement
        if(isset($_GET['society']) && $_GET['soc_name'] != "select"){
          // sorted soc_name into search variable
          $search = $_GET['soc_name'];
          // SQL Query based on society table and search information that are active
          $sql = "SELECT * FROM tbl_soc WHERE soc_name = '$search' AND soc_status = 1;";
          // SQL Query connection to database
      		$result = mysqli_query($db_connect, $sql);
          // If there is a correct sql query execute
          if($result){
            //Get information from sql query
            $row = mysqli_fetch_assoc($result);
            // Store society description from database in variable
            $description = $row['soc_desc'];
            $length = strpos($description,'.',300);
            //Length of first paragraphy
            $paragraph_one= substr($description,0,$length);
            //Length of second paragraphy
            $paragraph_two= substr($description,$length +1);
            // Store society image from database in variable
            $soc_image = $row['soc_image'];
            // Store society name from database in variable
            $soc_name = $row['soc_name'];
            // Description layout
            echo "<h1>Griffith College ".$soc_name." Society</h1>";
            echo "<p>$paragraph_one.</p>";
            echo "<p>$paragraph_two</p>";
            echo "<h1>Society Details</h1>";
            echo "<p><b>Email: </b>".$row['soc_email']."</p>";
            echo "<p><b>Phone: </b>".$row['soc_phone']."</p>";
            echo "<p><b>Meetup Day: </b>".$row['soc_day']."</p>";
            echo "<p><b>Start Time Day: </b>".$row['soc_stime']."</p>";
            echo "<p><b>End Time: </b>".$row['soc_etime']."</p>";?>
            <h1>Society Search</h1>
            <form action="societies.php" method="GET" class ="class_form">
              <!--Option based on societies that are active-->
              <select name="soc_name" class="details_select">
              <?php
                $pull_soc = "SELECT soc_id, soc_name FROM tbl_soc WHERE soc_status = 1;";
                $pull_soc_result = mysqli_query($db_connect, $pull_soc);
                if($pull_soc_result){
                  $num = mysqli_num_rows($pull_soc_result);
                 echo '<option value="select" hidden>Select Society</option>';
                 //  Test for valid test result
                 if($num>0){
                   while($row =mysqli_fetch_array($pull_soc_result)){
                     // Echo out society options
                     echo '<option value="'.$row["soc_name"].'">'.$row["soc_name"].'</option>';
                   }
                 }else{
                   // Echo out if there are no society listed.
                   echo '<option>No society available</option>';
                 }
                }
              ?>
              </select>
              <p><input type="submit" name="society" value="SUBMIT" class="details_btn"></p>
            </form>
          </section>
          <?php echo "<div><img src='img/society_image/".$soc_image."' alt='".$soc_name."' class = 'details_img'></div>"; ?>
        </section>
        <?php }
      // present the following if there is no society that has been selected.
      }else{?>
        <h1>Griffith College Society</h1>
        <p>Lorem ipsum dolor sit amet, nunc suspendisse ipsum fermentum interdum nulla fermentum, quam at justo rutrum, nec convallis orci.
          Ante vitae mus nisl tempus ultricies, id fusce, amet diam a suspendisse nibh, amet placerat, illo ultricies consequat massa scelerisque ut porta.
          Lacus aperiam mi tristique amet, nibh wisi eget, elit quis, lorem ut, orci dolor sunt vitae et ac. Mauris justo accusantium tincidunt, integer sapien adipiscing,
          massa a libero pede purus, fusce ex dui a imperdiet et, magna mi felis ac. Nibh sed maecenas sapien, in lorem tortor nunc libero. Sapien ultrices augue, dolor sit
          mauris minus in penatibus odio, vitae dapibus lectus enim, lectus massa aliquam sit, neque nunc.</p>
        <p>Orci ac enim tempus. Pharetra morbi, diam ut molestie faucibus quisque, eu suspendisse sint id vitae, lobortis elit lectus consectetuer libero, mus ut curabitur
          lectus pellentesque duis. Morbi netus nullam faucibus, eleifend potenti etiam nulla diam. A ipsum ut nulla vivamus augue, eu condimentum id vehicula eget maecenas
          integer, torquent felis. Morbi a lectus mauris ut gravida, feugiat elit mattis fringilla. Eu augue, hendrerit libero nam erat eros sed eget, facilisis vitae ante vel
          interdum sem tincidunt, mauris neque auctor pede quam ultrices elit. Ultrices curabitur dignissim id eget, et non cras, felis nec at lorem, erat dolorum. Cras lorem
          nec ac, magna morbi in vestibulum vel, wisi cum, vestibulum justo, porta est. Mattis dignissim mattis. Diam ligula, lorem elementum id sed. Nonummy ipsum. Ipsum massa,
          at nec justo purus in sit volutpat, nunc ac suscipit viverra libero id.</p>
        <h1>Society Search</h1>
        <form action="societies.php" method="GET" class ="details_form">
          <!--Option based on societies that are active-->
          <select name="soc_name" class="details_select">
          <?php
            $pull_soc = "SELECT soc_id, soc_name FROM tbl_soc WHERE soc_status = 1;";
            $pull_soc_result = mysqli_query($db_connect, $pull_soc);
            if($pull_soc_result){
              $num = mysqli_num_rows($pull_soc_result);
              echo '<option value="select" hidden>Select Society</option>';
              if($num>0){
               while($row =mysqli_fetch_array($pull_soc_result)){
                 // Echo out society options
                 echo '<option value="'.$row["soc_name"].'">'.$row["soc_name"].'</option>';
               }
            }else{
               // Echo out if there are no society listed.
               echo '<option>No society available</option>';
             }
            }
          ?>
          </select>
          <p><input type="submit" name="society" value="SUBMIT" class="details_btn"></p>
        </form>
      </section>
      <div><img src='img/Griffith_College.jpg' alt='Griffith College' class = 'details_img'></div>
  </section>
<!--Footer which contains the disconnection to the database and footer section-->
<?php } require_once("footer.php");?>
