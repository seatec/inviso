<?php
isset($_GET['action']) ? $action = $_GET['action'] : $action = "";
if ($action == 'deleted') {
    echo "<div>Record was deleted.</div>";
} //$action == 'deleted'
// add record link
echo "<a href='add_user.php'>Create New Record</a>";
//include database configuration
include 'db_connect.php';
//selecting records
$sql = "SELECT id, firstname, lastname, email FROM users";
//query the database
$rs = mysql_query($sql) or die($sql . ">>" . mysql_error());
//count how many records found
$num = mysql_num_rows($rs);
//check if more than 0 record found
if ($num > 0) {
    //start table
    echo "<table border='1'>";
    //creating our table heading
    echo "<tr>";
    echo "<th>Firstname</th>";
    echo "<th>Lastname</th>";
    echo "<th>Email</th>";
    //we're gonna add this column for edit and delete action
    echo "<th>Action</th>";
    echo "</tr>";
    //retrieve our table contents
    while ($row = mysql_fetch_array($rs)) {
        //extract row
        //this will make $row['firstname'] to
        //just $firstname only
        extract($row);
        //creating new table row per record
        echo "<tr>";
        echo "<td>{$firstname}</td>";
        echo "<td>{$lastname}</td>";
        echo "<td>{$email}</td>";
        // edit and delete options
        echo "<td>";
        echo "<a href='update_user.php?id={$id}'>Edit</a>";
        echo " / ";
        echo "<a href='#' onclick='delete_user( {$id} );'>Delete</a>";
        echo "</td>";
        echo "</tr>";
    } //$row = mysql_fetch_array($rs)
    //end table
    echo "</table>";
} //$num > 0
// if no records found
else {
    echo "No records found.";
}
?>

<script type='text/javascript'>
function delete_user( id ){

    var answer = confirm('Are you sure?');

    //if user clicked ok
    if ( answer ){
        //redirect to url with action as delete and id to the record to be deleted
        window.location = 'delete_user.php?id=' + id;
    }
}
</script>
