<?php
require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header('location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <style>
        .form-popup {
            max-width: 500px;
            background-color: white;
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -100px;
            /* Negative half of height. */
            margin-left: -250px;
            /* Negative half of width. */
            border-radius: 5px;
            z-index: 99;
            padding: 20px;
            box-shadow: 0px 0px 50px 0px #a3a3a3;
        }

        .form-container {
            background-color: white;
        }

        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            font-family: sans-serif;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .styled-table thead tr {
            background-color: #198754;
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

        .styled-table tbody tr.active-row {
            font-weight: bold;
            color: #009879;
        }
    </style>
</head>

<body>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            echo $_SESSION['success_message'];
            unset($_SESSION['success_message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="container d-grid gap-3">
        <div class="row">
            <div class="col-md-12 d-grid gap-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex pt-3 bd-highlight justify-content-start align-items-center gap-5">
                        <h2>Lista Clanova</h2>
                        <a href="export.php?what=members" class="btn btn-success">Izvedi u excel</a>
                    </div>
                    <a href="logout.php" class="btn btn-danger">Odjava</a>
                </div>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Ime</th>
                            <th>Prezime</th>
                            <th>E-pošta</th>
                            <th>Broj telefona</th>
                            <th>Trener</th>
                            <th>Slika</th>
                            <th>Plan treninga</th>
                            <th>Pristupna karta</th>
                            <th>Registriran</th>
                            <th>Izbrisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT members.*, traning_plans.name AS traning_plan_name, trainers.first_name AS trainer_first_name, trainers.last_name AS trainer_last_name FROM members LEFT JOIN traning_plans ON members.training_plan_id = traning_plans.plan_id LEFT JOIN trainers ON members.trainer_id = trainers.trainer_id";
                        $results = $conn->query($sql);
                        $result = $results->fetch_all(MYSQLI_ASSOC);
                        foreach ($results as $result): ?>
                            <tr>
                                <td>
                                    <?php echo $result['first_name']; ?>
                                </td>
                                <td>
                                    <?php echo $result['last_name']; ?>
                                </td>
                                <td>
                                    <?php echo $result['email']; ?>
                                </td>
                                <td>
                                    <?php echo $result['phone_number']; ?>
                                </td>
                                <td>
                                    <?php
                                    if ($result['trainer_first_name'] || $result['trainer_last_name']) {
                                        echo $result['trainer_first_name'] . " " . $result['trainer_last_name'];
                                    } else {
                                        $name = $result['first_name'] . " " . $result['last_name'];
                                        echo '<button class="btn btn-success" onclick="openForm(' . $result['member_id'] . ', \'' . $name . '\')">Dodjeli trenera</button>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <img style="width: 60px;" src="<?php echo $result['photo_path']; ?>" alt="">
                                </td>
                                <td>
                                    <?php
                                    if ($result['traning_plan_name']) {
                                        echo $result['traning_plan_name'];
                                    } else {
                                        echo "Nema plana";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a target="_blank" class="text-decoration-none"
                                        href="<?php echo 'card.php?member_id=' . $result['member_id']; ?>">Pristupna
                                        karta</a>
                                </td>
                                <td>
                                    <?php

                                    $created_at = strtotime($result['created_at']);
                                    $new_date = date("j.m.Y", $created_at);
                                    echo $new_date;
                                    ?>
                                </td>
                                <td>
                                    <form action="delete_member.php" method="POST">
                                        <input type="hidden" name="member_id" value="<?php echo $result['member_id'] ?>">
                                        <button class="btn btn-danger btn-sm">Izbrisi</button>
                                    </form>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 d-grid gap-3">
                <div class="d-flex pt-3 bd-highlight justify-content-start align-items-center gap-5">
                    <h2>Lista Trenera</h2>
                    <a href="export.php?what=trainers" class="btn btn-success">Izvedi u excel</a>
                </div>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Ime</th>
                            <th>Prezime</th>
                            <th>E-pošta</th>
                            <th>Broj telefona</th>
                            <th>Registriran</th>
                            <th>Izbriši</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM trainers";
                        $results = $conn->query($sql);
                        $result = $results->fetch_all(MYSQLI_ASSOC);
                        foreach ($results as $result): ?>
                            <tr>
                                <td>
                                    <?php echo $result['first_name']; ?>
                                </td>
                                <td>
                                    <?php echo $result['last_name']; ?>
                                </td>
                                <td>
                                    <?php echo $result['email']; ?>
                                </td>
                                <td>
                                    <?php echo $result['phone_number']; ?>
                                </td>
                                <td>
                                    <?php

                                    $created_at = strtotime($result['created_at']);
                                    $new_date = date("j.m.Y", $created_at);
                                    echo $new_date;
                                    ?>
                                </td>
                                <td>
                                    <form action="delete_trainer.php" method="POST">
                                        <input type="hidden" name="trainer_id" value="<?php echo $result['trainer_id'] ?>">
                                        <button class="btn btn-danger btn-sm">Izbrisi</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-6 d-grid gap-3">
                <h2>Registriraj clana</h2>
                <form action="register_member.php" method="post" enctype="multipart/form-data">
                    Ime: <input class="form-control" type="text" name="first_name"><br>
                    Prezime: <input class="form-control" type="text" name="last_name"><br>
                    E-pošta: <input class="form-control" type="email" name="email"><br>
                    Broj telefona: <input class="form-control" type="text" name="phone_number"><br>
                    Plan treninga:
                    <select class="form-control" name="training_plan_id">
                        <option value="" disabled selected>Plan treninga</option>

                        <?php

                        $sql = "SELECT * FROM traning_plans";

                        $run = $conn->query($sql);
                        $results = $run->fetch_all(MYSQLI_ASSOC);

                        foreach ($results as $result) {
                            echo "<option value='" . $result['plan_id'] . "'>" . $result['name'] . "</option>";
                        }

                        ?>
                    </select><br>
                    <input type="hidden" name="photo_path" id="photoPathInput">

                    <div id="dropzone-upload" class="dropzone"></div>

                    <input class="btn btn-success mt-3" type="submit" value="Registriraj">
                </form>
            </div>
            <div class="col-md-6">
                <h2>Registriraj trenera</h2>
                <form action="register_trainer.php" style="margin-top: 23px;" method="post"
                    enctype="multipart/form-data">
                    Ime: <input class="form-control" type="text" name="first_name"><br>
                    Prezime: <input class="form-control" type="text" name="last_name"><br>
                    E-pošta: <input class="form-control" type="email" name="email"><br>
                    Broj telefona: <input class="form-control" type="text" name="phone_number"><br>
                    <input class="btn btn-success" type="submit" value="Registriraj">
                </form>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-6 form-popup" id="assign-form">
                <form class="form-container d-grid gap-3" action="assing_trainer.php" method="post"
                    enctype="multipart/form-data">
                    <div class="d-flex bd-highlight justify-content-between align-items-center">
                        <h2 id="addtrainer_h2">Dodjeli trenera</h2>
                        <button type="button" class="btn" onclick="closeForm()">X</button>
                    </div>
                    <div id="clan_select">
                        <select class="form-control" name="member_id">
                            <option id="id_clana" value=""></option>
                        </select><br>
                    </div>
                    <div>
                        Odaberi trenera:
                        <select class="form-control" name="trainer_id">
                            <option value="" disabled selected>Odaberi trenera</option>

                            <?php

                            $sql = "SELECT * FROM trainers";

                            $run = $conn->query($sql);
                            $results = $run->fetch_all(MYSQLI_ASSOC);

                            foreach ($results as $result) {
                                echo "<option value='" . $result['trainer_id'] . "'>" . $result['first_name'] . ' ' . $result['last_name'] . "</option>";
                            }

                            ?>
                        </select><br>
                    </div>
                    <input class="btn btn-success" type="submit" value="Dodjeli">
                </form>
            </div>
        </div>
    </div>

    <?php $conn->close(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>;

    <script>
        function openForm(id, name) {
            console.log(typeof name)
            document.getElementById("assign-form").style.display = "block";
            document.getElementById("id_clana").value = id
            document.getElementById("id_clana").innerText = name
            document.getElementById("addtrainer_h2").innerHTML = "Dodjeli trenera clanu " + name
            document.getElementById("clan_select").style.display = 'none'
        }

        function closeForm() {
            document.getElementById("assign-form").style.display = "none";
        }
    </script>
    <script>
        Dropzone.options.dropzoneUpload = {
            url: "upload_photo.php",
            paramName: "photo",
            maxFilesize: 20,
            acceptedFiles: "image/*",
            init: function () {
                this.on("success", function (file, response) {
                    const jsonResponse = JSON.parse(response)
                    document.getElementById('photoPathInput').value = jsonResponse.photo_path;
                })
            }
        }
    </script>

</body>

</html>