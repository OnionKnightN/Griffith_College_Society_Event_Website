<!--header that involves the navigation bar and connection to database-->
<?php require_once("header.php"); ?>
<?php
  // Validate for logged in users as Society.
  if(isset($_SESSION["user_email"]) && isset($_SESSION["user_id"]) && isset($_SESSION["user_type"])){
    if($_SESSION["user_type"] == "Society"){
      $user_id = $_SESSION['user_id'];
    }else{
      //You not logged in as a Society User
      header("Location: index.php?error=forbidden");
    }
  }else{
    //You have not logged into the website
    header("Location: login.php?error=forbidden");
  }
?>
<?php
  // Fetch society based society president
  $pull_society = "SELECT soc_id, soc_name FROM tbl_soc WHERE soc_president= $user_id";
  // Storing the connection in a variable
  $pull_society_result = mysqli_query($db_connect, $pull_society);
  // Testing and executing connection
  if($pull_society_result){
     mysqli_num_rows($pull_society_result);
     $row =mysqli_fetch_array($pull_society_result);
     // Creating varibables to store information on event_soc and soc_name
     $soc_id = $row["soc_id"];
     $soc_name = $row["soc_name"];
  }else{
    // Echo out if there are no society name listed.
    echo 'No Society Available';
  }
?>
  <section class ="details_container">
    <section class = "details">
      <?php
        //if you have searched a class from form above execute
        if(isset($_GET['event']) && $_GET['event_id'] != "select"){
          // sorting search information from form above
          $search = $_GET['event_id'];
          // SQL Query based on class table and search information
          $sql = "SELECT * FROM tbl_event WHERE event_id = '$search';";
          // SQL Query connection to database
          $result = mysqli_query($db_connect, $sql);
          // If there is a correct sql query execute
          if($result){
            //Get information from sql query
            $row = mysqli_fetch_assoc($result);
            // Store society description from database in variable
            $description = $row['event_desc'];
            $length = strpos($description,'.',500);
            //Length of first paragraphy
            $paragraph_one= substr($description,0,$length);
            //Length of second paragraphy
            $paragraph_two= substr($description,$length +1);
            // Fetch image from database
            $event_image = $row['event_image'];
            // Fetch name from database
            $event_name = $row['event_name'];
            // Fetch Status from database
            $status = $row['event_status'];
            // If statement changing the varaible status based on its value
            if($status == 1){
              $status = "<span style='color:Green;'><b>Approved</b></span>";
            }elseif($status == 2){
              $status = "<span style='color:Red;'><b>Disapproved</b></span>";
            }else{
              $status = "<span style='color:Orange;'><b>Waiting</b></span>";
            }
            // Echo out information on event based the society
            echo "<h1>".$row['event_name']." Event</h1>";
            echo "<p>$paragraph_one.</p>";
            echo "<p>$paragraph_two</p>";
            echo "<h1>Event Details</h1>";
            echo "<p><b>Location: </b>".$row['event_location']."</p>";
            echo "<p><b>Meetup Day: </b>".$row['event_date']."</p>";
            echo "<p><b>Start Time Day: </b>".$row['event_stime']."</p>";
            echo "<p><b>End Time: </b>".$row['event_etime']."</p>";
            echo "<p><b>Status: </b>$status</p>";?>
            <!--Event Search form-->
            <h1>Event Search</h1>
            <form action="society_events.php" method="GET" class ="details_form">
              <!--options of events based on society-->
              <select name="event_id" class="details_select">
                <?php
                  $pull_event = "SELECT event_id, event_name FROM tbl_event WHERE event_soc = $soc_id";
                  $pull_event_result = mysqli_query($db_connect, $pull_event);
                  if($pull_event_result){
                    echo '<option value="select" hidden>Select Event</option>';
                    $num = mysqli_num_rows($pull_event_result);
                    //  Test for valid test result
                    if($num>0){
                      while($row =mysqli_fetch_array($pull_event_result)){
                       // Echo out the events created by society
                       echo '<option value="'.$row["event_id"].'">'.$row["event_name"].'</option>';
                      }
                    }else{
                      // Echo out if there are no society events listed.
                      echo '<option>No Event Created</option>';
                    }
                  }
                ?>
              </select>
              <p><input type="submit" name="event" value="SUBMIT" class="details_btn"></p>
            </form>
          </section>
          <?php echo "<div><img src='img/event_image/".$event_image."' alt='".$event_name."' class = 'details_img'></div>";?>
        </section>
        <?php }
      // Present the following if event search has not been executed.
      }else{?>
        <h1>Griffith College Events</h1>
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
        <!--Event Search form-->
        <h1>Event Search</h1>
          <form action="society_events.php" method="GET" class ="details_form">
          <!--options of events based on society-->
          <select name="event_id" class="details_select">
            <?php
              $pull_event = "SELECT event_id, event_name FROM tbl_event WHERE event_soc = $soc_id";
              $pull_event_result = mysqli_query($db_connect, $pull_event);
              if($pull_event_result){
                echo '<option value="select" hidden>Select Event</option>';
                $num = mysqli_num_rows($pull_event_result);
                // Test for valid test result
                if($num>0){
                  while($row =mysqli_fetch_array($pull_event_result)){
                    // Echo out the events created by society
                   echo '<option value="'.$row["event_id"].'">'.$row["event_name"].'</option>';
                  }
                }else{
                  // Echo out if there are no society events listed.
                  echo '<option>No Event Created</option>';
                }
              }
            ?>
          </select>
          <p><input type="submit" name="event" value="SUBMIT" class="details_btn"></p>
        </form>
      </section>
      <div><img src='img/Griffith_College.jpg' alt='Griffith College' class = 'details_img'></div>
  </section>
<!--Footer which contains the disconnection to the database and footer section-->
<?php } require_once("footer.php");?>
