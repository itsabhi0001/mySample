<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css"><!-- for jquery-typeahead-UI -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script><!-- for jquery-typeahead-UI -->
<!--        <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>-->
        <?php
        $conn = new mysqli('localhost', 'root', '', 'btask');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        //Inserting Into database if any data is posted
        if (!empty($_POST['sname'])) {
            //print_r($_POST['sname']);
            for ($i = 0; $i < count($_POST['sname']); $i++) {
                $conn->query("INSERT INTO entries(sname,place,thing,animal) VALUES('" . $_POST['sname'][$i] . "','" . $_POST['place'][$i] . "','" . $_POST['thing'][$i] . "','" . $_POST['animal'][$i] . "')");
            }
            echo '<div class="alert alert-success">
				  <center><strong>Success!</strong> ' . ($i) . ' record(s) have been added.</center>
				</div>
				';
        }
        //Defining empty variables for typeahead tags
        $sname = '';
        $place = '';
        $thing = '';
        $animal = '';

        $result = $conn->query("SELECT * FROM entries");

        if ($result->num_rows > 0) {
            
            while ($row = $result->fetch_assoc()) {
                if ($sname == '') {
                    $sname = '"' . $row["sname"] . '"'; //concatinating for tags in typeahead
                } else {
                    $sname = $sname . ',"' . $row["sname"] . '"';
                }
                if ($place == '') {
                    $place = '"' . $row["place"] . '"';
                } else {
                    $place = $place . ',"' . $row["place"] . '"';
                }
                if ($thing == '') {
                    $thing = '"' . $row["thing"] . '"';
                } else {
                    $thing = $thing . ',"' . $row["thing"] . '"';
                }
                if ($animal == '') {
                    $animal = '"' . $row["animal"] . '"';
                } else {
                    $animal = $animal . ',"' . $row["animal"] . '"';
                }
            }
        }
        ?>
        <script>
            $(function () {
                var snameTags = [
                    <?= $sname ?>
                ];
                $("#sname").autocomplete({
                    source: snameTags
                });
                var placeTags = [
                    <?= $place ?>
                ];
                $("#place").autocomplete({
                    source: placeTags
                });
                var thingTags = [
                    <?= $thing ?>
                ];
                $("#thing").autocomplete({
                    source: thingTags
                });
                var animalTags = [
                    <?= $animal ?>
                ];
                $("#animal").autocomplete({
                    source: animalTags
                });
            });
        </script>
    </head>
    <body>
        <div class="container">
            <center><h2>Hello there! Lets Play.</h2></center>
            <div class="well col-md-4 col-md-offset-4" >

                <div class="form-group">
                    <input type="text" class="form-control" id="sname" oninput="bind_data()" onblur="bind_data()" placeholder="Name">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="place" oninput="bind_data()" onblur="bind_data()" placeholder="Place">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="thing" oninput="bind_data()" onblur="bind_data()" placeholder="Thing">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="animal" oninput="bind_data()" onblur="bind_data()" placeholder="Animal">
                </div>
                <center><button class="btn btn-primary" onclick="addData()">Add</button></center>
            </div>
        </div>

        <div class="container">

            <div class="table-responsive">
                <form action="" method="POST">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Place</th>
                                <th>Thing</th>
                                <th>Animal</th>
                            </tr>
                        </thead>
                        <tbody id="adding_body">
                            <tr>
                                <td><input type="text" class="form-control col-xs-2"  id="row-value1" name="sname[]" required></td>
                                <td><input type="text" class="form-control col-xs-2"  id="row-value2" name="place[]" required></td>
                                <td><input type="text" class="form-control col-xs-2"  id="row-value3" name="thing[]" required></td>
                                <td><input type="text" class="form-control col-xs-2"  id="row-value4" name="animal[]" required></td>
                            </tr>
                        </tbody>
                    </table>
                    <center><button type="submit" class="btn btn-primary">Save</button></center>
                </form>
            </div>
        </div>
        <script>

            function bind_data() {
                var sname = document.getElementById('sname').value;
                var place = document.getElementById('place').value;
                var thing = document.getElementById('thing').value;
                var animal = document.getElementById('animal').value;
                $('#row-value1').val(sname);
                $('#row-value2').val(place);
                $('#row-value3').val(thing);
                $('#row-value4').val(animal);
            }

            function addData() {//Adding the entered data as a new row into table and making entered data reset
                var sname = document.getElementById('sname').value;
                var place = document.getElementById('place').value;
                var thing = document.getElementById('thing').value;
                var animal = document.getElementById('animal').value;
                $('#adding_body').append('<tr>'
                        + '<td><input type="text" class="form-control col-xs-2" name="sname[]" value="' + sname + '" ></td>'
                        + '<td><input type="text" class="form-control col-xs-2" name="place[]" value="' + place + '" ></td>'
                        + '<td><input type="text" class="form-control col-xs-2" name="thing[]" value="' + thing + '" ></td>'
                        + '<td><input type="text" class="form-control col-xs-2" name="animal[]" value="' + animal + '" ></td>'
                        + '</tr>');

                document.getElementById('sname').value = '';
                document.getElementById('place').value = '';
                document.getElementById('thing').value = '';
                document.getElementById('animal').value = '';
                $('#row-value1').val('');
                $('#row-value2').val('');
                $('#row-value3').val('');
                $('#row-value4').val('');
            }
        </script>
    </body>
</html>

